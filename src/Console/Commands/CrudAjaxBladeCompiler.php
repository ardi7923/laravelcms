<?php

namespace Ardi7923\Laravelcms\Console\Commands;

use Illuminate\Console\Command;
use Ardi7923\Laravelcms\Utilities\CommandUtility;

class CrudAjaxBladeCompiler extends Command
{
    use CommandUtility;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravelcms:bladeAjax {directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compiler Blade for Ajax Crud';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $directory = $this->argument('directory');


        $fileNames = ['index','create','edit','delete','show'];
        $this->info("Compile Blade");
        $bar = $this->output->createProgressBar(count($fileNames));

        $bar->start();
        $this->line('');
        foreach($fileNames as $f){
            $this->createFile(  $directory.'/'. $f .'.blade.php', '<div></div>');
  
            $bar->advance();
            sleep(1);
        }
        
        $bar->finish();
        $this->line('');
        $this->info("Blade Compiled !!");
    }
}
