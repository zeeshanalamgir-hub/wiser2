<?php

use App\Http\Controllers\Seller\HomeController;
use App\Http\Controllers\Seller\JobsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\UserController;
use App\Http\Controllers\Gateway\Papyal\SellerPayPalController;


Route::get('profile_update', [UserController::class, 'home2'])->name('home2');
Route::get('profile', 'UserController@home1')->name('home1');
Route::post('accept-terms', [UserController::class, 'acceptTerms'])->name('accept.terms');
Route::get('dashboard', [UserController::class, 'home'])->name('home');
Route::get('deposit/history', 'HomeController@depositHistory')->name('deposit.history');
Route::get('profile-setting', 'UserController@profile')->name('profile.setting');
Route::post('profile-setting', 'UserController@submitProfile');
Route::get('change-password', 'UserController@changePassword')->name('change.password');
Route::post('change-password', 'UserController@submitPassword');
//freelancer memberships
Route::get('freelancermembership', 'FreelancermembershipController@index')->name('freelancermembership');
Route::get('payment', 'FreelancermembershipController@payment')->name('payment');
Route::post('payment', 'FreelancermembershipController@updatemembership')->name('payment');


//bids pakages
Route::get('bidpackages', 'BidspackagesController@index')->name('bidpackages');
Route::get('bidspayment', 'BidspackagesController@payment')->name('bidspayment');
Route::post('bidspayment', 'BidspackagesController@updatemembership')->name('bidspayment');
//freelancer jobs
Route::get('job/jobapplied', 'JobsController@index')->name('job.jobapplied');
Route::get('job/currentjobs', 'JobsController@current_jobs')->name('job.currentjobs');
Route::get('job/completedjobs', 'JobsController@completed_jobs')->name('job.completedjobs');
Route::post('job/bids/cancel-bid', 'JobsController@bidcancelbid')->name('job.cancel.bid');

//2FA
Route::get('twofactor', 'UserController@show2faForm')->name('twofactor');
Route::post('twofactor/enable', 'UserController@create2fa')->name('twofactor.enable');
Route::post('twofactor/disable', 'UserController@disable2fa')->name('twofactor.disable');

// Withdraw
Route::get('/withdraw', 'UserController@withdrawMoney')->name('withdraw');
Route::post('/withdraw', 'UserController@withdrawStore')->name('withdraw.money');
Route::get('/withdraw/preview', 'UserController@withdrawPreview')->name('withdraw.preview');
Route::post('/withdraw/preview', 'UserController@withdrawSubmit')->name('withdraw.submit');
Route::get('/withdraw/history', 'UserController@withdrawLog')->name('withdraw.history');

//Home Controller
Route::get('service/booking/', 'HomeController@serviceBookeds')->name('booking.service');
Route::get('service/booking/cancelled', 'HomeController@serviceBookingsCancelled')->name('booking.service.cancelled');
Route::get('service/booking/completed', 'HomeController@serviceBookingsCompleted')->name('booking.service.completed');
Route::get('service/booking/active', 'HomeController@serviceBookingsActive')->name('booking.service.active');

Route::get('index', 'HomeController@index');
Route::get('service/booking/details/{id}', 'HomeController@serviceBookingDetails')->name('booking.service.details');

Route::get('service/booking/details/contract/{id}', 'HomeController@serviceBookingDetailsContract')->name('booking.service.details.contract');

Route::get('service/booking/details/workprogress/{id}', 'HomeController@serviceBookingDetailsWorkprogress')->name('booking.service.details.workprogress');

Route::get('user/work/send/service/download/{id}', 'HomeController@userworkservicefileDownload')->name('work.send.service.download');

