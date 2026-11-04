<?php

use App\Events\AdminNotificationEvent;
use App\Jobs\SendAdminUserEmailJob;
use App\Jobs\SendEmailJob;
use App\Lib\GoogleAuthenticator;
use App\Lib\SendSms;
use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Advertise;
use App\Models\Category;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\Extension;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use App\Models\Job;
use App\Models\JobBiding;
use App\Models\Notification;
use App\Models\ProfileLanguage;
use App\Models\Rank;
use App\Models\SmsTemplate;
use App\Models\User;
use App\Models\Userbiding;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Pusher\Pusher;
use SendGrid\Mail\Mail;

function sidebarVariation()
{

    // / for sidebar
    $variation['sidebar'] = 'bg_img';

    // for selector
    $variation['selector'] = 'capsule--rounded';

    // for overlay
    $variation['overlay'] = 'overlay--indigo';

    // Opacity
    $variation['opacity'] = 'overlay--opacity-8'; // 1-10

    return $variation;
}

function systemDetails()
{
    $system['name'] = 'viserlance';
    $system['version'] = '1.0';

    return $system;
}

function getLatestVersion()
{
    $param['purchasecode'] = env('PURCHASECODE');
    $param['website'] = @$_SERVER['HTTP_HOST'].@$_SERVER['REQUEST_URI'].' - '.env('APP_URL');
    $url = 'https://license.viserlab.com/updates/version/'.systemDetails()['name'];
    $result = curlPostContent($url, $param);
    if ($result) {
        return $result;
    } else {
        return null;
    }
}

function slug($string)
{
    return Illuminate\Support\Str::slug($string);
}

function shortDescription($string, $length = 120)
{
    return Illuminate\Support\Str::limit($string, $length);
}

function shortCodeReplacer($shortCode, $replace_with, $template_string)
{
    return str_replace($shortCode, $replace_with, $template_string);
}

function verificationCode($length)
{
    if ($length == 0) {
        return 0;
    }
    $min = pow(10, $length - 1);
    $max = 0;
    while ($length > 0 && $length--) {
        $max = ($max * 10) + 9;
    }

    return random_int($min, $max);
}

function getNumber($length = 8)
{
    $characters = '1234567890';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}

// moveable
function uploadImage($file, $location, $size = null, $old = null, $thumb = null)
{

    if (! Storage::disk('public')->exists($location)) {
        Storage::disk('public')->makeDirectory($location);
    }

    if ($old) {
        removeFile($location.'/'.$old);
        removeFile($location.'/thumb_'.$old);
    }
    $filename = time().'.'.$file->getClientOriginalExtension();
    // $image = Image::make($file);
    // if ($size) {
    //     $size = explode('x', strtolower($size));
    //     $image->resize($size[0], $size[1]);
    // }
    // $image->save($location . '/' . $filename);

    // if ($thumb) {
    //     $thumb = explode('x', $thumb);
    //     Image::make($file)->resize($thumb[0], $thumb[1])->save($location . '/thumb_' . $filename);
    // }
    return $filename;
}

use App\Models\Freelancermembershiptransactions;
use App\Models\Employermembershiptransactions;
use App\Models\GatewayCurrency;
use App\Models\Service;
use App\Models\Skill;
use App\Models\SupportTicket;
use App\Notifications\AdminRealTimeNotification;
use App\Notifications\RealTimeNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

if (! function_exists('uploadFile')) {
    /**
     * Upload a file to the specified location.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $location
     * @param  string|null  $oldFileName
     * @return string
     *
     * @throws \Exception
     */
    function uploadFile($file, $location, $oldFileName = null)
    {
        // Create directory if it doesn't exist
        if (! Storage::disk('public')->exists($location)) {
            Storage::disk('public')->makeDirectory($location);
        }

        // Remove the old file if provided
        if ($oldFileName) {
            removeFile($location.'/'.$oldFileName);
        }

        // Use the original filename
        $originalFilename = $file->getClientOriginalName();

        // Store the file in the specified directory with its original name
        $filePath = $file->storeAs($location, $originalFilename, 'public');

        // Return the path relative to the storage
        return $filePath;
    }
}

if (! function_exists('removeFile')) {
    /**
     * Remove a file from the specified path.
     *
     * @param  string  $path
     * @return bool
     */
    function removeFile($path)
    {
        return Storage::disk('public')->exists($path) && Storage::disk('public')->delete($path);
    }
}

function activeTemplate($asset = false)
{
    $general = GeneralSetting::first(['active_template']);
    $template = $general->active_template;
    $sess = session()->get('template');
    if (trim($sess)) {
        $template = $sess;
    }
    if ($asset) {
        return 'assets/templates/'.$template.'/';
    }

    return 'templates.'.$template.'.';
}

function activeTemplateName()
{
    $general = GeneralSetting::first(['active_template']);
    $template = $general->active_template;
    $sess = session()->get('template');
    if (trim($sess)) {
        $template = $sess;
    }

    return $template;
}

function loadReCaptcha()
{
    $reCaptcha = Extension::where('act', 'google-recaptcha2')->where('status', 1)->first();

    return $reCaptcha ? $reCaptcha->generateScript() : '';
}

function loadAnalytics()
{
    $analytics = Extension::where('act', 'google-analytics')->where('status', 1)->first();

    return $analytics ? $analytics->generateScript() : '';
}

function loadTawkto()
{
    $tawkto = Extension::where('act', 'tawk-chat')->where('status', 1)->first();

    return $tawkto ? $tawkto->generateScript() : '';
}

function loadFbComment()
{
    $comment = Extension::where('act', 'fb-comment')->where('status', 1)->first();

    return $comment ? $comment->generateScript() : '';
}

function loadCustomCaptcha($height = 46, $width = '100%', $bgcolor = '#003', $textcolor = '#abc')
{
    $textcolor = '#'.GeneralSetting::first()->base_color;
    $captcha = Extension::where('act', 'custom-captcha')->where('status', 1)->first();
    if (! $captcha) {
        return 0;
    }
    $code = rand(100000, 999999);
    $char = str_split($code);
    $ret = '<link href="https://fonts.googleapis.com/css?family=Henny+Penny&display=swap" rel="stylesheet">';
    $ret .= '<div style="height: '.$height.'px; line-height: '.$height.'px; width:'.$width.'; text-align: center; background-color: '.$bgcolor.'; color: '.$textcolor.'; font-size: '.($height - 20).'px; font-weight: bold; letter-spacing: 20px; font-family: \'Henny Penny\', cursive;  -webkit-user-select: none; -moz-user-select: none;-ms-user-select: none;user-select: none;  display: flex; justify-content: center;">';
    foreach ($char as $value) {
        $ret .= '<span style="    float:left;     -webkit-transform: rotate('.rand(-60, 60).'deg);">'.$value.'</span>';
    }
    $ret .= '</div>';
    $captchaSecret = hash_hmac('sha256', $code, $captcha->shortcode->random_key->value);
    $ret .= '<input type="hidden" name="captcha_secret" value="'.$captchaSecret.'">';

    return $ret;
}

function captchaVerify($code, $secret)
{
    $captcha = Extension::where('act', 'custom-captcha')->where('status', 1)->first();
    $captchaSecret = hash_hmac('sha256', $code, $captcha->shortcode->random_key->value);
    if ($captchaSecret == $secret) {
        return true;
    }

    return false;
}

