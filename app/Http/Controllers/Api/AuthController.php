<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registrationData = $request->all();
        $validate = Validator::make($registrationData,[
            'nama_customer' => 'required|max:50',
            'nomor_telepon_customer' => 'required|numeric|digits_between:10,13|starts_with:08',
            'alamat_customer' => 'required|max:200',
            'email_customer' => 'required|email:rfc,dns|unique:users',
            'tanggal_lahir_customer' => 'required|date|before:date',
            'no_ktp_customer' => 'required|digits:16',
            'no_sim_customer' => 'required|max:16',
            'password_customer' => 'required|max:20'
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()],400);
        }

        $registrationData['password'] = bcrypt($request->password);
        $user = User::create($registrationData);
        // $user->sendApiEmailVerificationNotification();

        return response([
            'message' => 'Register Success',
            'user' => $user
        ],200);
    }

    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData,[
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors],400);

        if(!Auth::attempt($loginData))
            return response(['message' => 'Invalid Credentials'], 401);
        
        // $user = Auth::user();
        // if($user->email_verified_at == null){
        //     return response([
        //         'message' => 'Please Verify Your Email'
        //     ],401);
        // }
        // $token = $user->createToken('Authentication Token')->accessToken;

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);
    }
}
