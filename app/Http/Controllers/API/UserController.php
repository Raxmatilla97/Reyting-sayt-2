<?php

namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class UserController extends Controller
{
    public function userLogin(Request $request)
    {
        $input = $request->all();
    
        $validator = validator($input, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
    
        if(Auth::attempt(['email' => $input['email'], 'password' => $input['password']])){
            $user = Auth::user();
            $token = $user->createToken('MyApp')->accessToken;

            return response()->json(['token' => $token]);
        }
        // Yana qo'shimcha logika yozing...
    
        return response()->json(['success' => 'Login successful']);
    }
    
}
