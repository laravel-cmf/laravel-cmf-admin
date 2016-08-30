<?php

namespace LaravelCMF\Admin\Http\Controllers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use LaravelCMF\Admin\CMF;

class AssetController extends BaseController
{
    public $contentTypes = [
        "css" => "text/css",
        "js" => "application/javascript",
        "gif" => "image/gif",
        "png" => "image/png",
        "jpeg" => "image/jpg",
        "jpg" => "image/jpg",
//        "pdf" => "application/pdf",
//        "exe" => "application/octet-stream",
//        "zip" => "application/zip",
//        "docx" => "application/msword",
//        "doc" => "application/msword",
//        "xls" => "application/vnd.ms-excel",
//        "ppt" => "application/vnd.ms-powerpoint",
//        "mp3" => "audio/mpeg",
//        "wav" => "audio/x-wav",
//        "mpeg" => "video/mpeg",
//        "mpg" => "video/mpeg",
//        "mpe" => "video/mpeg",
//        "mov" => "video/quicktime",
//        "avi" => "video/x-msvideo",
//        "3gp" => "video/3gpp",
//        "jsc" => "application/javascript",
//        "php" => "text/html",
//        "htm" => "text/html",
//        "html" => "text/html"
    ];

    public function getAsset(Request $request, $filePath)
    {
        $file     = CMF::asset_path($filePath);
        $fileData = null;
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        if (array_key_exists($extension, $this->contentTypes)) {
            $type = $this->contentTypes[$extension];
        } else {
            abort(500, 'Invalid extension.');
        }

        if (file_exists($file)) {
            $fileData = file_get_contents($file);
        } else {
            abort(404);
        }




        return response($fileData, 200)
            ->header('Content-Type', $type);
    }

    public function getUpload(Request $request, $filePath)
    {

        /** @var Filesystem $disk */
        $disk = Storage::disk(CMF::configGet('disk', 'public'));

        $filePath = 'uploads/'.$filePath;
        if(!$disk->exists($filePath)) abort(404);

        $file = $disk->get($filePath);
        $type = $disk->mimeType($filePath);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }
}