Route::get('view/dispute/offer/{id}', 'HomeController@viewdisputeoffer')->name('work.view.dispute.offer');
Route::post('service/accept/dispute/offer', 'HomeController@acceptdisputeoffer')->name('service.accept.dispute.offer');
Route::post('service/decline/dispute/offer', 'HomeController@declinedisputeoffer')->name('service.decline.dispute.offer');
Route::post('service/new/dispute/offer', 'HomeController@newdisputeoffer')->name('service.new.dispute.offer');
Route::post('service/sendadmin/dispute/offer', 'HomeController@sendadmindisputeoffer')->name('service.sendadmin.dispute.offer');

Route::get('user/work/response/service/download/{id}', 'HomeController@userworkresponseservicefileDownload')->name('work.response.service.download');

Route::get('service/booking/details/workcompleted/{id}', 'HomeController@serviceBookingDetailsWorkcompleted')->name('booking.service.details.workcompleted');
Route::get('service/booking/details/workcompleted1/{id}', 'HomeController@serviceBookingDetailsWorkcompleted1')->name('booking.service.details.workcompleted1');
Route::post('rating/reply/service', 'HomeController@ratingreplyservice')->name('rating.reply.service');

Route::post('booking/confirm', 'HomeController@bookingConfirm')->name('booking.confirm');
Route::post('work/upload', 'HomeController@workUpload')->name('work.upload');
Route::post('jobwork/upload', 'HomeController@JobworkUpload')->name('jobwork.upload');
Route::get('work/file/download/{id}', 'HomeController@workFileDownload')->name('work.file.download');
Route::get('jobwork/delivery/download/{id}', 'HomeController@JobworkDeliveryDownload')->name('jobwork.delivery.download');
Route::get('/software/sales', 'HomeController@salesSoftware')->name('software.sales');


Route::get('/job/bidding/{slug}/{id}', [JobsController::class, 'jobBiddingfixed'])->name('job.bid.fixed');
// Route::get('/job/revisebiddingproject/{id1}/{id2}', [JobsController::class, 'jobreviseBiddingproject'])->name('job.revisebid.project');

// Route::get('/job/bidding/{slug}/{id}', 'SiteController@jobBiddingfixed')->name('job.bid.fixed');

Route::get('/job/list', [JobsController::class, 'jobListView'])->name('job.vacancy');
Route::prefix('job/list/ajax')->group(function () {
    Route::get('/bids', [JobsController::class, 'jobBids']);
    Route::get('/bids/details/{encryptedId}', [JobsController::class, 'jobVacancyBidList']);
    Route::get('/offers', [JobsController::class, 'jobOffers']);
    Route::get('/active', [JobsController::class, 'jobVacancyactive']);
    Route::get('/completed', [JobsController::class, 'jobVacancycompleted']);
    Route::get('/offers/accept-offer/{encUserBidId}', [JobsController::class, 'acceptBuyerFixedBid'])->name('offers.accept');
    Route::post('/offers/decline-offer/{encUserBidId}', [JobsController::class, 'declineBuyerFixedBid'])->name('offers.decline');
    Route::post('/offers/decline-invite-job-offer/{encUserBidId}', [JobsController::class, 'declineBuyerInviteFixedBid'])->name('offers.invite.decline');
});

//Route::get('/job/workroom/{encryptedId}', [JobsController::class, 'workroom'])->name('seller.workroom');
Route::get('/job/workroom/timesheet/add/{encryptedId}', [JobsController::class, 'TimesheetAdd']);
//milestone route

// bid details page
Route::get('/job/list/bids/{id}', [HomeController::class, 'jobVacancybids'])->name('job.vacancy.bids');

Route::post('job/addbiding', [JobsController::class, 'addbiding'])->name('job.addbiding');

// remove these onces settle
Route::get('/job/list/accepted_bid/{id}', 'HomeController@jobVacancyacceptedbid')->name('job.vacancy.accepted_bid');
// Route::get('/job/list', [HomeController::class, 'jobVacancy'])->name('job.vacancy');
// Route::get('/job/list/offers', 'HomeController@jobVacancyoffers')->name('job.vacancy.offers');
Route::get('/job/list/completed', 'HomeController@jobVacancycompleted')->name('job.vacancy.completed');

