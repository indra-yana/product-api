<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

    protected $logName  = 'auth.log';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/login')->with(['type' => 'success', 'message' => 'You just logged out.']);
    }

    protected function sendFailedLoginResponse(Request $request) {
        activity()
            ->inLog($this->logName)
            ->withProperties(['ip_address' => request()->ip(), 'browser_info' => \Utils::browserInfo()])
            ->log($request->input($this->username()) ." failed to login: " .trans('auth.failed'));

        return redirect()->back()
                ->withInput()
                ->with(['type' => 'danger', 'message' => 'Login failed. Please make sure you have registered in our system.'])
                ->withErrors([$this->username() => [trans('auth.failed')]]);
    }

    public function username() {
        $login = request()->input('email');

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$field => $login]);

        return $field;
    }

    protected function credentials(Request $request) {
        return [$this->username() => $request->email, 'password' => $request->password, 'is_active' => 1];
    }

    protected function authenticated(Request $request, $user) {
        activity()
            ->inLog($this->logName)
            ->withProperties(['ip_address' => request()->ip(), 'browser_info' => \Utils::browserInfo()])
            ->log("$user->email logged in");
    }

}
