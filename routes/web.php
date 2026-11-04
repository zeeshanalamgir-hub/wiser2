<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Buyer\HireEmployees;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Gateway\Stripe\ProcessController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\SearchCategoriesController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\ZoomSessionController;
use App\Http\Controllers\ZoomWebhookController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('download-auto-tracker', function () {
    $userAgent = request()->header('User-Agent');

    if (stripos($userAgent, 'Macintosh') !== false) {
        // If user is on Mac, download Mac app
        $downloadUrl = env('MAC_AUTO_TRACKER_DOWNLOAD_URL', 'https://titanlance.com/Wiserlance-0.0.0-arm64.dmg');
    } else {
        // Default: Windows app download
        $downloadUrl = env('AUTO_TRACKER_DOWNLOAD_URL', 'https://titanlance.com/Wiserlance-0.0.0.exe');
    }

    return redirect($downloadUrl);
})->name('download-auto-tracker');


Route::get('/check-app-installed', [SiteController::class, 'checkAppInstalled'])->name('check-app-installed');
// Route::get('/inbox', [MessagesController::class, 'index'])->name('user.messenger.index');
// Route::get('/inbox/{id}', [MessagesController::class, 'index'])->name('messenger.single.index');
Route::get('all-notifications', 'SiteController@allnotifications')->name('all-notifications')->middleware('auth');
Route::post('/notifications/mark-as-read', [SiteController::class, 'markNotificationAsRead']);

Route::get("/email", 'PHPMailerController@email');
Route::post("send-email", 'PHPMailerController@composeEmail')->name("send-email");
Route::get('generate-invoice-pdf/{id}', array('as' => 'generate.invoice.pdf', 'uses' => 'SiteController@generateInvoicePDF'));
Route::get('generate-invoice-pdf-offer/{id}', array('as' => 'generate.invoice.pdf', 'uses' => 'SiteController@generateInvoicePDFoffer'));
Route::get('generate-invoice-pdftwo/{id}', array('as' => 'generate.invoice.pdf', 'uses' => 'SiteController@generateInvoicePDFtwo'));
Route::get('generate-invoice-pdf-offertwo/{id}', array('as' => 'generate.invoice.pdf', 'uses' => 'SiteController@generateInvoicePDFoffertwo'));
// service invoice
Route::get('generate-invoice-pdf-seller-service/{id}', array('as' => 'generate.invoice.pdf', 'uses' => 'SiteController@generateInvoicePDFserviceseller'));
Route::get('generate-invoice-pdf-buyer-service/{id}', array('as' => 'generate.invoice.pdf', 'uses' => 'SiteController@generateInvoicePDFservicebuyer'));
Route::post('updatenoti', 'SiteController@updallnotifications')->name('updatenoti');
Route::get('booking/service/cron', 'CronController@service')->name('service.cron');
Route::get('job/hire/cron', 'CronController@job')->name('job.cron');
Route::get('membership/empexpiry', 'CronController@checkemployermembership')->name('membership.expiry');
Route::get('membership/lancerexpiry', 'CronController@checkfreelancermembership')->name('membership.expiry');


Route::
        namespace('Gateway')->prefix('ipn')->name('ipn.')->group(function () {
            require_once __DIR__ . '/groups/gateway-ipn.php';
        });

// User Support Ticket
Route::prefix('ticket')->group(function () {
    require_once __DIR__ . '/groups/ticket.php';
});



/*
|--------------------------------------------------------------------------
| Start Admin Area
|--------------------------------------------------------------------------
*/

Route::
        namespace('Admin')->prefix('admin')->name('admin.')->group(function () {
            require_once __DIR__ . '/groups/admin-admin.php';
        });

/*
|--------------------------------------------------------------------------
| Start User Area
|--------------------------------------------------------------------------
*/


Route::get('auth/facebook', 'FaceBookController@redirectToFacebook');
Route::get('auth/facebook/callback', 'FaceBookController@handleFacebookCallback');
Route::get('auth/facebook/facebookdatadelete/{usercode}', 'FaceBookController@dataDelRequests');

Route::get('auth/google', 'GoogleController@redirectToGoogle');
Route::get('auth/google/callback', 'GoogleController@handleGoogleCallback');