Route::get('/job/list/offers/detail/{id}', 'HomeController@jobVacancyoffersdetail')->name('job.vacancy.offers.detail');
Route::get('/job/list/workspace/{id}', 'HomeController@jobVacancyworkspace')->name('job.vacancy.workspace');
Route::get('/job/list/hourlytimesheet/{id}', 'HomeController@jobVacancyhourlytimesheet')->name('job.vacancy.hourlytimesheet');

Route::get('/job/list/invoicetab/{idw}', 'HomeController@jobVacancyinvoicetab')->name('job.vacancy.invoicetab');
Route::get('/job/list/ratingtab/{id}', 'HomeController@jobVacancyratingtab')->name('job.vacancy.ratingtab');

Route::post('/job/list/bids/acceptresponce', 'HomeController@jobVacancybidsacceptresponce')->name('job.vacancy.bids.acceptresponce');
Route::post('/job/list/bids/sellercancelbid', 'HomeController@jobVacancybidssellercancelbid')->name('job.vacancy.bids.sellercancelbid');
Route::get('user/bid/sent/file/download/{id}', 'HomeController@userbidsentfileDownload')->name('bid.sent.file.download');
Route::get('user/bid/offer/file/download/{id}', 'HomeController@userbidofferfileDownload')->name('bid.offer.file.download');
Route::get('job/list/offers/offerresponce/{id}', 'HomeController@jobvacancyoffersoffer')->name('job.vacancy.offers.offer');
Route::post('job/list/offers/canceloffer', 'HomeController@jobvacancyofferscanceloffer')->name('job.vacancy.offers.canceloffer');
Route::post('job/list/offers/cancelresponce', 'HomeController@jobvacancyofferscancelresponce')->name('job.vacancy.offers.cancelresponce');

Route::post('job/list/offers/cancelresponce', 'HomeController@jobvacancyofferscancelresponce')->name('job.vacancy.offers.cancelresponce');
Route::post('job/list/offers/submitresponce', 'HomeController@jobvacancyofferssubmitresponce')->name('job.vacancy.offers.submitresponce');
Route::get('job/list/offers/offer/file/download/{id}', 'HomeController@jobvacancyofferfiledownload')->name('job.vacancy.offers.file.download');
Route::get('job/list/offers/offerresponce/file/download/{id}', 'HomeController@jobvacancyofferresponcefiledownload')->name('job.vacancy.offersresponce.file.download');
// worknew




Route::post('job/send/timesheet/work/bid', 'HomeController@sendtimesheetworkbid')->name('job.send.timesheet.work.bid');
Route::post('job/send/timesheet/get-amount', 'HomeController@getAmount');

Route::post('job/send/timesheet/work/offer', 'HomeController@sendtimesheetworkoffer')->name('job.send.timesheet.work.offer');
Route::get('user/timesheet/bid/file/download/{id}', 'HomeController@usertimesheetbidfileDownload')->name('timesheet.bid.file.download');
Route::get('user/timesheet/response/bid/file/download/{id}', 'HomeController@usertimesheetbidresponsefileDownload')->name('timesheet.response.bid.file.download');

//dispute
Route::get('dispute', 'UserController@seller_dispute')->name('seller.dispute');
Route::get('dispute/{type}/{id}', action: 'HomeController@userbidviewdispute')->name('seller.dispute.view');
Route::post('dispute/accept', 'HomeController@acceptworkbiddisputeoffer')->name('seller.dispute.accept');


