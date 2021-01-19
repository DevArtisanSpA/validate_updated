<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BranchOfficeController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EmployeeController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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
        'create', 'edit', 'update',
    ]);
    Route::get('services/{id_service}/employees/create', [EmployeeController::class, 'create']);
    Route::post('employees/update', [EmployeeController::class, 'update']);
    Route::get('services/{id_service}/employees/{id_employee}/edit', [EmployeeController::class, 'edit']);

    Route::get('documents/companies/base', [DocumentController::class, 'companyBaseIndex']);
    Route::get('documents/companies/monthly/{monthYear}', [DocumentController::class, 'companyMonthlyIndex'])->name('companyMonthly');
    Route::get('documents/companies/monthly/', function () {
        return redirect()->route('companyMonthly', ['monthYear' => Carbon::now()->format('Y-m')]);
    });
    Route::get('documents/employees/base', [DocumentController::class, 'employeeBaseIndex']);
    Route::get('documents/employees/monthly/{monthYear}', [DocumentController::class, 'employeeMonthlyIndex'])->name('employeeMonthly');
    Route::get('documents/employees/monthly/', function () {
        return redirect()->route('employeeMonthly', ['monthYear' => Carbon::now()->format('Y-m')]);
    });
    Route::get('services/{id_service}/documents/companies/{id}/base/edit', [DocumentController::class, 'createEdit']);
    Route::get('services/{id_service}/documents/companies/{id}/monthly/{monthYear}/edit', [DocumentController::class, 'createEdit'])->name('createEditCompanyMonthly');
    Route::get('services/{id_service}/documents/companies/{id}/monthly/edit', function ($id_service,$id) {
        return redirect()->route('createEditCompanyMonthly', [
            $id_service,$id, 'monthYear' => Carbon::now()->format('Y-m')
        ]);
    });
    Route::get('services/{id_service}/documents/employees/{id}/base/edit', [DocumentController::class, 'createEdit']);
    Route::get('services/{id_service}/documents/employees/{id}/monthly/{monthYear}/edit', [DocumentController::class, 'createEdit'])->name('createEditEmployeeMonthly');
    Route::get('services/{id_service}/documents/employees/{id}/monthly/edit', function ($id_service,$id) {
        return redirect()->route('createEditEmployeeMonthly', [$id_service,$id, 'monthYear' => Carbon::now()->format('Y-m')]);
    });
    Route::post('documents/', [DocumentController::class, 'storeUpdate']);
    Route::get('documents/download/{id}', [DocumentController::class, 'download']);
    Route::post('documents/download/zip', [DocumentController::class, 'downloadZip']);
    Route::post('documents/delete', [DocumentController::class, 'destroy']);
});
