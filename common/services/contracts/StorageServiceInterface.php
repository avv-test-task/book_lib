<?php

declare(strict_types=1);

namespace common\services\contracts;

use yii\web\UploadedFile;

interface StorageServiceInterface
{
    /**
     * Saves the given uploaded file and returns the relative path.
     *
     * @param UploadedFile $file
     *
     * @return string
     */
    public function saveCover(UploadedFile $file): string;

    /**
     * Deletes the file at the given relative path if it exists.
     *
     * @param string|null $path
     */
    public function delete(?string $path): void;
}


