<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('process', [
    'as' => 'process',
    'uses' => 'FileController@process'
]);

Route::get('processed-data', [
    'as' => 'processed.data',
    'uses' => 'FileController@processedData'
]);

Route::get('view-file/{name}', [
    'as' => 'processed.data',
    'uses' => 'FileController@viewFile'
]);



