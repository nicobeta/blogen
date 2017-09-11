<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('posts', 'PostController');

Route::post('auth/signup', 'RegistrationController@store');

Route::post('auth/login', 'SessionController@store');
Route::get('auth/whoami', 'SessionController@show');