Route::name('user.')->group(function () {
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'Auth\LoginController@login');
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('signup', 'Auth\RegisterController@showSignUpForm')->name('signupform');
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register')->middleware('regStatus');
    Route::post('check-mail', 'Auth\RegisterController@checkUser')->name('checkUser');

    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetCodeEmail')->name('password.email');
    Route::get('password/code-verify', 'Auth\ForgotPasswordController@codeVerify')->name('password.code.verify');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/verify-code', 'Auth\ForgotPasswordController@verifyCode')->name('password.verify.code');
});

Route::name('user.')->prefix('user')->group(function () {
    require_once __DIR__ . '/groups/auth-user.php';
    require __DIR__ . '/services.php';

});

Route::get('/changeCurrency/{currency?}', 'SiteController@changeCurrency')->name('currency.change');
Route::get('/handleCurrency', 'SiteController@handle');
Route::prefix('profile')->middleware(['auth'])->group(function () {
    Route::post('/personal-info', [SiteController::class, 'updatePersonalInfo'])->name('profile.personal_info');
    Route::post('/education', [SiteController::class, 'updateEducation'])->name('profile.education');
    Route::post('/certifications', [SiteController::class, 'updateCertifications'])->name('profile.certifications');
    Route::post('/languages', [SiteController::class, 'updateLanguages'])->name('profile.languages');
    Route::post('/occupation', [SiteController::class, 'updateOccupation'])->name('profile.occupation');
    Route::post('/skills', [SiteController::class, 'updateSkills'])->name('profile.skills');
    Route::post('/portfolio', [SiteController::class, 'updatePortfolio'])->name('profile.portfolio');
    Route::post('/notifications', [SiteController::class, 'toggleNotificationStatus'])->name('profile.notifications');
    Route::delete('/education/delete/{id}', [SiteController::class, 'destroyEducation'])->name('education.delete');
    Route::delete('/certificate/delete/{id}', [SiteController::class, 'destroyCertificate'])->name('certificate.delete');
    Route::delete('/language/delete/{id}', [SiteController::class, 'destroyLanguage'])->name('language.delete');
    Route::delete('/portfolio/delete/{id}', [SiteController::class, 'destroyPortfolio'])->name('portfolio.delete');
});
Route::prefix('workspace/zoom')->middleware(['auth'])->group(function () {
    Route::post('/{booking}/ring', [ZoomSessionController::class, 'ring'])->name('workspace.zoom.ring');
    Route::post('/{booking}/decline', [ZoomSessionController::class, 'decline'])->name('workspace.zoom.decline');
    Route::post('/{booking}/cancel', [ZoomSessionController::class, 'cancel'])->name('workspace.zoom.cancel');
    Route::post('/{booking}/ringing', [ZoomSessionController::class, 'ringing'])->name('workspace.zoom.ringing');
    Route::post('/{booking}/missed', [ZoomSessionController::class, 'missed'])->name('workspace.zoom.missed');
    Route::post('/{booking}/start', [ZoomSessionController::class, 'start'])->name('workspace.zoom.start');
    Route::post('/session/{session}/end', [ZoomSessionController::class, 'end'])->name('workspace.zoom.end');
    Route::post('/session/{session}/sync', [ZoomSessionController::class, 'sync'])->name('workspace.zoom.sync');
    Route::get('/recordings/{recording}/download', [ZoomSessionController::class, 'downloadRecording'])->name('workspace.zoom.recording.download');
});
Route::post('zoom/webhook', [ZoomWebhookController::class, 'handle'])->name('zoom.webhook');

Route::post('/selling_profile', 'SiteController@selling_profile');
Route::post('/buying_profile', 'SiteController@buying_profile');
Route::post('/buyer-profile-update/{id}', 'SiteController@buying_profile_update');
Route::post('/buyer-personal-info/{id}', 'SiteController@updateBuyerPersonalInfo');
Route::post('/buyer-business-info', 'SiteController@updateBuyerBusinessInfo');
Route::post('/buyer-business-contact', 'SiteController@updateBuyerBusinessContact');
Route::post('/buyer-notification-settings', 'SiteController@buyerToggleNotificationStatus')->name('buyer.notification.settings');
Route::post('/selling_profile_update/{id}', 'SiteController@selling_profile_update');
Route::get('/contact', 'SiteController@contact')->name('contact');
Route::post('/contact', 'SiteController@contactSubmit');
Route::get('/change/{lang?}', 'SiteController@changeLanguage')->name('lang');
Route::get('/cookie/accept', 'SiteController@cookieAccept')->name('cookie.accept');
Route::get('blog', 'SiteController@blog')->name('blog');
Route::get('blog/{id}/{slug}', 'SiteController@blogDetails')->name('blog.details');
Route::get('placeholder-image/{size}', 'SiteController@placeholderImage')->name('placeholder.image');

