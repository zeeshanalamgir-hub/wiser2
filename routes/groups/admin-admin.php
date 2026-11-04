<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\HelpCenterController;
use Illuminate\Support\Facades\Route;
    use Illuminate\Http\Request;
    use Pusher\Pusher;
Route::namespace('Auth')->group(function () {
    Route::get('/', 'LoginController@showLoginForm')->name('login');
    Route::post('/', 'LoginController@login')->name('login');
    Route::get('logout', 'LoginController@logout')->name('logout');
    // Admin Password Reset
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
    Route::post('password/reset', 'ForgotPasswordController@sendResetCodeEmail');
    Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify.code');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset.form');
    Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');

});
Route::get('read-notifications', 'AdminController@readAll')->name('admin.notifications.read');

Route::middleware('admin')->group(function () {

    Route::prefix('help-center/{type}')->group(function () {
        Route::get('/', [HelpCenterController::class, 'index'])->name('help-center.index');
        Route::get('/create', [HelpCenterController::class, 'create'])->name('help-center.create');
        Route::post('/', [HelpCenterController::class, 'store'])->name('help-center.store');
        Route::get('/{id}/edit', [HelpCenterController::class, 'edit'])->name('help-center.edit');
        Route::put('/{id}', [HelpCenterController::class, 'update'])->name('help-center.update');
        Route::delete('/{id}', [HelpCenterController::class, 'destroy'])->name('help-center.destroy');
    });

    //membership
    Route::group(['prefix' => 'membership'], function () {
        //freelancer and employer

        Route::get('employer', 'EmployermembershipController@index')->name('employer');
        Route::get('addfreelancer', 'FreelancermembershipController@addfreelancer')->name('addfreelancer');
        Route::get('addemployer', 'EmployermembershipController@add')->name('addemployer');
        Route::get('addredirect', 'FreelancermembershipController@redirect')->name('addredirect');
        Route::post('addredirect', 'FreelancermembershipController@addredirect')->name('addredirect');
        Route::post('store', 'FreelancermembershipController@store')->name('store');
        Route::post('storeemp', 'EmployermembershipController@store')->name('store');
        Route::post('update', 'FreelancermembershipController@update')->name('update');
        Route::post('updateemp', 'EmployermembershipController@update')->name('updateemp');
        Route::get('package-plan', 'FreelancermembershipController@getPackagePlan')->name('package.plan');
        Route::post('create-package', 'FreelancermembershipController@createPackage')->name('package.create');
        Route::get('package', 'FreelancermembershipController@package')->name('package');
        Route::post('addpackage', 'FreelancermembershipController@addPackage')->name('addpackage');
        Route::post('freelancer/package', 'FreelancermembershipController@getFreelancerPackage')->name('freelancer.post.package');
        Route::get('freelancer/package', 'FreelancermembershipController@getFreelancerPackage')->name('freelancer.package');
    });
    //Pakages
    Route::group(['prefix' => 'package'], function () {
        //bids
        Route::get('bidpakages', 'BidpakagesController@index')->name('bidpakages');
        Route::get('addbidpakage', 'BidpakagesController@add')->name('addbidpakage');
        Route::post('addbidpakage', 'BidpakagesController@store')->name('addbidpakage');
        Route::post('updatebidpakage', 'BidpakagesController@update')->name('updatebidpakage');
        //jobs
        Route::get('jobpakages', 'JobpakagesController@index')->name('jobpakages');
        Route::get('addjobpakage', 'JobpakagesController@add')->name('addjobpakage');
        Route::post('addjobpakage', 'JobpakagesController@store')->name('addjobpakage');
        Route::post('updatejobpakage', 'JobpakagesController@update')->name('updatejobpakage');
    });

    Route::get('profile', 'AdminController@profile')->name('profile');
    Route::post('profile', 'AdminController@profileUpdate')->name('profile.update');
    Route::get('password', 'AdminController@password')->name('password');
    Route::post('password', 'AdminController@passwordUpdate')->name('password.update');

    Route::middleware('staffaccess:1')->group(function () {
        Route::get('dashboard', 'AdminController@dashboard')->name('dashboard');


        //Notification
        Route::get('notifications', 'AdminController@notifications')->name('notifications');
        Route::get('notification/read/{id}', 'AdminController@notificationRead')->name('notification.read');
        Route::get('notifications/read-all', 'AdminController@readAll')->name('notifications.readAll');
    });

    Route::middleware('staffaccess:29')->group(function () {
        Route::get('system-info', 'AdminController@systemInfo')->name('system.info');
    });

    Route::middleware('staffaccess:32')->group(function () {
        //Report Bugs
        Route::get('request-report', 'AdminController@requestReport')->name('request.report');
        Route::post('request-report', 'AdminController@reportSubmit');
    });

    Route::middleware('staffaccess:9')->group(function () {
        // Users Manager

        Route::get('users', 'ManageUsersController@allUsers')->name('users.all');
        // Route::get('userverification','ManageUsersController@userverification')->name('userverification');
        // Route::get('verifydocumentsdetails/{id}','ManageUsersController@verifydocumentsdetails')->name('verifydocumentsdetails');
        Route::post('verifyimage/{id}', 'ManageUsersController@verifyimage')->name('verifyimage');
        Route::get('users/active', 'ManageUsersController@activeUsers')->name('users.active');
        Route::get('users/banned', 'ManageUsersController@bannedUsers')->name('users.banned');
        Route::get('users/email-verified', 'ManageUsersController@emailVerifiedUsers')->name('users.email.verified');
        Route::get('users/email-unverified', 'ManageUsersController@emailUnverifiedUsers')->name('users.email.unverified');
        Route::get('users/sms-unverified', 'ManageUsersController@smsUnverifiedUsers')->name('users.sms.unverified');
        Route::get('users/sms-verified', 'ManageUsersController@smsVerifiedUsers')->name('users.sms.verified');
        Route::get('users/with-balance', 'ManageUsersController@usersWithBalance')->name('users.with.balance');

        Route::get('users/{scope}/search', 'ManageUsersController@search')->name('users.search');
        Route::get('user/detail/{id}', 'ManageUsersController@detail')->name('users.detail');
        Route::post('user/update/{id}', 'ManageUsersController@update')->name('users.update');
        Route::post('user/add-sub-balance/{id}', 'ManageUsersController@addSubBalance')->name('users.add.sub.balance');
        Route::get('user/send-email/{id}', 'ManageUsersController@showEmailSingleForm')->name('users.email.single');
        Route::post('user/send-email/{id}', 'ManageUsersController@sendEmailSingle')->name('users.email.single');
        Route::get('user/login/{id}', 'ManageUsersController@login')->name('users.login');
        Route::get('user/transactions/{id}', 'ManageUsersController@transactions')->name('users.transactions');
        Route::get('user/deposits/{id}', 'ManageUsersController@deposits')->name('users.deposits');
        Route::get('user/deposits/via/{method}/{type?}/{userId}', 'ManageUsersController@depositViaMethod')->name('users.deposits.method');
        Route::get('user/withdrawals/{id}', 'ManageUsersController@withdrawals')->name('users.withdrawals');
        Route::get('user/withdrawals/via/{method}/{type?}/{userId}', 'ManageUsersController@withdrawalsViaMethod')->name('users.withdrawals.method');
        // Login History
        Route::get('users/login/history/{id}', 'ManageUsersController@userLoginHistory')->name('users.login.history.single');

        Route::get('users/send-email', 'ManageUsersController@showEmailAllForm')->name('users.email.all');
        Route::post('users/send-email', 'ManageUsersController@sendEmailAll')->name('users.email.send');
        Route::get('users/email-log/{id}', 'ManageUsersController@emailLog')->name('users.email.log');
        Route::get('users/email-details/{id}', 'ManageUsersController@emailDetails')->name('users.email.details');
        Route::get('users/service/{id}', 'ManageUsersController@userService')->name('users.service');
        Route::get('users/software/{id}', 'ManageUsersController@userSoftware')->name('users.software');
        Route::get('users/job/{id}', 'ManageUsersController@userJob')->name('users.job');
        Route::get('users/service/booking/{id}', 'ManageUsersController@userServiceBooking')->name('users.service.booking');
        Route::get('users/software/purchases/{id}', 'ManageUsersController@userSoftwareBuy')->name('users.software.purchases');
       //vikash change
        Route::get('/user/chat/{sender_id}/{reciever_id}', 'ManageUsersController@user_chat')->name('user.chat');

    });

    Route::middleware('staffaccess:18')->group(function () {
        // Subscriber
        Route::get('subscriber', 'SubscriberController@index')->name('subscriber.index');
        Route::get('subscriber/send-email', 'SubscriberController@sendEmailForm')->name('subscriber.sendEmail');
        Route::post('subscriber/remove', 'SubscriberController@remove')->name('subscriber.remove');
        Route::post('subscriber/send-email', 'SubscriberController@sendEmail')->name('subscriber.sendEmail');
    });

    Route::middleware('staffaccess:5')->group(function () {
        //Manage Service
        Route::get('service', 'ServiceController@index')->name('service.index');
        Route::get('service/details/{id}', 'ServiceController@details')->name('service.details');
        Route::get('service/pending', 'ServiceController@pending')->name('service.pending');
        Route::get('service/approved', 'ServiceController@approved')->name('service.approved');
        Route::get('service/cancel', 'ServiceController@cancel')->name('service.cancel');
        Route::post('service/featured/include', 'ServiceController@featuredInclude')->name('service.featured.include');
        Route::post('service/featured/remove', 'ServiceController@featuredNotInclude')->name('service.featured.remove');
        Route::post('service/approvedBy', 'ServiceController@approvedBy')->name('service.approvedBy');
        Route::post('service/cancelBy', 'ServiceController@cancelBy')->name('service.cancelBy');
        Route::get('service/category', 'ServiceController@serviceCategory')->name('service.category');
        Route::get('service/{scope}/search', 'ServiceController@search')->name('service.search');
    });

    Route::middleware('staffaccess:6')->group(function () {
        //Manage Software
        Route::get('software/index', 'SoftwareController@index')->name('software.index');
        Route::get('software/pending', 'SoftwareController@pending')->name('software.pending');
        Route::get('software/approved', 'SoftwareController@approved')->name('software.approved');
        Route::get('software/cancel', 'SoftwareController@cancel')->name('software.cancel');
        Route::get('software/category', 'SoftwareController@softwareCategory')->name('software.category');
        Route::get('software/detail/{id}', 'SoftwareController@details')->name('software.details');
        Route::post('software/approvedBy', 'SoftwareController@approvedBy')->name('software.approvedBy');
        Route::post('software/cancelBy', 'SoftwareController@cancelBy')->name('software.cancelBy');
        Route::get('software/{scope}/search', 'SoftwareController@search')->name('software.search');
        Route::get('software/download/{id}', 'SoftwareController@softwareFile')->name('software.download');
        Route::get('software/document/{id}', 'SoftwareController@softwareDocument')->name('document.download');
    });

    Route::middleware('staffaccess:7')->group(function () {
        //Manage Job
        Route::get('job/index', 'JobController@index')->name('job.index');
        Route::get('job/closed', 'JobController@closed')->name('job.closed');
        Route::get('job/pending', 'JobController@pending')->name('job.pending');
        Route::get('job/approved', 'JobController@approved')->name('job.approved');
        Route::get('job/cancel', 'JobController@cancel')->name('job.cancel');
        Route::post('job/cancelBy', 'JobController@cancelBy')->name('job.cancelBy');
        Route::post('job/approvedBy', 'JobController@approvedBy')->name('job.approvedBy');
        Route::post('job/closedBy', 'JobController@closedBy')->name('job.closedBy');
        Route::get('job/details/{id}', 'JobController@details')->name('job.details');
        Route::get('job/biding/list/{id}', 'JobController@jobBiding')->name('job.biding.list');
        Route::get('job/biding/details/{id}', 'JobController@jobBidingDetails')->name('job.biding.details');
        Route::get('job/category', 'JobController@jobCategory')->name('job.category');
        Route::get('job/{scope}/search', 'JobController@search')->name('job.search');
        Route::get('job/closed-job-earnings', 'JobController@closedJobFee')->name('job.earnings');
    });

    Route::middleware('staffaccess:34')->group(function () {
        // Manage Staff
        Route::get('staff/index', 'StaffController@index')->name('staff.index');
        Route::get('staff/create', 'StaffController@create')->name('staff.create');
        Route::post('staff/store', 'StaffController@store')->name('staff.store');
        Route::get('staff/edit/{id}', 'StaffController@edit')->name('staff.edit');
        Route::post('staff/update/{id}', 'StaffController@update')->name('staff.update');
        Route::post('staff/delete/', 'StaffController@delete')->name('staff.delete');
    });

    Route::middleware('staffaccess:13')->group(function () {
        // Deposit Gateway
        Route::name('gateway.')->prefix('gateway')->group(function () {
            // Automatic Gateway
            Route::get('automatic', 'GatewayController@index')->name('automatic.index');
            Route::get('automatic/edit/{alias}', 'GatewayController@edit')->name('automatic.edit');
            Route::post('automatic/update/{code}', 'GatewayController@update')->name('automatic.update');
            Route::post('automatic/remove/{code}', 'GatewayController@remove')->name('automatic.remove');
            Route::post('automatic/activate', 'GatewayController@activate')->name('automatic.activate');
            Route::post('automatic/deactivate', 'GatewayController@deactivate')->name('automatic.deactivate');


            // Manual Methods
            Route::get('manual', 'ManualGatewayController@index')->name('manual.index');
            Route::get('manual/new', 'ManualGatewayController@create')->name('manual.create');
            Route::post('manual/new', 'ManualGatewayController@store')->name('manual.store');
            Route::get('manual/edit/{alias}', 'ManualGatewayController@edit')->name('manual.edit');
            Route::post('manual/update/{id}', 'ManualGatewayController@update')->name('manual.update');
            Route::post('manual/activate', 'ManualGatewayController@activate')->name('manual.activate');
            Route::post('manual/deactivate', 'ManualGatewayController@deactivate')->name('manual.deactivate');
        });
    });

    Route::middleware('staffaccess:14')->group(function () {
        // DEPOSIT SYSTEM
        Route::name('deposit.')->prefix('deposit')->group(function () {
            Route::get('/', 'DepositController@deposit')->name('list');
            Route::get('pending', 'DepositController@pending')->name('pending');
            Route::get('rejected', 'DepositController@rejected')->name('rejected');
            Route::get('approved', 'DepositController@approved')->name('approved');
            Route::get('successful', 'DepositController@successful')->name('successful');
            Route::get('details/{id}', 'DepositController@details')->name('details');

            Route::post('reject', 'DepositController@reject')->name('reject');
            Route::post('approve', 'DepositController@approve')->name('approve');
            Route::get('via/{method}/{type?}', 'DepositController@depositViaMethod')->name('method');
            Route::get('/{scope}/search', 'DepositController@search')->name('search');
            Route::get('date-search/{scope}', 'DepositController@dateSearch')->name('dateSearch');
        });
    });

    Route::middleware('staffaccess:15')->group(function () {
        // WITHDRAW SYSTEM
        Route::name('withdraw.')->prefix('withdraw')->group(function () {
            Route::get('pending', 'WithdrawalController@pending')->name('pending');
            Route::get('approved', 'WithdrawalController@approved')->name('approved');
            Route::get('rejected', 'WithdrawalController@rejected')->name('rejected');
            Route::get('log', 'WithdrawalController@log')->name('log');
            Route::get('via/{method_id}/{type?}', 'WithdrawalController@logViaMethod')->name('method');
            Route::get('{scope}/search', 'WithdrawalController@search')->name('search');
            Route::get('date-search/{scope}', 'WithdrawalController@dateSearch')->name('dateSearch');
            Route::get('details/{id}', 'WithdrawalController@details')->name('details');
            Route::post('approve', 'WithdrawalController@approve')->name('approve');
            Route::post('reject', 'WithdrawalController@reject')->name('reject');


            // Withdraw Method
            Route::get('method/', 'WithdrawMethodController@methods')->name('method.index');
            Route::get('method/create', 'WithdrawMethodController@create')->name('method.create');
            Route::post('method/create', 'WithdrawMethodController@store')->name('method.store');
            Route::get('method/edit/{id}', 'WithdrawMethodController@edit')->name('method.edit');
            Route::post('method/edit/{id}', 'WithdrawMethodController@update')->name('method.update');
            Route::post('method/activate', 'WithdrawMethodController@activate')->name('method.activate');
            Route::post('method/deactivate', 'WithdrawMethodController@deactivate')->name('method.deactivate');
        });
    });

    Route::middleware('staffaccess:2')->group(function () {
        //Service Booking
        Route::get('service/booking', 'ServiceBookingController@index')->name('booking.service.index');
        Route::get('service/booking/details/{id}', 'ServiceBookingController@details')->name('booking.service.details');
        Route::get('service/booking/pending', 'ServiceBookingController@pending')->name('booking.service.pending');
        Route::get('service/booking/completed', 'ServiceBookingController@completed')->name('booking.service.completed');
        Route::get('service/booking/delivered', 'ServiceBookingController@delivered')->name('booking.service.delivered');
        Route::get('service/booking/inprogress', 'ServiceBookingController@inProgress')->name('booking.service.inProgress');
        Route::get('service/booking/dispute', 'ServiceBookingController@dispute')->name('booking.service.dispute');
        Route::get('service/booking/admin/disputes', 'ServiceBookingController@expired')->name('booking.service.expired');
        Route::get('service/booking/admin/disputes/resolved', 'ServiceBookingController@resolved')->name('booking.service.dispute.resolved');
        Route::get('service/booking/cacnel', 'ServiceBookingController@cacnel')->name('booking.service.cacnel');
        Route::post('service/booking/payment', 'ServiceBookingController@payment')->name('booking.service.payment');
        Route::get('service/booking/{scope}/search', 'ServiceBookingController@search')->name('booking.service.search');
        Route::get('service/booking/work/delivery/{id}', 'ServiceBookingController@workDeliveryDownload')->name('work.delivery.download');
        Route::post('service/dispute/admindecision', 'ServiceBookingController@admindecision')->name('service.dispute.admindecision');
    });

    Route::middleware('staffaccess:3')->group(function () {
        // Sales Software
        Route::get('software/sales', 'BuySoftwareController@index')->name('sales.software.index');
        Route::get('software/file/download/{id}', 'BuySoftwareController@softwareFileDownload')->name('software.file.download');
        Route::get('software/document/download/{id}', 'BuySoftwareController@softwareDocumentFile')->name('software.document.download');
        Route::get('sales/software/search', 'BuySoftwareController@search')->name('sales.software.search');
    });

    Route::middleware('staffaccess:4')->group(function () {
        // Hire Employ
        Route::get('hire/employees', 'HireEmployController@index')->name('hire.index');
        Route::get('work/completed', 'HireEmployController@completed')->name('hire.completed');
        Route::get('work/delivered', 'HireEmployController@delivered')->name('hire.delivered');
        Route::get('work/inprogress', 'HireEmployController@inprogress')->name('hire.inprogress');
        Route::get('work/expired', 'HireEmployController@expired')->name('hire.expired');
        Route::get('work/dispute', 'HireEmployController@dispute')->name('hire.dispute');
        Route::get('hire/employees/details/{id}', 'HireEmployController@details')->name('hire.details');
        Route::get('hire/file/download/{id}', 'HireEmployController@workFileDownload')->name('hire.work.file.download');
        Route::get('dispute/file/download/{id}', 'HireEmployController@disputeFileDownload')->name('hire.dispute.file.download');
        Route::post('employees/payment', 'HireEmployController@payment')->name('employ.payment');
        Route::get('hire/employees/{scope}/search', 'HireEmployController@search')->name('hire.search');
    });

    Route::middleware('staffaccess:33')->group(function () {
        //Rank
        Route::get('rank', 'RankController@index')->name('rank.index');
        Route::post('rank/store', 'RankController@store')->name('rank.store');
        Route::post('rank/update', 'RankController@update')->name('rank.update');
    });

    Route::middleware('staffaccess:10')->group(function () {
        // Coupon
        Route::get('coupon', 'CouponController@index')->name('coupon.index');
        Route::post('coupon/store', 'CouponController@store')->name('coupon.store');
        Route::post('coupon/update', 'CouponController@update')->name('coupon.update');
        Route::post('coupon/delete', 'CouponController@delete')->name('coupon.delete');
    });

    Route::middleware('staffaccess:11')->group(function () {
        //Category
        Route::get('category', 'CategoryController@index')->name('category.index');
        Route::post('category/store', 'CategoryController@store')->name('category.store');
        Route::post('category/update', 'CategoryController@update')->name('category.update');

        Route::get('sub/category', 'CategoryController@subCategoryIndex')->name('category.subcategory.index');
        Route::get('sub/category/data', 'CategoryController@subCategoryData')->name('category.subcategory.data');
        Route::post('sub/category/store', 'CategoryController@subCategoryStore')->name('category.subcategory.store');
        Route::post('sub/category/update', 'CategoryController@subCategoryUpdate')->name('category.subcategory.update');

        Route::get('child/category', 'CategoryController@childCategoryIndex')->name('category.childcategory.index');
        Route::get('child/category/data', 'CategoryController@childCategoryData')->name('category.childcategory.data');
        Route::post('child/category/store', 'CategoryController@childCategoryStore')->name('category.childcategory.store');
        Route::post('child/category/update', 'CategoryController@childCategoryUpdate')->name('category.childcategory.update');

        Route::get('skill/category', 'CategoryController@skillCategoryIndex')->name('category.skillcategory.index');
        Route::get('skill/category/data', 'CategoryController@skillCategoryData')->name('category.skillcategory.data');
        Route::post('skill/category/store', 'CategoryController@skillCategoryStore')->name('category.skillcategory.store');
        Route::post('skill/category/update', 'CategoryController@skillCategoryUpdate')->name('category.skillcategory.update');

        Route::get('skill', 'CategoryController@skillIndex')->name('skill.index');
        Route::post('skill/store', 'CategoryController@skillStore')->name('skill.store');
        Route::post('skill/update', 'CategoryController@skillUpdate')->name('skill.update');
    });

    Route::middleware('staffaccess:12')->group(function () {
        //features
        Route::get('features', 'FeaturesController@index')->name('features.index');
        Route::post('features/store', 'FeaturesController@store')->name('features.store');
        Route::post('features/update', 'FeaturesController@update')->name('features.update');
    });

    Route::middleware('staffaccess:8')->group(function () {
        // Advertisement
        Route::get('ads/index', 'AdvertisementController@index')->name('ads.index');
        Route::post('ads/store', 'AdvertisementController@store')->name('ads.store');
        Route::get('ads/edit/{id}', 'AdvertisementController@edit')->name('ads.edit');
        Route::post('ads/update/{id}', 'AdvertisementController@update')->name('ads.update');
        Route::post('ads/delete', 'AdvertisementController@delete')->name('ads.delete');
    });

    Route::middleware('staffaccess:17')->group(function () {
        // Report
        Route::get('report/transaction', 'ReportController@transaction')->name('report.transaction');
        Route::get('report/transaction/search', 'ReportController@transactionSearch')->name('report.transaction.search');
        Route::get('report/login/history', 'ReportController@loginHistory')->name('report.login.history');
        Route::get('report/login/ipHistory/{ip}', 'ReportController@loginIpHistory')->name('report.login.ipHistory');
        Route::get('report/email/history', 'ReportController@emailHistory')->name('report.email.history');
    });

    Route::middleware('staffaccess:16')->group(function () {
        // Admin Support
        Route::get('tickets', 'SupportTicketController@tickets')->name('ticket');
        Route::get('tickets/pending', 'SupportTicketController@pendingTicket')->name('ticket.pending');
        Route::get('tickets/closed', 'SupportTicketController@closedTicket')->name('ticket.closed');
        Route::get('tickets/answered', 'SupportTicketController@answeredTicket')->name('ticket.answered');
        Route::get('tickets/view/{id}', 'SupportTicketController@ticketReply')->name('ticket.view');
        Route::post('ticket/reply/{id}', 'SupportTicketController@ticketReplySend')->name('ticket.reply');
        Route::get('ticket/download/{ticket}', 'SupportTicketController@ticketDownload')->name('ticket.download');
        Route::post('ticket/delete', 'SupportTicketController@ticketDelete')->name('ticket.delete');
    });

    Route::middleware('staffaccess:22')->group(function () {
        // Language Manager
        Route::get('/language', 'LanguageController@langManage')->name('language.manage');
        Route::post('/language', 'LanguageController@langStore')->name('language.manage.store');
        Route::post('/language/delete/{id}', 'LanguageController@langDel')->name('language.manage.del');
        Route::post('/language/update/{id}', 'LanguageController@langUpdate')->name('language.manage.update');
        Route::get('/language/edit/{id}', 'LanguageController@langEdit')->name('language.key');
        Route::post('/language/import', 'LanguageController@langImport')->name('language.importLang');

        Route::post('language/store/key/{id}', 'LanguageController@storeLanguageJson')->name('language.store.key');
        Route::post('language/delete/key/{id}', 'LanguageController@deleteLanguageJson')->name('language.delete.key');
        Route::post('language/update/key/{id}', 'LanguageController@updateLanguageJson')->name('language.update.key');
    });

    Route::middleware('staffaccess:19')->group(function () {
        // General Setting
        Route::get('general-setting', 'GeneralSettingController@index')->name('setting.index');
        Route::post('general-setting', 'GeneralSettingController@update')->name('setting.update');
    });

    Route::middleware('staffaccess:31')->group(function () {
        Route::get('optimize', 'GeneralSettingController@optimize')->name('setting.optimize');
    });

    Route::middleware('staffaccess:20')->group(function () {
        // Logo-Icon
        Route::get('setting/logo-icon', 'GeneralSettingController@logoIcon')->name('setting.logo.icon');
        Route::post('setting/logo-icon', 'GeneralSettingController@logoIconUpdate')->name('setting.logo.icon');
    });

    Route::middleware('staffaccess:30')->group(function () {
        //Custom CSS
        Route::get('custom-css', 'GeneralSettingController@customCss')->name('setting.custom.css');
        Route::post('custom-css', 'GeneralSettingController@customCssSubmit');
        //Vikash change
        Route::get('email_detected', 'GeneralSettingController@email_detected')->name('email_detected');

    });

    Route::middleware('staffaccess:26')->group(function () {
        //Cookie
        Route::get('cookie', 'GeneralSettingController@cookie')->name('setting.cookie');
        Route::post('cookie', 'GeneralSettingController@cookieSubmit');
    });

    Route::middleware('staffaccess:21')->group(function () {
        // Plugin
        Route::get('extensions', 'ExtensionController@index')->name('extensions.index');
        Route::post('extensions/update/{id}', 'ExtensionController@update')->name('extensions.update');
        Route::post('extensions/activate', 'ExtensionController@activate')->name('extensions.activate');
        Route::post('extensions/deactivate', 'ExtensionController@deactivate')->name('extensions.deactivate');
    });


    Route::middleware('staffaccess:24')->group(function () {
        // Email Setting
        Route::get('email-template/global', 'EmailTemplateController@emailTemplate')->name('email.template.global');
        Route::post('email-template/global', 'EmailTemplateController@emailTemplateUpdate')->name('email.template.global');
        Route::get('email-template/setting', 'EmailTemplateController@emailSetting')->name('email.template.setting');
        Route::post('email-template/setting', 'EmailTemplateController@emailSettingUpdate')->name('email.template.setting');
        Route::get('email-template/index', 'EmailTemplateController@index')->name('email.template.index');
        Route::get('email-template/{id}/edit', 'EmailTemplateController@edit')->name('email.template.edit');
        Route::post('email-template/{id}/update', 'EmailTemplateController@update')->name('email.template.update');
        Route::post('email-template/send-test-mail', 'EmailTemplateController@sendTestMail')->name('email.template.test.mail');
    });

    Route::middleware('staffaccess:25')->group(function () {
        // SMS Setting
        Route::get('sms-template/global', 'SmsTemplateController@smsTemplate')->name('sms.template.global');
        Route::post('sms-template/global', 'SmsTemplateController@smsTemplateUpdate')->name('sms.template.global');
        Route::get('sms-template/setting', 'SmsTemplateController@smsSetting')->name('sms.templates.setting');
        Route::post('sms-template/setting', 'SmsTemplateController@smsSettingUpdate')->name('sms.template.setting');
        Route::get('sms-template/index', 'SmsTemplateController@index')->name('sms.template.index');
        Route::get('sms-template/edit/{id}', 'SmsTemplateController@edit')->name('sms.template.edit');
        Route::post('sms-template/update/{id}', 'SmsTemplateController@update')->name('sms.template.update');
        Route::post('email-template/send-test-sms', 'SmsTemplateController@sendTestSMS')->name('sms.template.test.sms');
    });

    Route::middleware('staffaccess:23')->group(function () {
        // SEO
        Route::get('seo', 'FrontendController@seoEdit')->name('seo');
    });

    Route::middleware('staffaccess:27')->group(function () {
        Route::name('frontend.')->prefix('frontend')->group(function () {
            Route::get('templates', 'FrontendController@templates')->name('templates');
            Route::post('templates', 'FrontendController@templatesActive')->name('templates.active');
        });
    });


    Route::middleware('staffaccess:33')->group(function () {
        Route::name('frontend.')->prefix('frontend')->group(function () {
            Route::get('managetiers', 'FrontendController@managetiers')->name('managetiers');
            Route::get('addtiers', 'FrontendController@addtiers')->name('addtiers');
            Route::post('tier/new', 'FrontendController@submittier')->name('tier.store');
            Route::get('edittiers/{id}', 'FrontendController@edittiers')->name('tier.edit');
            Route::post('tier/update/{id}', 'FrontendController@update')->name('tier.update');
            Route::get('managelanguagesprofile', 'FrontendController@manageprolangs')->name('managelanguagesprofile');
            Route::get('addlanguages', 'FrontendController@addlanguages')->name('addlanguages');
            Route::post('language/new', 'FrontendController@submitlang')->name('language.store');

            Route::get('editlanguage/{id}', 'FrontendController@editlang')->name('language.edit');
            Route::post('language/update/{id}', 'FrontendController@updatelang')->name('language.update');

            Route::get('chatdata', 'FrontendController@chatdata')->name('chatdata');
            Route::get('chatdata-detail/{sid?}/{rid?}', 'FrontendController@chatdataDetail')->name('chatdata-detail');
            Route::get('managedegreeprofile', 'FrontendController@manageprodegree')->name('managedegreeprofile');
            Route::get('adddegree', 'FrontendController@adddegree')->name('adddegree');
            Route::post('degree/new', 'FrontendController@submitdegree')->name('degree.store');

            Route::get('editdegree/{id}', 'FrontendController@editdegree')->name('degree.edit');
            Route::post('degree/update/{id}', 'FrontendController@updatedegree')->name('degree.update');
        });
    });
    Route::middleware('staffaccess:28')->group(function () {
        // Frontend
        Route::name('frontend.')->prefix('frontend')->group(function () {
            Route::get('frontend-sections/{key}', 'FrontendController@frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'FrontendController@frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'FrontendController@frontendElement')->name('sections.element');
            Route::post('remove', 'FrontendController@remove')->name('remove');
        });
    });

    // Contact Us
    Route::get('/contact-us', [App\Http\Controllers\Admin\ContactUsController::class, 'index'])->name('contact.us.index');
    Route::delete('/contact-us/{id}', [App\Http\Controllers\Admin\ContactUsController::class, 'destroy'])->name('contact.us.destroy');

});


?>
