<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Seller\ServiceController;
use App\Http\Controllers\ServiceDetailsController;
use App\Http\Controllers\Buyer\ServiceDashboardController;
use App\Http\Controllers\Buyer\ServicePaymentControler;
use App\Http\Controllers\Seller\SellerServiceDashboardController;
use App\Http\Controllers\ServiceTiersController;

Route::post('/search-servce-tiers', [ServiceTiersController::class, 'filtertiers'])->name('service.tiers.filter');

Route::get('/service', [ServiceController::class, 'service'])->name('service');
Route::post('/search-filter-services', [ServiceController::class, 'searchFilterServices'])->name('search.filter.service');

Route::namespace('Seller')->prefix('seller')->group(function () {


    //ServiceCrud
    Route::get('/service-index', [ServiceController::class, 'index'])->name('service.index');
    Route::get('/service-create', [ServiceController::class, 'create'])->name('service.create');
    Route::post('service-image-temp-upload', [ServiceController::class, 'uploadTempImage'])->name('service.image.temp.upload');
    Route::post('service-image-temp-delete', [ServiceController::class, 'deleteTempImage'])->name('service.image.temp.delete');

    //Search Tiers


    Route::post('/service-store', [ServiceController::class, 'store'])->name('service.store');
    Route::post('/service-update/{id}', [ServiceController::class, 'update'])->name('service.update');
    Route::get('/service-edit/{slug}/{id}', [ServiceController::class, 'edit'])->name('service.edit');
    Route::post('/optional/image', [ServiceController::class, 'optionalImageRemove'])->name('optional.image');

    Route::get('/service-getskills', [ServiceController::class, 'getServiceSkills'])->name('service.getskills');
    Route::get('/service-getskillsbyname', [ServiceController::class, 'getServiceSkillsByName'])->name('service.getskillsbyname');
    Route::get('/service-skillcategory', [ServiceController::class, 'serviceSkillCategory'])->name('service.skillcategory');

    Route::get('/service-category', [ServiceController::class, 'serviceCategory'])->name('service.category');
    Route::get('/service-subcategory', [ServiceController::class, 'serviceSubCategory'])->name('service.subcategory');

    Route::delete('{id}/service', [ServiceController::class, 'destroy'])->name('service.delete');

    Route::get('/service/{service_id}/order-now', [ServiceController::class, 'orderNow'])->name('service.order');
    Route::get('/service/{service_id}/order-now-payment', [ServiceController::class, 'orderNowPayment'])->name('service.order.payment');

     //Service Details
    Route::get('/service/service-details/{id}', [ServiceDetailsController::class, 'serviceDetails'])->name('service.details');


    //Seller Services Dashboard
    Route::controller(SellerServiceDashboardController::class)->group(function () {

        Route::get('seller-service-booking', 'serviceBookeds')->name('seller-booking-service');
        Route::get('seller-service-booking-details/{id}', 'sellerServiceBookingDetails')->name('booking-service-details');
        Route::get('seller-service-booking-details-contract/{id}', 'sellerServiceBookingDetailsContract')->name('booking-service-details-contract');
        Route::get('seller-service-booking-details-work-progress/{id}', 'sellerServiceBookingDetailsWorkprogress')->name('booking-service-details-work-progress');
        Route::get('service-booking-details-invoice/{id}', 'sellerServiceBookingDetailsWorkInvoice')->name('booking-service-details-invoice');
        Route::get('seller-service-booking-cancelled', 'serviceBookingsCancelled')->name('seller-booking-service-cancelled');
        Route::get('seller-service-booking-completed', 'serviceBookingsCompleted')->name('seller-booking-service-completed');
        Route::get('seller-service-booking-active', 'serviceBookingsActive')->name('seller-booking-service-active');
        Route::get('seller-user-work-send-service-download/{id}', 'sellerUserworkservicefileDownload')->name('work-send-service-download');
        Route::get('service-booking-details-work-completed/{id}', 'sellerServiceBookingDetailsWorkcompleted')->name('booking-service-details-work-completed');
        Route::get('generate-seller-invoice-pdf-seller-service/{id}', 'generateSellerInvoicePDFserviceseller')->name('seller.generate.invoice.pdf');
        Route::post('service-rating-reply-service', 'sellerReplyRatingService')->name('service.rating.reply');
        Route::post('service-extension-request', 'serviceExtensionRequest')->name('service.extension.request');

        Route::post('work-upload', 'workUpload')->name('work-upload');
        Route::post('approve-service/{id}', 'approveService')->name('approve-service');
        Route::post('decline-service/{id}', 'declineService')->name('decline-service');

    });
});


