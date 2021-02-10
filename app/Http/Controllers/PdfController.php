<?php

namespace App\Http\Controllers;

use App\Helper\Functions;
use App\Mail\MailWelcome;
use App\Models\Company;
use App\Models\Document;
use App\Models\Employee;
use App\Models\Service;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use PDF;

class PdfController extends Controller
{
    public function companyCertificate()
    {
        $service = Service::whereId(1)->complete()->with([
            'company.branchOffices.commune.region',
            'company.commercialBusiness',
            'branchOffice.commune.region',
            'employees'
        ])->first();
        $principal = clone ($service->branchOffice->company);
        unset($service->branchOffice->company);
        $principal->branchOffice = $service->branchOffice;
        $contractor = $service->company;
        $contractor->branchOffice = $service->company->branchOffices[0];
        unset($service->company->branchOffices);
        $employees = $service->employees;
        //view()->share('principal',$principal);
        $today = Carbon::now('America/Santiago');
        $year = $today->year;
        $month = $today->locale('es')->monthName;
        $day = $today->day;
        $dayName = $today->locale('es')->dayName;
        $expirationDate = $today->addDays(25)->format('d-m-Y');
        $documents = Document::where('service_id', 1)->with('type:id,name')->wherehas('type', function ($query) {
            return $query->whereName('Traslado')
                ->orWhere('name', 'Licencia Medica')
                ->orWhere('name', 'Finiquito')
                ->orWhere('name', 'Contrato de Trabajo');
        })->where(function ($query) {
            $yearMonth = Carbon::now()->format('Y-m');
            return $query->where('month_year_registry', $yearMonth)->orWhere(function ($query) use ($yearMonth) {
                return  $query->where('month_year_registry', null)->whereDate('start', '>=', $yearMonth . '-01');
            });
        })->get()->groupBy('type.name');
        try {
            $settlement = count($documents['Finiquito']);
        } catch (\Throwable $th) {
            $settlement = 0;
        }
        try {
            $contracts = count($documents['Contrato de Trabajo']);
        } catch (\Throwable $th) {
            $contracts = 0;
        }
        try {
            $transfers = count($documents['Traslado']);
        } catch (\Throwable $th) {
            $transfers = 0;
        }
        try {
            $licenses = count($documents['Licencia Medica']);
        } catch (\Throwable $th) {
            $licenses = 0;
        }
        $resumen = (object)[
            'settlement' => $settlement,
            'contracts' => $contracts,
            'transfers' => $transfers,
            'licenses' => $licenses,
        ];
        $data = [
            'principal' => $principal,
            'contractor' => $contractor,
            'employees' => $employees,
            'today' => $day . ' de ' . $month . ' de ' . $year,
            'expirationDate' => $expirationDate,
            'resumen' => $resumen
        ];
        return view('pdfs/certificate', [
            'dataList' => json_encode([
                'principal' => $principal,
                'contractor' => $contractor,
                'employees' => $employees,
                'today' => ucfirst($dayName) . ', ' . $day . ' de ' . $month . ' de ' . $year,
                'expirationDate' => $expirationDate,
                'resumen' => $resumen
            ]),

        ]);
    }
    public function companyCertificateEventual()
    {
        $service = Service::whereId(1)->complete()->with([
            'company.branchOffices.commune.region',
            'branchOffice.commune.region',
            'employees.jobType',
            'company.commercialBusiness',
        ])->first();
        $principal = clone ($service->branchOffice->company);
        unset($service->branchOffice->company);
        $principal->branchOffice = $service->branchOffice;
        $contractor = $service->company;
        $contractor->branchOffice = $service->company->branchOffices[0];
        unset($service->company->branchOffices);
        $employees = $service->employees;
        $today = Carbon::now('America/Santiago');
        $year = $today->year;
        $month = $today->locale('es')->monthName;
        $day = $today->day;
        $dayName = $today->locale('es')->dayName;
        $expirationDate = $today->addDays(20)->format('d-m-Y');
        $documents = Document::where('service_id', 1)->with('type:id,name')->wherehas('type', function ($query) {
            return $query->whereName('Traslado')
                ->orWhere('name', 'Licencia Medica')
                ->orWhere('name', 'Finiquito')
                ->orWhere('name', 'Contrato de Trabajo');
        })->where(function ($query) {
            $yearMonth = Carbon::now()->format('Y-m');
            return $query->where('month_year_registry', $yearMonth)->orWhere(function ($query) use ($yearMonth) {
                return  $query->where('month_year_registry', null)->whereDate('start', '>=', $yearMonth . '-01');
            });
        })->get()->groupBy('type.name');
        try {
            $settlement = count($documents['Finiquito']);
        } catch (\Throwable $th) {
            $settlement = 0;
        }
        try {
            $contracts = count($documents['Contrato de Trabajo']);
        } catch (\Throwable $th) {
            $contracts = 0;
        }
        try {
            $transfers = count($documents['Traslado']);
        } catch (\Throwable $th) {
            $transfers = 0;
        }
        try {
            $licenses = count($documents['Licencia Medica']);
        } catch (\Throwable $th) {
            $licenses = 0;
        }
        $resumen = (object)[
            'settlement' => $settlement,
            'contracts' => $contracts,
            'transfers' => $transfers,
            'licenses' => $licenses,
        ];
        $data = [
            'principal' => $principal,
            'contractor' => $contractor,
            'employees' => $employees,
            'today' => $day . ' de ' . $month . ' de ' . $year,
            'expirationDate' => $expirationDate,
            'resumen' => $resumen
        ];
        \Debugbar::info($employees);
        // view()->share('data', $data);
        // $pdf = PDF::loadView('pdfs\certificateEventual');
        return view('pdfs/certificateEventual', [
            'dataList' => json_encode([
                'principal' => $principal,
                'contractor' => $contractor,
                'employees' => $employees,
                'today' => ucfirst($dayName) . ', ' . $day . ' de ' . $month . ' de ' . $year,
                'expirationDate' => $expirationDate,
                'resumen' => $resumen
            ]),
        ]);
    }
    public function companyReport()
    {
        $service = Service::whereId(1)->complete()->with([
            'company.branchOffices.commune.region',
            'branchOffice.commune.region',
            'employees.jobType',
            'company.commercialBusiness',
        ])->first();
        $principal = clone ($service->branchOffice->company);
        unset($service->branchOffice->company);
        $principal->branchOffice = $service->branchOffice;
        $contractor = $service->company;
        $contractor->branchOffice = $service->company->branchOffices[0];
        unset($service->company->branchOffices);
        $employees = $service->employees;
        $today = Carbon::now('America/Santiago');
        $year = $today->year;
        $month = $today->locale('es')->monthName;
        $day = $today->day;
        $dayName = $today->locale('es')->dayName;
        $expirationDate = $today->addDays(25)->format('d-m-Y');
        $documents = Document::where('service_id', 1)->with(['type:id,name', 'employee'])->wherehas('type', function ($query) {
            return $query->whereName('Traslado')
                ->orWhere('name', 'Licencia Medica')
                ->orWhere('name', 'Finiquito')
                ->orWhere('name', 'Contrato de Trabajo');
        })->where(function ($query) {
            $yearMonth = Carbon::now()->format('Y-m');
            return $query->where('month_year_registry', $yearMonth)->orWhere(function ($query) use ($yearMonth) {
                return  $query->where('month_year_registry', null)->whereDate('start', '>=', $yearMonth . '-01');
            });
        })->get()->groupBy('type.name');

        try {
            $settlements = $documents['Finiquito'];
        } catch (\Throwable $th) {
            $settlements = [];
        }
        try {
            $contracts = $documents['Contrato de Trabajo'];
        } catch (\Throwable $th) {
            $contracts = [];
        }
        try {
            $transfers = $documents['Traslado'];
        } catch (\Throwable $th) {
            $transfers = [];
        }
        try {
            $licenses = $documents['Licencia Medica'];
        } catch (\Throwable $th) {
            $licenses = [];
        }
        $resumen = (object)[
            'settlements' => $settlements,
            'contracts' => $contracts,
            'transfers' => $transfers,
            'licenses' => $licenses,
        ];
        $data = json_encode([
            'principal' => $principal,
            'contractor' => $contractor,
            'employees' => $employees,
            'today' => $day . ' de ' . $month . ' de ' . $year,
            'expirationDate' => $expirationDate,
            'resumen' => $resumen
        ]);
        view()->share('dataList', $data);
        // Mail::to('alma@devartisan.cl')->send(new MailWelcome('123456789', $employee));
        // return $pdf->download('pdfview.pdf');
        return view('pdfs\report');
    }
    public function companyReportEventual()
    {
        $service = Service::whereId(1)->complete()->with([
            'company.branchOffices.commune.region',
            'branchOffice.commune.region',
            'employees.jobType',
            'company.commercialBusiness',
        ])->first();
        $principal = clone ($service->branchOffice->company);
        unset($service->branchOffice->company);
        $principal->branchOffice = $service->branchOffice;
        $contractor = $service->company;
        $contractor->branchOffice = $service->company->branchOffices[0];
        unset($service->company->branchOffices);
        $employees = $service->employees;
        $today = Carbon::now('America/Santiago');
        $year = $today->year;
        $month = $today->locale('es')->monthName;
        $day = $today->day;
        $dayName = $today->locale('es')->dayName;
        $expirationDate = $today->addDays(25)->format('d-m-Y');
        $documents = Document::where('service_id', 1)->with(['type:id,name', 'employee'])->wherehas('type', function ($query) {
            return $query->whereName('Traslado')
                ->orWhere('name', 'Licencia Medica')
                ->orWhere('name', 'Finiquito')
                ->orWhere('name', 'Contrato de Trabajo');
        })->where(function ($query) {
            $yearMonth = Carbon::now()->format('Y-m');
            return $query->where('month_year_registry', $yearMonth)->orWhere(function ($query) use ($yearMonth) {
                return  $query->where('month_year_registry', null)->whereDate('start', '>=', $yearMonth . '-01');
            });
        })->get()->groupBy('type.name');

        try {
            $settlements = $documents['Finiquito'];
        } catch (\Throwable $th) {
            $settlements = [];
        }
        try {
            $contracts = $documents['Contrato de Trabajo'];
        } catch (\Throwable $th) {
            $contracts = [];
        }
        try {
            $transfers = $documents['Traslado'];
        } catch (\Throwable $th) {
            $transfers = [];
        }
        try {
            $licenses = $documents['Licencia Medica'];
        } catch (\Throwable $th) {
            $licenses = [];
        }
        $resumen = (object)[
            'settlements' => $settlements,
            'contracts' => $contracts,
            'transfers' => $transfers,
            'licenses' => $licenses,
        ];
        $data = json_encode([
            'principal' => $principal,
            'contractor' => $contractor,
            'employees' => $employees,
            'today' => $day . ' de ' . $month . ' de ' . $year,
            'expirationDate' => $expirationDate,
            'resumen' => $resumen
        ]);
        view()->share('dataList', $data);
        // Mail::to('alma@devartisan.cl')->send(new MailWelcome('123456789', $employee));
        // return $pdf->download('pdfview.pdf');
        return view('pdfs\reportEventual');
    }
    public function companyCertificateDownload(Request $request)
    {
        $input = $request->all();

        $form = (object) $input['form'];
        $principal = (object) $input['principal'];
        $contractor = (object) $input['contractor'];
        $employees = (object) $input['employees'];
        view()->share('form', $form);
        view()->share('expirationDate', $input['expirationDate']);
        view()->share('today', $input['today']);
        view()->share('folio', $input['folio']);
        view()->share('principal', $principal);
        view()->share('contractor', $contractor);
        view()->share('employees', $employees);
        // return view('pdfs/certificatePdf');
        $pdf = PDF::loadView('pdfs\certificatePdf');
        if (!file_exists(storage_path('app\\uploads'))) {
            File::makeDirectory(storage_path('app\\uploads'));
        }

        $pdf->save(storage_path('app\\uploads\\') . 'archivo.pdf');
        return response()->download(storage_path('app\\uploads\\' . 'archivo.pdf'))->deleteFileAfterSend();
    }
    public function companyCertificateEventualDownload(Request $request)
    {
        $input = $request->all();
        \Debugbar::info($input);
        $principal = (object) $input['principal'];
        $contractor = (object) $input['contractor'];
        $employees = (object) $input['employees'];
        view()->share('expirationDate', $input['expirationDate']);
        view()->share('today', $input['today']);
        view()->share('folio', $input['folio']);
        view()->share('principal', $principal);
        view()->share('contractor', $contractor);
        view()->share('employees', $employees);
        $pdf = PDF::loadView('pdfs\certificateEventualPdf');
        if (!file_exists(storage_path('app\\uploads'))) {
            File::makeDirectory(storage_path('app\\uploads'));
        }

        $pdf->save(storage_path('app\\uploads\\') . 'archivo.pdf');
        return response()->download(storage_path('app\\uploads\\' . 'archivo.pdf'))->deleteFileAfterSend();
    }
    public function companyReportDownload(Request $request)
    {
        $input = $request->all();

        $form = (object) $input['form'];
        $principal = (object) $input['principal'];
        $contractor = (object) $input['contractor'];
        $employees = (object) $input['employees'];
        view()->share('form', $form);
        view()->share('expirationDate', $input['expirationDate']);
        view()->share('today', $input['today']);
        view()->share('folio', $input['folio']);
        view()->share('principal', $principal);
        view()->share('contractor', $contractor);
        view()->share('employees', $employees);
        // return view('pdfs/certificatePdf');
        foreach ($employees as $key => $employee) {
            \Debugbar::info($employee['name']);
        }
        $pdf = PDF::loadView('pdfs\reportPdf');
        if (!file_exists(storage_path('app\\uploads'))) {
            File::makeDirectory(storage_path('app\\uploads'));
        }
        $pdf->save(storage_path('app\\uploads\\') . 'archivo.pdf');
        return response()->download(storage_path('app\\uploads\\' . 'archivo.pdf'))->deleteFileAfterSend();
    }
    public function companyReportEventualDownload(Request $request)
    {
        $input = $request->all();
        \Debugbar::info($input);
        $form = (object) $input['form'];
        $principal = (object) $input['principal'];
        $contractor = (object) $input['contractor'];
        $employees = (object) $input['employees'];
        view()->share('expirationDate', $input['expirationDate']);
        view()->share('today', $input['today']);
        view()->share('folio', $input['folio']);
        view()->share('principal', $principal);
        view()->share('contractor', $contractor);
        view()->share('employees', $employees);
        view()->share('form', $form);

        $pdf = PDF::loadView('pdfs\reportEventualPdf');
        if (!file_exists(storage_path('app\\uploads'))) {
            File::makeDirectory(storage_path('app\\uploads'));
        }

        $pdf->save(storage_path('app\\uploads\\') . 'archivo.pdf');
        return response()->download(storage_path('app\\uploads\\' . 'archivo.pdf'))->deleteFileAfterSend();
    }
}
