<?php

namespace App\Http\Controllers;

use App\Models\BranchOffice;
use App\Models\Company;
use App\Models\Region;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchOfficeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$companies_branch = BranchOffice::join('companies', 'companies.id', '=', 'BranchOffices.company_id')
        //    ->orderBy('business_name')->get();
        if(Auth::user()->user_type_id == 1 ){
            $companies_branch = BranchOffice::all();
            foreach ($companies_branch as $branch) {
                $branch->Company;
                $branch->Commune->Region;
            }
        } else {
            $companies_branch = BranchOffice::where('company_id', Auth::user()->company_id)->get();
            foreach ($companies_branch as $branch) {
                $branch->Company;
                $branch->Commune->Region;
            }
        }
        $companies = Company::where('active', true)->orderBy('business_name')->get();    
        return view('branchOffices/index', [
            'companies_branch' => $companies_branch,
            'companies' => $companies,
            'auth' => Auth::user()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regions = Region::with('communes')->get();
        $companies = Company::where('active', true)->orderBy('business_name')->get();
        return view('branchOffices/new', [
            'regions' => $regions,
            'companies' => json_encode($companies),
            'auth' => Auth::user()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $regions = Region::with('communes')->get();
        $branch = BranchOffice::find($id);
        $branch->Company;
        $branch->Commune->Region;
        $companies = Company::where('active', true)->orderBy('business_name')->get();
        return view('branchOffices/edit', [
            'auth' => Auth::user(),
            'regions' => $regions,
            'branch' => $branch,
            'companies' => json_encode($companies)
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
        $validation = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:3'],
            'company_id' => 'required'
        ]);
        $error_array = array();
        if ($validation->fails())
        {
          foreach($validation->messages()->getMessages() as $field_name => $messages)
          {
            $error_array[] = $messages[0];
          }
          return response()->json($error_array, 201);
        }
        else
        {
            $result = BranchOffice::create($request->all());
            
            if($result)
                return response()->json(["message" => "Sucursal creado exitosamente"], 200);
            else
                return response()->json(["message" => "Error al intentar crear sucursal, por favor intentar nuevamente"], 400);
        }
        return true;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validation = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:3'],
            'company_id' => 'required'
        ]);
        $error_array = array();
        if ($validation->fails())
        {
          foreach($validation->messages()->getMessages() as $field_name => $messages)
          {
            $error_array[] = $messages[0];
          }
          return response()->json($error_array, 201);
        }
        else
        {
            $result = BranchOffice::edit($request->all());
            if($result)
                return response()->json(["message" => "Sucursal actualizado exitosamente"], 200);
            else
                return response()->json(['message' => "Error al intentar actualizar información, por favor intentar nuevamente"], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        BranchOffice::where('id', $id)->delete();
    }
}
