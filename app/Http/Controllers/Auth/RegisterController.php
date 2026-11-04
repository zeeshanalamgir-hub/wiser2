<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin;
use App\Models\BidsLog;
use App\Models\UserLogin;
//use App\Models\Usermembership;
use App\Models\UserCompany;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Events\UserRegistration;
use App\Models\AdminNotification;
use App\Models\Employermembership;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
//use App\Models\Membership;
use App\Http\Controllers\Controller;
use App\Models\Freelancermembership;
use Illuminate\Support\Facades\Hash;
use App\Models\Employerservicescount;
use Illuminate\Auth\Events\Registered;
use App\Models\Freelancerservicescount;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Propaganistas\LaravelPhone\PhoneNumber;
use Propaganistas\LaravelPhone\Rules\Phone;
use App\Models\Employermembershiptransactions;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\Freelancermembershiptransactions;
use App\Notifications\AdminRealTimeNotification;

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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('regStatus')->except('registrationNotAllowed');

        $this->activeTemplate = activeTemplate();
    }

    public function showSignUpForm()
    {
        $pageTitle = "Sign Up";
        return view($this->activeTemplate . 'user.auth.signup', compact('pageTitle'));
    }

    public function showRegistrationForm(Request $request)
    {
        $type = $request->query('type');

        // Validate type parameter
        if (!in_array($type, ['1', '0'])) {
            return redirect()->route('register')->with('error', 'Invalid type selected');
        }

        $pageTitle = "Sign Up";

        // Get user's location information
        $info = getIpInfo();
        // Load country data from JSON file
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        // Determine default country based on IP data
        $defaultCountryCode = $info['code'] ?? null;
        $defaultCity = $info['city'] ?? null;
        $mobile_code = null;

        // Find the mobile code based on the detected country
        foreach ($countries as $key => $country) {
            if (strcasecmp($key, $defaultCountryCode) == 0) {
                $mobile_code = $country->dial_code;
                break;
            }
        }
        return view($this->activeTemplate . 'user.auth.register', compact('pageTitle', 'countries', 'type', 'defaultCountryCode', 'mobile_code'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $general = GeneralSetting::first();
        $password_validation = Password::min(6);

        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->symbols()->numbers();
        }
        // if ($general->secure_password) {
        //     $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        // }
        $agree = 'nullable';
        if ($general->agree) {
            $agree = 'required';
        }
        $countryData = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $mobileCodes = implode(',', array_column($countryData, 'dial_code'));
        $countries = implode(',', array_column($countryData, 'country'));
        $countryCodes = implode(',', array_keys($countryData));
        $validate = Validator::make($data, [
            'firstname' => 'sometimes|required|string|max:50',
            'lastname' => 'sometimes|required|string|max:50',
            'email' => 'required|string|email|max:90|unique:users',
            'mobile' => ['required', 'string', 'unique:users', (new Phone)->countryField('country_code')],
            'password' => ['required', 'string', 'confirmed', $password_validation],
            'username' => 'required|alpha_num|unique:users|min:6',
            'captcha' => 'sometimes|required',
            'mobile_code' => 'required|in:' . $mobileCodes,
            'country_code' => 'required|in:' . $countryCodes,
            // 'country' => 'required|in:' . $countries,
            'agree' => $agree
        ]);
        return $validate;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $exist = User::where('mobile', $request->mobile_code . $request->mobile)->first();
        if ($exist) {
            $notify[] = ['error', 'The mobile number already exists'];
            return back()->withNotify($notify)->withInput();
        }

        $exist = User::where('email', $request->email)->first();
        if ($exist) {
            $notify[] = ['error', 'The email address already exists'];
            return back()->withNotify($notify)->withInput();
        }

        if (isset($request->captcha)) {
            if (!captchaVerify($request->captcha, $request->captcha_secret)) {
                $notify[] = ['error', "Invalid captcha"];
                return back()->withNotify($notify)->withInput();
            }
        }
        if ($request->ftype == "") {
            $notify[] = ['error', 'Please Select Registration type.'];
            return back()->withNotify($notify)->withInput();
        }

        $data = $request->all();
        $data['type'] = $request->ftype;
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')), true);
        $countryCode = $data['country_code'];
        $data['country'] = $countries[$countryCode]['country'];
        $formattedNumber = (string) new PhoneNumber($request->mobile, $request->country_code);
        $data['mobile'] = $formattedNumber;

        $user = $this->create($data);
        //event(new Registered($user));
        //event(new UserRegistration('New User has registered', '146'));

        $this->guard()->login($user);
        $user->ev_code = verificationCode(6);
        $user->save();

        // sendGeneralEmail('EVER_CODE', $user);

        return $this->registered($request, $user) ?: redirect($this->redirectPath());
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */

    protected function create(array $data)
    {
        $general = GeneralSetting::first();
        // $path = imagePath()['user_document']['path'];
        //User Create


        $user = new User();
        $user->firstname = isset($data['firstname']) ? $data['firstname'] : null;
        $user->lastname = isset($data['lastname']) ? $data['lastname'] : null;
        $user->fullname =  @$data['firstname'] . ' ' . @$data['lastname'];
        $user->name =  @$data['firstname'] . ' ' . @$data['lastname'];
        $user->email = strtolower(trim($data['email']));
        $user->password = Hash::make($data['password']);
        $user->username = trim($data['username']);
        // $user->country_code = $data['country_code'];
        $user->country_code = $data['country'];
        $user->mobile = $data['mobile'];
        $user->address = [
            'address' => '',
            'state' => '',
            'zip' => '',
            'country' => isset($data['country']) ? $data['country'] : null,
            'city' => isset($data['city']) ? $data['city'] : null,
        ];
        $user->status = 3;
        $user->ev = $general->ev;
        $user->ev_code = rand(0, 99999);
        $user->sv = $general->sv ? 0 : 1;
        $user->ts = 0;
        $user->tv = 1;

        //     if ($data['file']) {
        //     $file = $data['file'];
        //     try {
        //         $filename = uploadImage($file, $path);
        //     } catch (\Exception $exp) {
        //         $notify[] = ['error', 'Image could not be uploaded.'];
        //         return back()->withNotify($notify);
        //     }
        //     $user->file = $filename;
        // }

        if ($data['type'] == 0) {
            $user->is_freelancer = 1;
            $membership = Freelancermembership::where('default', 1)->where('freelancer_payment_plan_id',1)->get();
            $membership_id = $membership[0]->id;
            $usermembership = new Freelancermembershiptransactions();
        }

        // if ($data['type'] == 2) {
        //     $user->is_freelancer = 1;
        //     $freelancermembershipdata = Freelancermembership::where('default', 1)->get();
        //     $membership_id = $freelancermembershipdata[0]->id;
        //     $freelancermembership = new Freelancermembershiptransactions();
        // }
        // if ($data['type'] == 2) {
        //     $user->is_employer = 1;
        //     $membership = Employermembership::where('default', 1)->get();
        //     $membership_id = $membership[0]->id;
        //     $usermembership = new Employermembershiptransactions();
        // }

        if ($data['type'] == 1) {
            $user->is_employer = 1;
            $membership = Employermembership::where('default', 1)->get();
            $membership_id = $membership[0]->id;
            $usermembership = new Employermembershiptransactions();
        }
        $user->save();

        if ($data['type'] == 1) {
            $user_company = new UserCompany();
            $user_company->user_id = $user->id;
            $user_company->contact = $data['mobile'];
            $user_company->country = $data['country'];
            $user_company->save();
        }
        // if ($data['type'] == 2) {
        //     $userid = $user->id;

        //     $expiry = config('app.membership_expiry');
        //     $end_at = date('Y-m-d', strtotime("+" . $expiry . " days"));

        //     $freelancermembership->user_id = $userid;
        //     $freelancermembership->membership_id = $membership_id;
        //     $freelancermembership->start_at = date('Y-m-d');
        //     $freelancermembership->end_at = $end_at;
        //     $freelancermembership->status = 1;
        //     $freelancermembership->save();
        //     $addservices = new Freelancerservicescount();
        //     $addservices->user_id = $userid;
        //     $addservices->bids = $freelancermembershipdata[0]->bids;
        //     $addservices->normal_services = $freelancermembershipdata[0]->normal_services;
        //     $addservices->priority_services = $freelancermembershipdata[0]->priority_services;
        //     $addservices->featured_services = $freelancermembershipdata[0]->featured_services;
        //     $addservices->save();

        //     $transaction_arr[] = [
        //         'user_id' => $userid,
        //         'bids' => $freelancermembershipdata[0]->bids,
        //         'tr_op' => '+',
        //         'post_balance' => $freelancermembershipdata[0]->bids,
        //         'details' => 'Default',

        //     ];
        //     BidsLog::insert($transaction_arr);
        // }
        $userid = $user->id;

        $expiry = config('app.membership_expiry');
        $end_at = $expiry;

        $usermembership->user_id = $userid;
        $usermembership->membership_id = $membership_id;
        $usermembership->start_at = date('Y-m-d');
        $usermembership->end_at = $end_at;
        $usermembership->status = 1;
        $usermembership->save();
        if ($data['type'] == 0) {
            $addservices = new Freelancerservicescount();
            $addservices->user_id = $userid;
            $addservices->bids = $membership[0]->bids;
            $addservices->normal_services = $membership[0]->normal_services;
            $addservices->priority_services = $membership[0]->priority_services;
            $addservices->featured_services = $membership[0]->featured_services;
            $addservices->save();

            $transaction_arr[] = [
                'user_id' => $userid,
                'bids' => $membership[0]->bids,
                'tr_op' => '+',
                'post_balance' => $membership[0]->bids,
                'details' => 'Default',

            ];
            BidsLog::insert($transaction_arr);
        }
        if ($data['type'] == 1) {
            $addservices = new Employerservicescount();
            $addservices->user_id = $userid;
            $addservices->normal_jobs = $membership[0]->normal_jobs;
            $addservices->priority_jobs = $membership[0]->priority_jobs;
            $addservices->featured_jobs = $membership[0]->featured_jobs;
            $addservices->invites = $membership[0]->invites;
            $addservices->save();

            $transaction_arr[] = [
                'user_id' => $userid,
                'bids' => $membership[0]->normal_jobs + $membership[0]->priority_jobs + $membership[0]->featured_jobs,
                'tr_op' => '+',
                'post_balance' => $membership[0]->normal_jobs + $membership[0]->priority_jobs + $membership[0]->featured_jobs,
                'details' => 'Default',

            ];
            BidsLog::insert($transaction_arr);
        }

        // if ($data['type'] == 2) {
        //     $addservices = new Employerservicescount();
        //     $addservices->user_id = $userid;
        //     $addservices->normal_jobs = $membership[0]->normal_jobs;
        //     $addservices->priority_jobs = $membership[0]->priority_jobs;
        //     $addservices->featured_jobs = $membership[0]->featured_jobs;
        //     $addservices->save();

        //     $transaction_arr[] = [
        //         'user_id' => $userid,
        //         'bids' => $membership[0]->normal_jobs + $membership[0]->priority_jobs + $membership[0]->featured_jobs,
        //         'tr_op' => '+',
        //         'post_balance' => $membership[0]->normal_jobs + $membership[0]->priority_jobs + $membership[0]->featured_jobs,
        //         'details' => 'Default',

        //     ];
        //     BidsLog::insert($transaction_arr);
        // }


        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'New member registered';
        $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
        $adminNotification->save();

        // $admins = Admin::all();
        // foreach ($admins as $admin) {
        //     $admin->notify(new AdminRealTimeNotification(
        //         message: 'New member registered.',
        //         senderId: $user->id,
        //         clickUrl: urlPath('admin.users.detail', $user->id)
        //     ));
        // }
        // sendJobEmailWithNotification($user->id, $freelancer->id, $job->id, 'New member registered');


        //Login Log Create
        $ip = $_SERVER["REMOTE_ADDR"];
        $exist = UserLogin::where('user_ip', $ip)->first();
        $userLogin = new UserLogin();
        //Check exist or not
        if ($exist) {
            $userLogin->longitude =  $exist->longitude;
            $userLogin->latitude =  $exist->latitude;
            $userLogin->city =  $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country =  $exist->country;
        } else {
            $info = json_decode(json_encode(getIpInfo()), true);
            // $userLogin->longitude =  @implode(',', $info['long']);
            // $userLogin->latitude =  @implode(',', $info['lat']);
            // $userLogin->city =  @implode(',', $info['city']);
            // $userLogin->country_code = @implode(',', $info['code']);
            // $userLogin->country =  @implode(',', $info['country']);

            $userLogin->longitude = array_key_exists('long',$info) ? $info['long'] : null;
            $userLogin->latitude =  array_key_exists('lat',$info) ? $info['lat'] : null;
            $userLogin->city =  array_key_exists('city',$info) ? $info['city'] : null;
            $userLogin->country_code = array_key_exists('code',$info) ? $info['code'] : null;
            $userLogin->country =  array_key_exists('country',$info) ? $info['country'] : null;
        }

        $userAgent = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip =  $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os = @$userAgent['os_platform'];
        $userLogin->save();
        return $user;
    }

    public function checkUser(Request $request)
    {
        $exist['data'] = null;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data'] = User::where('email', $request->email)->first();
            $exist['type'] = 'email';
        }
        if ($request->mobile) {
            $exist['data'] = User::where('mobile', $request->mobile)->first();
            $exist['type'] = 'mobile';
        }
        if ($request->username) {
            $exist['data'] = User::where('username', $request->username)->first();
            $exist['type'] = 'username';
        }
        return response($exist);
    }

    public function registered()
    {
        return redirect()->route('user.home');
    }
}
