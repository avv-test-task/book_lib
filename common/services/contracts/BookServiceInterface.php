<?php

namespace common\services\contracts;

use common\models\Book;
use common\models\BookForm;

interface BookServiceInterface
{
    /**
     * Creates a new book from the given form.
     *
     * @param BookForm $form
     *
     * @return Book
     */
    public function create(BookForm $form);

    /**
     * Updates the given book using data from the form.
     *
     * @param Book     $book
     * @param BookForm $form
     *
     * @return Book
     */
    public function update(Book $book, BookForm $form);

    /**
     * Deletes the given book.
     *
     * @param Book $book
     */
    public function delete(Book $book);
}


