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
use App\Models\ServiceType;
use App\Models\Service;
use App\Helpers\Constants;

use Auth;
use stdClass;
use Illuminate\Http\Request;
use App\Mail\CompanyAssociation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List all resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->user_type_id == 1) {
            $services = Service::active()->orWhere(function ($query) {
                $query->pending();
            })->complete()->get();
            return view('services/index', [
                'services' => $services,
                'auth' => Auth::user()
            ]);
        }
        else {
            $myCompany = Company::find(Auth::user()->company_id);
            $pendingServices = $myCompany->services()->pending()->complete()->get();
            $activeServices = $myCompany->services()->active()->complete()->get();
            $contractedServices = $myCompany->contractedServices()->complete()->get();
            $viewData = [
                'services' => json_encode([
                    'pending' => $pendingServices,
                    'active' => $activeServices,
                    'contracted' => $contractedServices,
                ]),
                'auth' => Auth::user()
            ];
            return view('services/index', $viewData);
        }
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
        $documentTypes = new stdClass();
        $authData = Auth::user();
        $authData->isAdmin = Auth::user()->user_type_id == 1;
        foreach (Constants::$DOC_COMPANY_CREATE as $key => $value) {
            $documentTypes->$key = DocumentType::find($value)->sortBy('name');
        }
        $serviceTypes = ServiceType::all();
        $companies = Company::with('branchOffices')->where("active", true)->orderBy('business_name')->get();
        $viewData = [
            'dataList' => json_encode([
                'regions' => $regions,
                'commercialBusinesses' => $commercialBusinesses,
                'affiliations' => Constants::$AFFILIATIONS,
                'documentTypes' => $documentTypes
            ]),
            'auth' => $authData,
            'serviceTypes' => $serviceTypes,
            'companies' => $companies
        ];
        return view('services/associate', $viewData);
    }

    /**
     * Show the form for editing an existing resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $service = Service::select([
            'id', 'branch_office_id','service_type_id','company_id','description','active','start','finished'
        ])->with('branchOffice.company:id,business_name,rut')->find($id);
        $serviceCompany = Company::select(['id', 'rut', 'business_name'])->find($service->company_id);
        $serviceType = ServiceType::select(['id', 'name'])->find($service->service_type_id);
        $serviceParentCompany = $service->branchOffice->company;
        unset($service->branchOffice->company);
        $serviceParentCompany->branchOffices = collect([$service->branchOffice]);
        unset($service->branchOffice);
        $authData = Auth::user();
        $authData->isAdmin = Auth::user()->user_type_id == 1;
        $viewData = [
            'service' => $service,
            'companyRut' => $serviceCompany->rut,
            'companyId' => $serviceCompany->id,
            'serviceTypes' => collect([$serviceType]),
            'companies' => collect([$serviceParentCompany]),
            'auth' => $authData,
        ];
        //return $viewData;
        return view('services/edit', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'branch_office_id' => ['required', 'integer'],
            'service_type_id' => ['required', 'integer'],
            'company_id' => ['required', 'integer'],
            'description' => ['nullable'],
            'active' => ['boolean']
        ]);

        $error_array = array();
        if ($validation->fails()) {
            foreach ($validation->messages()->getMessages() as $field_name => $messages) {
                $error_array[] = $messages[0];
            }
            return response()->json($error_array, 201);
        } else {
            $result = Service::create($request->all());
            if ($result) {
                return response()->json(["message" => "Servicio creado exitosamente", "service" => $result], 200);
            }
            else {
                return response()->json(["message" => "Error al intentar crear empresa, por favor intentar nuevamente"], 400);
            }
        }
        return true;
    }
}
