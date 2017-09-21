<?php

use Illuminate\Http\Request;

Route::post('auth/signup', 'RegistrationController@store');
Route::post('auth/login', 'SessionController@store');
Route::get('auth/whoami', 'SessionController@show')->middleware('api.auth');

Route::apiResource('posts', 'PostController');
Route::apiResource('posts/{post}/comments', 'CommentController');
Route::apiResource('categories', 'CategoryController');
Route::get('tags', 'TagController@index');
