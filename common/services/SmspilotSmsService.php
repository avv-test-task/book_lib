<?php

declare(strict_types=1);

namespace common\services;

use common\services\contracts\SmsServiceInterface;
use Yii;
use yii\base\InvalidConfigException;

class SmspilotSmsService implements SmsServiceInterface
{
    private string $apiKey;

    private string $apiUrl = 'https://smspilot.ru/api.php';

    /**
     * @param string|null $apiKey
     *
     * @throws InvalidConfigException
     */
    public function __construct(?string $apiKey = null)
    {
        $this->apiKey = $apiKey ?: Yii::$app->params['smspilot']['apiKey'];

        if ($this->apiKey === null || $this->apiKey === '') {
            throw new InvalidConfigException('API ключ SMS не настроен.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function send(string $phone, string $message): bool
    {
        $params = [
            'to' => $phone,
            'send' => $message,
            'apikey' => $this->apiKey,
            'format' => 'json',
        ];

        $url = $this->apiUrl . '?' . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $httpCode !== 200) {
            Yii::error("Отправка SMS не удалась. HTTP код: {$httpCode}, Ответ: " . ($response ?: 'null'), __METHOD__);
            return false;
        }

        $result = json_decode($response, true);

        if (!is_array($result)) {
            Yii::error("Отправка SMS не удалась. Невалидный JSON ответ: {$response}", __METHOD__);
            return false;
        }

        if (isset($result['error'])) {
            Yii::error("Отправка SMS не удалась. Ошибка: {$result['error']['description']}", __METHOD__);
            return false;
        }

        if (isset($result['send'][0]['server_id'])) {
            Yii::info("SMS отправлено успешно. ID сервера: {$result['send'][0]['server_id']}", __METHOD__);
            return true;
        }

        Yii::error("Отправка SMS не удалась. Неожиданный ответ: {$response}", __METHOD__);
        return false;
    }
}

