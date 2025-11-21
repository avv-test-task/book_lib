<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/common',
        __DIR__ . '/backend',
        __DIR__ . '/frontend',
    ]);

    $rectorConfig->skip([
        __DIR__ . '/vendor',
        __DIR__ . '/*/tests',
        __DIR__ . '/*/runtime',
        __DIR__ . '/*/web/assets',
        __DIR__ . '/*/views',
        __DIR__ . '/*/web',
        __DIR__ . '/environments',
    ]);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_81,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::TYPE_DECLARATION,
    ]);

    // Disable parallel processing to avoid issues in Docker
    $rectorConfig->disableParallel();
};

