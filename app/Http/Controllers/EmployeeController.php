<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Models\Document;
use App\Models\Employee;
use App\Models\JobType;
use App\Models\Region;
use App\Models\Service;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use stdClass;

class EmployeeController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $my_company = new stdClass;
    if (Auth::user()->user_type_id == 1) {
      $user = Auth::user();
      $employees = Employee::with([
        'job_type:id,name'
      ])->get();
    } else {
    }
    return view('employees/index', [
      'auth' => Auth::user()
    ]);
  }

  public function create($id_service)
  {
    $regions = Region::with('communes')->get();
    $jobs = JobType::all();
    $service = (new Service)->scopeComplete(Service::where('id', $id_service))
      ->first();
    $authData = Auth::user();
    $authData->isAdmin = Auth::user()->user_type_id == 1;
    return view('employees/new', [
      'dataList' => json_encode([
        'regions' => $regions,
        'job_types' => $jobs,
        'countries' => Constants::$COUNTRIES
        // 'commercialBusinesses' => $commercialBusinesses,
        // 'affiliations' => Constants::$AFFILIATIONS,
      ]),
      'service' => $service,
      'auth' => $authData
    ]);
  }
  public function store(Request $request)
  {
    $input = $request->all();
    $file = $request->file;
    $document = json_decode($input["document"]);
    if ($request->hasFile('file') && $request->file('file')->isValid()) {
      $inputEmployee = (array) (json_decode($input["employee"]));
      $inputDocument = (array)(json_decode($input["document"]));
      $validationEmployee = Validator::make($inputEmployee, [
        'commune_id' => ['required', 'integer'],
        'job_type_id' => ['nullable', 'integer'],
        'identification_id' => ['required', 'min:3', 'max:20'],
        'identification_type' => ['required', 'integer'],
        'name' => ['required', 'string', 'max:30'],
        'surname' => ['required', 'string', 'max:30'],
        'second_surname' => ['nullable', 'string', 'max:20'],
        'birthday' => ['required', 'date'],
        'address' => ['required', 'string', 'max:100'],
        'email' => ['required', 'regex:/^.+@.+$/i'],
        'phone' => ['required', 'integer'],
        'contract_start' => ['required', 'date'],
        'contract_finished' => ['nullable', 'date'],
        'gender' => ['required', 'integer'],
        // 'valid_document' => ['required', 'date'],
        'nationality' => ['required', 'string', 'max:50'],
        'working_day' => ['nullable', 'string', 'max:50'],
        'disability' => ['nullable', 'boolean'],
        'payment' => ['required', 'integer'],
      ]);
      $validationDocument = Validator::make($inputDocument, [
        "document_type_id" => ['required', 'integer'],
        "service_id" => ['required', 'integer'],
        // "employee_id" => [],
        "start" => ['required', 'date'],
        "finish" => ['required', 'date'],
        // "month_year_registry" => [],
        // "path_data" => [],
        "validation_state_id" => ['required', 'integer'],
        // "id" => [],
      ]);
      $error_array = array();
      if ($validationEmployee->fails() || $validationDocument->fails()) {
        foreach ($validationEmployee->messages()->getMessages() as $field_name => $messages) {
          $error_array[] = $messages[0];
        }
        foreach ($validationDocument->messages()->getMessages() as $field_name => $messages) {
          $error_array[] = $messages[0];
        }
        return response()->json($error_array, 201);
      } else {
        $employee = Employee::create($inputEmployee);

        if ($employee) {
          try {
            $file_path = $request->file('file')->store('uploads', 's3');
            $inputDocument['path_data'] = $file_path;
            $inputDocument['employee_id'] = $employee->id;
            $document = Document::create($inputDocument);
            if ($document) {
              return response()->json(["message" => "Empleado creado con exito"], 200);
            } else {
              $employee->delete();
              return response()->json(["message" => "Error al intentar crear empleado. Por favor intente nuevamente"], 400);
            }
          } catch (\Throwable $th) {
            $employee->delete();
            return response()->json(["message" => "Error al intentar crear empleado. Por favor intente nuevamente"], 400);
          }
        } else {
          return response()->json(["message" => "Error al intentar crear empleado. Por favor intente nuevamente"], 400);
        }
      }
    }
  }
}
