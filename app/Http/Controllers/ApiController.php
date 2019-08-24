<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ApiController extends AppController
{
    public function index()
    {
        return view('api/index');
    }

    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');
        
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                $response['status'] = "Error";
                $response['data']['errors'] = "invalid credentials. Please check your email or password";
                return response()->json($response, 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            $response['status'] = "Error could_not_create_token";
            return response()->json($response, 500);
        }

        if ( Auth::attempt($credentials) ) {
            $tokenAux = compact('token');
            $response['status'] = "OK";
            $response['data']['user'] = Auth::user();
            $response['data']['token'] = $tokenAux['token'];
        }
        // all good so return the token
        return response()->json($response);
    }


}
