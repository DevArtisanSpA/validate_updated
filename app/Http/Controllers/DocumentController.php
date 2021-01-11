<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
  public function companyBaseIndex()
  {
    $authData = Auth::user();
    return view('documents/companies/base/index', [
      'auth' => $authData
    ]);
  }
  public function companyMonthlyIndex()
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
  public function employeeMonthlyIndex()
  {
    $authData = Auth::user();
    return view('documents/employees/monthly/index', [
      'auth' => $authData
    ]);
  }
}
