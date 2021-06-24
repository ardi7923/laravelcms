<?php

namespace Ardi7923\Laravelcms\Utilities;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

trait CommandUtility
{
    /**
     * Create File
     *
     * @param [string] $directory
     * @param [string] $file
     * @param [string] $filename
     * @param [string] $context
     * @return void
     */
    private function createFile($file, $content)
    {
        $directory = Str::beforeLast($file, '/');
        $filename  = Str::afterLast($file, '/');
        $files     = new Filesystem;

        // cek directory
        if ($files->isDirectory($directory)) {

            /**
             * if directory found
             */

            // cek file
            if ($files->isFile($file))
                // if file found
                return $this->error($filename . ' File Already exists!');

            // if fail create file
            if (!$files->put($file, str_replace(":-:", "$", $content)))

                return $this->error('Something went wrong!');

            // notif success create file
            $this->info("$filename generated!");

        } else {
            /**
             * if directory not found
             */

            //  create directory 
            $files->makeDirectory($directory, 0777, true, true);

            // if fail create file
            if (!$files->put($file, str_replace(":-:", "$", $content)))
                return $this->error('Something went wrong!');
            
            // notif success create file
            $this->info("$filename generated!");
        }
    }
}
