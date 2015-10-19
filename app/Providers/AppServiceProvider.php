<?php namespace App\Providers;

use App\Contracts;
use App\Services;
use Illuminate\Support\Facades\Validator;
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
        Validator::extend('productQuality', function($attribute, $value, $parameters) {
            return !preg_match('/[^A-Za-z]/', $value)
                && preg_match('/eets|eats|uites|etes|ites|ettes$/', $value);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Contracts\Productable::class, Services\ProductService::class);
    }
}