Route::get('/search', 'SearchController@search');
Route::post('/filterjobs', 'SearchController@filterjobs');
Route::post('/filter_services', 'SearchController@filterservices');
Route::post('/filter_services2', 'SearchController@filterservice2');
Route::post('/filter_pros', 'SearchController@filterpros');
Route::post('/search_other_langs', 'SearchController@search_other_langs');
Route::post('/get_jobbid_detail', 'SearchController@getbidjobdetail');
Route::post('/countfee_multi_bids', 'SearchController@countfee_multi_bids');

//Service
Route::get('/', 'SiteController@index')->name('home');
Route::get('/service/categories/{slug}/{id}', 'SiteController@servicecategoriesall')->name('servicecategories');
Route::get('/service/subcategories/{slug}/{id}', 'SiteController@servicesubcategoriesallservices')->name('servicesubcategories.services');
Route::get('/service/childcategories/{slug}/{id}', 'SiteController@servicechildcategoriesallservices')->name('servicechildcategories.services');
Route::get('/allcategories', 'SiteController@allcategories')->name('allcategories');


Route::get('/category/services/{id}', 'SiteController@categoryservice')->name('category.services');
Route::get('/subcategory/services/{id}', 'SiteController@subcategoryservice')->name('subcategory.services');
Route::get('/search/item/filter', 'FilterController@allServiceSearch')->name('home.search.item');
Route::get('/service/search', 'FilterController@serviceSearch')->name('service.search');
Route::get('/service/filter', 'FilterController@serviceDefault')->name('service.filter');
Route::get('/service/category/{slug}/{id}', 'FilterController@serviceCategory')->name('service.category');
Route::get('/service/sub/category/{slug}/{id}', 'FilterController@serviceSubCategory')->name('service.sub.category');

//software
Route::get('/software', 'SiteController@software')->name('software');
Route::get('/software/details/{slug}/{id}', 'SiteController@softwareDetails')->name('software.details');
Route::get('/software/search/filter', 'FilterController@softwareItemSearch')->name('software.search.filter');
Route::get('/software/category/{slug}/{id}', 'FilterController@softwareCategory')->name('software.category');
Route::get('/software/sub/category/{slug}/{id}', 'FilterController@softwareSubCategory')->name('software.sub.category');
Route::get('/software/search/', 'FilterController@softwareSearch')->name('software.search');

//filtercats
Route::get('/ajax/job/caegories', [SearchCategoriesController::class, 'ajaxJobCategories']);
Route::get('/ajax/job/subcaegories/{categoryId}', [SearchCategoriesController::class, 'ajaxJobSubCategories']);
Route::get('/ajax/job/subcaegory-skills/{subcategoryId}', [SearchCategoriesController::class, 'ajaxJobSubCategorySkills']);
Route::get('/ajax/job/skills-by-category-id/{categoryId}', [SearchCategoriesController::class, 'ajaxJobSubCategoryGetskills']);
Route::get('/ajax/job/skills-by-search', [SearchCategoriesController::class, 'ajaxJobSkillsSearch']);
Route::get('/ajax/job/get-country-states', [SearchCategoriesController::class, 'getCountryStates'])->name('get.country.states');
Route::get('/ajax/job/get-country-cities', [SearchCategoriesController::class, 'getCountryCities'])->name('get.country.cities');

Route::post('/filters_left_menu', 'SearchCategoriesController@filters_left_menu');
Route::post('/filterpro_subcats', 'SearchCategoriesController@filtersubs');
Route::post('/filterpro_childcats', 'SearchCategoriesController@filterchild');
Route::post('/filterpro_skills', 'SearchCategoriesController@filterskills');

//job
Route::get('/job/{cat_id?}', 'SiteController@job')->name('job');
Route::prefix('job/list/ajax')->group(function () {
    Route::post('/line-items', 'SiteController@jobListAjax');
});

