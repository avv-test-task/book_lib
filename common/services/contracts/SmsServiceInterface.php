<?php

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
    public function send($phone, $message);
}

