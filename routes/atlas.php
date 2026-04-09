<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'AtlasController@index')->name('atlas.index');
Route::get('/export', 'AtlasController@export')->name('atlas.export');
Route::get('/{panel}', 'AtlasController@show')->name('atlas.show');