function getTrx($length = 12)
{
    $characters = 'ABCDEFGHJKMNOPQRSTUVWXYZ123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}

function getMembership($id1, $id2)
{
    $job = Job::where('id', decrypt($id2))->firstOrFail();
    $bid = UserBiding::where('job_id', decrypt($id2))->where('id', decrypt($id1))->firstOrFail();
    $jobBidings = JobBiding::where('job_id', $job->id)->with('user')->inRandomOrder()->paginate(5);
    $usermembership = null;

    if (! empty($user) && $user->is_freelancer == 1) {
        $usermembership = Freelancermembershiptransactions::join('freelancer_membership', 'freelancer_membership_transactions.membership_id', '=', 'freelancer_membership.id')->where('user_id', $user->id)->get();
    }

    return $usermembership;
}

function getAmount($amount, $length = 0)
{
    if ($length > 0) {
        $amount = round($amount, $length);
    } else {
        $amount = round($amount, 2);
    }

    return $amount + 0;
}

function showAmount($amount, $decimal = 2, $separate = true, $exceptZeros = false)
{
    $separator = '';
    if ($separate) {
        $separator = ',';
    }
    $amount = is_numeric($amount) ? (float) $amount : 0;
    $printAmount = number_format($amount, $decimal, '.', $separator);
    if ($exceptZeros) {
        $exp = explode('.', $printAmount);
        if ($exp[1] * 1 == 0) {
            $printAmount = $exp[0];
        }
    }

    return $printAmount;
}

function removeElement($array, $value)
{
    return array_diff($array, (is_array($value) ? $value : [$value]));
}

function cryptoQR($wallet)
{

    return "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$wallet&choe=UTF-8";
}

// moveable
function curlContent($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

// moveable
function curlPostContent($url, $arr = null)
{
    if ($arr) {
        $params = http_build_query($arr);
    } else {
        $params = '';
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

function inputTitle($text)
{
    return ucfirst(preg_replace('/[^A-Za-z0-9 ]/', ' ', $text));
}

function titleToKey($text)
{
    return strtolower(str_replace(' ', '_', $text));
}

function str_limit($title = null, $length = 10)
{
    return \Illuminate\Support\Str::limit($title, $length);
}

// moveable
// function getIpInfo()
// {
//     $ip = $_SERVER['REMOTE_ADDR'];

//     // Handle local environment cases (localhost)
//     if ($ip == '127.0.0.1' || $ip == '::1') {
//         // Default values for local testing (Pakistan - Karachi)
//         return [
//             'country' => 'Pakistan',
//             'city' => 'Karachi',
//             'area' => '021',
//             'code' => 'PK',
//             'long' => '67.0011',  // Longitude of Karachi, Pakistan
//             'lat' => '24.8607',   // Latitude of Karachi, Pakistan
//             'ip' => $ip,
//             'time' => date('d-m-Y h:i:s A'),
//             'timezone' => 'Asia/Karachi',
//         ];
//     }

//     // Detect IP from forwarded headers if available
//     if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
//         $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
//     }
//     if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
//         $ip = $_SERVER['HTTP_CLIENT_IP'];
//     }

//     // Get IP info from API
//     try {
//         dd($ip);
//         $xml = @simplexml_load_file('http://www.geoplugin.net/xml.gp?ip='.$ip);
//         dd($xml);

//         if ($xml === false) {
//             throw new Exception('Failed to load geolocation data');
//         }

//         $country = (string) $xml->geoplugin_countryName ?? 'Unknown';
//         $city = (string) $xml->geoplugin_city ?? 'Unknown';
//         $area = (string) $xml->geoplugin_areaCode ?? '000';
//         $code = (string) $xml->geoplugin_countryCode ?? 'UNK';
//         $long = (string) $xml->geoplugin_longitude ?? '0.0000';
//         $lat = (string) $xml->geoplugin_latitude ?? '0.0000';
//         $timezone = (string) $xml->geoplugin_timezone ?? 'UTC';
//         $data = [
//             'country' => $country,
//             'city' => $city,
//             'area' => $area,
//             'code' => $code,
//             'long' => $long,
//             'lat' => $lat,
//             'ip' => $ip,
//             'time' => date('d-m-Y h:i:s A'),
//             'timezone' => $timezone ?? 'UTC',
//         ];
//     } catch (Exception $e) {
//         // Log the error if needed
//         \Log::error('GeoPlugin API Error: '.$e->getMessage());

//         $data = [
//             'country' => 'Unknown',
//             'city' => 'Unknown',
//             'area' => '000',
//             'code' => 'UNK',
//             'long' => '0.0000',
//             'lat' => '0.0000',
//             'ip' => $ip,
//             'time' => date('d-m-Y h:i:s A'),
//             'timezone' => 'UTC',
//         ];
//     }

//     return $data;
// }

function getIpInfo()
{
    // Get visitor IP
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;

    // Check forwarded IP first
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $forwardedIps = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

        $forwardedIp = trim($forwardedIps[0]);

        if (filter_var($forwardedIp, FILTER_VALIDATE_IP)) {
            $ip = $forwardedIp;
        }
    }

    if (
        !empty($_SERVER['HTTP_CLIENT_IP']) &&
        filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)
    ) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }

    // Localhost fallback
    if ($ip == '127.0.0.1' || $ip == '::1') {
        return [
            'country' => 'Pakistan',
            'city' => 'Karachi',
            'area' => '000',
            'code' => 'PK',
            'long' => '67.0011',
            'lat' => '24.8607',
            'ip' => $ip,
            'time' => date('d-m-Y h:i:s A'),
            'timezone' => 'Asia/Karachi',
        ];
    }

    try {

        $url = "http://ip-api.com/json/{$ip}?fields=status,message,country,city,countryCode,lon,lat,timezone,query";

        $response = file_get_contents($url);

        if ($response === false) {
            throw new Exception('Failed to load geolocation data');
        }

        $location = json_decode($response, true);

        if (
            !isset($location['status']) ||
            $location['status'] !== 'success'
        ) {
            throw new Exception(
                $location['message'] ?? 'Unable to detect IP location'
            );
        }

        return [
            'country' => $location['country'] ?? 'Unknown',
            'city' => $location['city'] ?? 'Unknown',
            'area' => '000',
            'code' => $location['countryCode'] ?? 'UNK',
            'long' => $location['lon'] ?? '0.0000',
            'lat' => $location['lat'] ?? '0.0000',
            'ip' => $location['query'] ?? $ip,
            'time' => date('d-m-Y h:i:s A'),
            'timezone' => $location['timezone'] ?? 'UTC',
        ];

    } catch (\Exception $e) {

        \Log::error('IP Location API Error: ' . $e->getMessage());

        return [
            'country' => 'Unknown',
            'city' => 'Unknown',
            'area' => '000',
            'code' => 'UNK',
            'long' => '0.0000',
            'lat' => '0.0000',
            'ip' => $ip,
            'time' => date('d-m-Y h:i:s A'),
            'timezone' => 'UTC',
        ];
    }
}

// moveable
function osBrowser()
{
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $osPlatform = 'Unknown OS Platform';
    $osArray = [
        '/windows nt 10/i' => 'Windows 10',
        '/windows nt 6.3/i' => 'Windows 8.1',
        '/windows nt 6.2/i' => 'Windows 8',
        '/windows nt 6.1/i' => 'Windows 7',
        '/windows nt 6.0/i' => 'Windows Vista',
        '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i' => 'Windows XP',
        '/windows xp/i' => 'Windows XP',
        '/windows nt 5.0/i' => 'Windows 2000',
        '/windows me/i' => 'Windows ME',
        '/win98/i' => 'Windows 98',
        '/win95/i' => 'Windows 95',
        '/win16/i' => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i' => 'Mac OS 9',
        '/linux/i' => 'Linux',
        '/ubuntu/i' => 'Ubuntu',
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile',
    ];
    foreach ($osArray as $regex => $value) {
        if (preg_match($regex, $userAgent)) {
            $osPlatform = $value;
        }
    }
    $browser = 'Unknown Browser';
    $browserArray = [
        '/msie/i' => 'Internet Explorer',
        '/firefox/i' => 'Firefox',
        '/safari/i' => 'Safari',
        '/chrome/i' => 'Chrome',
        '/edge/i' => 'Edge',
        '/opera/i' => 'Opera',
        '/netscape/i' => 'Netscape',
        '/maxthon/i' => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i' => 'Handheld Browser',
    ];
    foreach ($browserArray as $regex => $value) {
        if (preg_match($regex, $userAgent)) {
            $browser = $value;
        }
    }

    $data['os_platform'] = $osPlatform;
    $data['browser'] = $browser;

    return $data;
}

function siteName()
{
    $general = GeneralSetting::first();
    $sitname = str_word_count($general->sitename);
    $sitnameArr = explode(' ', $general->sitename);
    if ($sitname > 1) {
        $title = "<span>$sitnameArr[0] </span> ".str_replace($sitnameArr[0], '', $general->sitename);
    } else {
        $title = "<span>$general->sitename</span>";
    }

    return $title;
}

// moveable
function getTemplates()
{
    $param['purchasecode'] = env('PURCHASECODE');
    $param['website'] = @$_SERVER['HTTP_HOST'].@$_SERVER['REQUEST_URI'].' - '.env('APP_URL');
    $url = 'https://license.viserlab.com/updates/templates/'.systemDetails()['name'];
    $result = curlPostContent($url, $param);
    if ($result) {
        return $result;
    } else {
        return null;
    }
}

function getPageSections($arr = false)
{

    $jsonUrl = resource_path('views/').str_replace('.', '/', activeTemplate()).'sections.json';
    $sections = json_decode(file_get_contents($jsonUrl));
    if ($arr) {
        $sections = json_decode(file_get_contents($jsonUrl), true);
        ksort($sections);
    }

    return $sections;
}

function getImage($image, $size = null)
{
    $clean = '';
    if (file_exists($image) && is_file($image)) {
        return asset($image).$clean;
    }
    if ($size) {
        return route('placeholder.image', $size);
    }

    return asset('assets/images/default.png');
}

function notify($user, $type, $shortCodes = null)
{
    sendEmailWithTemplate($user, $type, $shortCodes);
    sendSms($user, $type, $shortCodes);
}

function sendSms($user, $type, $shortCodes = [])
{
    $general = GeneralSetting::first();
    $smsTemplate = SmsTemplate::where('act', $type)->where('sms_status', 1)->first();
    $gateway = $general->sms_config->name;
    $sendSms = new SendSms;
    if ($general->sn == 1 && $smsTemplate) {
        $template = $smsTemplate->sms_body;
        foreach ($shortCodes as $code => $value) {
            $template = shortCodeReplacer('{{'.$code.'}}', $value, $template);
        }
        $message = shortCodeReplacer('{{message}}', $template, $general->sms_api);
        $message = shortCodeReplacer('{{name}}', $user->username, $message);
        $sendSms->$gateway($user->mobile, $general->sitename, $message, $general->sms_config);
    }
}
// Vikash Change
function sendRealTimeNotification($userId, $stub)
{
    $user = User::find($userId);
    $user->notify(new RealTimeNotification($stub));
}

function prepareEmailParameters($sender, $receiver, $job, $type, $userBiddingId = null, $transactionId = null, $transactionAmount = null)
{
    $params = [];
    switch ($type) {

        case 'NEW_PROPOSAL_RECEIVED':
            $params = [
                'sender_name' => $sender->username,
                'received_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'project' => $job->title,
                'click_url' => '/user/buyer/hire/employees/bids/'.encrypt($job->id),
            ];
            break;
        case 'PROPOSAL_WITHDRAWN':
            $params = [
                'sender_name' => $sender->username,
                'withdrawal_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'project' => $job->title,
                'click_url' => '/user/buyer/hire/employees/bids/'.encrypt($job->id),
            ];
            break;
        case 'PROPOSAL_BID_REJECTED':
            $params = [
                'employer_name' => $sender->username,
                'date_of_rejection' => Carbon::now()->format('Y-m-d H:i:s'),
                'project' => $job->title,
                'click_url' => '/job/offer-detail/'.encrypt($job->buyerCounterOffer->id),
            ];
            break;
        case 'PROPOSAL_BID_ACCEPTED':
            $params = [
                'employer_name' => $sender->username,
                'date_of_acceptance' => Carbon::now()->format('Y-m-d H:i:s'),
                'project' => $job->title,
                'click_url' => '/user/seller/job/list/bids/'.encrypt($job->id),
            ];
            break;
        case 'POSITION_APPLIED_CLOSED':
            $params = [
                'job_title' => $job->title,
                'cancellation_date' => Carbon::now()->format('Y-m-d H:i:s'),
            ];
            break;
        case 'SELLER_ACCEPT_DISPUTE':
            $params = [
                'sender_name' => $sender->username,
                'cancellation_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'click_url' => '/user/buyer/hire/employees/workspace/'.encrypt($job->id),

            ];
            break;
        case 'SELLER_DECLINE_DISPUTE':
            $params = [
                'sender_name' => $sender->username,
                'cancellation_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'click_url' => '/user/buyer/hire/employees/buyer/view-bid-dispute/'.encrypt($job->id),
            ];
            break;
        case 'REQUEST_PAYMENT_REJECTED':
            $params = [
                'sender_name' => $sender->username,
                'cancellation_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'click_url' => '/user/seller/job/list/workspace/'.encrypt($job->id),

            ];
            break;
        case 'ADDITIONAL_MILESTONE_SELLER':
            $params = [
                'sender_name' => $sender->username,
                'project' => $job->title,
                'approved_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'click_url' => '/user/seller/workspace/project/'.$userBiddingId,

            ];
            break;
        case 'PAYMENT_REQUEST_RECEIVED':
            $params = [
                'sender_name' => $sender->username,
                'cancellation_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'click_url' => '/user/buyer/hire/employees/workspace/'.encrypt($job->id),
                'project_name' => $job->title,
            ];
            break;
        case 'BUYER_DISPUTE_SELLER':
            $params = [
                'sender_name' => $sender->username,
                'cancellation_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'click_url' => '/user/seller/job/list/workspace/'.encrypt($job->id),

            ];
            break;
        case 'ADMIN_NOTIFY_BUYER_NEW_JOB_APPROVED':
            $params = [
                'job_title' => $job->title,
                'date' => Carbon::now()->format('Y-m-d H:i:s'),
                'click_url' => '/job/details/'.encrypt($job->id),

            ];
            break;
        case 'SELLER_NOTIFY_BUYER_OFFER_DECLINE':
            $params = [
                'sender_name' => $sender->username,
                'cancellation_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'click_url' => '/user/buyer/hire/employees/bids/'.encrypt($job->id),

            ];
            break;
        case 'SELLER_NOTIFY_BUYER_OFFER_ACCEPT':
            $params = [
                'sender_name' => $sender->username,
                'accepted_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'click_url' => '/user/buyer/hire/employees/bids/'.encrypt($job->id),
                'project_name' => $job->title,
            ];
            break;
        case 'JOB_POSTING_ALERTS':
            $params = [
                'sender_name' => $sender->username,
                'approved_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'click_url' => '/job/details/'.encrypt($job->id),
                'job_title' => $job->title,
            ];
            break;
        case 'SEND_OFFER':
            $params = [
                'sender_name' => $sender->username,
                'date_received' => Carbon::now()->format('Y-m-d H:i:s'),
                'project_name' => $job->title,
                'click_url' => '/job/offer-detail/'.encrypt($job->buyerCounterOffer->id),
            ];
            break;
        case 'WORK_REVIEW_FEEDBACK':
            $params = [
                'reviewer_name' => $sender->username,
                'review_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'project' => $job->title,
                'click_url' => '/user/seller/workspace/project/'.$userBiddingId,
            ];
            break;
        case 'PAYMENT_RECEIVED':
            $params = [
                'payer_name' => $sender->username,
                'date_of_payment' => Carbon::now()->format('Y-m-d H:i:s'),
                'invoice_number' => $transactionId,
                'received_amount' => $transactionAmount,
                'click_url' => '/user/seller/transactions',
            ];
            break;
    }

    return $params;
}

function sendJobAdminNotification($user, $job, $type)
{
    try {
        $adminNotification = new AdminNotification;
        $adminNotification->user_id = $user->id;
        $message = '';

        switch ($type) {
            case 'BUYER_CREATE_NEW_JOB':
                $message = 'New Job Created: '.$job->title;
                $adminNotification->title = $message;
                $adminNotification->click_url = urlPath('admin.job.details', $job->id);
                break;
        }

        $adminNotification->save();

    } catch (Exception $e) {
        Log::error('Error sending job admin notification: '.$e->getMessage());
    }
}

function sendJobAdminRealTimeNotification($user, $type, $click_url)
{
    try {
        $adminNotification = new AdminNotification;
        $adminNotification->user_id = $user->id;
        $message = '';

        switch ($type) {
            case 'BUYER_CREATE_NEW_JOB':
                $message = 'New Job Created';
                $adminNotification->title = $message;
                $adminNotification->click_url = $click_url ?? '#';
                break;

            case 'NEW_PROPOSAL_RECEIVED':
                $message = 'New Proposal Received';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'SEND_OFFER':
                $message = 'New Offer Received';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'NOTIFY_BUYER_OFFER_ACCEPT':
                $message = 'Offer Accepted';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'NOTIFY_BUYER_JOB_OFFER_DECLINED':
                $message = 'Offer Declined';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'ADDITIONAL_MILESTONE_SELLER_REQUESTED':
                $message = 'Additional Milestone Requested';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'PAYMENT_REQUEST_RECEIVED':
                $message = 'Payment Request Received';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'WORK_REVIEW_FEEDBACK':
                $message = 'Work Review Feedback';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'PAYMENT_RECEIVED':
                $message = 'Payment Received';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'WORK_REVIEW_DECLINED':
                $message = 'Work Review Declined';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'BUYER_DISPUTE_SELLER':
                $message = 'Buyer Dispute Seller';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'ADDITIONAL_MILESTONE_BUYER_REQUESTED':
                $message = 'Additional Milestone Requested';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'NOTIFY_SELLER_RECIEVED_BONUS':
                $message = 'Received Bonus';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'CONTRACT_HAS_ENDED':
                $message = 'Contract Has Ended';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'buyer_cancel_his_offer':
                $message = 'Buyer Cancel His Offer';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'ADDITIONAL_MILESTONE_APP_BY_BUYER':
                $message = 'Additional Milestone Approved By Buyer';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'ADDITIONAL_MILESTONE_REJ_BY_BUYER':
                $message = 'Additional Milestone Rejected By Buyer';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'ADDITIONAL_HOURLY_MILESTONE_REJ_BY_BUYER':
                $message = 'Additional Hourly Milestone Rejected By Buyer';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'DEPOSIT_COMPLETE':
                $message = 'Deposit Complete';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'PAYMENT_COMPLETE':
                $message = 'Payment Complete';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'PAYMENT_SELLER':
                $message = 'Seller Payment';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'SELLER_PAYMENT_DEPOSIT':
                $message = 'Seller Payment Deposit';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'BUYER_PAYMENT_DEPOSIT':
                $message = 'Buyer Payment Deposit';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'BUYER_PROFILE_COMPLETION':
                $message = 'Buyer Profile Completed';
                $adminNotification->title = $message;
                $adminNotification->click_url = $click_url ?? '#';
                break;

            case 'SELLER_PROFILE_COMPLETION':
                $message = 'Seller Profile Completed';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'BUYER_ONBOARDING':
                $message = 'Buyer OnBoarding';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'SELLER_ONBOARDING':
                $message = 'Seller OnBoarding';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'BUYER_MEMBERSHIP_UPGRADE':
                $message = 'Buyer Membership Upgrade';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'BUYER_JOB_PACKAGE_UPGRADE':
                $message = 'Buyer Job Package Upgrade';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'SELLER_MEMBERSHIP_UPGRADE':
                $message = 'Seller Membership Upgrade';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

            case 'SELLER_BIDS_UPGRADE':
                $message = 'Seller Bids Upgrade';
                $adminNotification->title = $message;
                $adminNotification->click_url = '#';
                break;

        }

        $adminNotification->save();
        event(new AdminNotificationEvent($adminNotification));

    } catch (\Exception $e) {
        Log::error('Error sending job admin notification: '.$e->getMessage());
    }
}

function sendJobEmailWithNotification($senderId, $receiverId, $jobId, $type, $admin = false, $userBiddingId = null, $transactionId = null, $transactionAmount = null, $offerId = null, $jobType = null, $jobDisputeBidId = null, $is_job_service = null)
{
    Log::info('sendJobEmailWithNotification called', [
        'senderId' => $senderId,
        'receiverId' => $receiverId,
        'jobId' => $jobId,
        'type' => $type,
        'jobType' => $jobType,
    ]);

    if ($jobId) {
        $job = Job::where('id', $jobId)->first();
    }
    if ($admin) {
        $sender = Admin::find($senderId);
    } else {
        $sender = User::find($senderId);
    }
    $receiver = User::find($receiverId);

    if (! $sender || ! $receiver) {
        Log::error('Sender or Receiver not found', [
            'senderId' => $senderId,
            'receiverId' => $receiverId,
        ]);

        return;
    }
    $emailTemplate = EmailTemplate::where('act', $type)->where('email_status', 1)->first();
    if (! $emailTemplate) {
        Log::error('Email template not found', ['type' => $type]);

        return;
    }
    if ($type == 'WITHDRAW_APPROVE') {
        $shortcodes = (array) $emailTemplate->shortcodes;
    } else {
        $shortcodes = json_decode($emailTemplate->shortcodes, true);
    }
    if (json_last_error() !== JSON_ERROR_NONE || ! $shortcodes || ! is_array($shortcodes)) {
        Log::error('Invalid or empty shortcodes in email template', [
            'type' => $type,
            'json_error' => json_last_error_msg(), // Logs the specific JSON error
            'shortcode_raw' => $emailTemplate->shortcodes, // Logs the raw data
        ]);

        return [];
    }

    $dynamicValues = [
        'sender_name' => $sender->username ?? '',
        'receiver_name' => $receiver->username ?? '',
        'project_name' => $job->title ?? '',
        'invoice_number' => $transactionId ?? '',
        'received_amount' => $transactionAmount ?? 0,
        'date' => Carbon::now()->format('Y-m-d H:i:s'),
        'bid_id' => encrypt($userBiddingId) ?? '',
        'offer_id' => encrypt($offerId),
        'job_dispute_bid_id' => encrypt($jobDisputeBidId),
        'id' => encrypt($receiver->id),
        'is_job_service' => $is_job_service ?? null,
    ];
    if ($jobId) {
        $dynamicValues['job_id'] = encrypt($job->id) ?? '';
        $dynamicValues['encryptedId'] = encrypt($job->id);
        $dynamicValues['id'] = encrypt($job->id);
    }
    if ($jobType) {
        if ($jobType == 'byproject') {
            $jobType = 'project';
        }
        $dynamicValues['job_type'] = $jobType;
    }
    // Build parameters dynamically and replace placeholders
    $params = [];
    foreach ($shortcodes as $key => $description) {
        if (strpos($description, '{{') !== false && strpos($description, '}}') !== false) {
            $params[$key] = preg_replace_callback('/\{\{(\w+)\}\}/', function ($matches) use ($dynamicValues) {
                return $dynamicValues[$matches[1]] ?? ''; // Replace placeholder with dynamic value
            }, $description);
        } else {
            if ($key == 'click_url') {
                $params[$key] = $description;
            } else {
                $params[$key] = $dynamicValues[$key] ?? ''; // Default value
            }
        }
    }
    // dd($emailTemplate,$params);
    // $params = prepareEmailParameters($sender, $receiver, $job, $type , $userBiddingId, $transactionId , $transactionAmount);
    $click_url = $params['click_url'] ?? null; // Get click URL from prepared parameters
    $message = $emailTemplate->notification_body;
    // reason on exluded_type is when admin approve then below templates hits means after job approving
    // admin will recieve all the notification i-e if 20 seller skills matches show 20 notification to admin
    // $excluded_type = ['ADMIN_NOTIFY_BUYER_NEW_JOB_APPROVED','JOB_POSTING_ALERTS','NOTIFY_SELLER_SAME_CATEGORY_JOB_POSTED','ADMIN_ACCOUNT_ACTIVATION','ADMIN_ACCOUNT_ACTIVATION_BUYER','WITHDRAW_APPROVE'];
    // $normalizedType = strtoupper(trim($type));

    // if (!in_array($normalizedType, $excluded_type, true)) {
    //     sendJobAdminRealTimeNotification($sender,$type,$click_url);
    // }

    foreach ($params as $code => $value) {
        $message = shortCodeReplacer('{{'.$code.'}}', $value, $message);
    }
    Log::info('Dispatching Notification', [
        'message' => $message,
        'senderId' => $senderId,
        'click_url' => $click_url,
    ]);
    $receiver->notify(new RealTimeNotification($message, $senderId, $click_url));

    // sendAdminRealTimeNotification($sender, $message, '#');

    dispatch(new SendEmailJob($emailTemplate, $receiver, $type, $params));
}

function sendJobActionsPrivateSocketNotifications($user, $type = null, $shortCodes = [], $noti = null)
{
    // Retrieve the email template
    $emailTemplate = EmailTemplate::where('act', $type)
        ->where('email_status', 1)
        ->first();

    if (! $emailTemplate) {
        return;
    }

    // Prepare the message by replacing short codes
    $message = $emailTemplate->notification_body;
    foreach ($shortCodes as $code => $value) {
        $message = shortCodeReplacer('{{'.$code.'}}', $value, $message);
    }

    // Initialize Pusher
    $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
        'cluster' => 'ap2',
    ]);

    // Ensure the 'click_url' key exists in $shortCodes
    if (isset($shortCodes['click_url'])) {
        $noti->message = $message;
        $noti->click_url = $shortCodes['click_url'];
    } else {
        // Handle missing click_url case
        $noti->message = $message;
        $noti->click_url = null; // or provide a default URL if appropriate
    }

    // Save the notification
    $noti->save();

    // Prepare data for Pusher
    $data = [
        'message' => $message,
        'click_url' => $shortCodes['click_url'] ?? null, // Use null if click_url is not set
        'noti' => $noti,
    ];

    try {
        // Trigger Pusher notification
        $pusher->trigger('private-notification-seven.'.$user->id, 'notification-seven', $data);
    } catch (\Exception $e) {
        // Handle exceptions (log or report the error)
        // Log::error('Pusher notification failed: ' . $e->getMessage());
    }
}

