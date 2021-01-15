<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Company;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Service;
use App\Models\Temporality;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
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
            $query->where('area_id', 2);
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
    return view('documents/companies/monthly/index', [
      'auth' => $authData
    ]);
  }
  public function employeeBaseIndex()
  {
    $authData = Auth::user();
    return view('documents/employees/base/index', [
      'auth' => $authData
    ]);
  }
  public function employeeMonthlyIndex($monthYear)
  {
    $authData = Auth::user();
    return view('documents/employees/monthly/index', [
      'auth' => $authData
    ]);
  }

  public function createEdit($id_service, $monthYear = null)
  {
    $path_split = explode("/",  URL::current());
    $area = ($path_split[6] == 'employees') ? 1 : 2;
    $temp = ($path_split[7] == 'base') ? 1 : 2;
    // $monthYear = ($path_split[7] == 'base') ? 1 : 2;
    $authData = Auth::user();
    $service = Service::where('id', $id_service)->complete()->first();
    $document_types = DocumentType::with(['area:id,name', 'temporality:id,name'])
      ->where('area_id', $area)->where('temporality_id', $temp)
      ->where('service_type_id', $service->service_type_id)->orderby('name')->get();

    $documents = Document::where('service_id', $id_service)->basic()
      ->whereHas('type', function (Builder $query) use ($area, $temp) {
        $query->where('area_id', $area)->where('temporality_id', $temp);
      })->get();

    return view('documents/' . $path_split[6] . '/' . $path_split[7] . '/new', [
      'auth' => $authData,
      'service' => $service,
      'documents' => collect($documents),
      'document_types' => collect($document_types),
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
      DB::transaction(function () use ($request, $i, $inputDocument,$creacted,$updated) {
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
}
