<?php

namespace Ardi7923\Laravelcms;

use Ardi7923\Laravelcms\Crud;
use Ardi7923\Laravelcms\Services\ResponseService;
use Ardi7923\Laravelcms\Traits\FileTrait;
use Faker\Core\File;

class CrudAjax extends Crud
{
    use FileTrait;
    private $facade = null,
            $params = [];

    public function __construct()
    {
        parent::__construct();
        $this->response  = new ResponseService;
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

    // save method =========================================
    public function save($data = [])
    {
        $response     = new ResponseService;
        try {

            if ($this->facade == null) {
                if ($data) {
                    $this->model->create($data);
                } else {
                    $this->model->create($this->request->except('_token'));
                }
            } else {
                $this->facade->save($this->request);
            }

            return $response->setCode(200)
                ->setMsg("Data Berhasil Disimpan")
                ->success();
        } catch (\Exception $e) {

            $errors = [$e->getMessage()];

            return $response->setErrors($errors)->error();
        }
    }

    // update ========================================
    public function change($data = [])
    {
        $response     = new ResponseService;

        try {

            if ($this->facade == null) {

                if ($data) {
                    $this->model->where($this->InitializeParams($this->params))->update($data);
                } else {
                    $this->model->where($this->InitializeParams($this->params))->update($this->request->except('_token', '_method'));
                }
            } else {
                $this->facade->update($this->request, $this->params);
            }

            return $response->setCode(200)
                ->setMsg("Data Berhasil Diubah")
                ->success();
        } catch (\Exception $e) {

            $errors = [$e->getMessage()];
            return $response->setErrors($errors)->error();
        }
    }
    // Delete =======================================
    public function delete()
    {
        $response = new ResponseService;

        try {

            if ($this->facade == null) {
                $data = $this->model->where($this->InitializeParams($this->params))->firstOrFail();

                if($this->files){
                    if(is_array($this->files)){
                        foreach ($this->files as $file){
                            $field_file = $this->files;
                            $file_name = $data->$field_file;
                            $this->deleteFile($file_name);
                        }
                    }else{
                        $field_file = $this->files;
                        $file = $data->$field_file;

                        $this->deleteFile($file);

                    }
                }
                $data->delete();
            } else {
                $this->facade->delete($this->InitializeParams($this->params));
            }

            return $response->setCode(200)
                ->setMsg("Data Berhasil Dihapus")
                ->success();
        } catch (\Exception $e) {

            $errors = [$e->getMessage()];
            return $response->setErrors($errors)->error();
        }
    }
}
