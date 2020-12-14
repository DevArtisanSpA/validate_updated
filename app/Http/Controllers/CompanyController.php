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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regions = Region::with('communes')->get();
        $commercialBusinesses = CommercialBusiness::all();
        $companies = Company::with('branchOffices')->where("active", true)->orderBy('business_name')->get();
        $documentTypes = new stdClass();
        $authData = Auth::user();
        $authData->isAdmin = Auth::user()->user_type_id == 1;
        foreach (Constants::$DOC_COMPANY_CREATE as $key => $value) {
            $documentTypes->$key = DocumentType::find($value)->sortBy('name');
        }
        return view('companies/new', [
            'dataList' => json_encode([
                'regions' => $regions,
                'commercialBusinesses' => $commercialBusinesses,
                'companiesFather' => $companies,
                'affiliations' => Constants::$AFFILIATIONS,
                'documentTypes' => $documentTypes
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
                /*foreach ($input['companies_p'] as $key => $id_p) {
                    $contractor = new Contractor();
                    $contractor->id_contractor = $result->id;
                    $contractor->id_company = $id_p;
                    $contractor->save();
                    Mail::to(
                        [Company::find($id_p)->email, $result->email, Constants::getAdmin()->email]
                    )->send(new CompanyAssociation($result, [$id_p]));
                }*/
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
