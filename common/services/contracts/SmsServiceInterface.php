<?php

declare(strict_types=1);

namespace common\services\contracts;

interface SmsServiceInterface
{
    public function send(string $phone, string $message): bool;
}

