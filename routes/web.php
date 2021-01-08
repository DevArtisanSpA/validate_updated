<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BranchOfficeController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EmployeeController;
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
    Route::resource('users', UserController::class);
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('companies', CompanyController::class);
    Route::get('companies/rut/{rut}', [CompanyController::class, 'getCompanyByRut']);
    Route::get('services/associate', [ServiceController::class, 'create']);
    Route::post('services/associate', [ServiceController::class, 'store']);
    Route::get('services/{id}/edit', [ServiceController::class, 'edit']);
    Route::put('services/edit', [ServiceController::class, 'update']);
    Route::delete('services/{id}', [ServiceController::class, 'destroy']);
    Route::get('services', [ServiceController::class, 'index']);
    Route::resource('branch_offices', BranchOfficeController::class);
    // route for report view
    Route::get('/reports', [ReportController::class, 'index']);
    Route::get('/reports/{id}', [ReportController::class, 'show']);
    Route::resource('employees', EmployeeController::class)->except([
        'create', 'edit','update',
    ]);
    Route::get('employees/{id_service}/create', [EmployeeController::class, 'create']);
    Route::post('employees/update', [EmployeeController::class, 'update']);
    Route::get('employees/{id_service}/edit/{id_employee}', [EmployeeController::class, 'edit']);
    Route::get('documents/companies/base',[DocumentController::class,'companyBaseIndex']);
    Route::get('documents/companies/monthly',[DocumentController::class,'companyMonthlyIndex']);
    Route::get('documents/employees/base',[DocumentController::class,'employeeBaseIndex']);
    Route::get('documents/employees/monthly',[DocumentController::class,'employeeMonthlyIndex']);
});
