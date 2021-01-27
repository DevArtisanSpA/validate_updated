<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Mail\DocumentsResponseCompanyBase;
use App\Mail\DocumentsResponseCompanyMonthly;
use App\Models\Company;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\Service;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use stdClass;

class ReviewController extends Controller
{
    public function companyBaseIndex()
    {
        $authData = Auth::user();
        $companies = Company::with([
            'services' => function ($query) {
                return $query->complete()->countDocuments(2, 1)
                    ->whereHas('documents.type', function (Builder $query) {
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
        return view('review/companies/base/index', [
            'auth' => $authData,
            'companies' => collect($companies2)
        ]);
    }
    public function companyMonthlyIndex()
    {
        $authData = Auth::user();
        $companies = Company::with([
            'services' => function ($query) {
                return $query->complete()
                    ->whereHas('documents.type', function (Builder $query) {
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
                    $GroupsDocument = Document::where('service_id', $service->id)
                        ->whereHas('type', function (Builder $query) {
                            return $query->where('area_id', 2)->where('temporality_id', 2);
                        })->select('id', 'month_year_registry', 'start', 'finish', 'validation_state_id')->get()->groupBy('month_year_registry');
                    foreach ($GroupsDocument as $key => $group) {
                        $company = clone $companyLocal;
                        $company->service = clone ($service);
                        $company->service->month_year_registry = $key;
                        $secondGroup = $group->groupBy('validation_state_id');
                        try {
                            $pending = count($secondGroup['2']);
                        } catch (\Throwable $th) {
                            $pending = 0;
                        }
                        $company->service->pending = $pending;
                        try {
                            $approved = count($secondGroup['3']);
                        } catch (\Throwable $th) {
                            $approved = 0;
                        }
                        $company->service->approved = $approved;
                        try {
                            $rejected = count($secondGroup['4']);
                        } catch (\Throwable $th) {
                            $rejected = 0;
                        }
                        $company->service->rejected = $rejected;

                        unset($company->services);
                        array_push($companies2, $company);
                    }
                }
            }
        }
        return view('review/companies/monthly/index', [
            'auth' => $authData,
            'companies' => collect($companies2),
            // 'monthYear' => $monthYear
        ]);
    }
    public function employeeBaseIndex()
    {
        $authData = Auth::user();
        $employees = Employee::with([
            'services' => function ($query) {
                return $query->complete();
            },
        ])->get();
        $employees2 = [];
        foreach ($employees as $key => $employeeLocal) {
            foreach ($employeeLocal->services as $key => $service) {
                $employee = clone $employeeLocal;
                $employee->service = Service::where('id', $service->id)->complete()->countDocuments(1, 1, null, $employee->id)->first();
                // ->whereHas('documents.type', function (Builder $query) {
                //     return $query->where('area_id', 1)->where('temporality_id', 2);
                // });
                unset($employee->services);
                array_push($employees2, $employee);
            }
        }
        return view('review/employees/base/index', [
            'auth' => $authData,
            'employees' => collect($employees2)
        ]);
    }
    public function employeeMonthlyIndex()
    {
        $authData = Auth::user();
        $employees = Employee::with([
            'services' => function ($query) {
                return $query->complete();
            },
        ])->whereHas('services', function (Builder $query) {
            return $query->where('service_type_id', 1);
        })->get();
        $employees2 = [];
        foreach ($employees as $key => $employeeLocal) {
            foreach ($employeeLocal->services as $key => $service) {
                if ($service->service_type_id == 1) {
                    $GroupsDocument = Document::where('service_id', $service->id)
                        ->where('employee_id', $employeeLocal->id)
                        ->whereHas('type', function (Builder $query) {
                            return $query->where('area_id', 1)->where('temporality_id', 2);
                        })->select('id', 'month_year_registry', 'start', 'finish', 'validation_state_id')->get()->groupBy('month_year_registry');
                    foreach ($GroupsDocument as $key => $group) {
                        $employee = clone $employeeLocal;
                        $employee->service = clone ($service);
                        $employee->service->month_year_registry = $key;
                        $secondGroup = $group->groupBy('validation_state_id');
                        try {
                            $pending = count($secondGroup['2']);
                        } catch (\Throwable $th) {
                            $pending = 0;
                        }
                        $employee->service->pending = $pending;
                        try {
                            $approved = count($secondGroup['3']);
                        } catch (\Throwable $th) {
                            $approved = 0;
                        }
                        $employee->service->approved = $approved;
                        try {
                            $rejected = count($secondGroup['4']);
                        } catch (\Throwable $th) {
                            $rejected = 0;
                        }
                        $employee->service->rejected = $rejected;

                        unset($employee->services);
                        array_push($employees2, $employee);
                    }
                }
            }
        }
        return view('review/employees/monthly/index', [
            'auth' => $authData,
            'employees' => collect($employees2),
            // 'monthYear' => $monthYear
        ]);
    }
    public function edit($id_service, $id, $monthYear = null)
    {
        $path_split = explode("/",  URL::current());
        $area = ($path_split[6] == 'employees') ? 1 : 2;
        $temp = ($path_split[8] == 'base') ? 1 : 2;
        $service = Service::where('id', $id_service)->complete()->with('company.commercialBusiness:id,name')->first();
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
        if (!is_null($monthYear)) {
            $service->month_year_registry = $monthYear;
        }
        $required = DocumentType::where('area_id', $area)->where('temporality_id', $temp)->where('optional', false)
            ->where('service_type_id', $service->service_type_id)->get()->modelKeys();

        $documents = $Q->get();
        $authData = Auth::user();
        return view('review/' . $path_split[6] . '/' . $path_split[8] . '/edit', [
            'auth' => $authData,
            'service' => $service,
            'documents' => collect($documents),
            'monthYear' => strval($monthYear),
            'required' => collect($required),
            'employee' => $employee,
        ]);
    }

    public function responseMail(Request $request)
    {

        $input = $request->all();
        $service_id = $input['service_id'];
        $documents = $input['documents'];
        $service = Service::where('id', $service_id)->complete()->first();
        $area = $input['area'];
        $temp = $input['temp'];
        try {
            $periodo = $input['periodo'];
        } catch (\Throwable $th) {
            $periodo = null;
        }
        \Debugbar::info($service->company);
        \Debugbar::info($service->branchOffice->company);
        // $document->observations = str_replace("\\n", " : ", explode("</div>", $document->observations)[0]);
        $wrong = false;
        foreach ($documents as $key => $document) {
            if ($document['observations'] == null) {
                $doc = Document::find($document['id']);
                $documents[$key]['observations'] = str_replace("\\n", " : ", explode("</div>", $doc->observations)[0]);
            }
            if ($document['validation_state_id'] == 4) {
                $wrong = true;
            }
        }
        if ($area == 1 && $temp == 1) {
        } else if ($area == 1 && $temp == 2) {
        } else if ($area == 2 && $temp == 1) {
            Mail::to([Constants::getAdmin()->email, $service->company->contact_email, $service->branchOffice->company->contact_email])
                ->send(new DocumentsResponseCompanyBase($service, $documents, $wrong));
        } else if ($area == 2 && $temp == 2) {
            Mail::to([Constants::getAdmin()->email, $service->company->contact_email, $service->branchOffice->company->contact_email])
                ->send(new DocumentsResponseCompanyMonthly($service, $documents, $periodo, $wrong));
        }
    }
}
