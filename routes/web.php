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
Route::post('cart/{id}', 'TransactionController@addToCart')->name('addToCart');

Route::middleware('auth')->group(function (){
    Route::get('change-password','ChangePasswordController@show')->name('showChangePassword');
    Route::post('change-password','ChangePasswordController@change')->name('changePassword');
});

Route::get('category/', 'CategoryController@manageCategory')->name('manageCategory');
Route::middleware('manager')->group(function () {
    Route::get('category/update/{id}', 'CategoryController@edit')->name('categoryEdit');
    Route::patch('category/{id}', 'CategoryController@update')->name('categoryUpdate');
    Route::delete('category/{id}', 'CategoryController@delete')->name('categoryDelete');
});
Route::get('category/{id}','CategoryController@getProductByCategory')->name('home');

Route::get('product/search','ProductController@search')->name('productSearch');
Route::middleware('manager')->group(function (){
    Route::get('product/update/{id}','ProductController@showUpdatePage')->name('showUpdateProduct');
    Route::patch('product/update{id}','ProductController@update')->name('productUpdate');
    Route::get('product/add','ProductController@showStorePage')->name('showProductStore');
    Route::post('product/add','ProductController@store')->name('productStore');
    Route::delete('product/{id}','ProductController@softDelete')->name('productDelete');
});
Route::get('product/{id}','ProductController@get')->name('productGet');

Route::middleware('user')->group(function() {
        Route::get('cart', 'TransactionController@cart')->name('userCart');
        Route::patch('cart/update/{id}', 'TransactionController@changeCartItemQty')->name('updateCartContent');

        Route::get('transaction/history', 'TransactionController@transactionHistory')->name('history');
        Route::get('transaction/history/{id}', 'TransactionController@transactionDetail')->name('transactionDetail');
        Route::post('transaction/checkout', 'TransactionController@checkout')->name('checkout');
});
