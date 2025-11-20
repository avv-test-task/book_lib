<?php

declare(strict_types=1);

namespace common\services;

use common\services\contracts\StorageServiceInterface;
use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Local filesystem storage implementation for uploaded files.
 */
class LocalStorageService implements StorageServiceInterface
{
    private string $basePath;
    private string $baseUrl;

    /**
     * @param string|null $basePath
     * @param string|null $baseUrl
     */
    public function __construct(?string $basePath = null, ?string $baseUrl = null)
    {
        if ($basePath === null) {
            $this->basePath = Yii::getAlias('@frontend') . '/web/uploads/covers';
        } else {
            $this->basePath = $basePath;
        }
        
        $this->baseUrl = $baseUrl ?: '/uploads/covers';
    }

    /**
     * {@inheritdoc}
     */
    public function saveCover(UploadedFile $file): string
    {
        FileHelper::createDirectory($this->basePath);

        $fileName = $this->generateFileName($file);
        $filePath = $this->basePath . DIRECTORY_SEPARATOR . $fileName;

        if (!$file->saveAs($filePath)) {
            throw new \RuntimeException('Failed to save uploaded file.');
        }

        return $this->baseUrl . '/' . $fileName;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }

        $relative = '';
        if (strpos($path, $this->baseUrl) === 0) {
            $relative = ltrim(substr($path, strlen($this->baseUrl)), '/');
        } else {
            $relative = ltrim($path, '/\\');
        }

        $filePath = $this->basePath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative);

        if (is_file($filePath)) {
            @unlink($filePath);
        }
    }

    /**
     * @param UploadedFile $file
     *
     * @return string
     */
    private function generateFileName(UploadedFile $file): string
    {
        $hash = sha1(uniqid($file->baseName, true) . microtime(true));

        return $hash . '.' . $file->extension;
    }
}


