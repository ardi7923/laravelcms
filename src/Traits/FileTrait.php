<?php

namespace Ardi7923\Laravelcms\Traits;

use Storage;
use File;

trait FileTrait
{
    public function saveFile($filename, $path, $file)
    {
        try {
            if($file){
                Storage::putFileAs($path,$file,$filename);
                return str_replace("/public","/storage",$path."/").$filename;
            }else{
                return null;
            }

        }catch (\Exception $exception){
            throw $exception;
        }
    }

    public function updateFile($filename, $path, $file,$data)
    {
        try {
            if($file){
                if($data){
                    File::delete(substr($data,1));
                    Storage::putFileAs($path,$file,$filename);
                    return str_replace("/public","/storage",$path."/").$filename;
                }else{
                    Storage::putFileAs($path,$file,$filename);
                    return str_replace("/public","/storage",$path."/").$filename;
                }

            }else{
                return $data;
            }

        }catch (\Exception $exception){
            throw $exception;
        }
    }

    public function deleteFile($data)
    {
        File::delete(substr($data,1));
    }
}
