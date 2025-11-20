<?php

declare(strict_types=1);

namespace common\events;

use common\models\Book;
use yii\base\Event;

/**
 * Event triggered when a new book is created.
 */
class BookCreatedNotificationEvent extends Event
{
    /**
     * @var Book The created book
     */
    public Book $book;
}

