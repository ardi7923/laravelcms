<?php

namespace Ardi7923\Laravelcms;

use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;

class CrudAjaxSet extends CrudAjax
{
    protected $modalSize;

    public function __construct()
    {
        parent::__construct();
        $this->modalSize = $this->modalSize ?? "md";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->datatable();
        }

        return view($this->folder . 'index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return renderToJson($this->folder . 'create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->model->findOrFail($id);
        return renderToJson($this->folder . "edit", compact("data"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($this->validator) {
            $validator = Validator::make($request->all(), $this->validator->rules());

            if ($validator->fails()) {
                $errors =  $validator->errors()->getMessages();

                return $this->response
                    ->setCode(422)
                    ->setErrors($errors)
                    ->error();
            }
        }

        return $this->save();
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
        if ($this->validator) {
            $validator = Validator::make($request->all(), $this->validator->rules());

            if ($validator->fails()) {
                $errors =  $validator->errors()->getMessages();

                return $this->response
                    ->setCode(422)
                    ->setErrors($errors)
                    ->error();
            }
        }

        return $this->setParams($id)->change();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->setParams($id)->delete();
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
                    'size'        => $this->modalSize,
                    'url_destroy' => url($this->url . $data->id),
                    'detele_title'=> "data",
                    'delete_text' => view($this->folder . 'delete', compact('data'))->render()
                ]);
            })
            ->make(true);
    }
}
