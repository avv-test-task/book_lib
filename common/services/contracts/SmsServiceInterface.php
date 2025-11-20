<?php

declare(strict_types=1);

namespace common\services\contracts;

interface SmsServiceInterface
{
    /**
     * Sends SMS message to phone number.
     *
     * @param string $phone
     * @param string $message
     *
     * @return bool
     */
    public function send(string $phone, string $message): bool;
}

