<?php

namespace App\Providers\v1;

use App\Services\v1;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class FlightServiceProvider extends ServiceProvider
{
    /** 
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('flightstatus', function ($attribute, $value, $parameters, $validator) {
            return (strpos($value, 'ontime') === 0 && strlen($value) === strlen('ontime')) || (strpos($value, 'delayed') === 0 && strlen($value) === strlen('delayed'));
            // return $value == 'ontime' || $value == 'delayed';
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FlightService::class, function ($app) {
            return new FlightService(); 
        });
    }
}
