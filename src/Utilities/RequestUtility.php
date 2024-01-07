<?php

namespace Ardi7923\Laravelcms\Utilities;

trait RequestUtility
{
    
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    public function setFacade($facade)
    {
        $this->facade = $facade;
        return $this;
    }

    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    public function setFiles($files)
    {
        $this->files = $files;
        return $this;
    }

    public function InitializeParams($params)
    {
        return (is_array($params) ? $params : ['id' => $params]);
    }
}