// Route::get('dispute/case/{id}', 'HomeController@loadDisputeCaseIdentification')->name('seller.dispute.case');
// Route::get('dispute/negotiation/{id}', 'HomeController@loadDisputeNegotiation')->name('seller.dispute.negotiation');
Route::post('job/reject/work/bid/dispute/offer', 'HomeController@rejectworkbiddisputeoffer')->name('job.reject.work.bid.dispute.offer');
Route::post('job/work/bid/dispute/offer', 'HomeController@jobworkbiddisputeoffer')->name('job.work.bid.dispute.offer');
Route::post('job/work/bid/dispute/sendtoadmin', 'HomeController@jobworkbiddisputesendtoadmin')->name('job.work.bid.dispute.offer.sendtoadmin');
Route::post('job/completed/seller', 'HomeController@addedtocompletedseller')->name('job.completed.seller');
Route::post('jobtwo/completed/seller', 'HomeController@addedtocompletedtwoseller')->name('jobtwo.completed.seller');

Route::get('/job/list/detail/{id}', 'HomeController@jobListDetails')->name('seller.job.list.details');
Route::get('/follow/{id}', 'HomeController@follow')->name('follow');
Route::get('report-logs', 'HomeController@reportLogs')->name('seller.report-logs');
Route::get('transactions', 'HomeController@transactions')->name('seller.transactions');
Route::get('export-transaction', 'HomeController@exportTransactions')->name('seller.export.transaction');

// //Service
// Route::get('/service/index', 'ServiceController@index')->name('service.index');
// Route::get('/service/create', 'ServiceController@create')->name('service.create');
// Route::post('service/image-temp-upload', 'ServiceController@uploadTempImage')->name('service.image.temp.upload');
// Route::post('service/image-temp-delete', 'ServiceController@deleteTempImage')->name('service.image.temp.delete');

// Route::post('/service/store', 'ServiceController@store')->name('service.store');
// Route::post('/service/update/{id}', 'ServiceController@update')->name('service.update');
// Route::get('/service/edit/{slug}/{id}', 'ServiceController@edit')->name('service.edit');
// Route::post('/optional/image', 'ServiceController@optionalImageRemove')->name('optional.image');


Route::get('/category', 'UserController@category')->name('category');
Route::get('/subcategory', 'UserController@subcategory')->name('subcategory');
Route::get('/skillcategory', 'UserController@skillcategory')->name('skillcategory');
Route::get('/getskills', 'UserController@getskills')->name('getskills');
Route::get('/getskillsbyname', 'UserController@getskillsbyname')->name('getskillsbyname');
Route::post('/favorite/service/', 'UserController@serviceFavorites')->name('favorite.service');
Route::post('/favorite/software', 'UserController@softwareFavorite')->name('favorite.software');

//Software
Route::get('/software', 'SoftwareController@index')->name('software.index');
Route::get('/software/file/download/{id}', 'SoftwareController@softwareFileDownload')->name('software.file.download');
Route::get('/software/document/download/{id}', 'SoftwareController@softwareDocumentFile')->name('software.document.download');
Route::get('/software/upload', 'SoftwareController@create')->name('software.create');
Route::post('/software/store', 'SoftwareController@store')->name('software.store');
Route::get('/software/edit/{slug}/{id}', 'SoftwareController@edit')->name('software.edit');
Route::post('/software/update/{id}', 'SoftwareController@update')->name('software.update');