function sendTestEmail($email, $subject, $message, $receiver_name = '')
{
    \Log::info('sendTestEmail called', [
        'email' => $email,
    ]);
    $general = GeneralSetting::first();
    $config = $general->mail_config;
    if (! $config) {
        return;
    }

    \Log::info('sendTestEmail called', [
        'config' => $config,
    ]);

    $message = shortCodeReplacer('{{message}}', $message, $general->email_template);
    $message = shortCodeReplacer('{{name}}', $receiver_name, $message);

    if ($config->name == 'php') {
        sendPhpMail($email, $receiver_name, $general->email_from, $subject, $message, $general);
    } elseif ($config->name == 'smtp') {
        sendSmtpMail($config, $email, $receiver_name, $subject, $message, $general);
    } elseif ($config->name == 'sendgrid') {
        sendSendGridMail($config, $email, $receiver_name, $subject, $message, $general);
    } elseif ($config->name == 'mailjet') {
        sendMailjetMail($config, $email, $receiver_name, $subject, $message, $general);
    }
}

function sendGeneralEmail($type, $user)
{
    \Log::info('sendGeneralEmail called', [

        'type' => $type,
    ]);
    $emailTemplate = EmailTemplate::where('act', $type)->where('email_status', 1)->first();
    \Log::error('Email template  found', context: ['emailTemplate' => $emailTemplate]);
    if (! $emailTemplate) {
        \Log::error('Email template not found', context: ['type' => $type]);

        return;
    }
    $shortcodes = json_decode($emailTemplate->shortcodes, true);
    if (json_last_error() !== JSON_ERROR_NONE || ! $shortcodes || ! is_array($shortcodes)) {
        \Log::error('Invalid or empty shortcodes in email template', [
            'type' => $type,
            'json_error' => json_last_error_msg(), // Logs the specific JSON error
            'shortcode_raw' => $emailTemplate->shortcodes, // Logs the raw data
        ]);

        return [];
    }
    $dynamicValues = [
        'code' => $user->ev_code ?? '',
    ];

    // Build parameters dynamically and replace placeholders
    $params = [];
    foreach ($shortcodes as $key => $description) {
        $params[$key] = $dynamicValues[$key] ?? '';
    }

    dispatch(new SendEmailJob($emailTemplate, $user, $type, $params));
}

