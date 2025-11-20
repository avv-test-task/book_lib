<?php

declare(strict_types=1);

namespace common\services;

use common\events\BookCreatedNotificationEvent;
use common\models\Author;
use common\models\AuthorSubscription;
use common\models\Book;
use common\models\BookForm;
use common\services\contracts\BookServiceInterface;
use common\services\contracts\SmsServiceInterface;
use common\services\contracts\StorageServiceInterface;
use DomainException;
use Throwable;
use Yii;
use yii\base\Component;
use yii\db\Transaction;
use yii\web\UploadedFile;

class BookService extends Component implements BookServiceInterface
{
    const EVENT_BOOK_CREATED_NOTIFICATION = 'bookCreatedNotification';

    private StorageServiceInterface $storage;
    private ?SmsServiceInterface $smsService;

    public function __construct(StorageServiceInterface $storage, ?SmsServiceInterface $smsService = null)
    {
        $this->storage = $storage;
        $this->smsService = $smsService;
    }

    /**
     * {@inheritdoc}
     */
    public function create(BookForm $form): Book
    {
        if (!$form->validate()) {
            throw new DomainException('Ошибка валидации.');
        }

        $book = new Book();
        $form->applyToBook($book);

        /** @var Transaction $transaction */
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$book->save()) {
                throw new DomainException('Не удалось сохранить.');
            }

            $this->saveCover($book, $form->coverFile);
            $this->syncAuthors($book, $form->authorIds);

            $transaction->commit();

            $event = new BookCreatedNotificationEvent(['book' => $book]);
            $this->trigger(self::EVENT_BOOK_CREATED_NOTIFICATION, $event);
        } catch (Throwable $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        return $book;
    }

    /**
     * {@inheritdoc}
     */
    public function update(Book $book, BookForm $form): Book
    {
        if (!$form->validate()) {
            throw new DomainException('Ошибка валидации.');
        }

        $form->applyToBook($book);

        /** @var Transaction $transaction */
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($form->deleteCover && $book->cover_path) {
                $this->storage->delete($book->cover_path);
                $book->cover_path = null;
            }

            if (!$book->save()) {
                throw new DomainException('Не удалось сохранить.');
            }

            $this->saveCover($book, $form->coverFile);
            $this->syncAuthors($book, $form->authorIds);

            $transaction->commit();
        } catch (Throwable $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        return $book;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Book $book): void
    {
        /** @var Transaction $transaction */
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $coverPath = $book->cover_path;

            $book->unlinkAll('authors', true);

            if ($book->delete() === false) {
                throw new DomainException('Не удалось удалить.');
            }

            if ($coverPath) {
                $this->storage->delete($coverPath);
            }

            $transaction->commit();
        } catch (Throwable $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    private function saveCover(Book $book, ?UploadedFile $file): void
    {
        if (!$file instanceof UploadedFile) {
            return;
        }

        if ($book->cover_path) {
            $this->storage->delete($book->cover_path);
        }

        $book->cover_path = $this->storage->saveCover($file);

        if (!$book->save(false, ['cover_path'])) {
            throw new DomainException('Не удалось обновить обложку.');
        }
    }

    /**
     * @param array<int> $authorIds
     */
    private function syncAuthors(Book $book, array $authorIds): void
    {
        $book->unlinkAll('authors', true);

        if ($authorIds === []) {
            return;
        }

        $authors = Author::find()
            ->andWhere(['id' => $authorIds])
            ->indexBy('id')
            ->all();

        foreach ($authorIds as $authorId) {
            if (!isset($authors[$authorId])) {
                continue;
            }

            $book->link('authors', $authors[$authorId]);
        }
    }

    public function notifySubscribers(Book $book): void
    {
        if ($this->smsService === null) {
            try {
                $this->smsService = Yii::$container->get(SmsServiceInterface::class);
            } catch (Throwable $e) {
                Yii::warning("Не удалось получить SmsServiceInterface: {$e->getMessage()}", __METHOD__);
                return;
            }
        }

        $subscriptions = AuthorSubscription::find()
            ->innerJoin('{{%book_author}} ba', 'ba.author_id = {{%author_subscription}}.author_id')
            ->where(['ba.book_id' => $book->id])
            ->with('author')
            ->all();

        if (empty($subscriptions)) {
            return;
        }

        foreach ($subscriptions as $subscription) {
            if ($subscription->author === null) {
                continue;
            }

            $message = 'Новая книга "' . $book->name . '" от ' . $subscription->author->name . ' доступна в библиотеке!';

            try {
                $this->smsService->send($subscription->phone, $message);
            } catch (Throwable $exception) {
                Yii::error("Не удалось отправить SMS на номер {$subscription->phone}: {$exception->getMessage()}", __METHOD__);
            }
        }
    }
}


