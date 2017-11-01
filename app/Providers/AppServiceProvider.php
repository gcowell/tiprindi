<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('valid_currency', function($attribute, $value, $parameters)
        {
            $list = Config::get('currencies.accepted_currencies');

            foreach ($list as $list_item)
            {
                if ($list_item == $value) return true;
            }
            return false;
        });

        Validator::replacer('valid_currency', function($message, $attribute, $rule, $params) {
            return str_replace('_', ' ' , 'The currency must be valid');
        });



        Validator::extend('greater_than', function($attribute, $value, $parameters)
        {
            $comparison_value = Input::get($parameters[0]);

            return isset($comparison_value) and intval($value) > intval($comparison_value);
        });


        Validator::replacer('greater_than', function($message, $attribute, $rule, $params) {
            return str_replace('_', ' ' , 'The '. $attribute .' must be greater than the ' .$params[0]);
        });



        Validator::extend('valid_release_type', function($attribute, $value, $parameters)
        {
            $list = Config::get('release.type');

            foreach ($list as $list_item)
            {
                if ($list_item == $value) return true;
            }
            return false;
        });

        Validator::replacer('valid_release_type', function($message, $attribute, $rule, $params) {
            return str_replace('_', ' ' , 'The release type must be valid');
        });

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
