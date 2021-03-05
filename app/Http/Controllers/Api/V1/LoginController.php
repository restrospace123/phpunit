<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Validator;

class LoginController extends Controller
{
    public function login(Request $request){
  
        if(request()->isMethod('get')){
            if (Auth::guard('api')->check()) {
                return response(['success' => 'You are already logedin'], 200);
            }

            return response(['error' => 'unauthenticated'], 401);
        }

        /**
         * Validate creds
         */
        $validator = Validator::make($request->all(), [
            'username' => 'required|min:8|max:50',
            'password' => 'required|min:6|max:50'  
         ]);
 
        if($validator->fails()){
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 202);
        }

        /**
         * Auth Validate
         */
        if(Auth::attempt(['username' => request()->username, 'password' => request()->password])){
           $user =  Auth::user();
           $responseArr['status']  = 'success';
           $responseArr['token']   = $user->createToken('BackendApi')->accessToken;
           $responseArr['name']    = $user->name;
           
           return response()->json($responseArr, 200);
        }

        return response()->json(['error' => 'unautherised'], 203);
    }

    public function logout(){
       
        Auth::user()->token()->revoke();
        Auth::user()->token()->delete();
        //Auth::logout();
        return response()->json(['status' => 'loggedout success'], 200);
    }
}