function sendTestEmailByAdmin($user, $subject, $message)
{
    try {
        $general = GeneralSetting::first();
        $config = $general->mail_config;

        dispatch(new SendAdminUserEmailJob($user, $subject, $message, $general, $config));
        Log::info('Email sent successfully.', ['user_id' => $user->id, 'email' => $user->email]);
    } catch (\Exception $e) {
        Log::error('Failed to send admin user email.', [
            'user_id' => $user->id,
            'email' => $user->email,
            'error_message' => $e->getMessage(),
        ]);
    }
}

function sendEmail($emailTemplate, $user, $type = null, $shortCodes = [])
{
    $general = GeneralSetting::first();
    if (! $general) {
        \Log::error('General settings not found. Email cannot be sent.', ['type' => $type]);

        return;
    }
    // Replacing shortcodes
    // $message = shortCodeReplacer("{{fullname}}", $user->fullname, $general->email_template);
    $message = shortCodeReplacer('{{username}}', $user->username, $general->email_template);
    $message = shortCodeReplacer('{{message}}', $emailTemplate->email_body, $message);

    if (empty($message)) {
        \Log::warning('Message body is empty after replacing shortcodes.', ['template_id' => $emailTemplate->id]);
        $message = $emailTemplate->email_body;
    }
    \Log::info('short.', ['shortCodes' => $shortCodes]);
    // Replace additional shortcodes
    foreach ($shortCodes as $code => $value) {

        if ($code == 'click_url') {
            $message = shortCodeReplacer('{{'.$code.'}}', url($value), $message);
        } else {
            $message = shortCodeReplacer('{{'.$code.'}}', $value, $message);
        }
    }

    $config = $general->mail_config;

    // Create email log entry
    $emailLog = new EmailLog;
    $emailLog->user_id = $user->id;
    $emailLog->mail_sender = $config->name;
    $emailLog->email_from = $general->sitename.' '.$general->email_from;
    $emailLog->email_to = $user->email;
    $emailLog->subject = $emailTemplate->subj;
    $emailLog->message = $message;
    $emailLog->save();

    \Log::info('Email log entry created.', ['email_log_id' => $emailLog->id]);

    // Sending email based on configuration
    try {
        if ($config->name == 'php') {
            \Log::info('Using PHP mailer to send email.', ['user_id' => $user->id]);
            sendPhpMail($user->email, $user->username, $general->email_from, $emailTemplate->subj, $message, $general);
        } elseif ($config->name == 'smtp') {
            \Log::info('Using SMTP to send email.', ['user_id' => $user->id]);
            sendSmtpMail($config, $user->email, $user->username, $emailTemplate->subj, $message, $general);
        } elseif ($config->name == 'sendgrid') {
            \Log::info('Using SendGrid to send email.', ['user_id' => $user->id]);
            sendSendGridMail($config, $user->email, $user->username, $emailTemplate->subj, $message, $general);
        } elseif ($config->name == 'mailjet') {
            \Log::info('Using Mailjet to send email.', ['user_id' => $user->id]);
            sendMailjetMail($config, $user->email, $user->username, $emailTemplate->subj, $message, $general);
        }
        \Log::info('Email sent successfully.', ['user_id' => $user->id, 'email' => $user->email]);
    } catch (\Exception $e) {
        \Log::error('Failed to send email.', [
            'user_id' => $user->id,
            'email' => $user->email,
            'error_message' => $e->getMessage(),
        ]);
    }
}

function resetPasswordSendEmail($emailTemplate, $user, $type = null, $shortCodes = [])
{
    $general = GeneralSetting::first();
    if (! $general) {
        \Log::error('General settings not found. Email cannot be sent.', ['type' => $type]);

        return;
    }
    // Replacing shortcodes
    // $message = shortCodeReplacer("{{fullname}}", $user->fullname, $general->email_template);
    $message = shortCodeReplacer('{{username}}', $user->username, $general->email_template);
    $message = shortCodeReplacer('{{message}}', $emailTemplate->email_body, $message);

    if (empty($message)) {
        \Log::warning('Message body is empty after replacing shortcodes.', ['template_id' => $emailTemplate->id]);
        $message = $emailTemplate->email_body;
    }
    \Log::info('short.', ['shortCodes' => $shortCodes]);
    // Replace additional shortcodes
    foreach ($shortCodes as $code => $value) {

        if ($code == 'click_url') {
            $message = shortCodeReplacer('{{'.$code.'}}', url($value), $message);
        } else {
            $message = shortCodeReplacer('{{'.$code.'}}', $value, $message);
        }
    }

    $config = $general->mail_config;

    // Create email log entry
    $emailLog = new EmailLog;
    $emailLog->user_id = $user->id;
    $emailLog->mail_sender = $config->name;
    $emailLog->email_from = $general->sitename.' '.$general->email_from;
    $emailLog->email_to = $user->email;
    $emailLog->subject = $emailTemplate->subj;
    $emailLog->message = $message;
    $emailLog->save();

    \Log::info('Email log entry created.', ['email_log_id' => $emailLog->id]);

    // Sending email based on configuration
    try {
        if ($config->name == 'php') {
            \Log::info('Using PHP mailer to send email.', ['user_id' => $user->id]);
            sendPhpMail($user->email, $user->username, $general->email_from, $emailTemplate->subj, $message, $general);
        } elseif ($config->name == 'smtp') {
            \Log::info('Using SMTP to send email.', ['user_id' => $user->id]);
            sendSmtpMail($config, $user->email, $user->username, $emailTemplate->subj, $message, $general);
        } elseif ($config->name == 'sendgrid') {
            \Log::info('Using SendGrid to send email.', ['user_id' => $user->id]);
            sendSendGridMail($config, $user->email, $user->username, $emailTemplate->subj, $message, $general);
        } elseif ($config->name == 'mailjet') {
            \Log::info('Using Mailjet to send email.', ['user_id' => $user->id]);
            sendMailjetMail($config, $user->email, $user->username, $emailTemplate->subj, $message, $general);
        }
        \Log::info('Email sent successfully.', ['user_id' => $user->id, 'email' => $user->email]);
    } catch (\Exception $e) {
        \Log::error('Failed to send email.', [
            'user_id' => $user->id,
            'email' => $user->email,
            'error_message' => $e->getMessage(),
        ]);
    }
}

function sendEmailWithTemplate($user, $type = null, $shortCodes = [], $emailTemplate = null)
{
    $general = GeneralSetting::first();
    if (! $general) {
        \Log::error('General settings not found. Email cannot be sent.', ['type' => $type]);

        return;
    }
    // Replacing shortcodes
    // $message = shortCodeReplacer("{{fullname}}", $user->fullname, $general->email_template);
    $message = shortCodeReplacer('{{username}}', $user->username, $general->email_template);
    $message = shortCodeReplacer('{{message}}', $emailTemplate?->email_body, $message);

    if (empty($message)) {
        \Log::warning('Message body is empty after replacing shortcodes.', ['template_id' => $emailTemplate?->id]);
        $message = $emailTemplate?->email_body;
    }
    \Log::info('short.', ['shortCodes' => $shortCodes]);
    // Replace additional shortcodes
    foreach ($shortCodes as $code => $value) {

        if ($code == 'click_url') {
            $message = shortCodeReplacer('{{'.$code.'}}', url($value), $message);
        } else {
            $message = shortCodeReplacer('{{'.$code.'}}', $value, $message);
        }
    }

    $config = $general->mail_config;

    // Create email log entry
    $emailLog = new EmailLog;
    $emailLog->user_id = $user->id;
    $emailLog->mail_sender = $config->name;
    $emailLog->email_from = $general->sitename.' '.$general->email_from;
    $emailLog->email_to = $user->email;
    $emailLog->subject = $emailTemplate?->subj;
    $emailLog->message = $message;
    $emailLog->save();

    \Log::info('Email log entry created.', ['email_log_id' => $emailLog->id]);

    // Sending email based on configuration
    try {
        if ($config->name == 'php') {
            \Log::info('Using PHP mailer to send email.', ['user_id' => $user->id]);
            sendPhpMail($user->email, $user->username, $general->email_from, $emailTemplate?->subj, $message, $general);
        } elseif ($config->name == 'smtp') {
            \Log::info('Using SMTP to send email.', ['user_id' => $user->id]);
            sendSmtpMail($config, $user->email, $user->username, $emailTemplate?->subj, $message, $general);
        } elseif ($config->name == 'sendgrid') {
            \Log::info('Using SendGrid to send email.', ['user_id' => $user->id]);
            sendSendGridMail($config, $user->email, $user->username, $emailTemplate?->subj, $message, $general);
        } elseif ($config->name == 'mailjet') {
            \Log::info('Using Mailjet to send email.', ['user_id' => $user->id]);
            sendMailjetMail($config, $user->email, $user->username, $emailTemplate?->subj, $message, $general);
        }
        \Log::info('Email sent successfully.', ['user_id' => $user->id, 'email' => $user->email]);
    } catch (\Exception $e) {
        \Log::error('Failed to send email.', [
            'user_id' => $user->id,
            'email' => $user->email,
            'error_message' => $e->getMessage(),
        ]);
    }
}

function sendPhpMail($receiver_email, $receiver_name, $email_from, $subject, $message, $general)
{
    $headers = "From: $email_from <$email_from> \r\n";
    $headers .= "Reply-To: $email_from <$email_from> \r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    @mail($receiver_email, $subject, $message, $headers);
}

function sendSmtpMail($config, $receiver_email, $receiver_name, $subject, $message, $general)
{
    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host = $config->host;
        $mail->SMTPAuth = true;
        $mail->Username = $config->username;
        $mail->Password = $config->password;

        if ($config->enc == 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } else {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }

        $mail->Port = $config->port;

        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];

        $mail->CharSet = 'UTF-8';

        $mail->setFrom($general->email_from, $general->sitename);
        $mail->addAddress($receiver_email, $receiver_name);
        $mail->addReplyTo($general->email_from, $general->sitename);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();

    } catch (Exception $e) {
        Log::info('SMTP Mailer Error: '.$e->getMessage());
        throw new Exception($e);
    }
}

function sendSendGridMail($config, $receiver_email, $receiver_name, $subject, $message, $general)
{
    Log::info('SendGrid email data', [
        'receiver_email' => $receiver_email,
        'receiver_name' => $receiver_name,
    ]);
    $sendgridMail = new Mail;
    $sendgridMail->setFrom($general->email_from, $general->sitename);
    $sendgridMail->setSubject($subject);
    $sendgridMail->addTo($receiver_email, $receiver_name);
    $sendgridMail->addContent('text/html', $message);
    $sendgrid = new \SendGrid($config->appkey);
    try {
        $response = $sendgrid->send($sendgridMail);
    } catch (Exception $e) {
        throw new Exception($e);
    }
}

