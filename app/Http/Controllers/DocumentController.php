<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Models\Area;
use App\Models\Company;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\Service;
use App\Models\Temporality;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use League\Flysystem\Filesystem;
use League\Flysystem\ZipArchive\ZipArchiveAdapter;
use stdClass;

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
    ])->get();
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
    $authData = Auth::user();
    $companies = Company::with([
      'services' => function ($query) {
        return $query->complete();
      },
      'services.documents' => function ($query) use ($monthYear) {
        return $query->basic()->where('month_year_registry', $monthYear)
          ->whereHas('type', function (Builder $query) {
            return $query->where('area_id', 2)->where('temporality_id', 2);
          });
      },
    ])->whereHas('services', function (Builder $query) {
      return $query->where('service_type_id', 1);
    })->get();
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
  public function employeeBaseIndex()
  {
    $authData = Auth::user();
    $employees = Employee::with([
      'services' => function ($query) {
        return $query->complete();
      }
    ])->get();
    $employees2 = [];
    foreach ($employees as $key => $employeeLocal) {
      foreach ($employeeLocal->services as $key => $service) {
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
    return view('documents/employees/base/index', [
      'auth' => $authData,
      'employees' => collect($employees2)
    ]);
  }
  public function employeeMonthlyIndex($monthYear)
  {
    $authData = Auth::user();
    $employees = Employee::with([
      'services' => function ($query) {
        return $query->complete();
      }
    ])->whereHas('services', function (Builder $query) {
      return $query->where('service_type_id', 1);
    })->get();
    $employees2 = [];
    foreach ($employees as $key => $employeeLocal) {
      foreach ($employeeLocal->services as $key => $service) {
        if ($service->service_type_id == 1) {
          $employee = clone $employeeLocal;
          $employee->service = clone ($service);
          $employee->service->documents = Document::where('service_id', $service->id)
            ->where('employee_id', $employee->id)->basic()->where('month_year_registry', $monthYear)
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
      'monthYear' => $monthYear
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
    $Q = Document::where('service_id', $id_service)->basic()
      ->whereHas('type', function (Builder $query) use ($area, $temp, $monthYear) {
        $Q = $query->where('area_id', $area)->where('temporality_id', $temp);
        if (!is_null($monthYear)) {
          $Q = $Q->where('month_year_registry', $monthYear);
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
        $Q = $Q->where('month_year_registry', $monthYear);
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
}
