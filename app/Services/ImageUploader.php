<?php

namespace App\Services;

use File, Image;

class ImageUploader {

    public static function upload($file, $directory) 
    {
        $file_ext = $file->getClientOriginalExtension();
        $file_name = Helper::create_filename($file_ext);
        $thumbnail = ['height' => 500, 'width' => 500];

        $base_directory = 'storage/'.$directory;
        $resized_directory = $base_directory.'/resized';
        $thumbs_directory = $base_directory.'/thumbnails';

        if (! File::exists($base_directory)) {
            File::makeDirectory($base_directory, $mode = 0777, true, true);
        }

        if (! File::exists($resized_directory)) {
            File::makeDirectory($resized_directory, $mode = 0777, true, true);
        }

        if (! File::exists($thumbs_directory)) {
            File::makeDirectory($thumbs_directory, $mode = 0777, true, true);
        }

        $path = $file->move($base_directory, $file_name);

        if (in_array($file_ext, ['jpeg', 'jpg', 'png', 'gif'])) {
            Image::make($base_directory.'/'.$file_name)
                ->save($resized_directory.'/'.$file_name, 95);
            Image::make($base_directory.'/'.$file_name)
                ->crop($thumbnail['width'], $thumbnail['height'])
                ->save($thumbs_directory.'/'.$file_name, 95);
        }

        return [
            'file_path' => $path,
            'file_directory' => $base_directory,
            'file_name' => $file_name
        ];
    }
}