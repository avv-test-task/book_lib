<?php

declare(strict_types=1);

namespace common\events;

use common\models\Book;
use yii\base\Event;

class BookCreatedNotificationEvent extends Event
{
    public Book $book;
}

