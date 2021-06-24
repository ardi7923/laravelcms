<?php

namespace Ardi7923\Laravelcms\Console\Commands;

use Illuminate\Support\Str;
use Ardi7923\Laravelcms\Utilities\CommandUtility;

class CreateControllerFile 
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

        $path = app_path();

        if (Str::contains($this->name, '/')) {

            $controllerName = Str::afterLast($this->name, '/');
            $file           = "${controllerName}.php";
            $subFolder      = Str::beforeLast($this->name, '/');
            $file           = $path . "/Http/Controllers/$subFolder/$file";
            $controllerDir  = $path . "/Http/Controllers/$subFolder";

        }else{

            $controllerName = $this->name;
            $file           = "${controllerName}.php";
            $file           = $path . "/Http/Controllers/$file";
            $controllerDir  = $path . "/Http/Controllers";
        }

        
        $controllerContent = $this->setContent($controllerDir,$controllerName, $this->model, $this->request, $this->url, $this->folder);

        return [
            'directory' => $controllerDir,
            'path'      => $path,
            'name'      => $controllerName,
            'file'      => $file,
            'content'   => $controllerContent
        ];
    }

    private function setContent($controllerDir,$controllerName, $modelName, $requestName, $url, $folder)
    {
        $requestImport = ($requestName == '' || $requestName == null) ? '' : 'use App\\Http\\Requests\\' . $requestName . ';';
        $requestParam  = ($requestName == '' || $requestName == null) ? 'Request': Str::afterLast($requestName, '/');
        $namespace = (Str::after($controllerDir, 'Controllers') != '') ? '\\'.Str::replaceFirst('/', '\\', (Str::after($controllerDir, 'Controllers/'))) : '';

        $contents = '<?php

namespace App\Http\Controllers'+ $namespace +';
        
use Illuminate\Http\Request;
use App\Models\\' . $modelName . '; 
use CrudAjax;
' . $requestImport . '

class ' . $controllerName . ' extends CrudAjax
{
    private :-:model;

    public function __construct(' . $modelName . ' :-:model)
    {
        :-:this->url    = "' . $url . '/";
        :-:this->folder = "' . $folder . '.";
        :-:this->model  = :-:model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request :-:request)
    {

        if ($request->ajax()) {
            return :-:this->datatable();
        }

        return view(:-:this->folder."index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return renderToJson(:-:this->folder."create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  :-:request
     * @return \Illuminate\Http\Response
     */
    public function store(' . $requestParam . ' :-:request)
    {
        return :-:this->setModel(:-:this->model)
                    ->setRequest(:-:request)
                    ->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  :-:id
     * @return \Illuminate\Http\Response
     */
    public function show(:-:id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  :-:id
     * @return \Illuminate\Http\Response
     */
    public function edit(:-:id)
    {
        :-:data = :-:this->model->findOrFail(:-:id);
        return renderToJson(:-:this->folder."edit",compact("data"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  :-:request
     * @param  int  :-:id
     * @return \Illuminate\Http\Response
     */
    public function update(' . $requestParam . ' :-:request, :-:id)
    {
        return :-:this->setModel(:-:this->model)
                    ->setRequest(:-:request)
                    ->setParams([ "id" => :-:id ])
                    ->change();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  :-:id
     * @return \Illuminate\Http\Response
     */
    public function destroy(:-:id)
    {
        return :-:this->setModel(:-:this->model)
                    ->setParams(["id" => :-:id])
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
        
    }
    
}';

        return $contents;
    }
}
