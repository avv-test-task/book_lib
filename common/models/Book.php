<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int|null $year
 * @property string|null $isbn
 * @property string|null $cover_path
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Author[] $authors
 */
class Book extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['year'], 'integer', 'min' => 0, 'max' => 2100],
            [['name'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 20],
            [['cover_path'], 'string', 'max' => 255],
            [['isbn'], 'unique', 'skipOnEmpty' => true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isbn === '') {
                $this->isbn = null;
            }
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'description' => 'Описание',
            'year' => 'Год',
            'isbn' => 'ISBN',
            'cover_path' => 'Обложка',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    /**
     * Gets query for [[Author]] models.
     *
     * @return ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('{{%book_author}}', ['book_id' => 'id']);
    }

    /**
     * @return string|null
     */
    public function getCoverUrl()
    {
        if (!$this->cover_path) {
            return null;
        }

        if (strpos($this->cover_path, 'http') === 0) {
            return $this->cover_path;
        }

        $frontendUrl = 'http://localhost:20080';
        return $frontendUrl . $this->cover_path;
    }
}


