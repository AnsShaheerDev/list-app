<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiLoginController extends Controller
{
    public function login(Request $request){
    	return response()->json('Successful!!!!',201);
    }
}
