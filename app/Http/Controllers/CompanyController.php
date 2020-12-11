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
        //foreach ($regions as $region) {
        //    $region->Communes;
        //}
        $commercialBusinesses = CommercialBusiness::all();
        $companies = Company::with('branchOffice')->where("active", true)->orderBy('business_name')->get();
        //$companies = Company::where('state', true)->join('branch_offices', 'companies.id', 'branch_offices.id_company')
        //    ->select('companies.*')->orderBy('business_name')->get();
        //$companies = $companies->unique();
        $documentTypes = new stdClass();
        $authData = Auth::user();
        $authData->isAdmin = Auth::user()->user_type_id == 1;
        foreach (Constants::$DOC_COMPANY_CREATE as $key => $value) {
            $documentTypes->$key = DocumentType::find($value)->sortBy('name');
        }
        //Document_type::getTypesDoc(0, 1);
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
        /*return [
            'dataList' => json_encode([
                'regions' => $regions,
                'commercialBusinesses' => $commercialBusinesses,
                'companiesFather' => $companies,
                'affiliations' => Constants::$AFFILIATIONS,
                'documentTypes' => $documentTypes
            ]),
            'auth' => $authData
        ];*/
    }
}
