<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Document;
use App\Helper\Functions;
use App\Models\Service;
use Auth;

class ReportController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (Auth::user()->user_type_id == 1) {
            $companies = Company::where('active', true)->orderBy('business_name')->get();
        } else {
            $my_company = Company::find(Auth::user()->company_id);
            $companies = collect([$my_company]);
            $child_companies = $my_company->Companies_c;
            $companies = $companies->merge($child_companies);
        }
        return view('reports/index', [
            "data" => json_encode([
                'companies' => $companies,
                'principal_data' => null,
                'employee_data' => null,
                'companies_childs_data' => null,
                'selected' => null
            ])
        ]);
    }
    public function show($id)
    {
        //
        if (Auth::user()->user_type_id == 1) {
            $companies = Company::where('active', true)->orderBy('business_name')->get();
        } else {
            $my_company = Company::find(Auth::user()->company_id);
            $companies = collect([$my_company]);
            $child_companies = $my_company->services;
            $companies = $companies->merge($child_companies);
        }
        $company = Company::find($id);
        $contractors = Service::wherehas('branchOffice.company', function ($query) use ($id) {
                return $query->whereId($id);
            })
            ->with('company:id,business_name')
            ->get();
        $contractors_data = [];
        $principal_data = [];
        $employee_data = [];
        foreach ($contractors as $child) {
            $contractors_data[] = [
                "business_name" => $child->description,
                "resume" => Employee::getResumeByCompany($child->id)
            ];
            $principal_data[] = [
                "business_name" => $child->description,
                "resume" => Document::getDocCompanyById($child->id)
            ];
            $employee_data[] = [
                "business_name" => $child->description,
                "resume" => Document::getDocEmployeeById($child->id)
            ];
        }

        return view('reports/index', [
            "data" => json_encode([
                'companies' => $companies,
                'principal_data' => $principal_data,
                'employee_data' => $employee_data,
                'contractors_data' => $contractors_data,
                'selected' => $id
            ])
        ]);
    }

    public function reportFixed(){
        $services = Service::where('active', true)
        ->complete()->where('service_type_id',1)
        ->orderBy('description')->get();
        return view('reports/certificate', [
          "data" => json_encode([
            'services' => $services,
            'url' =>'/pdf/report/fixed/',
            'componentName'=>'report-fixed',
            'text' =>"Informe de validación"
          ])
        ]);
    }
    public function reportEventual(){
        $services = Service::where('active', true)
        ->complete()->where('service_type_id','!=',1)
        ->orderBy('description')->get();
        return view('reports/certificate', [
          "data" => json_encode([
            'services' => $services,
            'url' =>'/pdf/report/eventual/',
            'componentName'=>'report-eventual',
            'text' =>"Informe de validación de trabajos eventuales"
          ])
        ]);
    }
    public function certificateFixed(){
        $services = Service::where('active', true)
        ->complete()->where('service_type_id',1)
        ->orderBy('description')->get();
        return view('reports/certificate', [
          "data" => json_encode([
            'services' => $services,
            'url' =>'/pdf/certificate/fixed/',
            'componentName'=>'certificate-fixed',
            'text' =>"Certificado de validación"
          ])
        ]);
    }
    public function certificateEventual(){
        $services = Service::where('active', true)
        ->complete()->where('service_type_id','!=',1)
        ->orderBy('description')->get();
        return view('reports/certificate', [
          "data" => json_encode([
            'services' => $services,
            'url' =>'/pdf/certificate/eventual/',
            'componentName'=>'certificate-eventual',
            'text' =>"Certificado de validación de trabajos eventuales"
          ])
        ]);
    }
}
