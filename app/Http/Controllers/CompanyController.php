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
        $companies = Company::with('branchOffice')->where("active", true)->orderBy('business_name')->get();
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getRut($rut)
    {
        //'regexp',
        $company = Company::where(function ($query) use ($rut) {
            $query->where('rut', $rut)
            ->orWhere('rut', 'regexp', $rut . '.')
            ->orWhere('rut', 'regexp', $rut . '.');
        })->where('active', true)->first();
        if (!is_null($company)) {
            $company->Companies_p;
            $company->Commune->Region;
            return response()->json(["company" => $company], 200);
        } else
            return response()->json([], 204);
    }
}
