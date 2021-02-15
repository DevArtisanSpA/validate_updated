<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BranchOfficeController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ReviewController;
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
    Route::get('employee/terminate', [EmployeeController::class, 'terminateEmployee']);
    Route::post('employee/terminate/{id}', [EmployeeController::class, 'terminate']);
    Route::post('employee/getEmployeeState', [EmployeeController::class, 'getEmployeeState']);

    Route::get('documents/companies/base', [DocumentController::class, 'companyBaseIndex']);
    Route::get('documents/companies/monthly/{monthYear}', [DocumentController::class, 'companyMonthlyIndex'])->name('companyMonthly');
    Route::get('documents/companies/monthly/', function () {
        return redirect()->route('companyMonthly', ['monthYear' => Carbon::now()->format('Y-m')]);
    });
    Route::get('services/{id_service}/documents/employees/base', [DocumentController::class, 'employeeBaseIndex']);
    Route::get('services/{id_service}/documents/employees/monthly/{monthYear}', [DocumentController::class, 'employeeMonthlyIndex'])->name('employeeMonthly');
    Route::get('services/{id_service}/documents/employees/monthly/', function () {
        return redirect()->route('employeeMonthly', ['monthYear' => Carbon::now()->format('Y-m')]);
    });
    Route::get('services/documents/employees/base', [DocumentController::class, 'employeePreTable']);
    Route::get('services/documents/employees/monthly/{monthYear}', [DocumentController::class, 'employeePreTable'])->name('docsReviewEmployeMonthly');
    Route::get('services/documents/employees/monthly/', function () {
        return redirect()->route('docsReviewEmployeMonthly', [
            'monthYear' => Carbon::now()->format('Y-m')
        ]);
    });

    Route::get('services/{id_service}/documents/companies/{id}/base/edit', [DocumentController::class, 'createEdit']);
    Route::get('services/{id_service}/documents/companies/{id}/monthly/{monthYear}/edit', [DocumentController::class, 'createEdit'])->name('createEditCompanyMonthly');
    Route::get('services/{id_service}/documents/companies/{id}/monthly/edit', function ($id_service, $id) {
        return redirect()->route('createEditCompanyMonthly', [
            $id_service, $id, 'monthYear' => Carbon::now()->format('Y-m')
        ]);
    });
    Route::get('services/{id_service}/documents/employees/{id}/base/edit', [DocumentController::class, 'createEdit']);
    Route::get('services/{id_service}/documents/employees/{id}/monthly/{monthYear}/edit', [DocumentController::class, 'createEdit'])->name('createEditEmployeeMonthly');
    Route::get('services/{id_service}/documents/employees/{id}/monthly/edit', function ($id_service, $id) {
        return redirect()->route('createEditEmployeeMonthly', [$id_service, $id, 'monthYear' => Carbon::now()->format('Y-m')]);
    });
    Route::post('documents/', [DocumentController::class, 'storeUpdate']);
    Route::post('documents/update', [DocumentController::class, 'update']);
    Route::get('documents/download/{id}', [DocumentController::class, 'download']);
    Route::post('documents/download/zip', [DocumentController::class, 'downloadZip']);
    Route::post('documents/delete', [DocumentController::class, 'destroy']);

    Route::get('review/employees/base', [ReviewController::class, 'employeePreTable']);
    Route::get('review/employees/monthly/{monthYear}', [ReviewController::class, 'employeePreTable'])->name('reviewServiceEmployeeMonthly');
    Route::get('review/employees/monthly/', function () {
        return redirect()->route('reviewServiceEmployeeMonthly', [
            'monthYear' => Carbon::now()->format('Y-m')
        ]);
    });
    Route::get('review/{id_service}/employees/base', [ReviewController::class, 'employeeBaseIndex']);
    Route::get('review/{id_service}/employees/monthly/{monthYear}', [ReviewController::class, 'employeeMonthlyIndex']);
    // Route::get('services/review/employees/base', [DocumentController::class, 'employeePreTable']);
    // Route::get('services/review/employees/monthly', [ReviewController::class, 'employeePreTable']);

    Route::get('review/companies/base', [ReviewController::class, 'companyBaseIndex']);
    Route::get('review/companies/monthly', [ReviewController::class, 'companyMonthlyIndex'])->name('reviewCompanyMonthly');

    Route::get('review/{id_service}/documents/companies/{id}/base', [ReviewController::class, 'edit']);
    Route::get('review/{id_service}/documents/companies/{id}/monthly/{monthYear}', [ReviewController::class, 'edit'])->name('reviewCompanyMonthly');
    Route::get('review/{id_service}/documents/companies/{id}/monthly', function ($id_service, $id) {
        return redirect()->route('reviewCompanyMonthly', [
            $id_service, $id, 'monthYear' => Carbon::now()->format('Y-m')
        ]);
    });
    Route::get('review/{id_service}/documents/employees/{id}/base', [ReviewController::class, 'edit']);
    Route::get('review/{id_service}/documents/employees/{id}/monthly/{monthYear}', [ReviewController::class, 'edit'])->name('reviewEmployeeMonthly');
    Route::get('review/{id_service}/documents/employees/{id}/monthly', function ($id_service, $id) {
        return redirect()->route('reviewEmployeeMonthly', [$id_service, $id, 'monthYear' => Carbon::now()->format('Y-m')]);
    });

    Route::post('mail/documents/load', [DocumentController::class, 'loadMail']);
    Route::post('mail/documents/response', [ReviewController::class, 'responseMail']);

    Route::get('/reports', [ReportController::class, 'index']);
    Route::get('/reports/{id}', [ReportController::class, 'show']);

    Route::get('/certificate/fixed', [ReportController::class, 'certificateFixed']);
    Route::get('/pdf/certificate/fixed/{id}', [PdfController::class, 'companyCertificate']);
    Route::post('/pdf/download/certificate/fixed', [PdfController::class, 'companyCertificateDownload']);
    Route::get('/certificate/eventual', [ReportController::class, 'certificateEventual']);
    Route::get('/pdf/certificate/eventual/{id}', [PdfController::class, 'companyCertificateEventual']);
    Route::post('/pdf/download/certificate/eventual', [PdfController::class, 'companyCertificateEventualDownload']);
    Route::get('/report/fixed', [ReportController::class, 'reportFixed']);
    Route::get('/pdf/report/fixed/{id}', [PdfController::class, 'companyReport']);
    Route::post('/pdf/download/report/fixed', [PdfController::class, 'companyReportDownload']);
    Route::get('/report/eventual', [ReportController::class, 'reportEventual']);
    Route::get('/pdf/report/eventual/{id}', [PdfController::class, 'companyReportEventual']);
    Route::post('/pdf/download/report/eventual', [PdfController::class, 'companyReportEventualDownload']);
    Route::post('/pdf/find', [PdfController::class, 'pdfFind']);
});
