<?php

namespace Ardi7923\Laravelcms;

use Illuminate\Http\Request;
use DataTables;
use Ardi7923\Laravelcms\Services\ResponseService;
use Illuminate\Support\Facades\Validator;

class CrudAjaxSet extends CrudAjax
{    
    protected $folder;
    protected $model;
    protected $url;
    protected $request;
    protected $validator;

    public function __construct()
    {
        $this->model     = new $this->model;
        $this->validator = new $this->validator;
        $this->response  = new ResponseService;
    }

    public function index(Request $request)
    {
        if($request->ajax()){
            return $this->datatable();
        }

        return view($this->folder.'index');
    }

    public function create()
    {
        return renderToJson($this->folder.'create');
    }

    public function edit($id)
    {
        $data = $this->model->findOrFail($id);
        return renderToJson($this->folder."edit",compact("data"));
    }

    public function store(Request $request)
    {   
        if($this->validator){
            $validator = Validator::make($request->all(),$this->validator->rules());

	        if ($validator->fails()) {
	           $errors =  $validator->errors()->getMessages();

	           return $this->response
                            ->setCode(422)
                            ->setErrors($errors)
                            ->error();
	        }
		}

        return $this->setModel($this->model)
                    ->setRequest($request)
                    ->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($this->validator){
            $validator = Validator::make($request->all(),$this->validator->rules());

	        if ($validator->fails()) {
	           $errors =  $validator->errors()->getMessages();

	           return $this->response
                            ->setCode(422)
                            ->setErrors($errors)
                            ->error();
	        }
		}

        return $this->setModel($this->model)
                    ->setRequest($request)
                    ->setParams([ "id" => $id ])
                    ->change();
    }
     

    public function destroy($id)
    {
       return $this->setModel($this->model)
                   ->setParams(['id' => $id])
                   ->delete();
    }


        /**
     * json data for datatable.
     *
     * 
     * @return DataTables
     */
    public function datatable()
    {
        $data  = $this->model->query();
        
        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function ($data) {
            return view('components.datatables.action', [
                'data'        => $data,
                'url_edit'    => url($this->url . $data->id . '/edit'),
                'url_destroy' => url($this->url . $data->id),
                'delete_text' => view($this->folder . 'delete', compact('data'))->render()
            ]);
        })
        ->make(true); 
    }
}
