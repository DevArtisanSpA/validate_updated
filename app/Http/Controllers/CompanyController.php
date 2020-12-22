<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\BranchOffice;
use App\Models\Commune;
use App\Models\CommercialBusiness;
use App\Models\Region;
use App\Models\DocumentType;
use App\Models\Document;
use App\Models\User;
use App\Helpers\Constants;

use Auth;
use stdClass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Mail\CompanyAssociation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;

class CompanyController extends Controller
{
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
        $my_company = new stdClass();
        if (Auth::user()->user_type_id == 1) {
            $user = Auth::user();
            $companies = Company::with([
                'commercialBusiness:id,name',
                'parentBranchOffices:id,company_id',
                'parentBranchOffices.company:id,business_name'
            ])->get();
            foreach($companies as $company) {
                $parentCompanies = collect();
                foreach($company->parentBranchOffices as $parentBranchOffice) {
                    $parentCompanies = $parentCompanies->merge($parentBranchOffice->company->business_name);
                }

                $company->parentCompanies = $parentCompanies;
                unset($company->parentBranchOffices);
            }
        } else {
            $user = Auth::user()->load('company.commercialBusiness:id,name');
            $my_company = $user->company;
            $my_company->load('parentBranchOffices.company:id,business_name');
            $myParentCompanies = collect();
            foreach($my_company->parentBranchOffices as $parentBranchOffice) {
                $myParentCompanies = $myParentCompanies->merge($parentBranchOffice->company->business_name);
            }
            $my_company->parentCompanies = $myParentCompanies;
            unset($my_company->parentBranchOffices);
            $companies = collect([$my_company]);
            $childData = $my_company->branchOffices()->with('childCompanies.commercialBusiness:id,name')->get();
            foreach ($childData as $data) {
                $companies = $companies->merge($data->childCompanies);
            }
        }
        return view('companies/index', [
            'companies' =>  $companies,
            'auth' => $user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regions = Region::with('communes')->get();
        $commercialBusinesses = CommercialBusiness::all();
        $companies = Company::where("active", true)->orderBy('business_name')->get();
        $authData = Auth::user();
        $authData->isAdmin = Auth::user()->user_type_id == 1;
        return view('companies/new', [
            'dataList' => json_encode([
                'regions' => $regions,
                'commercialBusinesses' => $commercialBusinesses,
                'affiliations' => Constants::$AFFILIATIONS,
            ]),
            'auth' => $authData
        ]);
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = Company::find($id);
        $regions = Region::with('communes')->get();
        $commercialBusinesses = CommercialBusiness::all();
        $companies = Company::where([['active', '=', '1'], ['id', '<>', $id]])->orderBy('business_name')->get();
        $authData = Auth::user();
        $authData->isAdmin = Auth::user()->user_type_id == 1;
        return view('companies/edit', [
            'company' => $company,
            'dataList' => json_encode([
                'regions' => $regions,
                'commercialBusinesses' => $commercialBusinesses,
                'affiliations' => Constants::$AFFILIATIONS,
            ]),
            'auth' => $authData
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $companyData = $request->company;
        $branchOfficeData = $request->branchOffice;
        $companyValidation = Validator::make($companyData, [
            'commercial_business_id' => ['required'],
            'business_name' => ['required', 'string', 'min:3', 'max:100'],
            'contact_name' => ['required', 'string', 'min:3', 'max:50'],
            'affiliation' => ['required', 'string', 'min:3'],
            'affiliation_date' => ['required', 'date'],
            'contact_email' => ['required', 'regex:/^.+@.+$/i'],
            'rut' => ['required', 'min:3', 'max:20'],
        ]);

        $officeValidation = Validator::make($branchOfficeData, [
            'address' => ['required', 'string', 'min:3', 'max:100'],
            'commune_id' => ['required'],
            'region_id' => ['required'],
            'phone1' => ['required', 'integer'],
            'phone2' => ['nullable', 'integer'],
        ]);

        $error_array = array();
        if ($companyValidation->fails()) {
            foreach ($companyValidation->messages()->getMessages() as $field_name => $messages) {
                $error_array[] = $messages[0];
            }
            return response()->json($error_array, 201);
        }else if ($officeValidation->fails()) {
            foreach ($officeValidation->messages()->getMessages() as $field_name => $messages) {
                $error_array[] = $messages[0];
            }
            return response()->json($error_array, 201);
        } else {
            $result = Company::create($companyData);
            if ($result) {
                $branchOfficeData["name"] = "Casa Matriz";
                $branchOfficeData["company_id"] = $result->id;
                $newBranchOffice = BranchOffice::create($branchOfficeData);
                if ($result)
                    return response()->json(["message" => "Empresa creada exitosamente", "company" => $result], 200);
                else
                    return response()->json(["message" => "Error al intentar crear empresa, por favor intentar nuevamente"], 400);
            }
            else
                return response()->json(["message" => "Error al intentar crear empresa, por favor intentar nuevamente"], 400);
        }
        return true;
    }

    /**
     * Update a resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => ['required'],
            'commercial_business_id' => ['required'],
            'business_name' => ['required', 'string', 'min:3', 'max:100'],
            'contact_name' => ['required', 'string', 'min:3', 'max:50'],
            'affiliation' => ['required', 'string', 'min:3'],
            'affiliation_date' => ['required', 'date'],
            'contact_email' => ['required', 'regex:/^.+@.+$/i'],
            'rut' => ['required', 'min:3', 'max:20'],
        ]);


        $error_array = array();
        if ($validation->fails()) {
            foreach ($validation->messages()->getMessages() as $field_name => $messages) {
                $error_array[] = $messages[0];
            }
            return response()->json($error_array, 201);
        } else {
            $company = Company::find($request->id);
            $result = $company->update($request->all());
            if ($result)
                return response()->json(["message" => "Empresa creada exitosamente", "company" => $result], 200);
            else
                return response()->json(["message" => "Error al intentar crear empresa, por favor intentar nuevamente"], 400);
        }
        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deletedCompany = Company::find($id);
        $deletedCompany->active = false;
        if ($deletedCompany->branchOffices()->count() > 0) {
            $deletedCompany->branchOffices()->update(["active" => 0]);
        }
        if ($deletedCompany->services()->count() > 0) {
            $deletedCompany->services()->update(["active" => 0, "finished" => now()]);
        }
        if ($deletedCompany->save())
            return response()->json(["message" => "Empresa eliminada exitosamente"], 200);

        else
            return response()->json(["message" => "Error al intentar eliminar empresa, por favor intentar nuevamente"], 400);
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCompanyByRut($rut)
    {
        //'regexp',
        $company = Company::where(function ($query) use ($rut) {
            $query->where('rut', $rut)
            ->orWhere('rut', 'regexp', $rut . '.')
            ->orWhere('rut', 'regexp', $rut . '.');
        })->where('active', true)->first();
        if (!is_null($company)) {
            //$company->Companies_p;
            //$company->Commune->Region;
            return response()->json(["company" => $company], 200);
        } else
            return response()->json([], 204);
    }
}
