<?php

namespace Ardi7923\Laravelcms;

use App\Http\Controllers\Controller;
use Ardi7923\Laravelcms\Utilities\RequestUtility;

class Crud extends Controller
{
    use RequestUtility;

    protected $model;
    protected $folder;
    protected $url;
    protected $validator;
    

    public function __construct()
    {
        $this->model = new $this->model;
        $this->request = app('request');
        if($this->validator){
            $this->validator = new $this->validator;
        }

        
    }
}
