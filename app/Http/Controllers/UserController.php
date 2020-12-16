<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\UserType;
use App\Mail\MailWelcome;
use App\Providers\RouteServiceProvider;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Expr\Cast\Object_;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        //$this->middleware('CheckUserType:users,can_read');
        $this->middleware('CheckUserType');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user_types = UserType::All();
        $companies = Company::where('active', true)->orderBy('business_name')->get();
        $create = json_encode([ 'companies' => $companies ]);
        return view('users/new', ['create' => $create]);
    }
}
