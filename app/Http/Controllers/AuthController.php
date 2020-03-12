<?php

namespace App\Http\Controllers;
use Auth;
use Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function login(){
    	return view('auth.login');
    }

    public function prosesLogin(Request $request){

        $error = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email tidak boleh kosong !',
            'email.exists' => 'Email tidak tersedia !',
            'password.required' => 'Password tidak boleh kosong !',
            'password.min' => 'Password minimal 6 karakter !'
        ]);

        if($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        } else if(Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['success' => 'Login berhasil']);
        } else {
            return response()->json(['error_login' => 'Login gagal, email dan password tidak sesuai']);
        }
    }

    public function logout()
    {
    	Auth::logout();
    	return redirect('/');
    }
}