Route::get('/job/categories/{slug}/{id}', 'SiteController@jobcategoriesall')->name('jobcategories');
Route::get('/job/subcategories/{slug}/{id}', 'SiteController@jobsubcategoriesalljobs')->name('jobsubcategories.jobs');
Route::get('/professionals', 'SiteController@professionals')->name('professionals');
Route::get('/hire/professional/{id}', 'SiteController@hireprofessional')->name('professioan.hire');
Route::get('/invite-freelancers/{id}', 'SiteController@inviteFreelancers')->name('invite.freelancers');
Route::get('/freelancer/skills/{id}', 'SiteController@getFreelancerSkills')->name('freelancer.skills');
Route::get('/hire/professional/edit/{id}', 'SiteController@hireprofessionaledit')->name('professioan.hire.edit');
Route::post('job/addofferfixedupdate/milestone/{id}', 'SiteController@addofferfixedupdate');
Route::post('job/addofferhourlyupdate/{id}', 'SiteController@addofferhourlyupdate');
Route::post('/selectedjob/{id}', 'SiteController@selectedjob');
Route::get('/job/details/{id}', 'SiteController@jobDetails')->name('job.details');
Route::get('/job/filter/search/', 'FilterController@jobItemSearch')->name('job.filter.search');
Route::get('/job/category/{slug}/{id}', 'FilterController@jobCategory')->name('job.category');
Route::get('/job/sub/category/{slug}/{id}', 'FilterController@jobSubCategory')->name('job.sub.category');
Route::get('/job/search', 'FilterController@jobSearch')->name('job.search');

Route::post('job/addbidinghourly/{encryptedId}', 'SiteController@addbidinghourly')->name('job.addbidinghourly');
//Route::post('job/addofferfixed', 'SiteController@addofferfixed')->name('job.addofferfixed');
Route::post('job/invite-professional', [HireEmployees::class, 'inviteProfessional'])->name('job.invite-professional');
//jobbids
Route::get('/job/bidding/{id}', 'SiteController@jobBiddingfixed')->name('job.bid.fixed');
Route::get('/job/biddings/{id}', 'SiteController@jobBiddinghourly')->name('job.bid.hourly');
Route::get('/membership/fee/{id}', 'SiteController@getMembership')->name('get.membership');
Route::get('/job/offer-detail/{id}', 'SiteController@OfferDetail')->name('job.offer.detail');
// Route::get('/job/revisebiddingproject/{id1}/{id2}', 'SiteController@jobreviseBiddingproject')->name('job.revisebid.project');
Route::get('/job/revisebiddinghourly/{id1}/{id2}', 'SiteController@jobreviseBiddinghourly')->name('job.revisebid.hourly');
Route::get('/job/revisebiddingmilestone/{id1}/{id2}', 'SiteController@jobreviseBiddingmilestone')->name('job.revisebid.milestone');
Route::post('job/revisebiding/project/{id}', 'SiteController@submitrevisebidingproject')->name('job.revisebiding.project');
Route::post('/job/revisebiding/milestone/{id}', 'SiteController@submitrevisebidingmilestone')->name('job.revisebiding.milestone');
Route::post('/job/revisebiding/hourly/{id}', 'SiteController@submitrevisebidinghourly')->name('job.revisebiding.hourly');

Route::post('/update-persona', 'SiteController@updatePersona')->name('persona');


Route::get('/user/{username}/{id}', 'SiteController@userProfile')->name('profile');
Route::get('/buyer/{username}/{id}', 'SiteController@buyeruserProfile')->name('buyerprofile');
Route::get('/user/service/{username}', 'SiteController@userService')->name('profile.service');
Route::get('/user/software/{username}', 'SiteController@userSoftware')->name('profile.software');
Route::get('/user/job/{username}', 'SiteController@userJob')->name('profile.job');
Route::get('/add/{id}', 'SiteController@adclicked')->name('add.clicked');
//get unread message route
Route::get('/getunreadmessage', 'SiteController@getunreadmessagecount')->name('getunreadmessage');

//Job Filter Search
// tiers
Route::post('/Search_tiers', 'ServiceTiersController@filtertiers');

Route::post('/subscribe', 'SiteController@subscribe')->name('subscribe');
Route::get('{slug}/{id}', 'SiteController@footerMenu')->name('footer.menu');


////start  chat route
Route::get('/chat-secreen/{sid}/{rid}', 'ChatController@index')->name('chat-secreen');
Route::post('/chat-store', 'ChatController@store')->name('chat-store');
Route::get('/get_chat/{id}/{rid}', 'ChatController@show')->name('chat-list-by-reciver');
Route::get('/get_chatlist/{id}', 'ChatController@getchat')->name('chat-list');
Route::get('/chat_read/{id}/{rid}', 'ChatController@update')->name('chat-readed');
Route::get('/is_online/{id}/{status}', 'ChatController@is_online')->name('chat-online');

