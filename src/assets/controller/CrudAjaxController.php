<?php

namespace App\Http\Controllers\controllernamespace;


use App\Models\modelPath;
use CrudAjax;
use Illuminate\Http\Request;
use DataTables;
requestpath

class controllerName extends CrudAjax
{
    protected $model  = modelName::class;
    protected $url    = "urlName";
    protected $folder = "folderPath";

    public function __construct()
    {

        parent::__construct($this);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            return $this->datatable($request);
        }

        return view($this->folder . "index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return renderToJson($this->folder . "create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(requestName $request)
    {
        return $this->save();
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(requestName $request, $id)
    {
        return $this->setParams($id)
            ->change();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->setParams($id)
            ->delete();
    }

    /**
     * json data for datatable.
     *
     *
     * @return DataTables
     */
    public function datatable($request)
    {
        $datas = $this->model->query();

        return DataTables::of($datas)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return view('components.datatables.action', [
                    'data'        => $data,
                    'size'        => 'md',
                    'url_show'    => url($this->url . $data->id),
                    'url_edit'    => url($this->url . $data->id . '/edit'),
                    'url_destroy' => url($this->url . $data->id),
                    'detele_title'=> "data",
                    'delete_text' => view($this->folder . 'delete', compact('data'))->render()
                ]);
            })
            ->make(true);
    }

}