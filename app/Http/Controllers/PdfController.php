<?php

namespace App\Http\Controllers;

use App\Helper\Functions;
use App\Mail\MailWelcome;
use App\Models\Company;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\Service;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PDF;

class PdfController extends Controller
{
    public function companyCertificate($id)
    {
        $service = Service::whereId($id)->complete()->with([
            'company.branchOffices.commune.region',
            'company.commercialBusiness',
            'branchOffice.commune.region',
            'employees'
        ])->first();
        $document_type = DocumentType::whereName('Certificado Validate')->where('service_type_id', $service->service_type_id)->first();
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
        $documents = Document::where('service_id', $id)->with('type:id,name')->wherehas('type', function ($query) {
            return $query->whereName('Traslado')
                ->orWhere('name', 'Licencia Medica')
                ->orWhere('name', 'Finiquito')
                ->orWhere('name', 'Contrato de Trabajo');
        })->where(function ($query) {
            $yearMonth = Carbon::now('America/Santiago')->format('Y-m');
            return $query->where('month_year_registry', $yearMonth)->orWhere(function ($query) use ($yearMonth) {
                return  $query->where('month_year_registry', null)->whereDate('start', '>=', $yearMonth . '-01');
            });
        })->get()->groupBy('type.name');
        $settlements = isset($documents["Finiquito"]) ? $documents['Finiquito'] : [];
        $contracts = isset($documents["Contrato de Trabajo"]) ? $documents['Contrato de Trabajo'] : [];
        $transfers = isset($documents["Traslado"]) ? $documents['Traslado'] : [];
        $licenses = isset($documents["Licencia Medica"]) ? $documents['Licencia Medica'] : [];
        $resumen = (object)[
            'settlement' => $settlements,
            'contracts' => $contracts,
            'transfers' => $transfers,
            'licenses' => $licenses,
        ];
        $data = json_encode([
            'principal' => $principal,
            'contractor' => $contractor,
            'employees' => $employees,
            'today' => ucfirst($dayName) . ', ' . $day . ' de ' . $month . ' de ' . $year,
            'expirationDate' => $expirationDate,
            'resumen' => $resumen,
            'folio' =>  $id . '-' . Carbon::now('America/Santiago')->format('Ymd') . '-CF'
        ]);
        return response()->json([
            'service' => (object)[
                'id' => $id,
                'type_id' => $document_type->id,
            ],
            'principal' => $principal,
            'contractor' => $contractor,
            'employees' => $employees,
            'today' => ucfirst($dayName) . ', ' . $day . ' de ' . $month . ' de ' . $year,
            'expirationDate' => $expirationDate,
            'resumen' => $resumen,
            'folio' =>  $id . '-' . Carbon::now('America/Santiago')->format('Ymd') . '-CF'
        ], 200);
        view()->share('dataList', $data);
        return view('pdfs/certificate');
    }
    public function companyCertificateEventual($id)
    {
        $service = Service::whereId($id)->complete()->with([
            'company.branchOffices.commune.region',
            'branchOffice.commune.region',
            'employees.jobType',
            'company.commercialBusiness',
        ])->first();
        $document_type = DocumentType::whereName('Certificado Validate')->where('service_type_id', $service->service_type_id)->first();
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
        $documents = Document::where('service_id', $id)->with('type:id,name')->wherehas('type', function ($query) {
            return $query->whereName('Traslado')
                ->orWhere('name', 'Licencia Medica')
                ->orWhere('name', 'Finiquito')
                ->orWhere('name', 'Contrato de Trabajo');
        })->where(function ($query) {
            $yearMonth = Carbon::now('America/Santiago')->format('Y-m');
            return $query->where('month_year_registry', $yearMonth)->orWhere(function ($query) use ($yearMonth) {
                return  $query->where('month_year_registry', null)->whereDate('start', '>=', $yearMonth . '-01');
            });
        })->get()->groupBy('type.name');
        $settlements = isset($documents["Finiquito"]) ? $documents['Finiquito'] : [];
        $contracts = isset($documents["Contrato de Trabajo"]) ? $documents['Contrato de Trabajo'] : [];
        $transfers = isset($documents["Traslado"]) ? $documents['Traslado'] : [];
        $licenses = isset($documents["Licencia Medica"]) ? $documents['Licencia Medica'] : [];
        $resumen = (object)[
            'settlement' => $settlements,
            'contracts' => $contracts,
            'transfers' => $transfers,
            'licenses' => $licenses,
        ];

        // $pdf = PDF::loadView('pdfs\certificateEventual');
        $data = json_encode([
            'principal' => $principal,
            'contractor' => $contractor,
            'employees' => $employees,
            'today' => ucfirst($dayName) . ', ' . $day . ' de ' . $month . ' de ' . $year,
            'expirationDate' => $expirationDate,
            'resumen' => $resumen,
            'folio' =>  $id . '-' . Carbon::now('America/Santiago')->format('Ymd') . '-CE'
        ]);
        return response()->json([
            'service' => (object)[
                'id' => $id,
                'type_id' => $document_type->id,
            ],
            'principal' => $principal,
            'contractor' => $contractor,
            'employees' => $employees,
            'today' => ucfirst($dayName) . ', ' . $day . ' de ' . $month . ' de ' . $year,
            'expirationDate' => $expirationDate,
            'resumen' => $resumen,
            'folio' =>  $id . '-' . Carbon::now('America/Santiago')->format('Ymd') . '-CE'
        ], 200);
        view()->share('dataList', $data);
        return view('pdfs/certificateEventual');
    }
    public function companyReport($id)
    {
        $service = Service::whereId($id)->complete()->with([
            'company.branchOffices.commune.region',
            'branchOffice.commune.region',
            'employees.jobType',
            'company.commercialBusiness',
        ])->first();
        $document_type = DocumentType::whereName('Informe Validate')
            ->where('service_type_id', $service->service_type_id)->first();
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
        $documents = Document::where('service_id', $id)->with(['type:id,name', 'employee', 'validationState:id,name'])->wherehas('type', function ($query) {
            return $query->whereName('Traslado')
                ->orWhere('name', 'Licencia Medica')
                ->orWhere('name', 'Finiquito')
                ->orWhere('name', 'Contrato de Trabajo')
                ->orWhere('name', 'Cert. Siniestralidad')
                ->orWhere('name', 'Formulario F30');
        })->where(function ($query) {
            $yearMonth = Carbon::now('America/Santiago')->format('Y-m');
            return $query->where('month_year_registry', $yearMonth)->orWhere(function ($query) use ($yearMonth) {
                return  $query->where('month_year_registry', null)->whereDate('start', '>=', $yearMonth . '-01');
            });
        })->orderBy('updated_at', 'desc')->get()->groupBy('type.name');
        $accident = isset($documents["Cert. Siniestralidad"]) ? $documents['Cert. Siniestralidad'][0] : null;
        $accident->observations = str_replace("\\n", " : ", explode("</div>", $accident->observations)[0]);
        $F30 = isset($documents["Formulario F30"]) ? $documents['Formulario F30'][0] : null;
        $F30->observations = str_replace("\\n", " : ", explode("</div>", $F30->observations)[0]);
        $settlements = isset($documents["Finiquito"]) ? $documents['Finiquito'] : [];
        $contracts = isset($documents["Contrato de Trabajo"]) ? $documents['Contrato de Trabajo'] : [];
        $transfers = isset($documents["Traslado"]) ? $documents['Traslado'] : [];
        $licenses = isset($documents["Licencia Medica"]) ? $documents['Licencia Medica'] : [];
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
            'resumen' => $resumen,
            'folio' =>  $id . '-' . Carbon::now('America/Santiago')->format('Ymd') . '-RF'

        ]);

        return response()->json([
            'service' => (object)[
                'id' => $id,
                'type_id' => $document_type->id,
            ],
            'principal' => $principal,
            'contractor' => $contractor,
            'employees' => $employees,
            'today' => $day . ' de ' . $month . ' de ' . $year,
            'expirationDate' => $expirationDate,
            'resumen' => $resumen,
            'folio' =>  $id . '-' . Carbon::now('America/Santiago')->format('Ymd') . '-RF',
            'monthly' => (object)[
                'accident' => $accident,
                'F30' => $F30
            ]
        ], 200);
        view()->share('dataList', $data);
        // Mail::to('alma@devartisan.cl')->send(new MailWelcome('123456789', $employee));
        // return $pdf->download('pdfview.pdf');
        return view('pdfs\report');
    }
    public function companyReportEventual($id)
    {
        $service = Service::whereId($id)->complete()->with([
            'company.branchOffices.commune.region',
            'branchOffice.commune.region',
            // 'employees.jobType',
            'company.commercialBusiness',
        ])->first();
        $document_type = DocumentType::whereName('Informe Validate')->where('service_type_id', $service->service_type_id)->first();
        $principal = clone ($service->branchOffice->company);
        unset($service->branchOffice->company);
        $principal->branchOffice = $service->branchOffice;
        $contractor = $service->company;
        $contractor->branchOffice = $service->company->branchOffices[0];
        unset($service->company->branchOffices);
        $employees = Employee::with([
            'jobType',
            'documents' => function ($query) use ($id) {
                return  $query->where('service_id', $id)->with('type')->where(function ($query) {
                    return $query->wherehas('type', function ($query) {
                        return $query->whereName('Contrato de Trabajo')
                            ->orWhere('name', 'Cédula de Identidad')
                            ->orWhere('name', 'Recepción de EPP')
                            ->orWhere('name', 'DAS/ODIS/Políticas')
                            ->orWhere('name', 'Toma de Conocimiento Reglamento Interno')
                            ->orderBy('updated_at', 'desc');
                    })
                        // ->orWhere(function ($query) {
                        //     $yearMonth = Carbon::now('America/Santiago')->format('Y-m');
                        //     return $query->wherehas('type', function ($query) {
                        //         return $query->whereName('Formulario F30')
                        //             ->orWhere('name', 'Cert. Siniestralidad');
                        //     })->where('month_year_registry', $yearMonth)->orWhere(function ($query) use ($yearMonth) {
                        //         return  $query->where('month_year_registry', null)->whereDate('start', '>=', $yearMonth . '-01');
                        //     });
                        // })
                    ;
                });
            }
        ])->wherehas('services', function ($query) use ($id) {
            return $query->where('services.id', $id);
        })->get();
        $today = Carbon::now('America/Santiago');
        $year = $today->year;
        $month = $today->locale('es')->monthName;
        $day = $today->day;
        $dayName = $today->locale('es')->dayName;
        $expirationDate = $today->addDays(25)->format('d-m-Y');
        $documentsParticular = Document::where('service_id', $id)->with(['type:id,name'])->wherehas('type', function ($query) {
            return $query->whereName('Contrato Comercial')
                ->orWhere('name', 'Orden de Compra')
                ->orWhere('name', 'Afiliación Mutualidad')
                ->orWhere('name', 'Reg.Interno de Orden, Higiene y Seguridad')
                ->orWhere('name', 'Políticas de Seguridad');
        })->orderBy('updated_at', 'desc')->get()->groupBy('type.name');
        \Debugbar::info($documentsParticular);
        $comercialContract = isset($documentsParticular["Contrato Comercial"]) ? $documentsParticular['Contrato Comercial'][0] : null;
        $affiliation = isset($documentsParticular["Afiliación Mutualidad"]) ? $documentsParticular['Afiliación Mutualidad'][0] : null;
        $regulation = isset($documentsParticular["Reg.Interno de Orden, Higiene y Seguridad"]) ? $documentsParticular['Reg.Interno de Orden, Higiene y Seguridad'][0] : null;
        $politics = isset($documentsParticular["Políticas de Seguridad"]) ? $documentsParticular['Políticas de Seguridad'][0] : null;

        $monthly = (object)[
            'comercialContract' => $comercialContract,
            'affiliation' => $affiliation,
            'regulation' => $regulation,
            'politics' => $politics,
        ];
        return response()->json([
            'service' => (object)[
                'id' => $id,
                'type_id' => $document_type->id,
            ],
            'principal' => $principal,
            'contractor' => $contractor,
            'employees' => $employees,
            'today' => $day . ' de ' . $month . ' de ' . $year,
            'expirationDate' => $expirationDate,
            'folio' =>  $id . '-' . Carbon::now('America/Santiago')->format('Ymd') . '-RE',
            'monthly' => $monthly
        ], 200);

        // view()->share('dataList', $data);
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
        $document = $input['document'];
        view()->share('form', $form);
        view()->share('expirationDate', $input['expirationDate']);
        view()->share('today', $input['today']);
        view()->share('folio', $input['folio']);
        view()->share('principal', $principal);
        view()->share('contractor', $contractor);
        \Debugbar::info($document);
        $pdf = PDF::loadView('pdfs\certificatePdf');
        if (!file_exists(storage_path('app\\uploads'))) {
            File::makeDirectory(storage_path('app\\uploads'));
        }
        // $pdf->save(storage_path('app\\uploads\\') . 'archivo.pdf');
        return $this->saveAndDownload($document,$pdf);
        // return response()->download(storage_path('app\\uploads\\' . 'archivo.pdf'))->deleteFileAfterSend();
    }
    public function companyCertificateEventualDownload(Request $request)
    {
        $input = $request->all();
        \Debugbar::info($input);
        $principal = (object) $input['principal'];
        $contractor = (object) $input['contractor'];
        $employees = (object) $input['employees'];
        $document = $input['document'];
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

        return $this->saveAndDownload($document,$pdf);

        // $pdf->save(storage_path('app\\uploads\\') . 'archivo.pdf');
        // return response()->download(storage_path('app\\uploads\\' . 'archivo.pdf'))->deleteFileAfterSend();
    }
    public function companyReportDownload(Request $request)
    {
        $input = $request->all();

        $form = (object) $input['form'];
        $principal = (object) $input['principal'];
        $contractor = (object) $input['contractor'];
        $employees = (object) $input['employees'];
        $document = $input['document'];
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
        return $this->saveAndDownload($document,$pdf);

        // $pdf->save(storage_path('app\\uploads\\') . 'archivo.pdf');
        // return response()->download(storage_path('app\\uploads\\' . 'archivo.pdf'))->deleteFileAfterSend();
    }
    public function companyReportEventualDownload(Request $request)
    {
        $input = $request->all();
        \Debugbar::info($input);
        $form = (object) $input['form'];
        $principal = (object) $input['principal'];
        $contractor = (object) $input['contractor'];
        $employees = (object) $input['employees'];
        $document = $input['document'];
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
        return $this->saveAndDownload($document,$pdf);

        // $pdf->save(storage_path('app\\uploads\\') . 'archivo.pdf');
        // return response()->download(storage_path('app\\uploads\\' . 'archivo.pdf'))->deleteFileAfterSend();
    }
    public function saveAndDownload($document,$pdf){
        $name = Carbon::now('America/Santiago')->timestamp;
        $name = 'uploads/' . $name . '.pdf';

        if (isset($document["path_data"])) {
            array_push($document, ['path_data' => $name]);
        } else {
            $document["path_data"] = $name;
        }
        $documentCreate = Document::create($document);
        \Debugbar::info($documentCreate);
        Storage::disk('s3')->put($name, $pdf->output());

        return Storage::disk('s3')->download($name);
    }

    public function pdfFind(Request $request){
        $input = $request->all();
        $document=Document::whereService_id($input['service_id'])
        ->whereMonth_year_registry($input['month_year_registry'])
        ->whereFinish($input['finish'])
        ->wherehas('type',function($query) use($input){
            return $query->whereName($input['name']);
        })
        ->orderBy('updated_at', 'desc')
        ->first();
        return response()->json(['document'=>$document],200);
    }
}
