<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ServiceController;

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
    if (Auth::user()) {
        return redirect('home');
    }
    return view('auth/login');
});

Auth::routes(["/register" => false]);
Route::group(['middleware' => ['auth']], function () {
    Route::resource('companies', 'CompanyController');
    Route::get('companies/rut/{rut}', 'CompanyController@getCompanyByRut');
    Route::get('services/associate', 'ServiceController@create');
});

Route::get('/home', "HomeController@index")->name('home');


