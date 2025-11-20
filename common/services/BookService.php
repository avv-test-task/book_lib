<?php

namespace common\services;

use common\models\Author;
use common\models\Book;
use common\models\BookForm;
use common\services\contracts\BookServiceInterface;
use common\services\contracts\StorageServiceInterface;
use DomainException;
use Yii;
use yii\db\Transaction;
use yii\web\UploadedFile;

class BookService implements BookServiceInterface
{
    /**
     * @var StorageServiceInterface
     */
    private $storage;

    /**
     * @param StorageServiceInterface $storage
     */
    public function __construct(StorageServiceInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function create(BookForm $form)
    {
        if (!$form->validate()) {
            throw new DomainException('Book form is not valid.');
        }

        $book = new Book();
        $form->applyToBook($book);

        /** @var Transaction $transaction */
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$book->save()) {
                throw new DomainException('Failed to save book.');
            }

            $this->saveCover($book, $form->coverFile);
            $this->syncAuthors($book, $form->authorIds);

            $transaction->commit();
        } catch (\Throwable $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        return $book;
    }

    /**
     * {@inheritdoc}
     */
    public function update(Book $book, BookForm $form)
    {
        if (!$form->validate()) {
            throw new DomainException('Book form is not valid.');
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
                throw new DomainException('Failed to save book.');
            }

            $this->saveCover($book, $form->coverFile);
            $this->syncAuthors($book, $form->authorIds);

            $transaction->commit();
        } catch (\Throwable $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        return $book;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Book $book)
    {
        /** @var Transaction $transaction */
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $coverPath = $book->cover_path;

            $book->unlinkAll('authors', true);

            if ($book->delete() === false) {
                throw new DomainException('Failed to delete book.');
            }

            if ($coverPath) {
                $this->storage->delete($coverPath);
            }

            $transaction->commit();
        } catch (\Throwable $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    /**
     * @param Book         $book
     * @param UploadedFile $file|null
     */
    private function saveCover(Book $book, $file)
    {
        if (!$file instanceof UploadedFile) {
            return;
        }

        if ($book->cover_path) {
            $this->storage->delete($book->cover_path);
        }

        $book->cover_path = $this->storage->saveCover($file);

        if (!$book->save(false, ['cover_path'])) {
            throw new DomainException('Failed to update book cover path.');
        }
    }

    /**
     * @param Book  $book
     * @param array $authorIds
     */
    private function syncAuthors(Book $book, array $authorIds)
    {
        $book->unlinkAll('authors', true);

        if (empty($authorIds)) {
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
}


