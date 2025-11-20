<?php

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
    public function saveCover(UploadedFile $file);

    /**
     * Deletes the file at the given relative path if it exists.
     *
     * @param string $path
     */
    public function delete($path);
}


