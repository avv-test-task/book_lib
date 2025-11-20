<?php

namespace common\services;

use common\services\contracts\SmsServiceInterface;
use Yii;
use yii\base\InvalidConfigException;

/**
 * SMS service implementation using smspilot.ru API.
 */
class SmspilotSmsService implements SmsServiceInterface
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $apiUrl = 'https://smspilot.ru/api.php';

    /**
     * @param string|null $apiKey
     *
     * @throws InvalidConfigException
     */
    public function __construct($apiKey = null)
    {
        $this->apiKey = $apiKey ?: Yii::$app->params['smspilot']['apiKey'] ?? null;

        if ($this->apiKey === null) {
            throw new InvalidConfigException('API ключ SMS не настроен.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function send($phone, $message)
    {
        $params = [
            'send' => $phone,
            'text' => $message,
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

        if ($httpCode !== 200) {
            Yii::error("Отправка SMS не удалась. HTTP код: {$httpCode}, Ответ: {$response}", __METHOD__);
            return false;
        }

        $result = json_decode($response, true);

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

