<?php

namespace common\models;

use yii\base\Model;

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
     * @var string|null
     */
    public $verificationCode;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone', 'authorId'], 'required', 'message' => 'Поле "{attribute}" обязательно для заполнения.'],
            [['phone'], 'filter', 'filter' => 'trim'],
            [['phone'], 'string', 'max' => 20, 'min' => 1, 'tooShort' => 'Номер телефона не может быть пустым.'],
            [['authorId'], 'integer'],
            [['phone'], 'match', 'pattern' => '/^(\+7|8|7)?[\d]{10,11}$/', 'message' => 'Номер телефона должен быть в формате +79001234567, 89001234567 или 9001234567', 'skipOnEmpty' => false],
            [['phone'], 'validatePhone'],
            [['verificationCode'], 'string', 'length' => 4, 'skipOnEmpty' => true],
            [['verificationCode'], 'match', 'pattern' => '/^\d{4}$/', 'message' => 'Код подтверждения должен содержать 4 цифры.', 'skipOnEmpty' => true],
            [['authorId'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['authorId' => 'id']],
        ];
    }

    /**
     * @param string $attribute
     */
    public function validatePhone($attribute): void
    {
        $phone = static::normalizePhone($this->$attribute);

        if ($phone === '' || $phone === '0') {
            $this->addError($attribute, 'Номер телефона не может быть пустым.');
            return;
        }

        if (!preg_match('/^\+7\d{10}$/', $phone)) {
            $this->addError($attribute, 'Номер телефона должен быть в международном формате (например: +79001234567).');
            return;
        }

        $this->$attribute = $phone;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'phone' => 'Номер телефона',
            'authorId' => 'Автор',
            'verificationCode' => 'Код подтверждения',
        ];
    }

    /**
     * @param string $phone
     */
    public static function normalizePhone($phone): string
    {
        if (empty($phone)) {
            return '';
        }

        $phone = preg_replace('/[^\d+]/', '', trim($phone));

        if (empty($phone)) {
            return '';
        }

        if (strlen($phone) < 10 || strlen($phone) > 12) {
            return '';
        }

        if (strpos($phone, '+') === 0) {
            if (strlen($phone) === 12 && substr($phone, 0, 2) === '+7') {
                return $phone;
            }
            return '';
        }

        if (strlen($phone) === 11 && substr($phone, 0, 1) === '8') {
            return '+7' . substr($phone, 1);
        }

        if (strlen($phone) === 11 && substr($phone, 0, 1) === '7') {
            return '+' . $phone;
        }

        if (strlen($phone) === 10) {
            return '+7' . $phone;
        }

        return '';
    }
}

