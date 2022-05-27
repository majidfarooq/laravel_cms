<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use MercurySeries\Flashy\Flashy;

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

  public function showRegistrationForm()
  {
    if (Auth::check()) {
      return redirect()->route('user.dashboard');
    } else {
      return view('frontend.user.register');
    }
  }

  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
       'name' => ['required', 'string'],
       'email' => 'required|unique:users',
       'password' => ['required', 'string', 'min:8'],
    ]);
    if ($validator->fails()) {
      Flashy::error("Please Enter Correct Information!.");
      return redirect()->back()->withErrors($validator)->withInput();
    } else {
      $user = User::create([
         'name' => $request->name,
         'email' => $request->email,
         'password' => Hash::make($request->password),
         'newsletter' => $request->newsletter == "on" ? 1 : 0,
         'terms_conditions' => $request->terms_conditions == "on" ? 1 : 0,
      ]);
      $this->guard()->login($user);
      Flashy::success("Your Account Created Successfully.");
      return redirect()->route('user.dashboard');
    }
  }

  protected function registered(Request $request, $user)
  {
    Flashy::success("User Register Successful.");
  }

  /**
   * Get a validator for an incoming registration request.
   *
   * @param array $data
   * @return \Illuminate\Contracts\Validation\Validator
   */
  protected function validator(array $data)
  {
    return Validator::make($data, [
       'name' => ['required', 'string', 'max:255'],
       'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
       'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);
  }

  /**
   * Create a new user instance after a valid registration.
   *
   * @param array $data
   * @return \App\Models\User
   */
  protected function create(array $data)
  {
    return User::create([
       'name' => $data['name'],
       'email' => $data['email'],
       'password' => Hash::make($data['password']),
    ]);
  }
}
