<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\RoomsPhotos;

class RoomsPhotosMigrate extends Command
{
   

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RoomsPhotos:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create table to rooms photos';

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
      $a = new RoomsPhotos;
      $a->migratePhotos();
    }

   
}
