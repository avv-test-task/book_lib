<?php

declare(strict_types=1);

namespace common\services\contracts;

use common\models\Book;
use common\models\BookForm;

interface BookServiceInterface
{
   
    public function create(BookForm $form): Book;
    
    public function update(Book $book, BookForm $form): Book;
   
    public function delete(Book $book): void;
}


