<?php

namespace Ardi7923\Laravelcms;

use Ardi7923\Laravelcms\Crud;
use Ardi7923\Laravelcms\Services\ResponseService;

class CrudAjax extends Crud
{
    private $model,
            $request,
            $facade = null,
            $params = [];



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
                    $this->model->where($this->params)->update($data);
                } else {
                    $this->model->where($this->params)->update($this->request->except('_token', '_method'));
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
                $this->model->where($this->params)->delete();
            } else {
                $this->facade->delete($this->params);
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
