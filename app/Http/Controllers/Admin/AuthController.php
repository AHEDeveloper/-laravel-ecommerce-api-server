<?php

namespace App\Http\Controllers\Admin;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $service = new AuthService();
        $validation = $service->validation($request);
        if ($validation->fails())
        {
            return ApiResponseClass::apiResponse(false,'Validation Fails',$validation->errors(),422);
        }
       $credentials = $validation->validated();
       if (!Auth::attempt($credentials)){
           return ApiResponseClass::errorResponse('UnAuthorized','invalid Credentials',null,403);
       }
       $user = Auth::user();
       if (!$user->is_admin)
       {
           return ApiResponseClass::apiResponse('Forbbiden','Access denied',null,403);
       }
       $token = $user->createToken('admin-Token')->plainTextToken;
       return ApiResponseClass::apiResponse(true,'Login SuccessFully',[
           'user' => $user,
           'token' => $token
       ],200);

    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return ApiResponseClass::apiResponse(true,'logout succseefully',null,200);
    }

    public function register(Request $request)
    {
        $service = new AuthService();
        $validation = $service->regiValidation($request);
        if ($validation->fails())
        {
            return ApiResponseClass::apiResponse(false,'Validation Fails',$validation->errors(),422);
        }

        $user = User::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_admin' => $request->is_admin,
        ]);
        $token = $user->createToken('api_token')->plainTextToken;
        return ApiResponseClass::apiResponse(true,'created User successFully',[
            'user' => $user,
            'token' => $token
        ],201);
    }
}
