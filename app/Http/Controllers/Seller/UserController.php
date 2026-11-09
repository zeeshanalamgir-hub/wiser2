<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Lib\GoogleAuthenticator;
use App\Models\Freelancermembershiptransactions;
use App\Models\BidsLog;
use App\Models\GeneralSetting;
use App\Models\Transaction;
use App\Models\WithdrawMethod;
use App\Models\Withdrawal;
use App\Models\SubCategory;
use App\Models\ChildCategories;
use App\Models\FavoriteItem;
use App\Models\Service;
use App\Models\Software;
use App\Models\Booking;
use App\Models\Userbiding;
use App\Models\Freelancerservicescount;
use App\Models\SkillCategory;
use App\Models\Skill;
use App\Models\UserLanguage;
use App\Models\UserEducation;
use App\Models\UserProfileAlerts;
use App\Models\UserCertification;
use App\Models\User;
use App\Models\UserProfileNewSkill;
use App\Models\UserProfileSkill;
use App\Rules\FileTypeValidate;
use App\Services\Membership;
use App\Services\MembershipService;
use App\Services\UserBidService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\StripeResponse;
use App\Models\Jobdisputebid;
use App\Models\Servicedisputedata;
use App\Http\Controllers\Gateway\Stripe\ProcessController;
use App\Models\Category;
use App\Models\Job;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct(private MembershipService $membershipService, private UserBidService $userBidService)
    {
        $this->activeTemplate = activeTemplate();
    }

    public function seller_dispute(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $pageTitle = "Dispute";
        $currentUserId = auth()->id();

        // $jobDisputes = Jobdisputebid::with(['job', 'booking'])
        //     ->where('seller_id', $currentUserId)
        //     ->where('status', '!=', 7)
        //     ->get()

        // 1. Job Disputes using Eloquent
        // $jobDisputes = Jobdisputebid::where('seller_id', $currentUserId)
        //     ->join('jobs', 'job_dispute_bid.job_id', '=', 'jobs.id')
        //     ->get()
        //     ->map(function ($dispute) {
        //         dd($dispute->job);
        //         $dispute->dispute_type = 'job';
        //         $dispute->booking_order = $dispute->booking->order_number ?? null;
        //         $dispute->booking_status = $dispute->booking->status ?? null;
        //         $dispute->booking_date = $dispute->booking->created_at ?? null;
        //         $dispute->job_title = $dispute->job->title ?? null;
        //         return $dispute;
        //     });
        //     dd($jobDisputes);

        $jobDisputes = DB::table('job_dispute_bid')
            ->joinSub(
                DB::table('bookings')
                    ->select('job_id', 'order_number', 'status', 'created_at')
                    ->groupBy('job_id')
                    ->orderBy('created_at', 'desc'),
                'latest_bookings',
                'job_dispute_bid.job_id',
                '=',
                'latest_bookings.job_id'
            )
            ->join('jobs', 'job_dispute_bid.job_id', '=', 'jobs.id')
            ->where('job_dispute_bid.seller_id', '=', $currentUserId)
            ->select(
                'job_dispute_bid.*',
                'latest_bookings.order_number as booking_order',
                'latest_bookings.status as booking_status',
                'latest_bookings.created_at as booking_date',
                'jobs.title as job_title',
                DB::raw("'job' as dispute_type")
            )
            ->get();

        // 2. Service Disputes using Eloquent
        $serviceDisputes = Servicedisputedata::with(['service', 'booking'])
            ->where('seller_id', $currentUserId)
            ->get()
            ->map(function ($dispute) {
                $dispute->dispute_type = 'service';
                $dispute->booking_order = $dispute->booking->order_number ?? null;
                $dispute->booking_status = $dispute->booking->status ?? null;
                $dispute->booking_date = $dispute->booking->created_at ?? null;
                $dispute->job_title = $dispute->service->title ?? null;
                return $dispute;
            });

        // 3. Merge & Sort by booking_date desc
        $disputes = $jobDisputes->merge($serviceDisputes)->sortByDesc('booking_date');

         $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => 'Disputes'],
        ];

        return view($this->activeTemplate . 'user.seller.seller_dispute', compact('pageTitle', 'disputes','breadcrumbs'));
    }


    public function home(Request $request)
    {
        if ($request->type == 'Jobs') {
            return $this->searchJob($request);
        } else if ($request->type == 'Professionals') {
            return $this->searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return $this->searchService($request);
        }

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('user.authorization');
        }
        if ($user->ev == 0) {
            return response()->json(['error' => 'Email not verified'], 403);
        }

        if ($request->session()->get('page')) {
            $page = $request->session()->get('page');
            $request->session()->forget('page');
            return redirect(url('/' . $page));
        }

        if ($user->is_freelancer == 0) {
            return redirect(url('/user/buyer/dashboard'));
        }

        $pageTitle = 'Dashboard';
        $emptyMessage = "No data found";
        $transactions = Transaction::where('user_id', $user->id)->orderBy('id', 'DESC')->limit(5)->get();
        $totalService = Service::where('user_id', $user->id)->count();
        $totalSoftware = Software::where('user_id', $user->id)->count();
        $totalServiceBooking = Booking::whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', '!=', '0')->whereNotNull('service_id')->count();
        $totalSoftwareBooking = Booking::whereHas('software', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', '!=', '0')->whereNotNull('software_id')->count();
        $withdrawAmount = Withdrawal::where('user_id', Auth::id())->where('status', '!=', 0)->sum('amount');
        $userservices = Freelancerservicescount::where('user_id', $user->id)->get();
        $proposal = Userbiding::where('user_id', $user->id)->count();
        $currentjobs = Userbiding::where('user_id', $user->id)->where('status', 1)->count();
        $completedjobs = Userbiding::where('user_id', $user->id)->where('status', 2)->count();

        $is_membership_expired = (new Membership())->isMembershipExpired($user);
        $expiry_date = (new Membership())->membershipExpireDate($user);
        $membership_id = Freelancermembershiptransactions::where("user_id", $user->id)->value('membership_id');
        $is_free_membership = (new Membership())->isFreeMembership($user, $membership_id);
        $show_alert = !$is_free_membership && $expiry_date <= now()->addDays(2);

        // Profile Completeness start
        $profile = User::where('id', auth()->user()->id)->with(['languages', 'educations', 'certifications', 'portfolioprofile', 'profileSkills', 'userProfileAlerts'])->first();
        $profile_completenes = 0;
        if (count($profile->languages) > 0) {
            $profile_completenes += 20;
        }

        if (count($profile->educations) > 0) {
            $profile_completenes += 20;
        }
        if (count($profile->certifications) > 0) {
            $profile_completenes += 20;
        }

        if (count($profile->portfolioprofile) > 0) {
            $profile_completenes += 20;
        }

        if (count($profile->profileSkills) > 0) {
            $profile_completenes += 10;
        }
        if (count($profile->userProfileAlerts) > 0) {
            $profile_completenes += 10;
        }
        // Profile Completeness ends

        $sumOfBids = BidsLog::where('user_id', $user->id)->sum('bids');
        $availableBids = $this->userBidService->getAvailableBids($user->id);
        $normalService = Freelancerservicescount::where('user_id', $user->id)->sum('normal_services');
        $portfolioSize = Freelancerservicescount::where('user_id', $user->id)->sum('portfolio_size');
        $featuredService = Freelancerservicescount::where('user_id', $user->id)->sum('featured_services');
        $proposalHistory = Userbiding::where('user_id', $user->id)->count();
        $completedjobs = Userbiding::where('user_id', $user->id)->where('status', 2)->count();
        $withdraw = intval(Withdrawal::where('user_id', $user->id)->sum('amount'));

        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')), true);

        $balance = intval($user->balance);
        $status = $user->status;
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => 'Dashboard'],
        ];
        $data = compact(
            'breadcrumbs',
            'completedjobs',
            'proposalHistory',
            'withdraw',
            'balance',
            'featuredService',
            'portfolioSize',
            'normalService',
            'sumOfBids',
            'availableBids',
            'pageTitle',
            'transactions',
            'emptyMessage',
            'withdrawAmount',
            'totalService',
            'totalSoftware',
            'totalServiceBooking',
            'totalSoftwareBooking',
            'userservices',
            'proposal',
            'currentjobs',
            'profile_completenes',
            'status',
            'countries',
            'is_membership_expired',
            'is_free_membership',
            'expiry_date',
            'show_alert',
        );
        // Check if the request is an AJAX call
        if ($request->ajax()) {
            return view($this->activeTemplate . 'user.seller.ajax_dashboard', $data);
        }

        return view($this->activeTemplate . 'user.seller.dashboard', $data);
    }


    public function home1(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $user = Auth::user();
        $next_step = $user?->completed_step + 1 > 8 ? 8 : $user?->completed_step + 1;

        if (!session()->has('activeStep')) {
            session()->flash('activeStep', $next_step ?? 1);
        }
        $userId = $user->id;
        $pageTitle = 'Seller Profile';
        $emptyMessage = "No data found";
        $categorys = DB::table('categories')->where('type', 'job')->get();
        $countries = (array) json_decode(file_get_contents(resource_path('views/partials/country.json')));

        $noti = DB::table('email_sms_templates')
            ->leftJoin('user_profile_alerts', function ($join) use ($userId) {
                $join->on('email_sms_templates.id', '=', 'user_profile_alerts.email_sms_template_id')
                    ->where('user_profile_alerts.user_id', '=', $userId);
            })
            ->select(
                'email_sms_templates.id',
                'email_sms_templates.name',
                'email_sms_templates.email_status',
                'user_profile_alerts.status as user_status' // Fetch the status from user_profile_alerts
            )
            ->where('email_sms_templates.email_status', 1)
            ->whereIn('email_sms_templates.notification_for', [2, 3])
            ->get();

        $selectedSkills = $user->profileSkills->map(function ($profileSkill) {
            return [
                'id' => $profileSkill->skill->id,
                'name' => $profileSkill->skill->name,
                'category_id' => $profileSkill->skill->category_id,
            ];
        });
        return view($this->activeTemplate . 'user.seller.profile_new', compact('pageTitle', 'emptyMessage', 'user', 'categorys', 'countries', 'selectedSkills', 'noti'));
    }

    public function acceptTerms(Request $request)
    {
        // Store acceptance in the database or session
        $user = auth()->user();
        $user->terms_accepted = true; // Assuming there's a 'terms_accepted' column in the users table
        $user->save();

        return response()->json(['message' => 'Terms accepted successfully.']);
    }
    public function home2(Request $request)
    {
        //echo "let me in";exit;
        $user = Auth::user();
        $next_step = $user?->completed_step + 1 > 8 ? 8 : $user?->completed_step + 1;
        session()->flash('activeStep', $next_step ?? 1);
        $ul = UserLanguage::where('user_id', Auth::user()->id)->get();
        $educations = UserEducation::where('user_id', Auth::user()->id)->get();
        $certificates = UserCertification::where('user_id', Auth::user()->id)->get();
        $noti = UserProfileAlerts::where('user_id', Auth::user()->id)->first();
        $pageTitle = 'Dashboard';
        $emptyMessage = "No data found";
        // $categoryss=DB::table('categories')->where('type','service')->get();
        $categoryss = DB::table('categories')->where('type', 'job')->get();

        $skills = UserProfileSkill::join('skills', 'skills.id', '=', 'user_profile_skills.skill_id')->where('user_profile_skills.user_id', $user->id)->select('user_profile_skills.id', 'skills.name')->get();
        $newskills = UserProfileNewSkill::where('user_id', $user->id)->get();

        return view($this->activeTemplate . 'user.seller.profile', compact('pageTitle', 'emptyMessage', 'user', 'categoryss', 'ul', 'educations', 'certificates', 'noti', 'newskills', 'skills'));
    }

    public function profile()
    {
        $pageTitle = "Profile Setting";
        $user = Auth::user();
        return view($this->activeTemplate . 'user.seller.profile_setting', compact('pageTitle', 'user'));
    }

    public function submitProfile(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'address' => 'sometimes|required|max:80',
            'designation' => 'required|max:255',
            'state' => 'sometimes|required|max:80',
            'zip' => 'sometimes|required|max:40',
            'city' => 'sometimes|required|max:50',
            'image' => ['image', new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ], [
            'firstname.required' => 'First name field is required',
            'lastname.required' => 'Last name field is required'
        ]);
        $user = Auth::user();
        $in['firstname'] = $request->firstname;
        $in['lastname'] = $request->lastname;
        $in['designation'] = $request->designation;
        $in['about_me'] = $request->about_me;
        $in['address'] = [
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => @$user->address->country,
            'city' => $request->city,
        ];
        if ($request->hasFile('image')) {
            $location = imagePath()['profile']['user']['path'];
            $size = imagePath()['profile']['user']['size'];
            $filename = uploadImage($request->image, $location, $size, $user->image);
            $in['image'] = $filename;
        }
        if ($request->hasFile('bg_image')) {
            $location = imagePath()['profile']['user_bg']['path'];
            $size = imagePath()['profile']['user_bg']['size'];
            $filename = uploadImage($request->bg_image, $location, $size, $user->bg_image);
            $in['bg_image'] = $filename;
        }
        $user->fill($in)->save();
        $notify[] = ['success', 'Profile updated successfully.'];
        return back()->withNotify($notify);
    }

    public function changePassword(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $pageTitle = 'Change password';
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => 'Change Password' ],
        ];
        return view($this->activeTemplate . 'user.seller.password', compact('pageTitle','breadcrumbs'));
    }

    public function submitPassword(Request $request)
    {

        $password_validation = Password::min(6);
        $general = GeneralSetting::first();
        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $this->validate($request, [
            'current_password' => 'required',
            'password' => ['required', 'confirmed', $password_validation]
        ]);


        try {
            $user = auth()->user();
            if (Hash::check($request->current_password, $user->password)) {
                $password = Hash::make($request->password);
                $user->password = $password;
                $user->save();
                $notify[] = ['success', 'Password changes successfully.'];
                return back()->withNotify($notify);
            } else {
                $notify[] = ['error', 'The password doesn\'t match!'];
                return back()->withNotify($notify);
            }
        } catch (\PDOException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    /*
     * Withdraw Operation
     */

    public function withdrawMoney(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $withdrawMethod = WithdrawMethod::where('status', 1)->get();
        $pageTitle = 'Withdraw Money';
        $emptyMessage = "No data found";
        $onBoarding = StripeResponse::where([
            ['user_id',auth()->user()->id],
            ['type','onBoarding']
        ])
        ->first();

        $account = StripeResponse::where([
            ['user_id',auth()->user()->id],
            ['type','create_account']
        ])
        ->first();
        $isOnboardingComplete = false;
        if($account && !empty($account))
        {
            $process_controller = new ProcessController();
            $isOnboardingComplete = $process_controller->checkOnboardingStatus($account?->response['id']);
        }
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => 'Withdraw Money' ],
        ];
        return view($this->activeTemplate . 'user.withdraw.methods', compact('pageTitle','onBoarding','breadcrumbs', 'withdrawMethod', 'emptyMessage','isOnboardingComplete'));
    }

    public function withdrawStore(Request $request)
    {
        $this->validate($request, [
            'method_code' => 'required',
            'amount' => 'required|numeric'
        ]);
        $method = WithdrawMethod::where('id', $request->method_code)->where('status', 1)->firstOrFail();
        $user = auth()->user();
        // if ($request->amount < $method->min_limit) {
        //     $notify[] = ['error', 'Your requested amount is smaller than minimum amount.'];
        //     return back()->withNotify($notify);
        // }
        // if ($request->amount > $method->max_limit) {
        //     $notify[] = ['error', 'Your requested amount is larger than maximum amount.'];
        //     return back()->withNotify($notify);
        // }

        if ($request->amount > $user->balance) {
            $notify[] = ['error', 'You do not have sufficient balance for withdraw.'];
            return back()->withNotify($notify);
        }

        $charge = $method->fixed_charge + ($request->amount * $method->percent_charge / 100);
        $afterCharge = $request->amount - $charge;
        $finalAmount = $afterCharge * $method->rate;

        $withdraw = new Withdrawal();
        $withdraw->method_id = $method->id; // wallet method ID
        $withdraw->user_id = $user->id;
        $withdraw->amount = $request->amount;
        $withdraw->currency = $method->currency;
        $withdraw->rate = $method->rate;
        $withdraw->charge = $charge;
        $withdraw->final_amount = $finalAmount;
        $withdraw->after_charge = $afterCharge;
        $withdraw->trx = getTrx();
        $withdraw->save();
        session()->put('wtrx', $withdraw->trx);
        return redirect()->route('user.withdraw.preview');
    }

    public function withdrawPreview(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $data = Withdrawal::with('method', 'user')->where('trx', session()->get('wtrx'))->where('status', 0)->orderBy('id', 'desc')->first();
        $pageTitle = 'Withdraw Preview';
        return view($this->activeTemplate . 'user.withdraw.preview', compact('pageTitle', 'data'));
    }


    public function withdrawSubmit(Request $request)
    {
        $general = GeneralSetting::first();
        $withdraw = Withdrawal::with('method', 'user')->where('trx', session()->get('wtrx'))->where('status', 0)->orderBy('id', 'desc')->firstOrFail();
        $stripe_response = StripeResponse::where([
            ['type','create_account'],
            ['user_id',Auth::user()->id]
        ])
        ->first();
        if(!is_null($stripe_response))
        {
            // $charge_response = (new ProcessController())->createCharge(number_format($withdraw?->charge,0));
            $transfer_response = (new ProcessController())->transfer($stripe_response->response['id'],number_format($withdraw?->final_amount,0));
            $payout_response = (new ProcessController())->payout($stripe_response->response['id'],number_format($withdraw?->final_amount,0));
            // Log::info("Withdraw Submit: Charge Response => " . $charge_response);
        }
        else
        {
            $notify[] = ['error', 'Please connect your account before withdrawing the money.'];
            return to_route('user.withdraw')->withNotify($notify);
        }

        $rules = [];
        $inputField = [];
        if ($withdraw->method->user_data != null) {
            foreach ($withdraw->method->user_data as $key => $cus) {
                $rules[$key] = [$cus->validation];
                if ($cus->type == 'file') {
                    array_push($rules[$key], 'image');
                    array_push($rules[$key], new FileTypeValidate(['jpg', 'jpeg', 'png']));
                    array_push($rules[$key], 'max:2048');
                }
                if ($cus->type == 'text') {
                    array_push($rules[$key], 'max:191');
                }
                if ($cus->type == 'textarea') {
                    array_push($rules[$key], 'max:300');
                }
                $inputField[] = $key;
            }
        }

        $this->validate($request, $rules);

        $user = auth()->user();
        if ($user->ts) {
            $response = verifyG2fa($user, $request->authenticator_code);
            if (!$response) {
                $notify[] = ['error', 'Wrong verification code'];
                return back()->withNotify($notify);
            }
        }


        if ($withdraw->amount > $user->balance) {
            $notify[] = ['error', 'Your request amount is larger then your current balance.'];
            return back()->withNotify($notify);
        }

        $directory = date("Y") . "/" . date("m") . "/" . date("d");
        $path = imagePath()['verify']['withdraw']['path'] . '/' . $directory;
        $collection = collect($request);
        $reqField = [];
        if ($withdraw->method->user_data != null) {
            foreach ($collection as $k => $v) {
                foreach ($withdraw->method->user_data as $inKey => $inVal) {
                    if ($k != $inKey) {
                        continue;
                    } else {
                        if ($inVal->type == 'file') {
                            if ($request->hasFile($inKey)) {
                                try {
                                    $reqField[$inKey] = [
                                        'field_name' => $directory . '/' . uploadImage($request[$inKey], $path),
                                        'type' => $inVal->type,
                                    ];
                                } catch (\Exception $exp) {
                                    $notify[] = ['error', 'Could not upload your ' . $request[$inKey]];
                                    return back()->withNotify($notify)->withInput();
                                }
                            }
                        } else {
                            $reqField[$inKey] = $v;
                            $reqField[$inKey] = [
                                'field_name' => $v,
                                'type' => $inVal->type,
                            ];
                        }
                    }
                }
            }
            $withdraw['withdraw_information'] = $reqField;
        } else {
            $withdraw['withdraw_information'] = null;
        }

        $withdraw->status = 2;
        $withdraw->save();
        $user->balance -= $withdraw->amount;
        $user->save();

        $user = Auth::user();
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->to_user = $withdraw->user_id;
        $transaction->amount = $withdraw->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge = $withdraw->charge;
        $transaction->trx_type = '-';
        $transaction->details = ' Withdraw amount ' . getAmount($withdraw->amount) ;
        $transaction->trx = $withdraw->trx;
        $transaction->save();

        $ctransaction = new Transaction();
        $ctransaction->user_id = $user->id;
        $ctransaction->to_user = $withdraw->user_id;
        $ctransaction->amount = $withdraw->charge;
        $ctransaction->post_balance = $user->balance;
        $ctransaction->trx_type = '-';
        $ctransaction->details =  ' Fee deducted '. getAmount($withdraw->charge);
        $ctransaction->trx = $withdraw->trx;
        $ctransaction->save();

        sendJobAdminRealTimeNotification($user, 'WITHDRAW_REQUEST', '#');


        // notify($user, 'WITHDRAW_REQUEST', [
        //     'method_name' => $withdraw->method->name,
        //     'method_currency' => $withdraw->currency,
        //     'method_amount' => getAmount($withdraw->final_amount),
        //     'amount' => getAmount($withdraw->amount),
        //     'charge' => getAmount($withdraw->charge),
        //     'currency' => $general->cur_text,
        //     'rate' => getAmount($withdraw->rate),
        //     'trx' => $withdraw->trx,
        //     'post_balance' => getAmount($user->balance),
        //     'delay' => $withdraw->method->delay
        // ]);

        $notify[] = ['success', 'Amount Withdraw Successfully'];
        return to_route('user.withdraw.history')->withNotify($notify);
    }

    public function withdrawLog(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $pageTitle = "Withdraw Log";
        $withdraws = Withdrawal::where('user_id', Auth::id())->where('status', '!=', 0)->with('method')->orderBy('id', 'desc')->paginate(getPaginate());
        $emptyMessage = "No data found";
        $data['emptyMessage'] = "No Data Found!";
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => 'Withdraw Log' ],
        ];
        return view($this->activeTemplate . 'user.withdraw.log', compact('pageTitle', 'withdraws', 'emptyMessage'));
    }


    public function show2faForm()
    {
        $general = GeneralSetting::first();
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $general->sitename, $secret);
        $pageTitle = 'Two Factor';
        return view($this->activeTemplate . 'user.seller.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = 1;
            $user->save();
            $userAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($user, '2FA_ENABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$userAgent['ip'],
                'time' => @$userAgent['time']
            ]);
            $notify[] = ['success', 'Google authenticator enabled successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }


    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = 0;
            $user->save();
            $userAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($user, '2FA_DISABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$userAgent['ip'],
                'time' => @$userAgent['time']
            ]);
            $notify[] = ['success', 'Two factor authenticator disable successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }


    public function category(Request $request)
    {
        $sub_category = SubCategory::where('category_id', $request->category)->get();
        if ($sub_category->isEmpty()) {
            return response()->json(['error' => "Sub category not available under this category"]);
        } else {
            return response()->json($sub_category);
        }
    }
    public function subcategory(Request $request)
    {
        $child_category = ChildCategories::where('subcat_id', $request->subcategory)->get();
        if ($child_category->isEmpty()) {
            return response()->json(['error' => "Child category not available under this category"]);
        } else {
            return response()->json($child_category);
        }
    }

    public function skillcategory(Request $request)
    {
        $skill_category = SkillCategory::where('sub_category_id', $request->subcategory)->get()->pluck('id', 'name')->toArray();
        if (count($skill_category) == 0) {
            return response()->json(['error' => "Sub category not available under this category"]);
        } else {
            // $data = [];
            // foreach($skill_category as $key => $s)
            // {
            //     $skill = Skill::where('skill_category_id',$s)->get();
            //         if(count($skill) > 0)
            //         {
            //             foreach($skill_category as $key => $s)
            //             {
            //             $data[$s] = ["id" => $s->id,"name" => $skill->name];
            //             }
            //         }
            // }
            //return $skill_category;
            return response()->json($skill_category);
        }
    }
    public function getskills(Request $request)
    {
        $skill = Skill::where('skill_category_id', $request->c_id)->get()->pluck('id', 'name', 'skill_category_id')->toArray();
        $skills = Skill::where('skill_category_id', $request->c_id)->get()->pluck('category_id')->toArray();
        if (count($skill) == 0) {
            return response()->json(['error' => "0"]);
        } else {
            $a = 'aaa';
            return response()->json($skill);
        }
    }
    public function getskillsbyname(Request $request)
    {
        $skill = Skill::where(Skill::raw('lower(name)'), 'LIKE', $request->search . '%')->get()->pluck('id', 'name')->toArray();
        if (count($skill) == 0) {
            return response()->json(['error' => "0"]);
        } else {

            return response()->json($skill);
        }
    }

    public function serviceFavorite(Request $request)
    {
        echo "$request->id";
        exit;
        $user = Auth::user();
        $service = Service::find($request->id);
        if (!$service) {
            return response()->json(['error' => 'Invalid service']);
        }
        if ($service->user_id == $user->id) {
            return response()->json(['error' => 'You can not be added your self service']);
        }
        $favorite = FavoriteItem::where('service_id', $service->id)->where('user_id', $user->id)->first();
        if ($favorite) {
            $service->favorite -= 1;
            $service->save();
            $favorite->delete();
            return response()->json(['success' => 'Service remove to your favorites item']);
        } else {
            $favoriteItem = new FavoriteItem();
            $favoriteItem->user_id = $user->id;
            $favoriteItem->service_id = $service->id;
            $favoriteItem->save();

            $service->favorite += 1;
            $service->save();
            return response()->json(['success' => 'Service added to your favorites item']);
        }
    }

    public function softwareFavorite(Request $request)
    {
        $user = Auth::user();
        $software = Software::find($request->id);
        if (!$software) {
            return response()->json(['error' => 'Invalid software']);
        }
        if ($software->user_id == $user->id) {
            return response()->json(['error' => 'You can not be added your self software']);
        }
        $favorite = FavoriteItem::where('software_id', $software->id)->where('user_id', $user->id)->first();
        if ($favorite) {
            $software->favorite -= 1;
            $software->save();
            $favorite->delete();
            return response()->json(['success' => 'Software remove to your favorites item']);
        } else {
            $favoriteItem = new FavoriteItem();
            $favoriteItem->user_id = $user->id;
            $favoriteItem->software_id = $software->id;
            $favoriteItem->save();

            $software->favorite += 1;
            $software->save();
            return response()->json(['success' => 'Software added to your favorites item']);
        }
    }

    private function searchJob(Request $request)
    {
        $job_title = $request->input('search', null);
        $location = $request->input('location', null);
        $sort = $request->input('sort', 'Date'); // JobTitle,JobPosted
        $pageTitle = "All Jobs";
        // Fetch categories related to jobs using the Category model
        $categories = Category::where('type', 'job')->get();

        // Fetch job data with the related user using Eloquent
        // Assuming each job has a 'user' relationship for the job poster
        $jobs = Job::where('status', 1)
            ->when(!is_null($job_title) && !empty($job_title), function ($query) use ($job_title) {
                $query->where('title', 'LIKE', "%{$job_title}%");
            })
            ->when(!is_null($location) && !empty($location), function ($query) use ($location) {
                $query->where('country', 'LIKE', "%{$location}%");
            })
            ->when(!is_null($sort) && !empty($sort), function ($query) use ($sort) {
                if ($sort == 'JobTitle') {
                    $query->orderBy('title', 'asc');
                } else if ($sort == 'Date') {
                    $query->orderBy('created_at', 'desc');
                } else if ($sort == 'JobPosted') {
                    $query->orderBy('created_at', 'desc');
                }
            })
            ->with('user')
            ->withCount('userBiding')
            ->orderBy('id', 'desc') // Eager load the user related to the job
            ->paginate(10)
            ->withQueryString(); // Adjust pagination as needed
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')), true);
        return redirect()->route('home', [
            'type'   => 'jobs',
            'search' => $request->search ?? $request->name,
        ]);
    }

    public function searchProfessionals(Request $request)
    {
        $search = $request->search ?? $request->name;

        $pageTitle = "All professionals";
        $emptyMessage = "No data found";
        // $frelancer_memberships = Freelancermembership::orderBy('price','desc')->distinct()->pluck('title')->toArray();
        $freelancer_memberships = membershipsArray();
        $user = Auth::user();

        $professionals = User::with('freelancer_membership_transaction.membership', 'languages')
            ->where('users.status', 1)
            ->where('is_freelancer', 1)
            ->where(function ($query) {
                $query->where('per_hour_rate', '!=', 0)
                    ->orWhere('per_hour_rate', '!=', '');
            });

        if ($request->has('skill') && $request->skill != '') {
            $skill = Skill::where('name', $request->skill)->pluck('id')->toArray();
            if (count($skill) > 0) {
                $professionals->whereHas('profileSkills', function ($query) use ($skill) {
                    $query->whereIn('skill_id', $skill);
                });
            } else {
                $professionals = $professionals->whereRaw('0 = 1');
            }
        }
        if ($search && $search != '') {
            $professionals->where('username', 'like', '%' . $search . '%');
        }

        $professionals = $professionals->leftJoin('review_ratings', 'users.id', '=', 'review_ratings.pro_id')
            ->leftJoin('freelancer_membership_transactions', 'users.id', '=', 'freelancer_membership_transactions.user_id')
            ->leftJoin('freelancer_membership', 'freelancer_membership_transactions.membership_id', '=', 'freelancer_membership.id')
            ->select('users.*', DB::raw('AVG(review_ratings.likely_rating) as avg_rating'))
            ->groupBy('users.id')
            ->orderByRaw("
                CASE
                    WHEN freelancer_membership.title = 'Premium' THEN 1
                    WHEN freelancer_membership.title = 'Standard' THEN 2
                    WHEN freelancer_membership.title = 'Essentional' THEN 3
                    WHEN freelancer_membership.title = 'Basic' THEN 4
                    WHEN freelancer_membership.title = 'Try it free' THEN 5
                    ELSE 6
                END
            ")
            ->orderBy('avg_rating', 'DESC');
        $professionals = $professionals->inRandomOrder()->paginate(6)->withQueryString();
        foreach ($professionals as $professional) {
            $sellerData = $professional->getSellerProfileData($professional->id);
            $professional->completedJobs = $sellerData['completedJobs']->count();
        }

        // Fetch job categories efficiently
        $categories = Category::where('type', 'job')->cursor();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')), true);
        return redirect()->route('home', [
            'type'   => 'professionals',
            'search' => $request->search ?? $request->name,
        ]);
    }

    public function searchService(Request $request)
    {
        $search = $request->search ?? $request->name;

        $pageTitle = "All Services";
        $emptyMessage = "No services found";

        $user = Auth::user();

        $services = Service::with(['user.freelancer_membership_transaction.membership', 'category'])
            ->where('services.status', 1)
            ->whereHas('user', function ($q) {
                $q->where('status', 1)
                ->where('is_freelancer', 1);
            });

        // Skill filter
        if ($request->has('skill') && $request->skill != '') {
            $skill = Skill::where('name', $request->skill)->pluck('id')->toArray();

            if (count($skill) > 0) {
                $services->whereHas('skills', function ($query) use ($skill) {
                    $query->whereIn('skill_id', $skill);
                });
            } else {
                $services = $services->whereRaw('0 = 1');
            }
        }

        // Search by title
        if ($search && $search != '') {
            $services->where('services.title', 'like', '%' . $search . '%');
        }

        $services = $services
            ->leftJoin('review_ratings', 'services.id', '=', 'review_ratings.service_id')
            ->leftJoin('users', 'services.user_id', '=', 'users.id')
            ->leftJoin('freelancer_membership_transactions', 'users.id', '=', 'freelancer_membership_transactions.user_id')
            ->leftJoin('freelancer_membership', 'freelancer_membership_transactions.membership_id', '=', 'freelancer_membership.id')

            ->select('services.*', DB::raw('AVG(review_ratings.likely_rating) as avg_rating'))

            ->groupBy('services.id')

            ->orderByRaw("
                CASE
                    WHEN freelancer_membership.title = 'Premium' THEN 1
                    WHEN freelancer_membership.title = 'Standard' THEN 2
                    WHEN freelancer_membership.title = 'Essentional' THEN 3
                    WHEN freelancer_membership.title = 'Basic' THEN 4
                    WHEN freelancer_membership.title = 'Try it free' THEN 5
                    ELSE 6
                END
            ")

            ->orderBy('avg_rating', 'DESC');

        $services = $services->inRandomOrder()->paginate(6)->withQueryString();

        $categories = Category::where('type', 'job')->cursor();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')), true);

         return redirect()->route('home', [
            'type'   => 'services',
            'search' => $request->search ?? $request->name,
        ]);
    }
}
