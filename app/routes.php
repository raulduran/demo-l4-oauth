<?php

//TEST CLIENT
Route::get('/', 'ClientController@getHome');
Route::get('login/{id}', 'ClientController@getLogin');

//SERVER ROUTING
Route::get('logout/{id}', 'ServerController@getLogout');

Route::get('oauth', 'ServerController@getOAuth');

Route::get('check', 'ServerController@getCheck');

Route::post('access_token', 'ServerController@getToken');

Route::get('authorize', 'ServerController@getAuthorize');
Route::post('authorize', 'ServerController@postAuthorize');

Route::get('signin', 'ServerController@getSignin');
Route::post('signin', 'ServerController@postSignin');
