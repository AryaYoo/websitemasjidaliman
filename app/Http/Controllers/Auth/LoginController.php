<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; // Correct namespace for handling HTTP requests
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function login(Request $request)
{
    $login = $request->input('email');
    $user = User::where('email', $login)->orWhere('nomor', $login)->first();

    if (!$user) {
        return redirect()->back()->withErrors(['email' => 'Invalid login credentials']);
    }

    $request->validate([
        'password' => 'required|min:8',
    ]);

    if (Auth::attempt(['email' => $user->email, 'password' => $request->password]) ||
        Auth::attempt(['nomor' => $user->nomor, 'password' => $request->password])) {
        Auth::loginUsingId($user->id);
        return redirect('/');
    } else {
        return redirect()->back()->withErrors(['password' => 'Invalid login credentials']);
    }
}
}
