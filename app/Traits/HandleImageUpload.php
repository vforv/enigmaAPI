<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Carbon\Carbon;
use Illuminate\Support\Str;

trait HandleImageUpload
{
    public static function imageUploadHandler($images, $customPath = false)
    {
        $imagesArray = [];
        $path = "";
        if (is_iterable($images)) {
            if (!$customPath) {
                foreach ($images as $image) {
                    $id = round(microtime(true) * 1000) . "-" . Str::random(25);
                    $name = $id . "." . $image->getClientOriginalExtension();
                    $path = "images/photos/" . Carbon::now()->year . "/" . Carbon::now()->format('m') . "/";
                    $file = $image->storeAs($path, $name, 'uploadImage');
                    array_push($imagesArray, $name);
                }
            } else {
                foreach ($images as $image) {
                    $id = round(microtime(true) * 1000) . "-" . Str::random(25);
                    $name = $id . "." . $image->getClientOriginalExtension();
                    $path = "images/$customPath/";
                    $file = $image->storeAs($path, $name, 'uploadImage');
                    array_push($imagesArray, $name);
                }
            }
        }
        return ["images" => $imagesArray, "path" => $path];
    }
}
