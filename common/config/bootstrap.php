<?php

use common\events\BookCreatedNotificationEvent;
use common\listeners\BookSmsNotificationListener;
use common\services\BookService;
use Yii;
use yii\base\Event;

date_default_timezone_set('Europe/Moscow');

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@covers', dirname(dirname(__DIR__)) . '/frontend/web/uploads/covers');
Yii::setAlias('@coversUrl', '/uploads/covers');

// Register event listener for book creation
Event::on(
    BookService::class,
    BookService::EVENT_BOOK_CREATED,
    function (BookCreatedNotificationEvent $event) {
        $listener = new BookSmsNotificationListener();
        $listener->handle($event);
    }
);
