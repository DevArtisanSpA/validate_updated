<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;

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

Auth::routes(["register" => false]);
Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('companies', CompanyController::class);
    Route::get('companies/rut/{rut}', [CompanyController::class, 'getCompanyByRut']);
    Route::get('services/associate', [ServiceController::class, 'create']);
    Route::post('services/associate', [ServiceController::class, 'store']);
    // route for report view
    Route::get('/reports', [ReportController::class, 'index']);
    Route::get('/reports/{id}', [ReportController::class, 'show']);


});



