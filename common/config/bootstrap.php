<?php

use common\events\BookCreatedNotificationEvent;
use common\listeners\BookSmsNotificationListener;
use common\services\BookService;
use yii\base\Event;

date_default_timezone_set('Europe/Moscow');

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(__DIR__, 2) . '/frontend');
Yii::setAlias('@backend', dirname(__DIR__, 2) . '/backend');
Yii::setAlias('@console', dirname(__DIR__, 2) . '/console');
Yii::setAlias('@covers', dirname(__DIR__, 2) . '/frontend/web/uploads/covers');
Yii::setAlias('@coversUrl', '/uploads/covers');

// Register event listener for book creation
Event::on(
    BookService::class,
    BookService::EVENT_BOOK_CREATED_NOTIFICATION,
    function (BookCreatedNotificationEvent $event): void {
        $listener = new BookSmsNotificationListener();
        $listener->handle($event);
    }
);
