<?php
namespace App\Manager;

use Intervention\Image\Facades\Image;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\UrlGenerator;



class ImageUploadManager
{
    public const DEFAULT_IMAGE ='images/hometex-logo.ico';
    /**
    *@param string $name
    *@param int $width
    *@param int $height
    *@param string $path
    *@param string $file
    *@param string
    */
    final public static function uploadImage(string $name, int $width, int $height, string $path, string $file):string
    {
        $image_file_name= $name.'.webp';
        Image::make($file)->fit($width, $height)->save(public_path($path).$image_file_name, 50, 'webp');
        return $image_file_name;
    }

    /**
    *@param string $path
    *@param string $img
    *@param void
    */
    final public static function deletePhoto(string $path, string $img):void
    {
        $path = public_path($path).$img;
        if($img !='' && file_exists($path)){
            unlink($path);
        }
    }

    /**
     *@param String $path
     *@param String|null $image
     *@return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\UrlGenerator|string
     */

    final public static function prepareImageUrl(string $path, string|null $image):string
    {
        $url = url($path.$image);
        if(empty($image)){
        $url = url(self::DEFAULT_IMAGE);
        }
        return $url;
    }

}
