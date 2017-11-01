<?php

Route::get('/', 'PagesController@index');

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

//User routes
Route::get('users/dashboard', 'UserController@index');
Route::get('users/{id}', 'UserController@show');



//Listener Routes
Route::get('listeners', 'ListenersController@index');
Route::get('listeners/create', 'ListenersController@create');
Route::post('listeners/create', 'ListenersController@store');
Route::get('listeners/edit/{id}', 'ListenersController@edit');
Route::post('listeners/edit/{id}', 'ListenersController@update');






//Artist Routes
Route::get('artists', 'ArtistsController@index');
Route::get('artists/create', 'ArtistsController@create');
Route::post('artists/create', 'ArtistsController@store');
Route::get('artists/edit/{id}', 'ArtistsController@edit');
Route::post('artists/edit/{id}', 'ArtistsController@update');
Route::get('artists/show/{id}', 'ArtistsController@show');

//Release Routes
Route::get('releases/{id}', 'ReleasesController@index');
Route::get('releases/create/{id}', 'ReleasesController@create');
Route::post('releases/create/{id}', 'ReleasesController@store');
Route::get('releases/edit/{id}', 'ReleasesController@edit');
Route::post('releases/edit/{id}', 'ReleasesController@update');
Route::get('releases/show/{id}', 'ReleasesController@show');



//Track Routes
Route::get('tracks/{id}', 'TracksController@index');
Route::get('tracks/create/{id}', 'TracksController@create');
Route::post('tracks/create/{id}', 'TracksController@store');
Route::get('tracks/edit/{id}', 'TracksController@edit');
Route::get('tracks/show/{id}', 'TracksController@show');
Route::post('tracks/edit/{id}', 'TracksController@update');
Route::post('tracks/finish/{id}', 'TracksController@finish');


//Stream Routes
Route::get('stream/{id}', 'StreamsController@play');

//Stream Routes
Route::get('listener/test', 'ListenersController@test');