Route::get('/job/workroom/{encryptedId}', [JobsController::class, 'workroom'])->name('seller.workroom');
// Route::get('/workroom/overview/{encryptedId}', [JobsController::class, 'workroomOverview']);
// Route::get('/workroom/timesheet/{encryptedId}', [JobsController::class, 'workroomTimesheet']);
// Route::get('/workroom/chat/{encryptedId}', [JobsController::class, 'workroomChat']);
Route::get('/workspace/overview/{encryptedId}', [JobsController::class, 'workroomOverview'])->name('seller.workspace.overview');
Route::get('/workspace/timesheet/{encryptedId}', [JobsController::class, 'workroomTimesheet'])->name('seller.workspace.timesheet');
Route::get('/workspace/project/{encUserBidingId}', [JobsController::class, 'workroomProject'])->name('seller.workspace.byproject');
Route::get('/workspace/bymilestone/{encUserBidingId}', [JobsController::class, 'workroomMilestone'])->name('seller.workspace.bymilestone');
Route::post('/job/work/submit/project/{id}', 'HomeController@submitByProjectWorks')->name('seller.job.work.submit');
Route::get('/job/work/data/{id}', 'HomeController@getByProjectWork')->name('seller.job.work.data');
Route::get('/job/work/milestone/data/{id}', 'HomeController@getByMilestoneWork')->name('seller.job.work.milestone.data');
Route::post('job/work/milestone/submit', 'HomeController@submitByMileStoneWork')->name('seller.job.work.milestone.submit');
Route::post('job/work/send/offer', 'HomeController@sendjobworkoffer')->name('job.work.send.offer');
Route::get('user/work/send/file/download/{id}', 'HomeController@userworksentfileDownload')->name('work.send.file.download');
Route::get('user/work/send/file/offer/download/{id}', 'HomeController@userworksentofferfileDownload')->name('work.send.file.offer.download');
Route::get('user/work/send/counter/file/download/{id}', 'HomeController@userworksentcounterfileDownload')->name('work.send.counter.file.download');
Route::get('user/work/send/counter/file/offer/download/{id}', 'HomeController@userworksentcounterfileofferDownload')->name('work.send.counter.file.offer.download');

Route::get('/workspace/chat/{encryptedId}', [JobsController::class, 'workroomChat'])->name('seller.workspace.messages');
Route::get('/workspace/invoice/{encryptedId}', [JobsController::class, 'invoice'])->name('seller.workspace.invoice');
Route::get('/workspace/invoice/print/{invoiceNumber?}', [JobsController::class, 'printInvoice'])->name('seller.workspace.invoice.print');

Route::get('/workspace/rating/{encryptedId}', [JobsController::class, 'rating'])->name('seller.workspace.rating');
Route::post('/delete-intervals', [JobsController::class, 'deleteIntervals'])->name('seller.deleteIntervals');


Route::post('job/request/work/milestone/bid', 'HomeController@requestmsjobworkbid')->name('job.request.work.milestone.bid');
Route::post('job/request/work/milestone/offer', 'HomeController@requestmsjobworkoffer')->name('job.request.work.milestone.offer');
Route::post('job/accept/extra/work/milestone/bid', 'HomeController@acceptextramsworkbid')->name('job.accept.extra.work.milestone.bid');
Route::post('job/reject/extra/work/milestone/bid', 'HomeController@rejectextramsworkbid')->name('job.reject.extra.work.milestone.bid');

Route::post('job/accept/extra/work/milestone/offer', 'HomeController@acceptextramsworkoffer')->name('job.accept.extra.work.milestone.offer');
Route::post('job/reject/extra/work/milestone/offer', 'HomeController@rejectextramsworkoffer')->name('job.reject.extra.work.milestone.offer');
Route::get('/job/milestone/add/{encryptedId}', [JobsController::class, 'milestoneAdd'])->name('job.milestone.add');
Route::post('/payment/request', [JobsController::class, 'paymentRequest'])->name('seller.add.milestone');
Route::post('/add/timesheet', [JobsController::class, 'addTimesheet'])->name('seller.add.timesheet');
Route::post('/manual-log/request', [JobsController::class, 'requestManualLog'])->name('seller.manual.log.request');
Route::post('/update-memo', [JobsController::class, 'updateMemo'])->name('seller.update.memo');


Route::get('paypal/payment', [SellerPayPalController::class, 'payment'])->name('seller.paypal.payment');
Route::get('paypal/payment/success', [SellerPayPalController::class, 'paymentSuccess'])->name('seller.paypal.payment.success');
Route::get('paypal/payment/cancel', [SellerPayPalController::class, 'paymentCancel'])->name('seller.paypal.payment.cancel');
