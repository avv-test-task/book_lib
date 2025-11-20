<?php

declare(strict_types=1);

namespace common\listeners;

use common\events\BookCreatedNotificationEvent;
use common\models\Author;
use common\models\AuthorSubscription;
use common\services\contracts\SmsServiceInterface;
use Throwable;
use Yii;
use yii\base\BaseObject;

class BookSmsNotificationListener extends BaseObject
{
    private ?SmsServiceInterface $smsService = null;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(?SmsServiceInterface $smsService = null, array $config = [])
    {
        parent::__construct($config);
        $this->smsService = $smsService;
    }

    public function handle(BookCreatedNotificationEvent $event): void
    {
        $book = $event->book;

        if (!$this->smsService instanceof SmsServiceInterface) {
            try {
                $this->smsService = Yii::$container->get(SmsServiceInterface::class);
            } catch (Throwable $e) {
                Yii::warning("Не удалось получить SmsServiceInterface: {$e->getMessage()}", __METHOD__);
                return;
            }
        }

        $authors = Author::find()
            ->innerJoin('{{%book_author}} ba', 'ba.author_id = {{%author}}.id')
            ->where(['ba.book_id' => $book->id])
            ->all();

        if (empty($authors)) {
            return;
        }

        $authorIds = array_map(fn(Author $author): int => $author->id, $authors);

        $subscriptions = AuthorSubscription::find()
            ->where(['author_id' => $authorIds])
            ->all();

        if (empty($subscriptions)) {
            return;
        }

        $authorNames = array_map(fn(Author $author): string => $author->name, $authors);

        $message = 'Новая книга "' . $book->name . '" от ' . implode(', ', $authorNames) . ' доступна в библиотеке!';

        foreach ($subscriptions as $subscription) {
            try {
                $this->smsService->send($subscription->phone, $message);
            } catch (Throwable $exception) {
                Yii::error("Не удалось отправить SMS на номер {$subscription->phone}: {$exception->getMessage()}", __METHOD__);
            }
        }
    }
}

