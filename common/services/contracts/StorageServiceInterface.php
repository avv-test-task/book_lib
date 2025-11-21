<?php

declare(strict_types=1);

namespace common\services\contracts;

use yii\web\UploadedFile;

interface StorageServiceInterface
{
    public function saveCover(UploadedFile $file): string;

    public function delete(?string $path): void;
}


