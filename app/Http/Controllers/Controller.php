<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * rename image in Post
     * return path
     */
    static public function renameImage($image, $folder) {
        $extension = $image->getClientOriginalExtension();
        $str_random = Str::random(9);
        $img_path = $str_random.time().'.'.$extension;
        $image->move(public_path("media/$folder"), $img_path);
        return $img_path;
    }

    /**
     * resize image
     */
    static public function resizeImage($folder, $image_name, $width=800, $height=600) {
        $filePath = public_path("media/$folder").'/'.$image_name;
        $imgFile = Image::make($filePath);
        // resize width or height according to the other
        // if w=null, h=? -> resize w aspect ratio with h=?
        $imgFile->resize($width, $height, function ($constraint) {
		    $constraint->aspectRatio();
		})->save($filePath);
    }
}
