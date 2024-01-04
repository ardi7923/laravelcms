<?php

namespace Ardi7923\Laravelcms\Console\Commands;

use Ardi7923\Laravelcms\Console\Commands\CreateCrudAjaxSetFileController;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Ardi7923\Laravelcms\Utilities\CommandUtility;
use File;

class CrudAjaxSetCommand extends Command
{
    use CommandUtility;


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravelcms:crudAjaxSet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Simple Crud With ajax Set';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files, CreateCrudAjaxSetFileController $create_controller)
    {
        $this->files = $files;
        $this->path = app_path();
        $this->create_controller = $create_controller;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Crud Ajax Started......");
        sleep(1);

        $modelName = $this->ask('Whats Model name ?');
        $modelDir  = "$this->path/Models/$modelName.php";

        /**
         * check file model
         * if model not found return Fail
         */
        if (!$this->files->isFile($modelDir)) {
            return $this->error('Model Not Found');
        }

        $this->info("Model Found");
        $this->info(str_repeat(".", 25));
        sleep(1);

        // input controller name

        $inputControllerName = $this->ask('Controller name ? (ex: Admin/TesController or TesController)');

        /**
         * check Controller Name
         * FAIL if controller name null or empty
         */
        if ($inputControllerName == '' || $inputControllerName == null) {
            return $this->error('Controller Name Required');
        }

        // url input
        $url = $this->ask('Url ? (ex : tes/)');

        /**
         * check Url
         * FAIL if Url null or empty
         */
        if ($url == '' || $url == null) {
            return $this->error('Url Required');
        }

        // Folder input
        $folder = $this->ask('Blade Folder Path ? (ex : pages.tes.)');

        /**
         * check Folder Path
         * FAIL if controller name null or empty
         */
        if ($folder == '' || $folder == null) {
            return $this->error('Folder Path Required');
        }


        $requestName = '';
        $choiceRequest = $this->choice(
            'Did you create Request?',
            ['No', 'Yes'],
            'Yes',
            null,
            false
        );

        if ($choiceRequest === 'Yes') {
            $requestName = $this->ask('Request name ?');
        }

        // compile Controller
        $this->create_controller
            ->setModel($modelName)
            ->setName($inputControllerName)
            ->setUrl($url)
            ->setFolder($folder)
            ->setRequest(($choiceRequest === "Yes") ? $requestName : '')
            ->create();
//        $this->info($tes);
        $this->info('Controller Compiled !!');

        // compile Blade
        $this->call('laravelcms:bladeAjax', [
            'directory' => resource_path().'/views/'.str_replace('.','/',$folder)
        ]);

        // compile Request
        if ($choiceRequest === 'Yes') {
            $this->call('make:request', [
                'name' => $requestName
            ]);
        }

        $this->info(str_repeat('-',25));
        $this->info('Crud Ajax Genenerated');
    }
}
