<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Models\Company;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\JobType;
use App\Models\Region;
use App\Models\Service;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    $employees = Employee::table()->active()->get();
    if (Auth::user()->user_type_id == 1) {
      $user = Auth::user();
    } else {
    }

    $employees2 = [];
    foreach ($employees as $key => $employeeLocal) {
      foreach ($employeeLocal->documents as $key => $document) {
        $employee = clone $employeeLocal;
        // $employee->document = clone $document;
        $employee->service = clone ($document->service);
        unset($employee->documents);
        array_push($employees2, $employee);
      }
    }
    return view('employees/index', [
      'employees' => collect($employees2),
      'auth' => Auth::user()
    ]);
  }

  public function create($id_service)
  {
    $regions = Region::with('communes')->get();
    $jobs = JobType::all();
    $service = Service::where('id', $id_service)->complete()->first();
    $documentType = DocumentType::where('service_type_id', $service->service_type_id)
      ->where('name', 'Cédula de Identidad')->first();
    $service->doc_cedula_id = $documentType->id;
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
      $validationEmployee = Employee::validator($inputEmployee);
      $validationDocument = Validator::make($inputDocument, [
        "document_type_id" => ['required', 'integer'],
        "service_id" => ['required', 'integer'],
        "start" => ['required', 'date'],
        "finish" => ['required', 'date'],
        "validation_state_id" => ['required', 'integer'],
      ]);
      $error_array = array();
      if ($validationEmployee->fails() || $validationDocument->fails()) {
        foreach ($validationEmployee->messages()->getMessages() as $field_name => $messages) {
          $error_array[] = $messages[0];
        }
        foreach ($validationDocument->messages()->getMessages() as $field_name => $messages) {
          $error_array[] = $messages[0];
        }
        return response()->json($error_array, 400);
      } else {
        return DB::transaction(function () use ($inputEmployee, $inputDocument, $request) {
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
        });
      }
    }
  }

  public function edit($id_service, $id_employee)
  {
    $regions = Region::with('communes')->get();
    $jobs = JobType::all();
    $service = Service::where('id', $id_service)->complete()->first();
    $documentType = DocumentType::where('service_type_id', $service->service_type_id)
      ->where('name', 'Cédula de Identidad')->first();
    $service->doc_cedula_id = $documentType->id;
    $employee = Employee::where('id', $id_employee)->table($id_service)->first();
    $authData = Auth::user();
    $authData->isAdmin = Auth::user()->user_type_id == 1;
    return view('employees/edit', [
      'dataList' => json_encode([
        'regions' => $regions,
        'job_types' => $jobs,
        'countries' => Constants::$COUNTRIES
        // 'commercialBusinesses' => $commercialBusinesses,
        // 'affiliations' => Constants::$AFFILIATIONS,
      ]),
      'employee' => $employee,
      'service' => $service,
      'auth' => $authData
    ]);
  }
  public function update(Request $request)
  {
    $input = $request->all();
    // $file = $request->file;
    $document = json_decode($input["document"]);
    $inputEmployee = (array) (json_decode($input["employee"]));
    $validationEmployee = Employee::validator($inputEmployee);
    if ($validationEmployee->fails()) {
      foreach ($validationEmployee->messages()->getMessages() as $field_name => $messages) {
        $error_array[] = $messages[0];
      }
      return response()->json($error_array, 400);
    }

    $inputDocument = (array)(json_decode($input["document"]));
    $validationDocument = Validator::make($inputDocument, [
      "document_type_id" => ['required', 'integer'],
      "service_id" => ['required', 'integer'],
      "employee_id" => ['required', 'integer'],
      "start" => ['required', 'date'],
      "finish" => ['required', 'date'],
      "validation_state_id" => ['required', 'integer'],
      "id" => ['required', 'integer'],
    ]);
    $error_array = array();
    if ($validationDocument->fails() || $validationEmployee->fails()) {
      foreach ($validationDocument->messages()->getMessages() as $field_name => $messages) {
        $error_array[] = $messages[0];
      }
      foreach ($validationEmployee->messages()->getMessages() as $field_name => $messages) {
        $error_array[] = $messages[0];
      }
      return response()->json($error_array, 400);
    }
    $employee = Employee::find($inputEmployee['id']);
    // $employeeClone = clone $employee;
    $document = Document::find($inputDocument['id']);
    $documentClone = clone $document;
    return DB::transaction(function () use ($employee, $inputEmployee, $document, $inputDocument, $request) {
      $employee = $employee->update($inputEmployee);
      if (!$employee) {
        return response()->json([
          "message" => "Error al intentar actualizar el  empleado. Por favor intente nuevamente"
        ], 400);
      }
      $file_path = null;
      if ($request->hasFile('file') && $request->file('file')->isValid()) {
        $file_path = $request->file('file')->store('uploads', 's3');
        $inputDocument['path_data'] = $file_path;
      }
      $document = $document->update($inputDocument);
      if (!$document) {
        return response()->json([
          "message" => "Error al intentar actualizar el  documeto del empleado. Por favor intente nuevamente"
        ], 400);
      }
      return response()->json([
        "message" => "Empleado actualizado con exito",
        "employee" => $employee,
        "document" => $document
      ], 200);
    });
  }

  public function destroy($id)
  {
    $employee = Employee::find($id);
    $employee->active = false;
    if ($employee->save()) {
      return response()->json([
        "message" => "Empleado desactivado con exito",
      ], 200);
    } else {
      return response()->json([
        "message" => "Error al desactivar empleado, intente más tarde",
      ], 400);
    }
  }
  public function terminate(Request $request, $id)
  {
    $input = $request->all();
    if ($request->hasFile('file') && $request->file('file')->isValid()) {
      $inputDocument = (array)(json_decode($input["document"]));
      $validationDocument = Validator::make($inputDocument, [
        "document_type_id" => ['required', 'integer'],
        "employee_id" => ['required', 'integer'],
        "service_id" => ['required', 'integer'],
        "start" => ['required', 'date'],
        "finish" => ['required', 'date'],
        "validation_state_id" => ['required', 'integer'],
      ]);
      $error_array = array();
      if ($validationDocument->fails()) {
        foreach ($validationDocument->messages()->getMessages() as $field_name => $messages) {
          $error_array[] = $messages[0];
        }
        return response()->json($error_array, 400);
      } else {
        return DB::transaction(function () use ($inputDocument, $id, $request) {
          try {
            $file_path = $request->file('file')->store('uploads', 's3');
            $inputDocument['path_data'] = $file_path;
            $document = Document::create($inputDocument);
            if ($document) {
              $employee = Employee::find($id);
              $employee->active = false;
              $employee->save();
              return response()->json(["message" => "Finiquito creado con exito"], 200);
            } else {
              return response()->json(["message" => "Error al intentar crear Finiquito. Por favor intente nuevamente"], 400);
            }
          } catch (\Throwable $th) {
            return response()->json(["message" => "Error al intentar crear Finiquito. Por favor intente nuevamente", "catch" => "catch"], 400);
          }
        });
      }
    }
  }


  /**
   * Terminate a employee.
   */

  public function terminateEmployee()
  {
    $authData = Auth::user();
    $services = Service::where('service_type_id', 1)->active()
      ->whereHas('company', function ($query) use ($authData) {
        if ($authData->user_type_id != 1) {
          return $query->where('id', $authData->company_id);
        }
        return $query;
      })->with('employees', function ($query) {
        return $query->where('active', 1);
      })->get();
    // foreach ($companies as $company) {
    //     $branches = $company->Branch_offices;
    //     $totalEmployees = new Collection();
    //     foreach ($branches as $key => $branch) {
    //         $employees = $branch->Employees;
    //         $totalEmployees = $totalEmployees->concat($employees);
    //     }
    //     $company->employees = $totalEmployees;
    // }
    return view('employees/terminate', [
      'auth' => Auth::user(),
      'datas_list' =>
      json_encode([
        'services' => $services
      ])
    ]);
  }
  public function getEmployeeState(Request $request)
  {
    //get employee data

    $document_id = str_replace(".", "", $request->number_identification);
    $company_id = Auth::user()->company_id;
    $employee = Employee::where(function ($query) use ($document_id) {
      return $query->where('identification_id', $document_id)
        ->orWhere('identification_id', 'regexp', $document_id . '.');
    })->wherehas('services', function ($query) use ($company_id) {
      return $query->where('company_id', $company_id);
    })->active()->first();
    //get principal contractor branch office data
    try {
      $documents = Document::where('employee_id', $employee->id)
        ->wherehas([
          'service' =>
          function ($query) use ($company_id) {
            return $query->where('company_id', $company_id);
          },
          'type' =>
          function ($query) {
            return $query->where('temporality_id', 1);
          },
        ]);
    } catch (\Throwable $th) {
      $documents = null;
    }
    //get employee document state
    try {
      $document_company = Document::wherehas([
        'service' =>
        function ($query) use ($company_id) {
          return $query->where('company_id', $company_id);
        },
        'type' =>
        function ($query) {
          return $query->where('temporality_id', 1);
        },
      ]);
    } catch (\Throwable $th) {
      $document_company = null;
    }
    if (
      $employee
      && $documents && $document_company
    ) {
      return response()->json(true, 200);
    } else {
      return response()->json(false, 200);
    }
  }
}
