<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Cache;
use Illuminate\Support\Facades\DB;
class User extends Authenticatable
{
    use Notifiable, HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = ['id'];

    protected $fillable = [
        'firstname', 'lastname', 'email', 'password', 'timezone','country','designation','address','state','country_code','inquiry_id','persona','weekly_hours'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'address' => 'object',
        'ver_code_send_at' => 'datetime'
    ];

    protected $data = [
        'data' => 1
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->timezone = $user->timezone ?? 'UTC'; // Set default timezone to UTC if not provided
        });
    }

    public function login_logs()
    {
        return $this->hasMany(UserLogin::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('id', 'desc');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status', '!=', 0);
    }

    public function allDeposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class)->where('status', '!=', 0);
    }

    public function services()
    {
        return $this->hasMany(Service::class)->where('status', 1);
    }

    public function softwares()
    {
        return $this->hasMany(Software::class)->where('status', 1);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class)->where('status', 1);
    }

    public function jobWorkDeliveryBids()
    {
        return $this->hasMany(Jobworkdeliverybid::class, 'buyer_id', 'id');
    }
    public function languages()
    {
        return $this->hasMany(UserLanguage::class, 'user_id');
    }
    public function educations()
    {
        return $this->hasMany(UserEducation::class);
    }
    public function certifications()
    {
        return $this->hasMany(UserCertification::class);
    }

    public function portfolioprofile()
    {
        return $this->hasMany(UserProfilePortfolio::class);
    }
    public function profileSkills()
    {
        return $this->hasMany(UserProfileSkill::class, 'user_id');
    }

    public function skillsprofilenew()
    {
        return $this->hasMany(UserProfileNewSkill::class);
    }
    public function userProfileAlerts()
    {
        return $this->hasMany(UserProfileAlerts::class);
    }

    public function getFullnameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function scopeActive()
    {
        return $this->where('status', 1);
    }

    public function scopeBanned()
    {
        return $this->where('status', 0);
    }

    public function scopeEmailUnverified()
    {
        return $this->where('ev', 0);
    }

    public function scopeSmsUnverified()
    {
        return $this->where('sv', 0);
    }
    public function scopeEmailVerified()
    {
        return $this->where('ev', 1);
    }

    public function scopeSmsVerified()
    {
        return $this->where('sv', 1);
    }

    public function scopePaymentVerified()
    {
        return $this->where('is_payment_verified', 1);
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class, 'rank_id');
    }

    public function isOnline()
    {
        return Cache::has('user-is-online' . $this->id);
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id');
    }

    public function reviews()
    {
        return $this->hasMany(ReviewRating::class, 'pro_id');
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating');
    }

    public function getReviewCountAttribute()
    {
        return $this->reviews()->count();
    }

    public function getAveragePrivateRatingAttribute()
    {
        return $this->reviews()->avg('likely_rating');
    }

    public function ChatStatus($id)
    {
        $chat = Chat::orWhere('ch_s_id', $id)->orWhere('ch_r_id', $id)->orderBy('ch_id', 'DESC')->first();
        if ($chat) {
            return ['userInboxOnline' => 'yes', 'userLastMessage' => $chat->message, 'userMessageTime' => $chat->created_at];
        } else {
            return ['userInboxOnline' => 'yes', 'userLastMessage' => '', 'userMessageTime' => ''];
        }
    }

    public function ChatUser($id)
    {
        $suser = Chat::where('ch_s_id', $id)->get();
        $ruser = Chat::where('ch_r_id', $id)->get();
        $users = [];
        foreach ($suser as $u) {
            array_push($users, $u->ch_r_id);
        }
        foreach ($ruser as $u) {
            array_push($users, $u->ch_s_id);
        }
        $rf =  array_unique($users, SORT_REGULAR);
        return $rf;
    }

    public function sendChat($id)
    {
        return Chat::where('ch_s_id', $id)->first();
    }
    public function reviewCount()
    {
        return $this->hasMany(ReviewRating::class, 'pro_id');
    }
    public function avatar()
    {
        return $this->belongsTo(FileManager::class, 'avatar_id');
    }
    public function userCompany()
    {
        return $this->hasOne(UserCompany::class);
    }
    public function getBuyerProfileData($userId)
    {
        // Fetch the user by ID
        $user = User::with('userCompany')->find($userId);

        // Count completed and pending projects
        $completedProjects = Booking::where('status', 3)->where('working_status', 1)
            ->whereHas('job', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->count();

        $pendingProjects = Booking::where('status', 1)->where('working_status', 0)
            ->whereHas('job', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->count();

        // Fetch the user's services and software
        $services = Service::where('user_id', $userId)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->get();

        $jobs = Job::where('user_id', $user->id)->where('status', 1)->with('user')->withCount('userBiding')->orderBy('id', 'desc')->get();
        $totalJobsPosted = Job::where('user_id', $user->id)->count();
        // Count the services bought by the buyer
        $serviceBoughtCount = Booking::where('buyer_id', $userId)->where('booking_type','byservice')->count();
        $totaltransactions = (int)Transaction::where('user_id', $user->id)->sum('amount');
        $isPaymentVerified = $user->is_payment_verified;
        // Return all fetched data
        return [
            'user' => $user,
            // 'ratings' => $ratings,
            // 'averageRatings' => $averageRatings,
            'completedProjects' => $completedProjects,
            'pendingProjects' => $pendingProjects,
            'services' => $services,
            'jobs' => $jobs,
            'serviceBoughtCount' => $serviceBoughtCount,
            'totaltransactions' => $totaltransactions,
            'isPaymentVerified'  => $isPaymentVerified,
            'totalJobsPosted' => $totalJobsPosted
        ];
    }
    public function getSellerProfileData($userId)
    {
        // Fetch the user by ID
        $user = User::with('reviews')->find($userId);
        $services = Service::with('latestDetail')->where('user_id', $userId)->whereHas('category', function ($q) {$q->where('status', 1);})->get();
        $inprogressJobs = Acceptedbid::where('seller_id', $userId)->whereNot('status',3)->get();
        $completedJobs = Acceptedbid::where('seller_id', $userId)->where('is_endcontract', 1)->get();
        return [
            'user' => $user,
            'services' => $services,
            'inprogressJobs' => $inprogressJobs,
            'completedJobs' => $completedJobs,
        ];
    }

    public function activeCertificates()
    {
        return $this->hasMany(UserCertification::class)->where('showcertificate', 1);
    }

    public function activeEducations()
    {
        return $this->hasMany(UserEducation::class)->where('showdegree', 1);
    }

    public function completedJobs()
    {
        return $this->hasMany(Acceptedbid::class, 'seller_id')->where('is_endcontract', 1);
    }

    public function buyerAcceptedBids()
    {
        return $this->hasMany(Acceptedbid::class, 'buyer_id','id')->where('is_endcontract',0);
    }
    public function latestLogin()
    {
        return $this->hasOne(UserLogin::class, 'user_id')->latest();
    }

    public function freelancer_membership_transaction()
    {
        return $this->hasOne(Freelancermembershiptransactions::class);
    }

    public function receivedDeliveries()
    {
        return $this->hasMany(WorkDelivery::class, 'receiver_id'); // key in work_deliveries
    }

    public function sentDeliveries()
    {
        return $this->hasMany(WorkDelivery::class, 'sender_id'); 
    }

    public function latestLastLogin()
    {
        return $this->hasOne(UserLogin::class, 'user_id')->latest();
    }
}
