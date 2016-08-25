<?php

namespace PortalTest\Console\Commands;

use Illuminate\Console\Command;
use PortalTest\Http\Controllers\FilesController;

class Streamline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'picking
                        {input : Full path of the file to import}
                        {--output= : (optional) Full path of the output file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Take a file and generate another one sorted by pick_location';

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
     * @return mixed
     */
    public function handle()
    {
        $filepathIn = $this->argument('input');
        $filepathOut = $this->option('output');

        if($filepathIn == null)
        {
            $this->error('Please indicate the file path of the input file');
        }
        else {
            
            // Cleaning arguments and validating if a output has been set.
            str_replace($filepathIn, "\\", "\\\\");
            if(isset($filepathOut) && $filepathOut!=null) {
                str_replace($filepathOut, "\\", "\\\\");
                $result = FilesController::picking($filepathIn, $filepathOut);
            }
            else {
                $result = FilesController::picking($filepathIn);
            }
            
            if($result === true)
            {
                $this->info('File created.');
            }
            else
            {
                $this->error("Something went wrong!\r\n".$result);
            }
        }
    }
}
