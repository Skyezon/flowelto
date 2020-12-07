<?php

use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return redirect(route('welcome'));
});

Auth::routes();

Route::get('home', 'CategoryController@index')->name('welcome');
Route::prefix('category')->group(function (){
    Route::get('/', 'CategoryController@manageCategory')->name('manageCategory');
    Route::delete('{id}', 'CategoryController@delete')->name('categoryDelete');
    Route::get('{id}','CategoryController@getProductByCategory')->name('home');
    Route::patch('{id}','CategoryController@update')->name('categoryUpdate');
    Route::get('update/{id}', 'CategoryController@edit')->name('categoryEdit');
});
Route::prefix('product')->group(function (){
    Route::get('search','ProductController@search')->name('productSearch');
    Route::delete('{id}','ProductController@softDelete')->name('productDelete');
    Route::get('{id}','ProductController@get')->name('productGet');
    Route::get('update/{id}','ProductController@showUpdatePage')->name('showUpdateProduct');
    Route::patch('update{id}','ProductController@update')->name('productUpdate');
});