function sendMailjetMail($config, $receiver_email, $receiver_name, $subject, $message, $general)
{
    $mj = new \Mailjet\Client($config->public_key, $config->secret_key, true, ['version' => 'v3.1']);
    $body = [
        'Messages' => [
            [
                'From' => [
                    'Email' => $general->email_from,
                    'Name' => $general->sitename,
                ],
                'To' => [
                    [
                        'Email' => $receiver_email,
                        'Name' => $receiver_name,
                    ],
                ],
                'Subject' => $subject,
                'TextPart' => '',
                'HTMLPart' => $message,
            ],
        ],
    ];
    $response = $mj->post(\Mailjet\Resources::$Email, ['body' => $body]);
}

function getPaginate($paginate = 20)
{
    return $paginate;
}

function paginateLinks($data, $design = 'admin.partials.paginate')
{
    return $data->appends(request()->all())->links($design);
}

function menuActive($routeName, $type = null)
{
    if ($type == 3) {
        $class = 'side-menu--open';
    } elseif ($type == 2) {
        $class = 'sidebar-submenu__open';
    } else {
        $class = 'active';
    }
    if (is_array($routeName)) {
        foreach ($routeName as $key => $value) {
            if (request()->routeIs($value)) {
                return $class;
            }
        }
    } elseif (request()->routeIs($routeName)) {
        return $class;
    }
}

function imagePath()
{
    $data['disputes'] = [
        'path' => 'storage/',
    ];
    $data['message'] = [
        'path' => 'assets/images/job',
        'size' => '590x300',
    ];
    $data['job'] = [
        'path' => 'assets/images/job',
        'size' => '920x468',
    ];
    $data['advertisement'] = [
        'path' => 'assets/images/advertisement',
    ];
    $data['optionalService'] = [
        'path' => 'assets/images/optionalService',
        'size' => '920x468',
    ];
    $data['document'] = [
        'path' => 'assets/software/document',
    ];
    $data['user_document'] = [
        'path' => 'assets/userdocuments',
    ];
    $data['workFile'] = [
        'path' => 'storage/',
    ];
    $data['uploadSoftware'] = [
        'path' => 'assets/software',
    ];
    $data['screenshot'] = [
        'path' => 'assets/images/screenshot',
        'size' => '920x468',
    ];
    $data['software'] = [
        'path' => 'assets/images/software',
        'size' => '920x468',
    ];
    $data['service'] = [
        'path' => 'assets/images/service',
        'size' => '920x468',
    ];
    $data['subcategory'] = [
        'path' => 'assets/images/subcategory',
        'size' => '590x300',
    ];
    $data['gateway'] = [
        'path' => 'assets/images/gateway',
        'size' => '800x800',
    ];
    $data['verify'] = [
        'withdraw' => [
            'path' => 'assets/images/verify/withdraw',
        ],
        'deposit' => [
            'path' => 'assets/images/verify/deposit',
        ],
    ];
    $data['image'] = [
        'default' => 'assets/images/default.png',
    ];
    $data['withdraw'] = [
        'method' => [
            'path' => 'assets/images/withdraw/method',
            'size' => '800x800',
        ],
    ];
    $data['ticket'] = [
        'path' => 'assets/support',
    ];
    $data['language'] = [
        'path' => 'assets/images/lang',
        'size' => '64x64',
    ];
    $data['logoIcon'] = [
        'path' => 'assets/images/logoIcon',
    ];
    $data['favicon'] = [
        'size' => '128x128',
    ];
    $data['extensions'] = [
        'path' => 'assets/images/extensions',
        'size' => '36x36',
    ];
    $data['seo'] = [
        'path' => 'assets/images/seo',
        'size' => '600x315',
    ];
    $data['profile'] = [
        'user' => [
            'path' => 'assets/images/user/profile',
            'size' => '590x300',
        ],
        'user_bg' => [
            'path' => 'assets/images/user/profile',
            'size' => '590x300',
        ],
        'admin' => [
            'path' => 'assets/admin/images/profile',
            'size' => '400x400',
        ],
    ];

    return $data;
}

function diffForHumans($date)
{
    $lang = session()->get('lang');
    Carbon::setlocale($lang);

    return Carbon::parse($date)->diffForHumans();
}

function showDateTime($date, $format = 'Y-m-d h:i A')
{
    $lang = session()->get('lang');
    Carbon::setlocale($lang);

    return Carbon::parse($date)->translatedFormat($format);
}

// moveable

function getContent($data_keys, $singleQuery = false, $limit = null, $orderById = false)
{
    if ($singleQuery) {
        $content = Frontend::where('data_keys', $data_keys)->orderBy('id', 'desc')->first();
    } else {
        $article = Frontend::query();
        $article->when($limit != null, function ($q) use ($limit) {
            return $q->limit($limit);
        });
        if ($orderById) {
            $content = $article->where('data_keys', $data_keys)->orderBy('id')->get();
        } else {
            $content = $article->where('data_keys', $data_keys)->orderBy('id', 'desc')->get();
        }
    }

    return $content;
}

function gatewayRedirectUrl($type = false)
{
    if ($type) {
        return 'user.deposit.historytwo';
    } else {
        return 'user.deposit.history';
    }
}

function verifyG2fa($user, $code, $secret = null)
{
    $ga = new GoogleAuthenticator;
    if (! $secret) {
        $secret = $user->tsc;
    }
    $oneCode = $ga->getCode($secret);
    $userCode = $code;
    if ($oneCode == $userCode) {
        $user->tv = 1;
        $user->save();

        return true;
    } else {
        return false;
    }
}

function urlPath($routeName, $routeParam = null)
{
    if ($routeParam == null) {
        $url = route($routeName);
    } else {
        $url = route($routeName, $routeParam);
    }
    $basePath = route('home');
    $path = str_replace($basePath, '', $url);

    return $path;
}

function rankUser($userId)
{
    $user = User::find($userId);
    if ($user) {
        $rank = Rank::where('status', 1)->where('amount', '<=', $user->income)->orderBy('amount', 'DESC')->first();
        if ($rank) {
            $user->rank_id = $rank->id;
            $user->save();
        }
    }
}

function impressionCount($id)
{
    $item = Advertise::where('id', $id)->first();
    $item->impression += 1;
    $item->save();
}

function sendUserNotification($reciever_id, $sender_id, $type, $job_id)
{
    $noti = new Notification;
    $noti->reciever_id = $reciever_id;
    $noti->sender_id = $sender_id;
    $noti->type = $type;
    $noti->job_id = $job_id;
    $noti->save();

    sendRealTimeNotification($reciever_id, 'job_created');
}

// function advertisements($size) {
//     $ad = Advertise::where('status',1)->where('size', $size)->inRandomOrder()->first();
//     if ($ad) {
//         if ($ad->type == 1) {
//             impressionCount($ad->id);
//             return  '<a  target="_blank" href="'.route('add.clicked',encrypt($ad->id)).'"><img src="'.getImage('assets/images/advertisement/'.$ad->adimage).'" alt="image"></a>';
//         }
//         if($ad->type == 2) {
//             impressionCount($ad->id);
//             return "<div class='dynamicScript' data-id=".encrypt($ad->id).">".$ad->script."</div>";
//         }
//     } else {
//         return '';
//     }
// }

function userDefaultImage($image, $type = null)
{
    $clean = '';
    if (file_exists($image) && is_file($image)) {
        return asset($image).$clean;
    }
    if ($type == 'profile_image') {
        return asset('assets/images/user.jpg');
    } elseif ($type == 'background_image') {
        return asset('assets/images/userbg.jpg');
    } else {
        return asset('assets/images/default.png');
    }
}

function authorizeAdmin($user)
{
    $access = $user;
    if (in_array(1, $access)) {
        return redirect()->route('admin.dashboard');
    } elseif (in_array(2, $access)) {
        return redirect()->route('admin.booking.service.index');
    } elseif (in_array(3, $access)) {
        return redirect()->route('admin.sales.software.index');
    } elseif (in_array(4, $access)) {
        return redirect()->route('admin.hire.index');
    } elseif (in_array(5, $access)) {
        return redirect()->route('admin.service.index');
    } elseif (in_array(6, $access)) {
        return redirect()->route('admin.software.index');
    } elseif (in_array(7, $access)) {
        return redirect()->route('admin.job.index');
    } elseif (in_array(8, $access)) {
        return redirect()->route('admin.ads.index');
    } elseif (in_array(9, $access)) {
        return redirect()->route('admin.users.all');
    } elseif (in_array(10, $access)) {
        return redirect()->route('admin.coupon.index');
    } elseif (in_array(11, $access)) {
        return redirect()->route('admin.category.index');
    } elseif (in_array(12, $access)) {
        return redirect()->route('admin.features.index');
    } elseif (in_array(13, $access)) {
        return redirect()->route('admin.gateway.automatic.index');
    } elseif (in_array(14, $access)) {
        return redirect()->route('admin.deposit.list');
    } elseif (in_array(15, $access)) {
        return redirect()->route('admin.withdraw.log');
    } elseif (in_array(16, $access)) {
        return redirect()->route('admin.ticket');
    } elseif (in_array(17, $access)) {
        return redirect()->route('admin.report.transaction');
    } elseif (in_array(18, $access)) {
        return redirect()->route('admin.subscriber.index');
    } elseif (in_array(19, $access)) {
        return redirect()->route('admin.setting.index');
    } elseif (in_array(20, $access)) {
        return redirect()->route('admin.setting.logo.icon');
    } elseif (in_array(21, $access)) {
        return redirect()->route('admin.extensions.index');
    } elseif (in_array(22, $access)) {
        return redirect()->route('admin.language.manage');
    } elseif (in_array(23, $access)) {
        return redirect()->route('admin.seo');
    } elseif (in_array(24, $access)) {
        return redirect()->route('admin.email.template.index');
    } elseif (in_array(25, $access)) {
        return redirect()->route('admin.sms.template.index');
    } elseif (in_array(26, $access)) {
        return redirect()->route('admin.setting.cookie');
    } elseif (in_array(27, $access)) {
        return redirect()->route('admin.frontend.templates');
    } elseif (in_array(28, $access)) {
        return redirect()->route('admin.frontend.sections');
    } elseif (in_array(29, $access)) {
        return redirect()->route('admin.system.info');
    } elseif (in_array(30, $access)) {
        return redirect()->route('admin.setting.custom.css');
    } elseif (in_array(31, $access)) {
        return redirect()->route('admin.setting.optimize');
    } elseif (in_array(32, $access)) {
        return redirect()->route('admin.request.report');
    } elseif (in_array(33, $access)) {
        return redirect()->route('admin.rank.index');
    } elseif (in_array(34, $access)) {
        return redirect()->route('admin.staff.index');
    } else {
        return redirect()->route('admin.profile');
    }
}

function getUserSkillsVsJobSkillsPercentage($user, $job)
{
    // Get user skills and job skills as arrays of IDs
    $userSkills = $user->profileSkills->pluck('skill_id')->toArray();
    $jobSkills = $job->jobSkills->pluck('skill_id')->toArray();

    // Log user and job skills for debugging
    Log::info('User ID:', ['user_id' => $user->id]);
    Log::info('Job ID:', ['job_id' => $job->id]);

    Log::info('User Skills:', $userSkills);
    Log::info('Job Skills:', $jobSkills);

    // Count unique skill IDs in both arrays
    $userSkillCount = count(array_unique($userSkills));
    $jobSkillCount = count(array_unique($jobSkills));

    // Find common skill IDs between the arrays
    $commonSkillIds = array_intersect($userSkills, $jobSkills);
    $commonSkillCount = count(array_unique($commonSkillIds));

    // Log the counts for each type of skills
    Log::info("User Skill Count: $userSkillCount");
    Log::info("Job Skill Count: $jobSkillCount");
    Log::info("Common Skill Count: $commonSkillCount");

    // Calculate percentage match of skills
    if ($userSkillCount > 0 && $jobSkillCount > 0) {
        $percentageMatch = ($commonSkillCount / min($userSkillCount, $jobSkillCount)) * 100;
    } else {
        $percentageMatch = 0; // Default to 0 if no skills are found
    }

    // Log the percentage match
    Log::info('Percentage Match: '.round($percentageMatch, 2).'%');

    return round($percentageMatch, 0);
}

