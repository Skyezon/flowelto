<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $satuan
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $satuan)
    {
        return Validator::make($satuan, [
            'name' => ['required', 'string', 'min:5'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'address' => ['required','min:10'],
            'gender' => ['required'],
            'dob' => ['required']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $satuan
     * @return \App\User
     */
    protected function create(array $satuan)
    {
        return User::create([
            'username' => $satuan['name'],
            'email' => $satuan['email'],
            'password' => Hash::make($satuan['password']),
            'gender' => $satuan['gender'],
            'dob' => $satuan['dob'],
            'address' => $satuan['address'],
            'role' => 'user'
        ]);
    }
}
