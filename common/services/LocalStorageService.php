<?php

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
    /**
     * @var string
     */
    private $basePath;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @param string|null $basePath
     * @param string|null $baseUrl
     */
    public function __construct($basePath = null, $baseUrl = null)
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
    public function saveCover(UploadedFile $file)
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
    public function delete($path)
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
    private function generateFileName(UploadedFile $file)
    {
        $hash = sha1(uniqid($file->baseName, true) . microtime(true));

        return $hash . '.' . $file->extension;
    }
}


