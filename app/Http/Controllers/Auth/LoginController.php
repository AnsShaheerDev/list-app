<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Models\User;

use Hash;
use Auth;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    public function login()
    {
      $validated = request()->validate([
                  'email' => 'required|email',
                  'password' => 'required',
                  ]);
      $credentials = request()->only('email', 'password');
      $response['redirect_url'] = '';
      if(User::where('email',request()->email)->first()){
        
        if (Auth::attempt($credentials)) {
            request()->session()->regenerate();
            $response['redirect_url'] = $this->redirectTo;
        }
        return response()->json($response,200);
      }else{
         User::create([
            'name' => uniqid('user_'),
            'email' => request()->email,
            'password' => Hash::make(request()->password),
        ]);

        if (Auth::attempt($credentials)) {
            request()->session()->regenerate();
            $response['redirect_url'] = $this->redirectTo;
        }
        return response()->json($response,200);

      }
   }
}
