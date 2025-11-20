<?php

namespace common\models;

use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Form model for creating and updating books.
 */
class BookForm extends Model
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string|null
     */
    public $description;

    /**
     * @var int|null
     */
    public $year;

    /**
     * @var string|null
     */
    public $isbn;

    /**
     * @var int[]
     */
    public $authorIds = [];

    /**
     * @var UploadedFile|null
     */
    public $coverFile;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'authorIds'], 'required'],
            [['description'], 'string'],
            [['year'], 'integer', 'min' => 0, 'max' => 2100],
            [['name'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 20],
            [['authorIds'], 'each', 'rule' => ['integer']],
            [
                ['authorIds'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Author::class,
                'targetAttribute' => ['authorIds' => 'id'],
                'allowArray' => true,
            ],
            [
                ['coverFile'],
                'file',
                'skipOnEmpty' => true,
                'extensions' => ['png', 'jpg', 'jpeg', 'gif'],
                'maxSize' => 5 * 1024 * 1024,
            ],
        ];
    }

    /**
     * Loads form fields from an existing Book model.
     *
     * @param Book $book
     */
    public function loadFromBook(Book $book)
    {
        $this->name = $book->name;
        $this->description = $book->description;
        $this->year = $book->year;
        $this->isbn = $book->isbn;
        $this->authorIds = $book->getAuthors()->select('id')->column();
    }

    /**
     * Applies form data to a Book model.
     *
     * @param Book $book
     */
    public function applyToBook(Book $book)
    {
        $book->name = $this->name;
        $book->description = $this->description;
        $book->year = $this->year;
        $book->isbn = $this->isbn;
    }
}


