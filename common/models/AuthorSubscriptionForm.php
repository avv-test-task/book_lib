<?php

namespace common\models;

use yii\base\Model;

/**
 * Form model for author subscription.
 */
class AuthorSubscriptionForm extends Model
{
    /**
     * @var string
     */
    public $phone;

    /**
     * @var int
     */
    public $authorId;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone', 'authorId'], 'required'],
            [['authorId'], 'integer'],
            [['phone'], 'string', 'max' => 20],
            [['phone'], 'match', 'pattern' => '/^\+?[1-9]\d{1,14}$/', 'message' => 'Номер телефона должен быть в международном формате (например: +79001234567)'],
            [['authorId'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['authorId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'phone' => 'Номер телефона',
            'authorId' => 'Автор',
        ];
    }

    /**
     * Normalizes phone number format.
     *
     * @param string $phone
     *
     * @return string
     */
    public static function normalizePhone($phone)
    {
        $phone = preg_replace('/[^\d+]/', '', $phone);

        if (strpos($phone, '+') !== 0 && strlen($phone) === 11 && substr($phone, 0, 1) === '8') {
            $phone = '+7' . substr($phone, 1);
        } elseif (strpos($phone, '+') !== 0 && strlen($phone) === 11 && substr($phone, 0, 1) === '7') {
            $phone = '+' . $phone;
        } elseif (strpos($phone, '+') !== 0 && strlen($phone) === 10) {
            $phone = '+7' . $phone;
        }

        return $phone;
    }
}

