<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api', 'middleware' => 'cors'], function () {
    Route::get('products/findOrCreate', 'ProductController@findOrCreate');
    Route::resource('products', 'ProductController', ['only' => ['index', 'show', 'store', 'update']]);
    Route::resource('products.descriptions', 'ProductDescriptionController', ['only' => ['index', 'store']]);
    Route::group(['prefix' => 'descriptions/{descriptionId}'], function () {
        Route::post('vote-down', 'DescriptionController@voteDown');
        Route::post('vote-up', 'DescriptionController@voteUp');
    });
});
