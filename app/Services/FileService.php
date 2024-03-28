<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileService
{
    const DISKS = [
        'local' => 'public',
        'S3' => 'S3',
        'gsc' => 'gsc'
    ];
    const CONTENT_TYPES = [
        'csv' => 'text/csv',
        'image' => [
            'jpeg' => 'image/jpeg',
            'png' => 'image/png'
        ]
    ];
    protected $path;
    protected $disk;

    public function __construct($disk = 'public', $path = 'file')
    {
        $this->path = $path;
        $this->disk = $disk;
    }

    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setDisk($disk)
    {
        $this->disk = $disk;
        return $this;
    }

    public function getFilePath($filename)
    {
        return "{$this->path}/$filename";
    }

    public function getFullFilePath($filename)
    {
        $filePath = $this->getFilePath($filename);
        if (substr($filePath, 0, 1) == '/') {
            $filePath = substr($filePath, 1);
        }
        return Storage::disk($this->disk)->url('/') . $filePath;
    }

    public function getFile($filename)
    {
        $filePath = $this->getFilePath($filename);
        try {
            return Storage::disk($this->disk)->get($filePath);
        } catch (\Exception $e) {
            Log::error(__METHOD__ . ': Image Not Found ' . $e->getMessage());
        }
        return false;
    }

    public function uploadFile($filename, $file)
    {
        $filePath = "public/" . $this->getFilePath($filename);
        try {
            Storage::disk($this->disk)->put($filePath, file_get_contents($file));
            return $filename;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function downloadFile($filename, $contentType)
    {
        $filePath = $this->getFilePath($filename);
        try {
            $file = Storage::disk($this->disk)->get($filePath);
            $headers = [
                'Content-Type' => $contentType,
                'Content-Description' => 'File Transfer',
                'Content-Disposition' => "attachment; filename={$file}",
                'filename' => $file
            ];
            return response($file, 200, $headers);
        } catch (\Exception $e) {
            Log::error(__METHOD__ . ': Image Not Found ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteFile($filename)
    {
        $filePath = $this->getFilePath($filename);
        try {
            Storage::disk($this->disk)->delete($filePath);
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}