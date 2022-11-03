<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/','App\Http\Controllers\IndexController@index');
Route::get('get-data','App\Http\Controllers\IndexController@getTable');
Route::get('update-data','App\Http\Controllers\IndexController@updateTable');
Route::get('export-data','App\Http\Controllers\IndexController@arrayToCsv');

Route::resource('upload','App\Http\Controllers\UploadController');

