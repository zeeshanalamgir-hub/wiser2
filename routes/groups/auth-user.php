<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Gateway\PaymentController;


Route::middleware('auth')->group(function () {
    Route::get('authorization', 'AuthorizationController@authorizeForm')->name('authorization');
    Route::get('resend-verify', 'AuthorizationController@sendVerifyCode')->name('send.verify.code');
    Route::post('verify-email', 'AuthorizationController@emailVerification')->name('verify.email');
    Route::post('verify-sms', 'AuthorizationController@smsVerification')->name('verify.sms');
    Route::post('verify-g2fa', 'AuthorizationController@g2faVerification')->name('go2fa.verify');

    Route::middleware(['checkStatus'])->group(function () {
        //member ship page route
        Route::get('membership/{id}', 'MembershipController@index')->name('membership');
        Route::get('payment', 'MembershipController@payment')->name('payment');
        Route::post('payment', 'MembershipController@updatemembership')->name('payment');
        Route::namespace('Seller')->prefix('seller')->group(function () {
            require_once __DIR__ . '/seller.php';
        });

        Route::any('/seller/deposit', 'Gateway\PaymentController@deposit')->name('deposit');
        Route::any('/buyer/deposit', 'Gateway\PaymentController@deposittwo')->name('deposittwo');
        Route::post('seller/deposit/insert', 'Gateway\PaymentController@depositInsert')->name('deposit.insert');

        Route::get('seller/deposit/preview', 'Gateway\PaymentController@depositPreview')->name('deposit.preview');

        Route::post('buyer/deposit/insert', 'Gateway\PaymentController@depositInserttwo')->name('deposit.inserttwo');
        Route::post('buyer/pay/confirm', 'Gateway\PaymentController@payment')->name('pay.confirm');
        Route::get('payment/success', [PaymentController::class, 'success'])->name('payment.success');
        Route::get('payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');



        Route::get('buyer/deposit/preview', 'Gateway\PaymentController@depositPreviewtwo')->name('deposit.previewtwo');
        Route::get('deposit/confirm', 'Gateway\PaymentController@depositConfirm')->name('deposit.confirm');


        Route::get('/stripe/success', [PaymentController::class, 'paymentSuccess'])->name('stripe.success');
        Route::get('/stripe/cancel', [PaymentController::class, 'paymentCancel'])->name('stripe.cancel');



        Route::get('seller/deposit/manual', 'Gateway\PaymentController@manualDepositConfirm')->name('deposit.manual.confirm');
        Route::get('buyer/deposit/manual', 'Gateway\PaymentController@manualDepositConfirmtwo')->name('deposit.manual.confirmtwo');

        Route::post('seller/deposit/manual', 'Gateway\PaymentController@manualDepositUpdate')->name('deposit.manual.update');
        Route::post('buyer/deposit/manual', 'Gateway\PaymentController@manualDepositUpdatetwo')->name('deposit.manual.updatetwo');

        Route::namespace('Buyer')->prefix('buyer')->group(function () {
            require_once __DIR__ . '/buyer.php';
        });

        //JobBiding
        Route::post('job/biding', 'JobBidingController@store')->name('job.biding.store');
        //Conversation
        Route::post('conversation', 'ConversationController@store')->name('conversation.store');
        Route::get('inbox', 'ConversationController@inbox')->name('conversation.inbox');
        Route::get('chat/{id}', 'ConversationController@chat')->name('conversation.chat');
        Route::post('message/store', 'ConversationController@messageStore')->name('message.store');

        //Comment
        Route::post('comment', 'CommentController@store')->name('comment.store');
        Route::post('comment/reply', 'CommentController@commentReply')->name('comment.reply');

        //Service Booking
        Route::get('service/booking/{id}', 'BookingController@serviceBooking')->name('service.booking');
        Route::post('service/booking/confirm',  'BookingController@confirmBooking')->name('service.booking.confirm');

        Route::get('service/coupon/apply', 'BookingController@applyCoupon')->name('service.coupon.apply');
        Route::post('service/booked', 'BookingController@serviceBooked')->name('service.booked');

        Route::get('payment/method', 'BookingController@payment')->name('payment.method');
        Route::post('payment/insert', 'BookingController@paymentInsert')->name('payment.insert');

        //Software Buy
        Route::get('software/buy/{slug}/{id}', 'SoftwareBuyController@softwareBuy')->name('software.buy');
        Route::get('software/coupon/apply', 'SoftwareBuyController@applyCouponSoftware')->name('software.coupon.apply');
        Route::post('software/buy/store', 'SoftwareBuyController@softwareBuyStore')->name('software.buy.store');

        // Job Biding
        Route::get('job/biding/order/{slug}/{id}', 'BidingOrderController@jobBiding')->name('job.biding.order');
        Route::post('hire/employ', 'BidingOrderController@hireEmploy')->name('hire.employ');
        // Route::get('/membership', 'FreelancermembershipController@getMembership');


        //Review
        Route::post('review', 'ReviewController@store')->name('review.store');
    });
});

?>