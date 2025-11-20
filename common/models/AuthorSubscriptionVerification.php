<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $author_id
 * @property string $phone
 * @property string $code
 * @property int $expires_at
 * @property int|null $created_at
 *
 * @property Author $author
 */
class AuthorSubscriptionVerification extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%author_subscription_verification}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['author_id', 'phone', 'code', 'expires_at'], 'required'],
            [['author_id', 'expires_at'], 'integer'],
            [['phone'], 'string', 'max' => 20],
            [['code'], 'string', 'length' => 4],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_id' => 'Автор',
            'phone' => 'Телефон',
            'code' => 'Код',
            'expires_at' => 'Истекает',
            'created_at' => 'Создано',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }

    public function isExpired(): bool
    {
        return time() > $this->expires_at;
    }

    public static function generateCode(): string
    {
        return str_pad((string)mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
    }
}

