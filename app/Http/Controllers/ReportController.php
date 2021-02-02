<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Document;
use App\Helper\Functions;

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
        $contractors = $company->services;
        $contractors_data = [];
        $principal_data = [];
        $employee_data = [];
        foreach ($contractors as $child) {
            \Debugbar::info($child);
            $contractors_data[] = [
                "business_name" => $child->company->business_name,
                "resume" => Employee::getResumeByCompany($child->company_id)
            ];
            $principal_data[] = [
                "business_name" => $child->company->business_name,
                "resume" => Document::getDocCompanyById($child->company_id)
            ];
            $employee_data[] = [
                "business_name" => $child->company->business_name,
                "resume" => Document::getDocEmployeeById($child->company_id)
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
}
