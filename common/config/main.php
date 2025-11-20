<?php

use common\services\BookService;
use common\services\contracts\BookServiceInterface;
use common\services\contracts\StorageServiceInterface;
use common\services\LocalStorageService;

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'container' => [
        'definitions' => [
            BookServiceInterface::class => BookService::class,
            StorageServiceInterface::class => LocalStorageService::class,
        ],
    ],
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
    ],
];

