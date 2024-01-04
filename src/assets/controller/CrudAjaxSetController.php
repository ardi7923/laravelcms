<?php

namespace App\Http\Controllers\controllernamespace;


use App\Models\modelPath;
use CrudAjaxSet;
requestpath;

class controllerName extends CrudAjaxSet
{
    protected $model     = modelName::class;
    protected $folder    = "folderPath";
    protected $url       = "urlName";
    protected $validator = requestName::class;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
}