function ordinal($number)
{
    $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
    if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
        return $number.'th';
    } else {
        return $number.$ends[$number % 10];
    }
}
if (! function_exists('getTimeIntervals')) {
    function getTimeIntervals($timezone = 'UTC', $loggedIntervals = [])
    {
        $timeIntervals = [];
        $currentTime = new DateTime('now', new DateTimeZone($timezone));
        $startOfDay = clone $currentTime;
        $startOfDay->setTime(0, 0, 0); // Start of the day

        for ($hour = 0; $hour < 24; $hour++) {
            for ($minute = 0; $minute < 60; $minute += 10) {
                $intervalTime = clone $startOfDay;
                $intervalTime->modify("+$hour hours +$minute minutes");

                $timeValue = $intervalTime->format('H:i'); // 24-hour format
                $timeLabel = $intervalTime->format('g:i A'); // 12-hour format

                // Check if the interval is logged
                $isLogged = false;
                foreach ($loggedIntervals as $logged) {
                    if ($logged['start'] <= $timeValue && $timeValue < $logged['end']) {
                        $isLogged = true;
                        break;
                    }
                }

                $timeIntervals[] = [
                    'value' => $timeValue,
                    'label' => $timeLabel.($isLogged ? ' (Already Logged)' : ''),
                    'disabled' => $isLogged,
                ];
            }
        }

        return $timeIntervals;
    }
}

if (! function_exists('calculateJobSuccessScore')) {
    function calculateJobSuccessScore($ratings, $likelyRating, $reason)
    {
        // Calculate average star rating from public feedback
        $averageStarRating = ($ratings['skills'] + $ratings['quality'] + $ratings['availability'] + $ratings['communication'] + $ratings['cooperation']) / 5;

        // Adjust based on private feedback - likelihood to recommend
        $privateFeedbackScore = $likelyRating / 10; // normalize likely_rating (1-10) to a scale of 0-1

        // Contract reason adjustment factor (positive or negative)
        $reasonAdjustment = 0;
        if ($reason == 'Job completed successfully') {
            $reasonAdjustment = 0.1; // positive boost
        } elseif (in_array($reason, ['Not satisfied with work', 'Very lazy freelancer'])) {
            $reasonAdjustment = -0.1; // negative impact
        }

        // Calculate Job Success Score as a weighted score
        $jobSuccessScore = ($averageStarRating * 0.7) + ($privateFeedbackScore * 0.2) + $reasonAdjustment;

        // Convert to a percentage out of 100 and return
        return max(0, min(round($jobSuccessScore * 100), 100));
    }
}
function getPercent($count, $total)
{
    return $total > 0 ? round(($count / $total) * 100) : 0;
}

function statusText($status)
{
    return match ((int) $status) {
        0 => 'unpaid',
        1 => 'running',
        2 => 'payable both',
        3 => 'paid',
        4 => 'refund',
        5 => 'accepted',
        6 => 'completed',
        7 => 'dispute send to admin',
        8 => 'in dispute',
        9 => 'pending',
        10 => 'cancel',
        default => 'running',
    };
}

function workingStatusText($status)
{
    return match ((int) $status) {
        0 => 'pending',
        1 => 'completed',
        2 => 'delivered',
        3 => 'cancel',
        4 => 'in progress',
        5 => 'delivery expired',
        6 => 'dispute',
        7 => 'payed',
        default => 'pending',
    };
}



function workingServiceStatusText($status)
{
    return match ((int) $status) {
        0 => 'pending',
        1 => 'completed',
        2 => 'delivered',
        3 => 'cancel',
        4 => 'in progress',
        5 => 'accepted',
        6 => 'dispute',
        default => 'pending',
    };
}

function notifyMatchedJobCategoryUsers($job)
{
    try {
        Log::info('Notify Same Category Sellers');

        $users = User::where('cat_id', $job->category_id)
            ->where('is_freelancer', 1)
            ->orderBy('id', 'desc')
            ->get();

        foreach ($users as $user) {
            try {
                sendJobEmailWithNotification(
                    $job->user_id,
                    $user->id,
                    $job->id,
                    'NOTIFY_SELLER_SAME_CATEGORY_JOB_POSTED'
                );
            } catch (\Exception $innerEx) {
                Log::error("Failed to send notification to user {$user->id}: ".$innerEx->getMessage());
            }
        }
    } catch (\Exception $e) {
        Log::error('Notify Same Category Sellers main error: '.$e->getMessage());
    }
}
function membershipsArray()
{
    return [
        0 => 'Premium',
        1 => 'Standard',
        2 => 'Essentional',
        3 => 'Basic',
        4 => 'Try it free',
    ];
}

function profileLanguages()
{
    return ProfileLanguage::pluck('name', 'id')->toArray();
}

function languageLevel()
{
    return [
        'Basic',
        'Conversational',
        'Fluent',
        'Native',
    ];
}

function ranks()
{
    $ranks = Rank::where('status', 1)->get();
    $ranks->transform(function ($item) {
        return [
            'id' => $item->id,
            'level' => 'Level '.ucfirst($item->level),
        ];
    });

    return $ranks;
}

function getPaymentProcessingFee($methodCode, $currency, $subTotal)
{
    $gateway = GatewayCurrency::whereHas('method', function ($query) {
        $query->where('status', 1);
    })
        ->where('method_code', $methodCode)
        ->where('currency', $currency)
        ->first();

    if (! $gateway) {
        return 0;
    }

    $fixedCharge = (float) $gateway->fixed_charge;

    $percentCharge = (float) $gateway->percent_charge;

    $paymentFee = $fixedCharge + ($subTotal * $percentCharge / 100);

    return number_format($paymentFee, 2);
}

function sendServiceEmailWithNotification($senderId, $receiverId, $serviceId, $type, $admin = false, $transactionId = null, $transactionAmount = null, $offerId = null, $serviceDisputeBidId = null, $bookingId = null)
{
    Log::info('sendServiceEmailWithNotification called', [
        'senderId' => $senderId,
        'receiverId' => $receiverId,
        'serviceId' => $serviceId,
        'type' => $type,
    ]);

    if ($serviceId) {
        $service = Service::where('id', $serviceId)->first();
    }

    $sender = User::find($senderId);
    $receiver = User::find($receiverId);

    if (! $sender || ! $receiver) {
        Log::error('Sender or Receiver not found', [
            'senderId' => $senderId,
            'receiverId' => $receiverId,
        ]);

        return;
    }
    $emailTemplate = EmailTemplate::where('act', $type)->where('email_status', 1)->first();
    if (! $emailTemplate) {
        Log::error('Email template not found', ['type' => $type]);

        return;
    }

    $shortcodes = json_decode($emailTemplate->shortcodes, true);

    if (json_last_error() !== JSON_ERROR_NONE || ! $shortcodes || ! is_array($shortcodes)) {
        Log::error('Invalid or empty shortcodes in email template', [
            'type' => $type,
            'json_error' => json_last_error_msg(),
            'shortcode_raw' => $emailTemplate->shortcodes,
        ]);

        return [];
    }

    $dynamicValues = [
        'sender_name' => $sender->username ?? '',
        'receiver_name' => $receiver->username ?? '',
        'project_name' => $service->title ?? '',
        'invoice_number' => $transactionId ?? '',
        'received_amount' => $transactionAmount ?? 0,
        'date' => Carbon::now()->format('Y-m-d H:i:s'),
        'booking_id' => encrypt($bookingId),
        'service_dispute_id' => encrypt($serviceDisputeBidId),
        'id' => encrypt($receiver->id),
    ];

    if ($serviceId) {
        $dynamicValues['service_id'] = encrypt($service->id) ?? '';
        $dynamicValues['encryptedId'] = encrypt($service->id);
        $dynamicValues['id'] = encrypt($service->id);
    }
    if ($bookingId) {
        $dynamicValues['booking_id'] = encrypt($bookingId);
    }

    // Build parameters dynamically and replace placeholders
    $params = [];
    foreach ($shortcodes as $key => $description) {
        if (strpos($description, '{{') !== false && strpos($description, '}}') !== false) {
            $params[$key] = preg_replace_callback('/\{\{(\w+)\}\}/', function ($matches) use ($dynamicValues) {
                return $dynamicValues[$matches[1]] ?? ''; // Replace placeholder with dynamic value
            }, $description);
        } else {
            if ($key == 'click_url') {
                $params[$key] = $description;
            } else {
                $params[$key] = $dynamicValues[$key] ?? ''; // Default value
            }
        }
    }
    // $params = prepareEmailParameters($sender, $receiver, $job, $type , $userBiddingId, $transactionId , $transactionAmount);
    $click_url = $params['click_url'] ?? null; // Get click URL from prepared parameters
    $message = $emailTemplate->notification_body;
    // reason on exluded_type is when admin approve then below templates hits means after job approving
    // admin will recieve all the notification i-e if 20 seller skills matches show 20 notification to admin

    foreach ($params as $code => $value) {
        $message = shortCodeReplacer('{{'.$code.'}}', $value, $message);
    }
    Log::info('Dispatching Service Notification', [
        'message' => $message,
        'senderId' => $senderId,
        'click_url' => $click_url,
    ]);
    $receiver->notify(new RealTimeNotification($message, $senderId, $click_url));

    // sendAdminRealTimeNotification($sender, $message, null);

    dispatch(new SendEmailJob($emailTemplate, $receiver, $type, $params));
}

function sendServiceEmailWithNotificationByAdmin($senderId, $receiverId, $serviceId, $type, $admin = false, $transactionId = null, $transactionAmount = null, $offerId = null, $serviceDisputeBidId = null, $bookingId = null)
{
    Log::info('sendServiceEmailWithNotificationByAdmin called', [
        'senderId' => $senderId,
        'receiverId' => $receiverId,
        'serviceId' => $serviceId,
        'type' => $type,
    ]);

    if ($serviceId) {
        $service = Service::where('id', $serviceId)->first();
    }

    $sender = Admin::find($senderId);
    $receiver = User::find($receiverId);

    if (! $sender || ! $receiver) {
        Log::error('Sender or Receiver not found', [
            'senderId' => $senderId,
            'receiverId' => $receiverId,
        ]);

        return;
    }
    $emailTemplate = EmailTemplate::where('act', $type)->where('email_status', 1)->first();
    if (! $emailTemplate) {
        Log::error('Email template not found', ['type' => $type]);

        return;
    }

    $shortcodes = json_decode($emailTemplate->shortcodes, true);

    if (json_last_error() !== JSON_ERROR_NONE || ! $shortcodes || ! is_array($shortcodes)) {
        Log::error('Invalid or empty shortcodes in email template', [
            'type' => $type,
            'json_error' => json_last_error_msg(),
            'shortcode_raw' => $emailTemplate->shortcodes,
        ]);

        return [];
    }

    $dynamicValues = [
        'sender_name' => $sender->username ?? '',
        'receiver_name' => $receiver->username ?? '',
        'project_name' => $service->title ?? '',
        'invoice_number' => $transactionId ?? '',
        'received_amount' => $transactionAmount ?? 0,
        'date' => Carbon::now()->format('Y-m-d H:i:s'),
        'booking_id' => encrypt($bookingId),
        'service_dispute_id' => encrypt($serviceDisputeBidId),
        'id' => encrypt($receiver->id),
    ];

    if ($serviceId) {
        $dynamicValues['service_id'] = encrypt($service->id) ?? '';
        $dynamicValues['encryptedId'] = encrypt($service->id);
        $dynamicValues['id'] = encrypt($service->id);
    }
    if ($bookingId) {
        $dynamicValues['booking_id'] = encrypt($bookingId);
    }

    // Build parameters dynamically and replace placeholders
    $params = [];
    foreach ($shortcodes as $key => $description) {
        if (strpos($description, '{{') !== false && strpos($description, '}}') !== false) {
            $params[$key] = preg_replace_callback('/\{\{(\w+)\}\}/', function ($matches) use ($dynamicValues) {
                return $dynamicValues[$matches[1]] ?? ''; // Replace placeholder with dynamic value
            }, $description);
        } else {
            if ($key == 'click_url') {
                $params[$key] = $description;
            } else {
                $params[$key] = $dynamicValues[$key] ?? ''; // Default value
            }
        }
    }
    // $params = prepareEmailParameters($sender, $receiver, $job, $type , $userBiddingId, $transactionId , $transactionAmount);
    $click_url = $params['click_url'] ?? null; // Get click URL from prepared parameters
    $message = $emailTemplate->notification_body;
    // reason on exluded_type is when admin approve then below templates hits means after job approving
    // admin will recieve all the notification i-e if 20 seller skills matches show 20 notification to admin

    foreach ($params as $code => $value) {
        $message = shortCodeReplacer('{{'.$code.'}}', $value, $message);
    }
    Log::info('Dispatching Service Notification', [
        'message' => $message,
        'senderId' => $senderId,
        'click_url' => $click_url,
    ]);
    $receiver->notify(new RealTimeNotification($message, $senderId, $click_url));

    dispatch(new SendEmailJob($emailTemplate, $receiver, $type, $params));
}

