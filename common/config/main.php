<?php

use common\services\BookService;
use common\services\contracts\BookServiceInterface;
use common\services\contracts\ReportServiceInterface;
use common\services\contracts\SmsServiceInterface;
use common\services\contracts\StorageServiceInterface;
use common\services\contracts\SubscriptionServiceInterface;
use common\services\LocalStorageService;
use common\services\ReportService;
use common\services\SmspilotSmsService;
use common\services\SubscriptionService;
use yii\caching\FileCache;
use yii\i18n\PhpMessageSource;

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'container' => [
        'definitions' => [
            BookServiceInterface::class => BookService::class,
            ReportServiceInterface::class => ReportService::class,
            StorageServiceInterface::class => LocalStorageService::class,
            SmsServiceInterface::class => SmspilotSmsService::class,
            SubscriptionServiceInterface::class => SubscriptionService::class,
        ],
    ],
    'components' => [
        'cache' => [
            'class' => FileCache::class,
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => PhpMessageSource::class,
                    'basePath' => '@common/messages',
                    'fileMap' => [
                        'app' => 'app.php',
                    ],
                ],
            ],
        ],
    ],
];

