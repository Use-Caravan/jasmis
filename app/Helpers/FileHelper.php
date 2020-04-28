<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Common;

class FileHelper
{

    /**
    * @param file $file
    * @param string $path
    * @return string file path
    */
    public static function uploadFile($file, $path = '')
    {   
        $uploadedPath = Storage::putFile($path, new File($file));
        return FILE_BASE_PATH.$uploadedPath;
    }


    /**
    * @param string $fromPathURL
    * @param string $toPath
    * @return boolean true/false
    */
    public static function copyFile($fromPathURL, $toPathName)
    {           
        $fromPath = str_replace(FILE_BASE_PATH,'',$fromPathURL);
        $file = pathinfo($fromPath);
        $toPath = $toPathName.'/'.$file['basename'];
        if(!Storage::exists($fromPath)) {
            return '';
        }
        if(Storage::exists($toPath)) {
            $file_name = md5($file['basename'].time()).'.'.$file['extension'];            
            Storage::put($toPathName.'/'.$file_name, file_get_contents(FILE_BASE_PATH.$toPath));
            return FILE_BASE_PATH.$toPath;
        } else {
            Storage::copy($fromPath, $toPath);
            return FILE_BASE_PATH.$toPath;
        }        
    }

    /**    
    * @param string $fileName
    * @return boolean
    */
    public static function deleteFile($fileName = '')
    {        
        return Storage::delete(str_replace(FILE_BASE_PATH, '', $fileName));
    }

    /**
     * @param string $filepath
     * @return string $imageUrl 
     */
    public static function loadImage($filepath = '')
    {        
        $imageUrl =  url(PLACEHOLDER_IMAGE);                
        if(Storage::exists(str_replace(FILE_BASE_PATH, '', $filepath))){
            $imageUrl =  url($filepath);                        
        }
        return $imageUrl;
    }
}