function getFirstWords($text, $numWords = 15)
{
    $plain = strip_tags(html_entity_decode($text));
    $words = explode(' ', $plain);

    if (count($words) <= $numWords) {
        return $text;
    }

    $short = implode(' ', array_slice($words, 0, $numWords));

    return $short.'...';
}

function sendContactEmail($contactData, $supportEmail, $type = 'contact')
{
    $general = GeneralSetting::first();
    if (! $general) {
        \Log::error('General settings not found. Contact email cannot be sent.', ['type' => $type]);

        return;
    }

    // Generate reference ID
    $referenceId = '#'.strtoupper(substr(md5(uniqid()), 0, 8));

    // Get company name from contact data or set default
    $company = $contactData['company'] ?? '';

    // Prepare the email HTML content using your template
    $message = view('Emails.contact-form', [
        'data' => [
            'fullname' => $contactData['fullname'],
            'email' => $contactData['email'],
            'company' => $company,
            'subject' => $contactData['subject'],
            'message' => nl2br(e($contactData['message'])),
            'ip_address' => $contactData['ip_address'] ?? request()->ip(),
            'submitted_at' => now()->format('h:i A'),
            'reference_id' => $referenceId,
        ],
        'general' => $general,
        'year' => date('Y'),
    ])->render();

    // Sending email based on configuration
    try {
        $config = $general->mail_config;

        if ($config->name == 'php') {
            \Log::info('Using PHP mailer to send contact email.');
            sendPhpMail(
                $supportEmail,
                'Support Team',
                $general->email_from,
                'New Contact Form Submission: '.$contactData['subject'],
                $message,
                $general
            );
        } elseif ($config->name == 'smtp') {
            \Log::info('Using SMTP to send contact email.');
            sendSmtpMail(
                $config,
                $supportEmail,
                'Support Team',
                'New Contact Form Submission: '.$contactData['subject'],
                $message,
                $general
            );
        } elseif ($config->name == 'sendgrid') {
            \Log::info('Using SendGrid to send contact email.');
            sendSendGridMail(
                $config,
                $supportEmail,
                'Support Team',
                'New Contact Form Submission: '.$contactData['subject'],
                $message,
                $general
            );
        } elseif ($config->name == 'mailjet') {
            \Log::info('Using Mailjet to send contact email.');
            sendMailjetMail(
                $config,
                $supportEmail,
                'Support Team',
                'New Contact Form Submission: '.$contactData['subject'],
                $message,
                $general
            );
        }

        \Log::info('Contact email sent successfully.', ['to' => $supportEmail]);

        return true;

    } catch (\Exception $e) {
        \Log::error('Failed to send contact email.', [
            'to' => $supportEmail,
            'error_message' => $e->getMessage(),
        ]);

        return false;
    }
}

function sendAdminRealTimeNotification($user, $message, $click_url = null)
{
    try {
        $admin = Admin::first();
        $admin->notify(new AdminRealTimeNotification($message, $user->id, $click_url));
    } catch (\Exception $e) {
        Log::error('Error sending admin notification: '.$e->getMessage());
    }
}

function sendTicketEmailWithNotificationByAdmin($senderId, $receiverId, $ticketId, $type)
{
    Log::info('sendTicketEmailWithNotificationByAdmin called', [
        'senderId' => $senderId,
        'receiverId' => $receiverId,
        'ticketId' => $ticketId,
        'type' => $type,
    ]);

    if ($ticketId) {
        $ticket = SupportTicket::find($ticketId);
    }

    $sender = Admin::find($senderId);
    $receiver = User::find($receiverId);

    if (! $sender || ! $receiver) {
        Log::error('Sender or Receiver not found', [
            'senderId' => $senderId,
            'receiverId' => $receiverId,
        ]);

        return;
    }
    $emailTemplate = EmailTemplate::where('act', $type)->where('email_status', 1)->first();
    if (! $emailTemplate) {
        Log::error('Email template not found', ['type' => $type]);

        return;
    }

    $shortcodes = json_decode($emailTemplate->shortcodes, true);

    if (json_last_error() !== JSON_ERROR_NONE || ! $shortcodes || ! is_array($shortcodes)) {
        Log::error('Invalid or empty shortcodes in email template', [
            'type' => $type,
            'json_error' => json_last_error_msg(),
            'shortcode_raw' => $emailTemplate->shortcodes,
        ]);

        return [];
    }

    $dynamicValues = [
        'sender_name' => $sender->username ?? '',
        'receiver_name' => $receiver->username ?? '',
        'date' => Carbon::now()->format('Y-m-d H:i:s'),
        'ticket_id' => encrypt($ticketId),
        'id' => encrypt($receiver->id),
        'project_name' => $ticket->subject ?? '',
    ];

    if ($ticketId) {
        $dynamicValues['ticket_id'] = ($ticket->ticket) ?? '';
        $dynamicValues['encryptedId'] = ($ticket->ticket);
        $dynamicValues['id'] = ($ticket->ticket);
    }

    $params = [];
    foreach ($shortcodes as $key => $description) {
        if (strpos($description, '{{') !== false && strpos($description, '}}') !== false) {
            $params[$key] = preg_replace_callback('/\{\{(\w+)\}\}/', function ($matches) use ($dynamicValues) {
                return $dynamicValues[$matches[1]] ?? '';
            }, $description);
        } else {
            if ($key == 'click_url') {
                $params[$key] = $description;
            } else {
                $params[$key] = $dynamicValues[$key] ?? '';
            }
        }
    }
    $click_url = $params['click_url'] ?? null;
    $message = $emailTemplate->notification_body;

    foreach ($params as $code => $value) {
        $message = shortCodeReplacer('{{'.$code.'}}', $value, $message);
    }
    Log::info('Dispatching Ticket Notification', [
        'message' => $message,
        'senderId' => $senderId,
        'click_url' => $click_url,
    ]);
    $receiver->notify(new RealTimeNotification($message, $senderId, $click_url));

    dispatch(new SendEmailJob($emailTemplate, $receiver, $type, $params));
}

function strip_tags_decode($string, $limit = null) {
    $cleanString = strip_tags(html_entity_decode($string));

    if ($limit) {
        return Str::limit($cleanString, $limit, '...');
    }

    return $cleanString;
}



if (!function_exists('getPercent')) {
    /**
     * Calculate percentage for rating progress bars
     * 
     * @param int|float $part The partial value
     * @param int|float $total The total value
     * @return int|float The percentage value
     */
    function getPercent($part, $total)
    {
        if ($total == 0) {
            return 0;
        }
        
        $percent = ($part / $total) * 100;
        return round($percent, 2);
    }
}

if (!function_exists('searchJob')) {
    function searchJob($request)
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
}

if (!function_exists('searchProfessionals')) {
    function searchProfessionals($request)
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
}

