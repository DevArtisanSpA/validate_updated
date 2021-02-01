<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Mail\DocumentsLoad;
use App\Mail\DocumentsLoadCompanyBase;
use App\Mail\DocumentsLoadCompanyMonthly;
use App\Mail\DocumentsLoadEmployeeBase;
use App\Mail\DocumentsLoadEmployeeMonthly;
use App\Mail\DocumentsResponseCompanyBase;
use App\Mail\DocumentsResponseCompanyMonthly;
use App\Models\Company;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\Service;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use League\Flysystem\Filesystem;
use League\Flysystem\ZipArchive\ZipArchiveAdapter;
use stdClass;
use Symfony\Component\Finder\Finder;

class DocumentController extends Controller
{
  public function companyBaseIndex()
  {
    $authData = Auth::user();
    $companies = Company::with([
      'services' => function ($query) {
        return $query->complete();
      },
      'services.documents' => function ($query) {
        return $query->basic()
          ->whereHas('type', function (Builder $query) {
            return $query->where('area_id', 2)->where('temporality_id', 1);
          });
      },
    ]);
    if ($authData->user_type_id != 1) {
      $companies->where('id', $authData->company_id);
    }
    $companies = $companies->get();
    $companies2 = [];
    foreach ($companies as $key => $companyLocal) {
      foreach ($companyLocal->services as $key => $service) {
        $company = clone $companyLocal;
        $company->service = clone ($service);
        unset($company->services);
        array_push($companies2, $company);
      }
    }
    return view('documents/companies/base/index', [
      'auth' => $authData,
      'companies' => collect($companies2)
    ]);
  }
  public function companyMonthlyIndex($monthYear)
  {
    $monthYear = explode("-",  $monthYear);
    $authData = Auth::user();
    $companies = Company::with([
      'services' => function ($query) {
        return $query->complete();
      },
      'services.documents' => function ($query) use ($monthYear) {
        return $query->basic()->whereMonth('start', $monthYear[1])->whereYear('start', $monthYear[0])
          ->whereHas('type', function (Builder $query) {
            return $query->where('area_id', 2)->where('temporality_id', 2);
          });
      },
    ])->whereHas('services', function (Builder $query) {
      return $query->where('service_type_id', 1);
    });
    if ($authData->user_type_id != 1) {
      $companies->where('id', $authData->company_id);
    }
    $companies = $companies->get();
    $companies2 = [];
    foreach ($companies as $key => $companyLocal) {
      foreach ($companyLocal->services as $key => $service) {
        if ($service->service_type_id == 1) {
          $company = clone $companyLocal;
          $company->service = clone ($service);
          unset($company->services);
          array_push($companies2, $company);
        }
      }
    }
    return view('documents/companies/monthly/index', [
      'auth' => $authData,
      'companies' => collect($companies2),
      'monthYear' => $monthYear
    ]);
  }

