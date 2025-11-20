<?php

declare(strict_types=1);

namespace common\services\contracts;

interface SubscriptionServiceInterface
{
    public function subscriptionExists(int $authorId, string $phone): bool;

    /**
     * @return array{success: bool, message: string}
     */
    public function sendVerificationCode(int $authorId, string $phone): array;

    /**
     * @return array{success: bool, message: string, alreadySubscribed: bool}
     */
    public function verifyAndSubscribe(int $authorId, string $phone, string $code): array;

    public function cancelVerification(int $authorId, string $phone): void;
}

