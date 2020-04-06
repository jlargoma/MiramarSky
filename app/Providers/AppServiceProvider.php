<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      //Show in apartamentosierranevada
      $this->app['config']['show_ASN'] = true;
      //show in riadpuertasdelalbaicin
      $this->app['config']['show_RPA'] = false;
      
      if (env('APP_ENV') == 'VIRTUAL') return '';
      if (isset($_SERVER["HTTP_HOST"])){
        if(!(isset($_SERVER["HTTPS"])) || $_SERVER["HTTPS"] != "on")
        {
            header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
            exit();
        }
      }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
