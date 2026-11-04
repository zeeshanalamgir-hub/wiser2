<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Extension;
use App\Models\UserLogin;
use App\Models\User;
use Hash;
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

    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
    }

    public function showLoginForm(Request $request)
    {
        $pageTitle = "Sign In";
        $page = "";
        if ($request->has('page')) {
            $page = $request->page;
        }
        return view(activeTemplate() . 'user.auth.login', compact('pageTitle', 'page'));
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);
        if (isset($request->captcha)) {
            if (!captchaVerify($request->captcha, $request->captcha_secret)) {
                $notify[] = ['error', "Invalid captcha"];
                return back()->withNotify($notify)->withInput();
            }
        }

        if ($request->page != "") {
            $request->session()->put('page', $request->page);
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {

            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $user = $this->guard()->user();
            $user->is_login = 1;
            $user->save();
            return $this->sendLoginResponse($request);
        }
        return $this->sendFailedLoginResponse($request);
    }

    public function findUsername()
    {
        $login = request()->input('username');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $login]);
        return $fieldType;
    }

    public function username()
    {
        return $this->username;
    }

    protected function validateLogin(Request $request)
    {
        \Log::info('Validating login with data:', $request->all());
        $customRecaptcha = Extension::where('act', 'custom-captcha')->where('status', 1)->first();
        $validation_rule = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];

        if ($customRecaptcha) {
            $validation_rule['captcha'] = 'required';
        }

        $request->validate($validation_rule);
    }

    public function logout()
    {
        $user = $this->guard()->user();
        $user->is_login = 0;
        $user->save();
        $this->guard()->logout();

        request()->session()->invalidate();
        session()->forget('converted_balance_' . auth()->id());


        $notify[] = ['success', 'You have been logged out.'];
        return redirect()->route('user.login')->withNotify($notify);
    }
    public function authenticated(Request $request, $user)
    {
        \Log::info('User authenticated successfully', ['user_id' => $user->id]);


        if ($user->status == 0) {
            $this->guard()->logout();
            $notify[] = ['error', 'Your account has been deactivated.'];
            return redirect()->route('user.login')->withNotify($notify);
        }

        $user = auth()->user();
        $user->tv = $user->ts == 1 ? 0 : 1;
        $user->save();

        $ip = $_SERVER["REMOTE_ADDR"];

        // Always create a new login record
        $info = json_decode(json_encode(getIpInfo()), true);
        $userLogin = new UserLogin();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip = $ip;
        $userLogin->longitude = $info['long'] ?? null;
        $userLogin->latitude = $info['lat'] ?? null;
        $userLogin->city = $info['city'] ?? null;
        $userLogin->country_code = $info['code'] ?? null;
        $userLogin->country = $info['country'] ?? null;
        $userLogin->timezone = $info['timezone'] ?? null;  // Ensure timezone is being saved

        $userAgent = osBrowser();
        $userLogin->browser = @$userAgent['browser'] ?? 'Unknown';
        $userLogin->os = @$userAgent['os_platform'] ?? 'Unknown';

        $userLogin->save();

        return redirect()->route('user.home');
    }
}
