<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Company;
use App\Models\Document;
use App\Models\Employee;
use Auth;
use Illuminate\Http\Request;
use stdClass;

class HomeController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */    
    public function index() {

        $times = new stdClass();
        $times->company = new stdClass();
        $times->employee = new stdClass();
        //if (Auth::user()->user_type_id == 1) {
            $total_users = User::all()->count();
            $total_companies = Company::all()->count();
            $total_employees = Employee::all()->count();
            /*
            $times->company->fixed = date_format(date_create(Document::getDocCompany(0)->max('documents.updated_at')), 'd/m/Y');
            $times->company->monthly = date_format(date_create(Document::getDocCompany(1)->max('documents.updated_at')), 'd/m/Y');
            $times->employee->fixed = date_format(date_create(Document::getDocEmployee(0)->max('documents.updated_at')), 'd/m/Y');
            $times->employee->monthly = date_format(date_create(Document::getDocEmployee(1)->max('documents.updated_at')), 'd/m/Y');
        } else {
            $my_company_id = Auth::user()->id_company;
            $my_company = Company::find($my_company_id);
            $total_users = $my_company->Users()->count();
            $total_companies =  1 + $my_company->Companies_c()->count();
            $total_employees = $my_company->Employees()->count();
            foreach ($my_company->Companies_c as $child) {
                $total_employees += $child->Employees()->count();
            }
            $times->company->fixed = date_format(date_create(Document::getDocCompany(0, Auth::user()->id_company)->max('documents.updated_at')), 'd/m/Y');
            $times->company->monthly = date_format(date_create(Document::getDocCompany(1, Auth::user()->id_company)->max('documents.updated_at')), 'd/m/Y');
            $times->employee->fixed = date_format(date_create(Document::getDocEmployee(0, Auth::user()->id_company)->max('documents.updated_at')), 'd/m/Y');
            $times->employee->monthly = date_format(date_create(Document::getDocEmployee(1, Auth::user()->id_company)->max('documents.updated_at')), 'd/m/Y');
        }*/
        

        return view('home', ['total_users' => $total_users, 'total_companies' => $total_companies, 'total_employees' => $total_employees, 'times' => $times]);
    }
}
