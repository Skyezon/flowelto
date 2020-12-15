<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller
{
    public function show(){
        return view('change_password');
    }

    public function change(Request $request){
        $user = Auth::user(); //mendapatkan user yang sedang login
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);//validasi inputan password yang baru
        if(!Hash::check($request->oldPassword,$user->password)){
            return back()->withErrors(['old password did not match']);
        }// memeriksa apakah passwordnya sesuai dengan password yang lama apabila tidak maka kembali dengan error
        $user = User::find(Auth::user()->id);
        $user->update([
            'password' => Hash::make($request->password)
        ]);//update password yang sesuai
        return redirect()->route('welcome');
    }
}
