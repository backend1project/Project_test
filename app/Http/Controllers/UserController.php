<?php

namespace App\Http\Controllers;

use App\Models\User;

use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\throwException;
use Illuminate\Http\Resources\Json;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index()
    {
        return DB::select("select * from users");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create(Request $request)
    {
        $rules = [
            'First_name' => 'required|string|min:3|max:255',
            'Last_name' => 'required|string|min:2|max:255',
            'Email' => 'required|string|email|max:255',
            'Phone_number'=> 'required|string|min:9|max:10',
            'Gender'=> 'required|string|min:4|max:9|',
            'Date_of_birth'=> 'required|date|before:2001-04-15',
            'password' => 'min:6|required|string',


        ];
        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray()
            ]);
        }
       else
           $this->store($request);
        return response()->json(["message"=>'Welcome to the family']);

    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->input();
        DB::table('users')->insert($data);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return string
     */
    public function show(User $user,$id)
    {
        $user = DB::table('users')
            ->where('Email', '=', $id)
            ->get();
        if(!is_object($user)){
         return  response()->json(['message'=>'user not found'],404);
        }
       return  response()->json(['message'=>$user]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(User $user,$id)
    {
       //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request,$id)
    {

        $u = DB::table('users')
            ->where('Email', '=', $id)
            ->get();
        $ut = DB::table('users')
            ->where('Email', '=', $id)
            ->first();
        if($ut==null){
            return response()->json(['message'=>'Not Found'],400);
        }
        $uj=json_decode($u,true);
        $check_user = DB::table('users')
        ->where('User_id', '=', $uj['0']['User_id'])
        ->get();
        $check_userjs=json_decode($check_user,true);
        echo ('2');
        $token = $request->header('X-USER-TOKEN');
        echo ('3');
        $jsonStr = base64_decode($token);
        echo ('4');
        $jsonPayload = json_decode($jsonStr, true);
            if($check_userjs['0']['Email']!=$jsonPayload['email']){
                return response()->json(['message'=>'Not Allowed'],400);
            }
            echo ('5');
        $rules = [
            'First_name' => 'required|string|min:3|max:255',
            'Last_name' => 'required|string|min:2|max:255',
            'Email' => 'required|string|email|max:255',
            'Phone_number'=> 'required|string|min:9|max:10',
            'Gender'=> 'required|string|min:4|max:9|',
            'Date_of_birth'=> 'required|date|before:2001-04-15',
            'password' => 'min:6|required|string',
        ];
        echo ('6');
        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray()
            ]);
        }
        else{
            echo ('7');
            $data=$request->input();
            DB::table('users')
                ->where('User_id', $uj['0']['User_id'])
                ->update($data);
            echo ('5');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