  public function employeePreTable($monthYear = null)
  {
    $authData = Auth::user();
    $path_split = explode("/",  URL::current());
    $last = $path_split[count($path_split) - 1];
    $temp = ($last == 'base') ? 1 : 2;
    $services = Service::complete();
    if ($authData->user_type_id != 1) {
      $services = $services->where('company_id', $authData->company_id);
    }
    if ($temp == 2) {
      $services = $services->where('service_type_id', 1);
    }
    $services = $services->get();
    foreach ($services as $key => $service) {
      $employees = $service->employees;
      $employeesKeys = $service->employees->modelkeys();
      $service->employeeTotal = count($employeesKeys);
      $service->employeePending = 0;
      $service->employeeApproved = 0;
      $service->employeeRejected = 0;
      $service->month_year_registry = $monthYear;
      foreach ($employees as $key => $employee) {
        $documents = Document::where('service_id', $service->id)
          ->where('employee_id', $employee->id)
          ->whereHas('type', function (Builder $query) use ($temp) {
            return $query->where('area_id', 1)->where('temporality_id', $temp);
          });
        if (!is_null($monthYear)) {
          $documents = $documents->where('month_year_registry', $monthYear);
        }
        $documents = $documents->select('id', 'validation_state_id', 'month_year_registry')->get()->groupBy('validation_state_id');
        try {
          count($documents['2']);
          $service->employeePending++;
        } catch (\Throwable $th) {
          try {
            count($documents['4']);
            $service->employeeRejected++;
          } catch (\Throwable $th) {
            try {
              count($documents['3']);
              $service->employeeApproved++;
            } catch (\Throwable $th) {
            }
          }
        }
      }
    }

    return view('documents/employees/pre_table', [
      'auth' => $authData,
      'services' => collect($services),
      'monthly' => $temp,
      'period' => json_encode(['monthYear' => $monthYear])
    ]);
  }
  public function employeeBaseIndex($id_service)
  {
    $authData = Auth::user();
    $service = Service::where('id', $id_service)->complete()->first();
    $employees = Employee::with([
      'services' => function ($query) use ($authData, $id_service) {
        if ($authData->user_type_id != 1) {
          $query = $query->whereHas('company', function (Builder $query) use ($authData) {
            return $query->where('id', $authData->company_id);
          });
        }
        return $query->where('services.id', $id_service)->complete();
      },
    ]);
    if ($authData->user_type_id != 1) {
      $employees->whereHas('services.company', function (Builder $query) use ($authData) {
        return $query->where('id', $authData->company_id);
      });
    }
    $employees = $employees->get();
    $employees2 = [];
    foreach ($employees as $key => $employeeLocal) {
      foreach ($employeeLocal->services as $key => $service) {
        if ($service->id == $id_service) {
          $employee = clone $employeeLocal;
          $employee->service = clone ($service);
          $employee->service->documents = Document::where('service_id', $service->id)
            ->where('employee_id', $employee->id)->basic()
            ->whereHas('type', function (Builder $query) {
              return $query->where('area_id', 1)->where('temporality_id', 1);
            })->get();
          unset($employee->services);
          array_push($employees2, $employee);
        }
      }
    }
    return view('documents/employees/base/index', [
      'auth' => $authData,
      'employees' => collect($employees2),
      'service' => $service,
    ]);
  }
  public function employeeMonthlyIndex($id_service, $monthYear)
  {
    $monthYear = explode("-",  $monthYear);
    $authData = Auth::user();
    $service = Service::where('id', $id_service)->complete()->first();
    $employees = Employee::with([
      'services' => function ($query) use ($authData, $id_service) {
        if ($authData->user_type_id != 1) {
          $query = $query->whereHas('company', function (Builder $query) use ($authData) {
            return $query->where('id', $authData->company_id);
          });
        }
        return $query->where('services.id', $id_service)->complete();
      }
    ])->whereHas('services', function (Builder $query) {
      return $query->where('service_type_id', 1);
    });
    if ($authData->user_type_id != 1) {
      $employees->whereHas('services.company', function (Builder $query) use ($authData) {
        return $query->where('id', $authData->company_id);
      });
    }
    $employees = $employees->get();
    $employees2 = [];
    foreach ($employees as $key => $employeeLocal) {
      foreach ($employeeLocal->services as $key => $service) {
        if ($service->service_type_id == 1 && $service->id == $id_service) {
          $employee = clone $employeeLocal;
          $employee->service = clone ($service);
          $employee->service->documents = Document::where('service_id', $service->id)
            ->where('employee_id', $employee->id)->basic()->whereMonth('start', $monthYear[1])->whereYear('start', $monthYear[0])
            ->whereHas('type', function (Builder $query) {
              return $query->where('area_id', 1)->where('temporality_id', 2);
            })->get();
          unset($employee->services);
          array_push($employees2, $employee);
        }
      }
    }
    return view('documents/employees/monthly/index', [
      'auth' => $authData,
      'employees' => collect($employees2),
      'monthYear' => $monthYear,
      'service' => $service,
    ]);
  }
  public function createEdit($id_service, $id, $monthYear = null)
  {
    $path_split = explode("/",  URL::current());
    $area = ($path_split[6] == 'employees') ? 1 : 2;
    $temp = ($path_split[8] == 'base') ? 1 : 2;
    $service = Service::where('id', $id_service)->complete()->first();
    // $monthYear = ($path_split[7] == 'base') ? 1 : 2;
    $authData = Auth::user();
    $document_types = DocumentType::with(['area:id,name', 'temporality:id,name'])
      ->where('area_id', $area)->where('temporality_id', $temp)
      ->where('service_type_id', $service->service_type_id)->orderby('name')->get();
    $required = DocumentType::where('area_id', $area)->where('temporality_id', $temp)->where('optional', false)
      ->where('service_type_id', $service->service_type_id)->get()->modelKeys();
    $Q = Document::where('service_id', $id_service)->basic()
      ->whereHas('type', function (Builder $query) use ($area, $temp, $monthYear) {
        $Q = $query->where('area_id', $area)->where('temporality_id', $temp);
        if (!is_null($monthYear)) {
          $monthYear = explode("-",  $monthYear);
          $Q = $Q->whereMonth('start', $monthYear[1])->whereYear('start', $monthYear[0]);
        }
        return $Q;
      });
    $employee = new stdClass();
    if ($area == 1) {
      $Q = $Q->where('employee_id', $id);
      $employee = Employee::find($id);
    }
    $documents = $Q->get();
    return view('documents/' . $path_split[6] . '/' . $path_split[8] . '/new', [
      'auth' => $authData,
      'service' => $service,
      'documents' => collect($documents),
      'document_types' => collect($document_types),
      'monthYear' => strval($monthYear),
      'required' => collect($required),
      'employee' => $employee,
    ]);
  }
  public function storeUpdate(Request $request)
  {
    $creacted = [];
    $updated = [];
    $input = $request->all();
    for ($i = 0; $i < count($input) / 2; $i++) {
      $inputDocument = (array) json_decode($input['data' . ($i + 1)]);
      $validationDocument = Document::validator($inputDocument);
      $error_array = array();
      if ($validationDocument->fails()) {
        foreach ($validationDocument->messages()->getMessages() as $field_name => $messages) {
          $error_array[] = $messages[0];
        }
        Document::destroy(collect($creacted));
        foreach ($updated as $key => $documentOld) {
          $document = Document::find($documentOld->id);
          $document->update((array)$documentOld);
        }
        return response()->json($error_array, 401);
      }
      DB::transaction(function () use ($request, $i, $inputDocument, $creacted, $updated) {
        if ($request->hasFile('file' . ($i + 1)) && $request->file('file' . ($i + 1))->isValid()) {
          $inputDocument['path_data'] = $request->file('file' . ($i + 1))->store('uploads', 's3');
        }
        $document = new stdClass();
        try {
          $document = Document::find($inputDocument['id']);
          array_push($updated, clone ($document));
          $document->update($inputDocument);
        } catch (\Throwable $th) {
          $document = Document::create($inputDocument);
          array_push($creacted, $document->id);
        }
        if ($document) {
          return response()->json(["message" => "Documento creado con exito"], 200);
        } else {
          Document::destroy(collect($creacted));
          foreach ($updated as $key => $documentOld) {
            $document = Document::find($documentOld->id);
            $document->update((array)$documentOld);
          }
          return response()->json(["message" => "Error al intentar crear documento. Por favor intente nuevamente"], 400);
        }
      });
    }
  }
  public function destroy(Request $request)
  {
    $input = $request->all();
    DB::transaction(function () use ($input) {
      Document::destroy(collect($input['ids']));
    });
  }
  public function download($id)
  {
    $document = Document::find($id);
    $company = $document->service->company;
    $employee = $document->employee;
    if (is_null($employee)) {
      $numID = $company->rut;
    } else {
      $numID = $employee->identification_id;
    }
    $headers = [
      'Content-Type' => 'multipart/form-data',
    ];
    $split = explode(".", $document->path_data);
    $ext = $split[count($split) - 1];
    $name = $numID . "_" . strtr($document->type->name, array(' ' => '_')) . '_' . $document->updated_at->toDateString() . '.' . $ext;
    return Storage::disk('s3')->download($document->path_data, $name, $headers);
  }
  public function downloadZip(Request $request)
  {
    $input = $request->all();
    $validator = Validator::make($input, [
      'service' => ['required', 'integer'],
      'area' => ['required', 'integer'],
      'temp' => ['required', 'integer']
    ]);
    $error_array = array();
    if ($validator->fails()) {
      foreach ($validator->messages()->getMessages() as $field_name => $messages) {
        $error_array[] = $messages[0];
      }
      return response()->json($error_array, 401);
    }
    $area = $input['area'];
    $temp = $input['temp'];
    $service = $input['service'];
    try {
      $monthYear = $input['monthYear'];
    } catch (\Throwable $th) {
      $monthYear = null;
    }
    try {
      $employee = $input['employee'];
    } catch (\Throwable $th) {
      $employee = null;
    }
    $documents = Document::whereHas('type', function (Builder $query) use ($area, $temp, $service, $monthYear, $employee) {
      $Q = $query->where('area_id', $area)->where('temporality_id', $temp)->where('service_id', $service);
      if (!is_null($monthYear)) {
        $monthYear = explode("-",  $monthYear);
        $Q = $Q->whereMonth('start', $monthYear[1])->whereYear('start', $monthYear[0]);
      }
      if (!is_null($employee)) {
        $Q = $Q->where('employee_id', $employee);
      }
      return $Q;
    })->get();
    $zipname = "app\\uploads\\all.zip";
    if (!file_exists(storage_path('app\\uploads'))) {
      File::makeDirectory(storage_path('app\\uploads'));
    }
    $zip = new Filesystem(new ZipArchiveAdapter(storage_path($zipname)));
    try {
      foreach ($documents as $key => $document) {
        if (
          Storage::disk('s3')->exists($document->path_data)
        ) {
          $file = Storage::disk('s3')->get($document->path_data);
          $split = explode(".", $document->path_data);
          $ext = $split[count($split) - 1];
          $company = $document->service->company;
          $employee = $document->employee;
          if (is_null($employee)) {
            $numID = $company->rut;
          } else {
            $numID = $employee->identification_id;
          }
          $name = $numID . "_" . strtr($document->type->name, array(' ' => '_')) . '_' . $document->updated_at->toDateString() . '.' . $ext;
          $temporality = ($temp === 2) ? $document->month_year_registry . '/' : '';
          $filename = $temporality . Constants::$BASE_URL_ZIP[$document->validation_state_id - 1] . $name;
          $zip->put($filename, $file);
        }
      }
      $zip->getAdapter()->getArchive()->close();
    } catch (\Exception $e) {
      return response()->json([], 204);
    }
    if (file_exists(storage_path($zipname))) {
      return response()->file(storage_path($zipname))->deleteFileAfterSend();
    } else {
      return response()->json([], 204);
    }
  }
  public function update(Request  $request)
  {
    $inputs = $request->all();
    foreach ($inputs['documents'] as $key => $input) {
      $obs = $inputs['observations'][$key];
      $document = Document::find($input['id']);
      $update = false;
      if ($document->validation_state_id !== $input['validation_state_id']) { //estados distintos
        $document->validation_state_id = $input['validation_state_id'];
        $update = true;
      }
      if ($input['validation_state_id'] == 4 && !is_null($obs) && trim($obs) != '') { // tenga observaciones no null o vacias
        $update = true;
        $fecha = Carbon::now()->timezone('America/Santiago')->format('Y-m-d H:i');
        $obs =  $fecha . '\\n' . $obs . "</div>";
        $document->observations = $obs . $document->observations;
      }
      $result = true;
      if ($update) {
        // \Debugbar::info($document->toArray());
        $result = $document->save();
      }
      if (!$result) {
        return response()->json(["message" => "Error al intentar modificar el documento, por favor intentar nuevamente"], 400);
      }
    }
    return response()->json(["message" => "Documentos modificados exitosamente"], 200);
  }

