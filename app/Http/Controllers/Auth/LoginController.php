<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Override login logic agar bisa pakai email atau name
     */
    protected function attemptLogin(Request $request)
    {
        $login = $request->input('login');

        // cek apakah input berupa email atau name
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        return $this->guard()->attempt(
            [$field => $login, 'password' => $request->password],
            $request->filled('remember')
        );
    }

    /**
     * Laravel akan pakai ini untuk validasi login
     */
    public function username()
    {
        return 'login'; // ambil input 'login' dari form
    }

    /**
     * Override method logout
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
