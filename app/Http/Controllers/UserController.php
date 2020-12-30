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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::with('company')->where('id', '!=', auth()->id())->get();

        return view('users/index', ['users' => $users]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $companies = Company::where('active', true)->orderBy('business_name')->get();
        $create = json_encode([ 'companies' => $companies ]);
        return view('users/new', ['create' => $create]);
    }

    /**
     * Show the form for editing a resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $companies = Company::where('active', true)->orderBy('business_name')->get();
        $create = json_encode([ 'companies' => $companies ]);
        $user = User::find($id);
        return view('users/edit', ['create' => $create, 'user' => $user]);
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
            'email' => ['required', 'unique:users', 'string', 'min:5'],
            'company_id' => ['required'],
            'user_type_id' => ['required']
        ]);
        if ($validation->fails())
        {
            $error_array = array();
            foreach($validation->messages()->getMessages() as $field_name => $messages)
            {
                $error_array[] = $messages[0];
            }
            return response()->json($error_array, 201);
        }
        else
        {
            $result = User::create($request->all());
            if($result){
                $user = User::find($result->id);

                $token = app('auth.password.broker')->createToken($user);
                Mail::to($user->email)->send(new MailWelcome($token, $user));
                return response()->json(["message" => "Usuario creado exitosamente"], 200);
            }
            else
                return response()->json(["message" => "Error al intentar crear usuario, por favor intentar nuevamente"], 400);
        }
        return true;
    }

    /**
     * Update a resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => ['required'],
            'name' => ['required', 'string', 'min:3'],
            'email' => ['required', 'string', 'min:5'],
            'company_id' => ['required'],
            'user_type_id' => ['required']
        ]);
        if ($validation->fails())
        {
            $error_array = array();
            foreach($validation->messages()->getMessages() as $field_name => $messages)
            {
                $error_array[] = $messages[0];
            }
            return response()->json($error_array, 201);
        }
        else
        {
            $user = User::find($request->id);
            $result = $user->update($request->all());
            if($result){
                return response()->json(["message" => "Usuario editado exitosamente"], 200);
            }
            else
                return response()->json(["message" => "Error al intentar editar usuario. Por favor intente nuevamente"], 400);
        }
        return true;
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
        return User::where('id', $id)->delete();
    }
}
