<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
  //
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
    $companies = Company::where('active', true)->orderBy('business_name')->get();
    return view('reports/certificate', [
      "data" => json_encode([
        'companies' => $companies
      ])
    ]);
  }
  public function show($id)
  {
    //
  }
}
