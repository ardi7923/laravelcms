<?php

namespace Ardi7923\Laravelcms\Console\Commands;

use Illuminate\Support\Str;
use Ardi7923\Laravelcms\Utilities\CommandUtility;
use File;

class CreateCrudAjaxControllerFile 
{
    use CommandUtility;

    private $name,
            $folder,
            $url,
            $model,
            $request;

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    public function setFolder($folder)
    {
        $this->folder = $folder;
        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    public function create(){

        $baseFolderApp = substr(dirname(__FILE__),0,-44)."/app";
        $baseFolderPackage = substr(dirname(__FILE__),0,-17);
        $pathExController = "/assets/controller/CrudAjaxController.php";

        if (Str::contains($this->name, '/')) {

            $controllerName = Str::afterLast($this->name, '/');
            $subFolder      = Str::beforeLast($this->name, '/');
            $file           = $baseFolderApp . "/Http/Controllers/$subFolder/".$controllerName.".php";
            $controllernamespace  = "\\".Str::replace("/","\\",$subFolder);

        }else{

            $controllerName = $this->name;
            $file           = $baseFolderApp . "/Http/Controllers/".$controllerName.".php";
            $controllerDir  = $baseFolderApp . "/Http/Controllers";
            $controllernamespace = "";
        }

        if (Str::contains($this->model, '/')) {
            $modelName      = Str::afterLast($this->model, '/');
            $modelFolder    = Str::beforeLast($this->model, '/');
            $modelPath      = "\\".Str::replace("/","\\",$this->model);
        }else{
            $modelName      = $this->model;
            $modelFolder    = "";
            $modelPath      = "\\".$this->model;
        }

        if($this->request){
            $requestName = Str::afterLast($this->request, '/');
            if (Str::contains($this->request, '/')) {
                $requestpath = "use App\Http\Requests"."\\".Str::replace("/","\\",$this->request).";";
                $requestName = $requestName;
            }else{

                $requestpath = "use App\Http\Requests"."\\".$requestName.";";
                $requestName = $requestName;
            }
        }else{
            $requestpath = "";
            $requestName = "Request";
        }
//        return $modelPath;


        File::copy($baseFolderPackage.$pathExController,$file);

        $str = file_get_contents($file);
        $str = str_replace("controllerName", $controllerName,$str);
        $str = str_replace("\controllernamespace", $controllernamespace,$str);

        $str = str_replace("\modelPath", $modelPath,$str);
        $str = str_replace("modelName", $modelName,$str);
        $str = str_replace("urlName", $this->url,$str);
        $str = str_replace("folderPath", $this->folder,$str);

        $str = str_replace("requestName", $requestName,$str);
        $str = str_replace("requestpath;", $requestpath,$str);



        file_put_contents($file, $str);

    }

}