//// end chat route
Route::any('/deleteproductimage', 'SiteController@deledu');
Route::any('/deletecer', 'SiteController@delcer');
Route::any('/deleteport', 'SiteController@delport');

Route::get('create-stripe-account', [ProcessController::class, 'createStripeAccount']);
Route::get('retrieve-stripe-account', [ProcessController::class, 'retrieveStripeAccount']);
// Route::get('checkout',[ProcessController::class,'checkoutScreen']);
Route::get('create-price', [ProcessController::class, 'createPrice']);
Route::get('retrieveAccount', [ProcessController::class, 'retrieveAccount']);
Route::get('payout', [ProcessController::class, 'payout']);
Route::get('balance', [ProcessController::class, 'balance']);
Route::get('create-charge', [ProcessController::class, 'createCharge']);
Route::get('payment-intent', [ProcessController::class, 'createPaymentIntent']);
Route::get('charges', [ProcessController::class, 'charges']);
Route::get('transfer', [ProcessController::class, 'transfer']);
Route::get('account-session', [ProcessController::class, 'accountSession']);
Route::get('load-onboarding', [ProcessController::class, 'loadOnBoarding']);
Route::post('/account_session', [ProcessController::class, 'accountSessionResponse']);
Route::get('onBoarding', [ProcessController::class, 'onBoarding']);
Route::get('acc-capabilities', [ProcessController::class, 'accCapabilities']);
Route::get('update-account', [ProcessController::class, 'updateAccount']);
Route::get('update-capabilities', [ProcessController::class, 'updateCapabilities']);
// Route::post('make-payment',[ProcessController::class,'makePayment'])->name('make.payment');

Route::post('/user/get-fresh-onboarding-link', [SiteController::class, 'getFreshOnboardingLink'])
    ->name('user.get.fresh.onboarding.link');

Route::post('/user/get-buyer-fresh-onboarding-link', [SiteController::class, 'getBuyerFreshOnboardingLink'])
    ->name('user.get.buyer.fresh.onboarding.link');

Route::get('check-stripe-onboarding-status', [ProcessController::class, 'checkOnboardingStatus']);

Route::get('unread-notifications', [AdminController::class, 'unread'])->name('admin.notifications.unread');

Route::controller(HomePageController::class)->group(function () {
    Route::get('/about-us', 'aboutUs')->name('about.us');
    Route::get('/why-choose-us', 'whyChooseUs')->name('why.choose.us');
    Route::get('/how-our-platform-works', 'howOurPlatformWorks')->name('how.our.platform.works');
    Route::get('/tips-for-success-seller', 'tipsForSuccessSeller')->name('tips.for.success.seller');
    Route::get('/tips-for-success-buyer', 'tipsForSuccessBuyer')->name('tips.for.success.buyer');
    Route::get('/help-support-center', 'helpSupportCenter')->name('help.support.center');
    Route::get('/project-rules', 'privacy')->name('privacy');
    Route::get('/terms-services', 'terms')->name('terms');
    Route::get('/contact-us', 'contactUs')->name('contact.us');
    Route::get('/authentication-registration', 'authReg')->name('auth.reg');
    Route::get('/become-wiserlance-buyer', 'becomeBuyer')->name('become.buyer');
    Route::get('/working-professional', 'workingProfessional')->name('working.professional');
    Route::get('/billing-payment', 'billingPayment')->name('billing.payment');
    Route::get('/policies', 'policies')->name('policies');
    Route::get('/profile-settings', 'profileSetting')->name('profile.setting');
    Route::get('/authentication-registration-professional', 'authRegProf')->name('auth.reg.prof');
    Route::get('/membership-pricing', 'memberships')->name('memberships.pricing');
});

Route::get('help/center/details/{slug}',[HomePageController::class,'helpCenterDetails'])->name('help.details');

// Contact page routes
Route::post('/contact/submit', [ContactController::class, 'submit'])->name('contact.submit');


Route::post('/convert-balance', [BalanceController::class, 'convert']);

//Webhook URL
Route::post('zoom/webhook', [ZoomWebhookController::class, 'handle'])->name('zoom.webhook');

Route::get('/clear-cache', function () {

    try {
        Artisan::call('optimize:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('event:clear');

        $exitCode = Artisan::call('migrate', [
            '--force' => true,
        ]);

        return response()->json([
            'success' => $exitCode === 0,
            'exit_code' => $exitCode,
            'output' => Artisan::output(),
            'database' => config('database.connections.mysql.database'),
            'environment' => app()->environment(),
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500);
    }
});

