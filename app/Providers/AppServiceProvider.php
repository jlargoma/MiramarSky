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
