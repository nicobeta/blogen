<?php

use Illuminate\Http\Request;

Route::post('auth/signup', 'RegistrationController@store');
Route::post('auth/login', 'SessionController@store');
Route::get('auth/whoami', 'SessionController@show')->middleware('api.auth');

Route::resource('posts', 'PostController')->except(['create', 'edit']);
Route::resource('posts/{post}/comments', 'CommentController')->except(['create', 'edit']);
Route::resource('categories', 'CategoryController')->except(['create', 'edit']);
Route::get('tags', 'TagController@index');
