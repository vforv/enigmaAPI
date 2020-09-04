<?php

namespace App\Traits;

trait HandleImageOptimization
{
    public static function handleImageOptimization(int $maxWidth, bool $thumbnail, array $images, string $path)
    {
        $imagesArray = implode(",", $images);
        $thumb = $thumbnail ? "true" : "false";
        $command = "./flexImageOptimizer -mw=$maxWidth -thumb=$thumb -images=$imagesArray -path=/$path 2>&1";
        json_decode(exec($command, $output, $return_var));
        return $command;
    }
}
