<?php

declare(strict_types=1);

namespace common\listeners;

use common\events\BookCreatedNotificationEvent;
use common\services\BookService;
use Yii;
use yii\base\BaseObject;

class BookSmsNotificationListener extends BaseObject
{
    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    public function handle(BookCreatedNotificationEvent $event): void
    {
        try {
            $bookService = Yii::$container->get(BookService::class);
            $bookService->notifySubscribers($event->book);
        } catch (\Throwable $e) {
            Yii::warning("Не удалось получить отправить сообщение: {$e->getMessage()}", __METHOD__);
        }
    }
}

