<?php
use App\Services\FileService;
use Illuminate\Support\Facades\Config;


if (!function_exists('uploadFileHelper')) {
    function uploadFileHelper($request, $keyName)
    {
        $fileService = new FileService(
            Config::get('filesystems.default'),
            Config::get('filesystems.disks_upload_path_avatar')
        );
        $filename = mt_rand() . "_" . microtime(true) . "_" . $request->$keyName->getClientOriginalName();
        $url = $fileService->uploadFile($filename, $request->$keyName);
        return $fileService->getFilePath($url);
    }
}

if (!function_exists('uploadMultiFileHelper')) {
    function uploadMultiFileHelper($request, $keyName)
    {
        $fileService = new FileService(
            Config::get('filesystems.default'),
            Config::get('filesystems.disks_upload_path_avatar')
        );

        $multiFilePath = [];
        foreach ($request->$keyName as $item) {
            $filename = mt_rand() . "_" . microtime(true) . "_" . $item->getClientOriginalName();
            $url = $fileService->uploadFile($filename, $item);
            $multiFilePath[] = $fileService->getFilePath($url);
        }
        return $multiFilePath;
    }
}