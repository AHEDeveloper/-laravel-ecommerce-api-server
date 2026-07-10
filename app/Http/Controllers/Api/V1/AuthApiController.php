<?php

namespace App\Http\Controllers\Api\V1;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Api\AuthApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends Controller
{
    public function register(Request $request)
    {
        $service = new AuthApiService();
        $validation = $service->validation($request);
        if ($validation->fails())
        {
            ApiResponseClass::apiResponse(true,'validation fails',$validation->errors(),200);
        }
        $user = User::query()->create([
           'name' => $request->string('name'),
           'email' => $request->string('email'),
           'password' => bcrypt($request->string('password')),
           'is_admin' => $request->boolean('is_admin'),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;
        return ApiResponseClass::apiResponse(true,'created user successFully',[
            'user' => $user,
            'token' => $token
        ],201);
    }

    public function login(Request $request)
    {
        $validation = AuthApiService::validationLogin($request);
        if ($validation->fails())
        {
            return ApiResponseClass::apiResponse('true','validation fails',$validation->errors(),200);
        }
        $credentials = $validation->validated();
        if (!Auth::attempt($credentials))
        {
            return ApiResponseClass::errorResponse('UnAuthorized','invalid Credentials',null,403);
        }
        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;
        return ApiResponseClass::apiResponse(true,'created user successFully',[
            'user' => $user,
            'token' => $token
        ],201);
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return ApiResponseClass::apiResponse(true,'logOut SuccessFully',null,200);
    }
}
