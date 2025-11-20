<?php

declare(strict_types=1);

namespace common\services;

use common\models\AuthorSubscription;
use common\models\AuthorSubscriptionVerification;
use common\services\contracts\SmsServiceInterface;
use common\services\contracts\SubscriptionServiceInterface;
use DomainException;

class SubscriptionService implements SubscriptionServiceInterface
{
    private ?SmsServiceInterface $smsService;

    public function __construct(?SmsServiceInterface $smsService = null)
    {
        $this->smsService = $smsService;
    }

    public function subscriptionExists(int $authorId, string $phone): bool
    {
        return AuthorSubscription::find()
            ->where(['author_id' => $authorId, 'phone' => $phone])
            ->exists();
    }

    /**
     * @return array{success: bool, message: string}
     */
    public function sendVerificationCode(int $authorId, string $phone): array
    {
        if ($this->smsService === null) {
            return [
                'success' => false,
                'message' => 'Служба отправки SMS не настроена.',
            ];
        }

        $code = AuthorSubscriptionVerification::generateCode();
        $expiresAt = time() + 600;

        AuthorSubscriptionVerification::deleteAll([
            'author_id' => $authorId,
            'phone' => $phone,
        ]);

        $verification = new AuthorSubscriptionVerification();
        $verification->author_id = $authorId;
        $verification->phone = $phone;
        $verification->code = $code;
        $verification->expires_at = $expiresAt;

        if (!$verification->save()) {
            return [
                'success' => false,
                'message' => 'Ошибка при создании кода подтверждения.',
            ];
        }

        $message = "Ваш код подтверждения: {$code}";
        
        if (!$this->smsService->send($phone, $message)) {
            return [
                'success' => false,
                'message' => 'Не удалось отправить SMS. Попробуйте позже.',
            ];
        }

        return [
            'success' => true,
            'message' => 'Код подтверждения отправлен на ваш номер телефона.',
        ];
    }

    /**
     * @return array{success: bool, message: string, alreadySubscribed: bool}
     */
    public function verifyAndSubscribe(int $authorId, string $phone, string $code): array
    {
        $trimmedCode = trim($code);
        
        if ($trimmedCode === '' || $trimmedCode === '0') {
            return [
                'success' => false,
                'message' => 'Введите код подтверждения.',
                'alreadySubscribed' => false,
            ];
        }

        $verification = AuthorSubscriptionVerification::find()
            ->where([
                'author_id' => $authorId,
                'phone' => $phone,
                'code' => $trimmedCode,
            ])
            ->orderBy(['created_at' => SORT_DESC])
            ->one();

        if ($verification === null || $verification->isExpired()) {
            return [
                'success' => false,
                'message' => 'Неверный или устаревший код подтверждения.',
                'alreadySubscribed' => false,
            ];
        }

        $existingSubscription = AuthorSubscription::find()
            ->where(['author_id' => $authorId, 'phone' => $phone])
            ->one();

        if ($existingSubscription !== null) {
            AuthorSubscriptionVerification::deleteAll([
                'author_id' => $authorId,
                'phone' => $phone,
            ]);

            return [
                'success' => true,
                'message' => 'Вы уже подписаны на обновления этого автора.',
                'alreadySubscribed' => true,
            ];
        }

        $subscription = new AuthorSubscription();
        $subscription->author_id = $authorId;
        $subscription->phone = $phone;

        if (!$subscription->save()) {
            return [
                'success' => false,
                'message' => 'Ошибка при сохранении подписки.',
                'alreadySubscribed' => false,
            ];
        }

        AuthorSubscriptionVerification::deleteAll([
            'author_id' => $authorId,
            'phone' => $phone,
        ]);

        return [
            'success' => true,
            'message' => 'Вы успешно подписались на обновления!',
            'alreadySubscribed' => false,
        ];
    }
}