if (!function_exists('searchService')) {
    function searchService($request)
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

function sendPrivacyAndTermsNotification($senderId, $receiverId, $type)
{
    Log::info('sendJobEmailWithNotification called', [
        'senderId' => $senderId,
        'receiverId' => $receiverId,
        'type' => $type,
    ]);

    $sender = Admin::find($senderId);
    $receiver = User::find($receiverId);

    if (! $sender || ! $receiver) {
        Log::error('Sender or Receiver not found', [
            'senderId' => $senderId,
            'receiverId' => $receiverId,
        ]);

        return;
    }
    $emailTemplate = EmailTemplate::where('act', $type)->where('email_status', 1)->first();
    if (! $emailTemplate) {
        Log::error('Email template not found', ['type' => $type]);

        return;
    }
   
    $shortcodes = json_decode($emailTemplate->shortcodes, true);
    if (json_last_error() !== JSON_ERROR_NONE || ! $shortcodes || ! is_array($shortcodes)) {
        Log::error('Invalid or empty shortcodes in email template', [
            'type' => $type,
            'json_error' => json_last_error_msg(), // Logs the specific JSON error
            'shortcode_raw' => $emailTemplate->shortcodes, // Logs the raw data
        ]);

        return [];
    }

    $dynamicValues = [
        'sender_name' => $sender->username ?? '',
        'receiver_name' => $receiver->username ?? '',
        'date' => Carbon::now()->format('Y-m-d H:i:s'),
        'id' => encrypt($receiver->id),
    ];
    $params = [];
    foreach ($shortcodes as $key => $description) {
        if (strpos($description, '{{') !== false && strpos($description, '}}') !== false) {
            $params[$key] = preg_replace_callback('/\{\{(\w+)\}\}/', function ($matches) use ($dynamicValues) {
                return $dynamicValues[$matches[1]] ?? '';
            }, $description);
        } else {
            if ($key == 'click_url') {
                $params[$key] = $description;
            } else {
                $params[$key] = $dynamicValues[$key] ?? '';
            }
        }
    }
    $click_url = $params['click_url'] ?? null;
    $message = $emailTemplate->notification_body;
    
    foreach ($params as $code => $value) {
        $message = shortCodeReplacer('{{'.$code.'}}', $value, $message);
    }
    Log::info('Dispatching Notification', [
        'message' => $message,
        'senderId' => $senderId,
        'click_url' => $click_url,
    ]);
    $receiver->notify(new RealTimeNotification($message, $senderId, $click_url));

    dispatch(new SendEmailJob($emailTemplate, $receiver, $type, $params));
}

function getMembershipFee($userId)
{
    if (!$userId) {
        return -1;
    }

    $user = User::where(['id' => $userId])->first();

    if (!$user) {
        return -1;
    }

    if ($user->is_freelancer == 1) {
        $usermembership = Freelancermembershiptransactions::join('freelancer_membership', 'freelancer_membership_transactions.membership_id', '=', 'freelancer_membership.id')->where('user_id', $user->id)->get();
        return $usermembership[0]->fee;
    }

    if ($user->is_employer == 1) {
        $usermembership1 = Employermembershiptransactions::join('employer_membership', 'employer_membership_transactions.membership_id', '=', 'employer_membership.id')->where('user_id', $user->id)->get();
        return $usermembership1[0]->fee;
    }

    return 0;
}

function sendLowBalanceEmail($tmplt, $user, $type = null, $shortCodes = [])
{
    $emailTemplate = EmailTemplate::where('act', $tmplt)->first();
    $general = GeneralSetting::first();
    if (! $general) {
        \Log::error('General settings not found. Email cannot be sent.', ['type' => $type]);

        return;
    }
    // Replacing shortcodes
    // $message = shortCodeReplacer("{{fullname}}", $user->fullname, $general->email_template);
    $message = shortCodeReplacer('{{username}}', $user->username, $general->email_template);
    $message = shortCodeReplacer('{{message}}', $emailTemplate->email_body, $message);

    if (empty($message)) {
        \Log::warning('Message body is empty after replacing shortcodes.', ['template_id' => $emailTemplate->id]);
        $message = $emailTemplate->email_body;
    }
    \Log::info('short.', ['shortCodes' => $shortCodes]);
    // Replace additional shortcodes
    foreach ($shortCodes as $code => $value) {

        if ($code == 'click_url') {
            $message = shortCodeReplacer('{{'.$code.'}}', url($value), $message);
        } else {
            $message = shortCodeReplacer('{{'.$code.'}}', $value, $message);
        }
    }

    $config = $general->mail_config;

    // Create email log entry
    $emailLog = new EmailLog;
    $emailLog->user_id = $user->id;
    $emailLog->mail_sender = $config->name;
    $emailLog->email_from = $general->sitename.' '.$general->email_from;
    $emailLog->email_to = $user->email;
    $emailLog->subject = $emailTemplate->subj;
    $emailLog->message = $message;
    $emailLog->save();

    \Log::info('Email log entry created.', ['email_log_id' => $emailLog->id]);

    // Sending email based on configuration
    try {
        if ($config->name == 'php') {
            \Log::info('Using PHP mailer to send email.', ['user_id' => $user->id]);
            sendPhpMail($user->email, $user->username, $general->email_from, $emailTemplate->subj, $message, $general);
        } elseif ($config->name == 'smtp') {
            \Log::info('Using SMTP to send email.', ['user_id' => $user->id]);
            sendSmtpMail($config, $user->email, $user->username, $emailTemplate->subj, $message, $general);
        } elseif ($config->name == 'sendgrid') {
            \Log::info('Using SendGrid to send email.', ['user_id' => $user->id]);
            sendSendGridMail($config, $user->email, $user->username, $emailTemplate->subj, $message, $general);
        } elseif ($config->name == 'mailjet') {
            \Log::info('Using Mailjet to send email.', ['user_id' => $user->id]);
            sendMailjetMail($config, $user->email, $user->username, $emailTemplate->subj, $message, $general);
        }
        \Log::info('Email sent successfully.', ['user_id' => $user->id, 'email' => $user->email]);
    } catch (\Exception $e) {
        \Log::error('Failed to send email.', [
            'user_id' => $user->id,
            'email' => $user->email,
            'error_message' => $e->getMessage(),
        ]);
    }
}



if (!function_exists('currency_from_country')) {

    function currency_from_country($country)
    {
        $map = [
            'Afghanistan' => 'AFN',
            'Albania' => 'ALL',
            'Algeria' => 'DZD',
            'Andorra' => 'EUR',
            'Angola' => 'AOA',
            'Antigua and Barbuda' => 'XCD',
            'Argentina' => 'ARS',
            'Armenia' => 'AMD',
            'Australia' => 'AUD',
            'Austria' => 'EUR',
            'Azerbaijan' => 'AZN',
            'Bahamas' => 'BSD',
            'Bahrain' => 'BHD',
            'Bangladesh' => 'BDT',
            'Barbados' => 'BBD',
            'Belarus' => 'BYN',
            'Belgium' => 'EUR',
            'Belize' => 'BZD',
            'Benin' => 'XOF',
            'Bhutan' => 'BTN',
            'Bolivia' => 'BOB',
            'Bosnia and Herzegovina' => 'BAM',
            'Botswana' => 'BWP',
            'Brazil' => 'BRL',
            'Brunei' => 'BND',
            'Bulgaria' => 'BGN',
            'Burkina Faso' => 'XOF',
            'Burundi' => 'BIF',
            'Cabo Verde' => 'CVE',
            'Cambodia' => 'KHR',
            'Cameroon' => 'XAF',
            'Canada' => 'CAD',
            'Central African Republic' => 'XAF',
            'Chad' => 'XAF',
            'Chile' => 'CLP',
            'China' => 'CNY',
            'Colombia' => 'COP',
            'Comoros' => 'KMF',
            'Congo' => 'XAF',
            'Costa Rica' => 'CRC',
            'Croatia' => 'EUR',
            'Cuba' => 'CUP',
            'Cyprus' => 'EUR',
            'Czech Republic' => 'CZK',
            'Czechia' => 'CZK',
            'Democratic Republic of the Congo' => 'CDF',
            'Denmark' => 'DKK',
            'Djibouti' => 'DJF',
            'Dominica' => 'XCD',
            'Dominican Republic' => 'DOP',
            'East Timor' => 'USD',
            'Ecuador' => 'USD',
            'Egypt' => 'EGP',
            'El Salvador' => 'USD',
            'Equatorial Guinea' => 'XAF',
            'Eritrea' => 'ERN',
            'Estonia' => 'EUR',
            'Eswatini' => 'SZL',
            'Ethiopia' => 'ETB',
            'Fiji' => 'FJD',
            'Finland' => 'EUR',
            'France' => 'EUR',
            'Gabon' => 'XAF',
            'Gambia' => 'GMD',
            'Georgia' => 'GEL',
            'Germany' => 'EUR',
            'Ghana' => 'GHS',
            'Greece' => 'EUR',
            'Grenada' => 'XCD',
            'Guatemala' => 'GTQ',
            'Guinea' => 'GNF',
            'Guinea-Bissau' => 'XOF',
            'Guyana' => 'GYD',
            'Haiti' => 'HTG',
            'Honduras' => 'HNL',
            'Hungary' => 'HUF',
            'Iceland' => 'ISK',
            'India' => 'INR',
            'Indonesia' => 'IDR',
            'Iran' => 'IRR',
            'Iraq' => 'IQD',
            'Ireland' => 'EUR',
            'Israel' => 'ILS',
            'Italy' => 'EUR',
            'Ivory Coast' => 'XOF',
            'Jamaica' => 'JMD',
            'Japan' => 'JPY',
            'Jordan' => 'JOD',
            'Kazakhstan' => 'KZT',
            'Kenya' => 'KES',
            'Kiribati' => 'AUD',
            'Kosovo' => 'EUR',
            'Kuwait' => 'KWD',
            'Kyrgyzstan' => 'KGS',
            'Laos' => 'LAK',
            'Latvia' => 'EUR',
            'Lebanon' => 'LBP',
            'Lesotho' => 'LSL',
            'Liberia' => 'LRD',
            'Libya' => 'LYD',
            'Liechtenstein' => 'CHF',
            'Lithuania' => 'EUR',
            'Luxembourg' => 'EUR',
            'Madagascar' => 'MGA',
            'Malawi' => 'MWK',
            'Malaysia' => 'MYR',
            'Maldives' => 'MVR',
            'Mali' => 'XOF',
            'Malta' => 'EUR',
            'Marshall Islands' => 'USD',
            'Mauritania' => 'MRU',
            'Mauritius' => 'MUR',
            'Mexico' => 'MXN',
            'Micronesia' => 'USD',
            'Moldova' => 'MDL',
            'Monaco' => 'EUR',
            'Mongolia' => 'MNT',
            'Montenegro' => 'EUR',
            'Morocco' => 'MAD',
            'Mozambique' => 'MZN',
            'Myanmar' => 'MMK',
            'Namibia' => 'NAD',
            'Nauru' => 'AUD',
            'Nepal' => 'NPR',
            'Netherlands' => 'EUR',
            'New Zealand' => 'NZD',
            'Nicaragua' => 'NIO',
            'Niger' => 'XOF',
            'Nigeria' => 'NGN',
            'North Korea' => 'KPW',
            'North Macedonia' => 'MKD',
            'Norway' => 'NOK',
            'Oman' => 'OMR',
            'Pakistan' => 'PKR',
            'Palau' => 'USD',
            'Palestine' => 'ILS',
            'Panama' => 'PAB',
            'Papua New Guinea' => 'PGK',
            'Paraguay' => 'PYG',
            'Peru' => 'PEN',
            'Philippines' => 'PHP',
            'Poland' => 'PLN',
            'Portugal' => 'EUR',
            'Qatar' => 'QAR',
            'Romania' => 'RON',
            'Russia' => 'RUB',
            'Rwanda' => 'RWF',
            'Saint Kitts and Nevis' => 'XCD',
            'Saint Lucia' => 'XCD',
            'Saint Vincent and the Grenadines' => 'XCD',
            'Samoa' => 'WST',
            'San Marino' => 'EUR',
            'Sao Tome and Principe' => 'STN',
            'Saudi Arabia' => 'SAR',
            'Senegal' => 'XOF',
            'Serbia' => 'RSD',
            'Seychelles' => 'SCR',
            'Sierra Leone' => 'SLL',
            'Singapore' => 'SGD',
            'Slovakia' => 'EUR',
            'Slovenia' => 'EUR',
            'Solomon Islands' => 'SBD',
            'Somalia' => 'SOS',
            'South Africa' => 'ZAR',
            'South Korea' => 'KRW',
            'South Sudan' => 'SSP',
            'Spain' => 'EUR',
            'Sri Lanka' => 'LKR',
            'Sudan' => 'SDG',
            'Suriname' => 'SRD',
            'Sweden' => 'SEK',
            'Switzerland' => 'CHF',
            'Syria' => 'SYP',
            'Taiwan' => 'TWD',
            'Tajikistan' => 'TJS',
            'Tanzania' => 'TZS',
            'Thailand' => 'THB',
            'Timor-Leste' => 'USD',
            'Togo' => 'XOF',
            'Tonga' => 'TOP',
            'Trinidad and Tobago' => 'TTD',
            'Tunisia' => 'TND',
            'Turkey' => 'TRY',
            'Turkmenistan' => 'TMT',
            'Tuvalu' => 'AUD',
            'Uganda' => 'UGX',
            'Ukraine' => 'UAH',
            'United Arab Emirates' => 'AED',
            'United Kingdom' => 'GBP',
            'United States' => 'USD',
            'Uruguay' => 'UYU',
            'Uzbekistan' => 'UZS',
            'Vanuatu' => 'VUV',
            'Vatican City' => 'EUR',
            'Venezuela' => 'VES',
            'Vietnam' => 'VND',
            'Yemen' => 'YER',
            'Zambia' => 'ZMW',
            'Zimbabwe' => 'ZWL',
        ];
        return $map[$country] ?? 'USD';
    }
}

if (!function_exists('currencyBasedOnCountry')) {
    function currencyBasedOnCountry($user)
    {
        try {
            $country = $user?->latestLastLogin?->country ?? $user->country ?? null;
            
            $currency = currency_from_country($country);

            $balance = (float) ($user->balance ?? 0);

            if ($balance <= 0) {
                return [
                    'success' => true,
                    'currency' => $currency,
                    'original_balance' => 0,
                    'amount' => 0,
                    'formatted' => $currency . ' 0.00',
                ];
            }

            $response = Http::timeout(10)->get(
                'https://api.exchangerate.host/convert',
                [
                    'access_key' => config('services.exchangerate.key'),
                    'from'       => 'USD',
                    'to'         => $currency,
                    'amount'     => $balance,
                ]
            );

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'currency' => 'USD',
                    'original_balance' => $balance,
                    'amount' => $balance,
                    'formatted' => 'USD ' . number_format($balance, 2),
                ];
            }

            $data = $response->json();

            $converted = round(
                (float) ($data['result'] ?? 0),
                2
            );

            return [
                'success' => true,
                'currency' => $currency,
                'original_balance' => $balance,
                'amount' => $converted,
                'formatted' => $currency . ' ' . number_format($converted, 2),
            ];

        } catch (\Throwable $e) {
            $balance = isset($balance) ? $balance : (float) (($user->balance ?? 0));

            return [
                'success' => false,
                'currency' => 'USD',
                'original_balance' => $balance,
                'amount' => $balance,
                'formatted' => 'USD ' . number_format($balance, 2),
            ];
        }
    }
}