  public function loadMail(Request $request)
  {

    $input = $request->all();
    $service_id = $input['service_id'];
    $documents = $input['documents'];
    $service = Service::where('id', $service_id)->complete();
    $area = $input['area'];
    $temp = $input['temp'];
    try {
      $period = $input['period'];
    } catch (\Throwable $th) {
      $period = null;
    }
    if ($area == 1) { // empleados
      $service = $service->with(
        'employees',
        function ($query) use ($temp, $period) {
          return $query->with(['documents' =>
          function ($query) use ($period, $temp) {
            return $query->where('validation_state_id', 2)->where('month_year_registry', $period)->wherehas('type', function ($query) use ($temp) {
              return $query->where('temporality_id', $temp);
            }); // solo los pendientes
          }, 'documents.type'])
            ->wherehas('documents', function ($query) use ($period, $temp) {
              return $query->where('validation_state_id', 2)->where('month_year_registry', $period)
                ->wherehas('type', function ($query) use ($temp) {
                  return $query->where('temporality_id', $temp);
                });
            });
        }
      );
    }
    $service = $service->first();
    // \Debugbar::info($input,Constants::getAdmin()->email, $service->company->contact_email, $service->branchOffice->company->contact_email);
    if ($area == 1 && $temp == 1) { //empleado base
      if (count($service->employees) == 0) {
        return response()->json(["message" => "No hay empleados con los documentos requeridos asociados al servicio"], 400);
      }
      Mail::to([Constants::getAdmin()->email, $service->company->contact_email, $service->branchOffice->company->contact_email])
        ->send(new DocumentsLoadEmployeeBase($service, $service->employees));
    } else if ($area == 1 && $temp == 2) { // empleado mensual
      if (count($service->employees) == 0) {
        return response()->json(["message" => "No hay empleados con los documentos requeridos asociados al servicio"], 400);
      }
      Mail::to([Constants::getAdmin()->email, $service->company->contact_email, $service->branchOffice->company->contact_email])
        ->send(new DocumentsLoadEmployeeMonthly($service, $service->employees, $period));
    } else if ($area == 2 && $temp == 1) { //empresa base
      Mail::to([Constants::getAdmin()->email, $service->company->contact_email, $service->branchOffice->company->contact_email])
        ->send(new DocumentsLoadCompanyBase($service, $documents));
    } else if ($area == 2 && $temp == 2) { // empresa mensual
      Mail::to([Constants::getAdmin()->email, $service->company->contact_email, $service->branchOffice->company->contact_email])
        ->send(new DocumentsLoadCompanyMonthly($service, $documents, $period));
    }
    return response()->json(["message" => "Correo enviado con exito"], 200);
  }
}