//Service Payment
Route::post('buyer-service-booking-confirm', [ServicePaymentControler::class, 'serviceBooking'])->name('service.booking.confirm');
Route::get('buyer-service-payment-success', [ServicePaymentControler::class, 'servicePaymentSuccess'])->name('service.payment.success');
Route::post('apply-service-coupon', [ServicePaymentControler::class, 'applyCoupon'])->name('service.apply.coupon');

Route::get(
    'buyer/service/{id}/invoice-pdf',
    [ServiceDashboardController::class, 'generateBuyerInvoicePDServiceSeller']
)->name('buyer.service.invoice.pdf');

Route::get(
    'seller/service/{id}/invoice-pdf',
    [ServiceDashboardController::class, 'generateSellerInvoicePDFServiceBuyer']
)->name('seller.service.invoice.pdf');


Route::post('booking-confirm', [ServiceController::class, 'serviceBookingConfirm'])->name('service-booking-confirm');
Route::post('work-delivery-approved', [ServiceDashboardController::class, 'workDeliveryApproved'])->name('work-delivery-approved');
Route::post('work-delivery-rejected', [ServiceDashboardController::class, 'workDeliveryRejected'])->name('work-delivery-rejected');
Route::post('service-dispute-create-{orderNumber}', [ServiceDashboardController::class, 'storeServiceDispute'])->name('service-dispute-create');
Route::post('service-dispute-send-to-admin', [ServiceDashboardController::class, 'sendServiceDisputeToAdmin'])->name('service-dispute-send-to-admin');

Route::post('service-booked-via-wallet', [ServicePaymentControler::class, 'serviceBookedViaWallet'])->name('service-booked-via-wallet');

Route::namespace('Buyer')->prefix('buyer')->group(function () {

    // Buyer Services
    Route::controller(ServiceDashboardController::class)->group(function () {
        Route::get('buyer-booked-service', 'serviceBookingItems')->name('service-booked');
        Route::get('buyer-service-cancelled', 'buyerServiceBookingsCancelled')->name('booking-service-cancelled');
        Route::get('buyer-service-completed', 'buyerServiceBookingsCompleted')->name('booking-service-completed');
        Route::get('buyer-service-active', 'buyerServiceBookingsActive')->name('booking-service-active');

        Route::get('buyer-service-booking-details/{id}', 'serviceBookingDetails')->name('service-booked-details');
        Route::get('service-booking-details-contract/{id}', 'serviceBookingDetailsContract')->name('service-booked-details-contract');
        Route::get('buyer-service-booking-details-work-progress/{id}', 'serviceBookingDetailsWorkprogress')->name('service-booked-details-workprogress');
        Route::get('buyer-service-booking-details-work-invoice/{id}', 'serviceBookingDetailsWorkInvoice')->name('service-booked-details-workcompleted');
        Route::get('buyer-service-booking-details-work-completed/{id}', 'serviceBookingDetailsWorkcompleted')->name('service-booked-details-workcompleted1');
        Route::get('buyer-user-work-send-service-download/{id}', 'userworkservicefileDownload')->name('work-send-service-download');
        Route::get('buyer-user-work-response-service-download/{id}', 'userworkresponseservicefileDownload')->name('work-response-service-download');
        Route::get('buyer-user-work-rejected-response-service-download/{id}', 'userworkresponserejectedservicefileDownload')->name('work-response-service-rejected-download');

        // Review
        Route::post('hire-employee-service-rating','hireEmployServiceRating')->name('booking-service-rating');
        Route::post('work-delivery-approved-payed','workDeliveryApprovedpayed')->name('work-delivery-approved-payed');

        Route::post('change-extension-service-status', 'changeExtensionServiceStatus')->name('change-extension-service');

    });
});
