<?php

use common\services\BookService;
use common\services\contracts\BookServiceInterface;
use common\services\contracts\ReportServiceInterface;
use common\services\contracts\StorageServiceInterface;
use common\services\LocalStorageService;
use common\services\ReportService;

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
        ],
    ],
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@common/messages',
                    'fileMap' => [
                        'app' => 'app.php',
                    ],
                ],
            ],
        ],
    ],
];

