<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Buyer\GlobalController;
use App\Http\Controllers\Buyer\HomeController;
use App\Http\Controllers\Buyer\JobController;
use App\Http\Controllers\Buyer\HireEmployees;
use App\Http\Controllers\Gateway\Papyal\PayPalController;
use App\Http\Controllers\WorkspaceController;

// global ajax apis that will be neded for buyer validations now and then
Route::prefix('global/ajax')->group(function () {
    Route::get('get-current-wallet-balance', [GlobalController::class, 'getCurrentWalletBalance']);

});
Route::get('dashboard', [HomeController::class, 'index'])->name('buyer.dashboard');
Route::get('profile', 'HomeController@profile')->name('buyer.profile');
Route::post('accept-terms', [HomeController::class, 'acceptTerms'])->name('buyer.accept.terms');
Route::get('deposit/history', 'HomeController@depositHistorytwo')->name('deposit.historytwo');
Route::get('report-logs', 'HomeController@reportLogs')->name('buyer.report-logs');
Route::get('transactions', 'HomeController@transactions')->name('buyer.transactions');
Route::post('save-transaction', 'HomeController@saveTransaction')->name('buyer.savetransaction');
Route::get('work/delivered/download/{id}', 'HomeController@workDeliveryDownload')->name('work.delivery.download');
//Employer memberships
Route::get('employermembership', 'EmployermembershipController@index')->name('buyer.employermembership');
Route::get('payment', 'EmployermembershipController@payment')->name('payment');
Route::post('payment', 'EmployermembershipController@updatemembership');
//Jobs pakages
Route::get('jobspackages', 'JobspackagesController@index')->name('jobspackages');
Route::get('jobspayment', 'JobspackagesController@payment')->name('jobspayment');
Route::post('jobspayment', 'JobspackagesController@updatemembership')->name('jobspayment');
//service
Route::get('service/booked', 'HomeController@serviceBookingItem')->name('buyer.service.booked');
Route::get('buyer/service/booking/cancelled', 'HomeController@buyerServiceBookingsCancelled')->name('buyer.booking.service.cancelled');
Route::get('buyer/service/booking/completed', 'HomeController@buyerServiceBookingsCompleted')->name('buyer.booking.service.completed');
Route::get('buyer/service/booking/active', 'HomeController@buyerServiceBookingsActive')->name('buyer.booking.service.active');

Route::get('service/booking/details/contract/{id}', 'HomeController@serviceBookingDetailsContract')->name('buyer.service.booked.details.contract');
Route::get('service/booking/details/workprogress/{id}', 'HomeController@serviceBookingDetailsWorkprogress')->name('buyer.service.booked.details.workprogress');
Route::get('service/booking/details/workcompleted/{id}', 'HomeController@serviceBookingDetailsWorkcompleted')->name('buyer.service.booked.details.workcompleted');
Route::get('service/booking/details/workcompleted1/{id}', 'HomeController@serviceBookingDetailsWorkcompleted1')->name('buyer.service.booked.details.workcompleted1');
Route::get('service/booking/details/{id}', 'HomeController@serviceBookingDetails')->name('buyer.service.booked.details');
Route::get('user/work/send/service/download/{id}', 'HomeController@userworkservicefileDownload')->name('buyer.work.send.service.download');
Route::get('user/work/response/service/download/{id}', 'HomeController@userworkresponseservicefileDownload')->name('buyer.work.response.service.download');

Route::get('favorite/service/item', 'HomeController@serviceFavoriteItem')->name('service.favorite');
Route::get('favorite/software/item', 'HomeController@softwareFavoriteItem')->name('software.favorite');
Route::post('work/delivery/approved', 'HomeController@workDeliveryApproved')->name('work.delivery.approved');
Route::post('work/delivery/approved/payed', 'HomeController@workDeliveryApprovedpayed')->name('work.delivery.approved.payed');
Route::post('pay/bid', 'HomeController@bidpayment')->name('buyer.pay.bid');
Route::post('pay/offer', 'HomeController@offerpayment')->name('buyer.pay.offer');
Route::post('pay/bidhourly', 'HomeController@bidhourlypayment')->name('buyer.pay.bid.hourly');
Route::post('pay/offerhourly', 'HomeController@offerhourlypayment')->name('buyer.pay.offer.hourly');
Route::post('work/delivery/rejected', 'HomeController@workDeliveryrejected')->name('work.delivery.rejected');
Route::post('proposal/approved', 'HomeController@proposalApproved')->name('proposal.approved');

Route::get('work/view/dispute/offers/{id}', 'HomeController@viewdisputeoffers')->name('buyer.work.view.dispute.offers');
// Route::post('hire/employees/dispute/offer/work/bid1/sendtoadmin', 'HomeController@hireEmploydisputeofferworkbidsendtoadmin1')->name('buyer.dispute.offer.work.bid.sendtoadmin1');
//software
Route::get('software/purchases/list', 'HomeController@softwarePurchases')->name('software.purchases');
Route::get('software/file/download/{id}', 'HomeController@buyerSoftwareFileDownload')->name('buyer.software.file.download');
Route::get('software/document/download/{id}', 'HomeController@buyerSoftwareDocumentFile')->name('buyer.software.document.download');
Route::get('hire/employees', [HomeController::class, 'hireEmploy' ])->name('buyer.hire.employ');
Route::get('hire/open', [HomeController::class, 'openJobs' ])->name('buyer.hire.open');
Route::get('close/job/{encryptedId}', [HireEmployees::class, 'closeJob'])->name('closed.job');
Route::get('hire/offered-jobs', [HireEmployees::class, 'offeredJobList'])->name('offered.jobs');
Route::get('hire/offered-jobs/details/{encryptedId}', [HireEmployees::class, 'offeredJobDetails'])->name('offered.jobs.details');

Route::prefix('hire/employees/list/ajax')->group(function () {
    Route::get('/open-jobs', [HireEmployees::class, 'hireEmployOpenJobsAjax'])->name('buyer.hire.open.ajax');
    Route::get('/offered-jobs', [HireEmployees::class, 'offeredJobListAjax'])->name('offered.jobs.ajax');
    Route::get('closed-jobs', [HireEmployees::class, 'hireEmployClosedJobs']);
    Route::post('close-job-from-buyer', [HireEmployees::class, 'hireEmployCancelJobFromBuyer']);
    Route::get('bids-list', [HomeController::class, 'hireEmploybidsList']);
    Route::get('bids-list/buyer-offer-fixed-bid-details', [HireEmployees::class, 'buyerOfferFixedBidDetails']);
    //Route::get('/open-offers', [HireEmployees::class, 'offeredJobList']);
    Route::get('/active-offers', [HireEmployees::class, 'activeJobList']);
    Route::get('/completed-job', [HireEmployees::class, 'completedJobList']);
    // Route::get('/workroom/{encryptedId}', [HireEmployees::class, 'workroom']);
    // Route::get('/workroom/overview/{encryptedId}', [HireEmployees::class, 'workroomOverview']);
    // Route::get('/workroom/milestone/{encryptedId}', [HireEmployees::class, 'workroomMilestone']);
    //Route::get('/workroom/timesheet/{encryptedId}', [HireEmployees::class, 'workroomTimesheet']);

});
Route::get('/workspace/project/{encUserBidingId}', [HireEmployees::class, 'workroomProject'])->name('buyer.workspace.byproject');
Route::get('/workspace/overview/{encryptedId}', [WorkspaceController::class, 'overview'])->name('workspace.overview');
Route::get('/workspace/timesheet/{encryptedId}', [WorkspaceController::class, 'workroomTimesheet'])->name('workspace.timesheet');
Route::get('/workspace/messages/{encryptedId}', [WorkspaceController::class, 'messages'])->name('workspace.messages');
Route::get('/workspace/invoice/{encryptedId}', [WorkspaceController::class, 'invoice'])->name('workspace.invoice');
Route::get('/workspace/rating/{encryptedId}', [WorkspaceController::class, 'rating'])->name('workspace.rating');
Route::get('/job/work/data/{id}', 'HireEmployees@getByProjectWork')->name('buyer.job.work.data');
Route::post('hire/employees/accept/work/bid', 'HomeController@hireEmployacceptworkbid')->name('buyer.accept.work.bid');
Route::post('hire/employees/reject/work/bid', 'HomeController@hireEmployrejectworkbid')->name('buyer.reject.work.bid');
Route::get('/workspace/bymilestone/{encUserBidingId}', [HireEmployees::class, 'workroomMilestone'])->name('buyer.workspace.bymilestone');
Route::get('/job/work/milestone/data/{id}', 'HireEmployees@getByMilestoneWork')->name('buyer.job.work.milestone.data');
Route::get('hire/employees/active-job/{id}', [HireEmployees::class, 'activeJobById']);
Route::post('store/job/bonus', [WorkspaceController::class, 'storeJobBonus'])->name('buyer.store.job.bonus');
Route::get('/workspace/invoice/print/{invoiceNumber}', [WorkspaceController::class, 'printBuyerInvoice'])->name('buyer.workspace.invoice.print');


// bid details
Route::get('hire/employees/bids/{id}', [HomeController::class, 'hireEmploybids'])->name('buyer.hire.employ.bids');
Route::get('hire/employees/bids/send-offer/{id}', [HireEmployees::class, 'hireEmploybidsendoffer'])->name('buyer.send.bid.offer');
Route::post('hire/employees/bids/submit-offer', [HireEmployees::class, 'hireEmploybidsubmitoffer'])->name('buyer.submit.bid.offer');

Route::get('offered/employees', 'HomeController@offeredEmploy')->name('buyer.offered.employ');

Route::get('hire/employees/offers/{id}', 'HomeController@hireEmployoffers')->name('buyer.hire.employ.offers');


Route::get('hire/employees/active-jobs/overview/{id}', 'HomeController@activeJobOverview')->name('buyer.hire.employ.offers');



Route::get('hire/employees/workspace/{id}', 'HomeController@hireEmployworkspace')->name('buyer.hire.employ.workspace');
Route::get('hire/employees/timesheet/{id}', 'HomeController@hireEmploytimesheet')->name('buyer.hire.employ.timesheet');
Route::get('hire/employees/acceptedbidoffer/{id}', 'HomeController@hireEmployacceptedbidoffer')->name('buyer.hire.employ.acceptedbidoffer');
Route::get('hire/employees/rateandreview/{id}', 'HomeController@hirerateandreview')->name('buyer.hire.employ.rateandreview');

Route::get('hire/employees/invoicetab/{id}', 'HomeController@hireinvoicesec')->name('buyer.hire.employ.invoicetab');

Route::get('hire/employees/acceptedofferoffer/{id}', 'HomeController@hireEmployacceptedofferoffer')->name('buyer.hire.employ.acceptedofferoffer');

Route::get('hire/employees/completed', 'HomeController@hireEmploycompleted')->name('buyer.hire.employ.completed');
Route::get('hire/employees/closed', 'HomeController@hireEmployclosed')->name('buyer.hire.employ.closed');
Route::get('hire/employees/hourlytimesheet', 'HomeController@hireEmployhourlytimesheet')->name('buyer.hire.employ.hourlytimesheet');


Route::get('hire/employees/bids/cancel-offer/{id}', 'HomeController@hireEmploybidcanceloffer')->name('buyer.cancel.bid.offer');
Route::get('hire/employees/bids/cancel-bid/{id}', 'HomeController@hireEmploybidcancelbid')->name('buyer.get.cancel.bid');
Route::post('hire/employees/bids/cancel-bid', 'HomeController@hireEmploybidcancelbid')->name('buyer.cancel.bid');
Route::get('hire/offersent/file/download/{id}', 'HomeController@employoffersendfiledownload')->name('buyer.hire.offer.file.download');
Route::get('hire/counter/file/download/{id}', 'HomeController@employcounterofferfiledownload')->name('buyer.counter.offer.file.download');
Route::post('hire/employees/cancel/his-offer', 'HomeController@hireEmploycancelhisoffer')->name('buyer.cancel.hisoffer');
Route::post('hire/employees/cancel/his-offerresponce', 'HomeController@hireEmploycancelhisofferresponce')->name('buyer.cancel.hisofferresponce');
Route::post('hire/employees/accept/his-offerresponce', 'HomeController@hireEmployaccepthisofferresponce')->name('buyer.accept.hisofferresponce');

Route::get('hire/hisoffer/file/download/{id}', 'HomeController@employhisofferfiledownload')->name('buyer.hisoffer.file.download');
Route::get('hire/hisofferresponce/file/download/{id}', 'HomeController@employhisofferresponcefiledownload')->name('buyer.hisofferresponce.file.download');

// workbuyer

Route::post('hire/employees/accept/work/offer', 'HomeController@hireEmployacceptworkoffer')->name('buyer.accept.work.offer');
Route::post('hire/employees/reject/work/offer', 'HomeController@hireEmployrejectworkoffer')->name('buyer.reject.work.offer');
Route::get('hire/work/recieve/file/download/{id}', 'HomeController@employworkrecievefiledownload')->name('buyer.work.recieve.file.download');
Route::get('hire/work/recieve/counter/file/download/{id}', 'HomeController@employworkrecievecounterfiledownload')->name('buyer.hire.work.recieve.counter.file.download');
Route::post('hire/employees/request/work/milestone/bid', 'HomeController@hireEmployrequestworkmsbid')->name('buyer.request.work.milestone.bid');

Route::post('hire/employees/request/work/milestone/offer', 'HomeController@hireEmployrequestworkmsoffer')->name('buyer.request.work.milestone.offer');
Route::post('hire/employees/accept/extra/work/milestone/bid', 'HomeController@hireEmployacceptextraworkmsbid')->name('buyer.accept.extra.work.milestone.bid');
Route::post('hire/employees/reject/extra/work/milestone/bid', 'HomeController@hireEmployrejectextraworkmsbid')->name('buyer.reject.extra.work.milestone.bid');
Route::post('hire/employees/accept/extra/work/milestone/offer', 'HomeController@hireEmployacceptextraworkmsoffer')->name('buyer.accept.extra.work.milestone.offer');
Route::post('hire/employees/reject/extra/work/milestone/offer', 'HomeController@hireEmployrejectextraworkmsoffer')->name('buyer.reject.extra.work.milestone.offer');
Route::post('hire/employees/accept/timesheet/bid', 'HomeController@hireEmployacceptworktimesheetbid')->name('buyer.accept.timesheet.bid');
Route::post('hire/employees/rejected/timesheet/bid', 'HomeController@hireEmployrejectedworktimesheetbid')->name('buyer.rejected.timesheet.bid');
Route::post('hire/employees/accept/timesheet/offer', 'HomeController@hireEmployacceptworktimesheetoffer')->name('buyer.accept.timesheet.offer');
Route::post('hire/employees/rejected/timesheet/offer', 'HomeController@hireEmployrejectedworktimesheetoffer')->name('buyer.rejected.timesheet.offer');
Route::get('hire/timesheet/file/download/{id}', 'HomeController@employtimesheetfiledownload')->name('buyer.timesheet.bid.file.download');
// Ratings
Route::post('hire/employees/rating', 'HomeController@hireEmployrating')->name('buyer.rating');
Route::post('hire/employees/rating/bid', 'HomeController@hireEmployratingbid')->name('buyer.rating.bid');
Route::post('hire/employees/rating/bid/edit', 'HomeController@hireEmployratingbidedit')->name('buyer.rating.bid.edit');
Route::post('hire/employees/rating/offer/edit', 'HomeController@hireEmployratingofferedit')->name('buyer.rating.offer.edit');
//Route::post('hire/employees/rating/service', 'HomeController@hireEmployratingservice')->name('buyer.rating.service');
Route::post('hire/employees/rating/service/{service}', 'HomeController@hireEmployratingservice')
    ->name('buyer.rating.service');

Route::get('hire/timesheet/counter/file/download/{id}', 'HomeController@employtimesheetcounterfiledownload')->name('buyer.timesheet.response.bid.file.download');

//dispute
Route::get('dispute', action: 'HomeController@dispute')->name(name: 'buyer.dispute');
Route::get('dispute/{type?}/{id?}', 'HomeController@hireEmployviewbiddispute')->name('buyer.dispute.view');

// Route::get('dispute/case/{id}', 'HomeController@loadDisputeCaseIdentification')->name('buyer.dispute.case');
// Route::get('dispute/case/{type}/{id}', 'HomeController@loadDisputeCase')->name('buyer.dispute.case');
Route::get('dispute/negotiation/{type}/{id}', 'HomeController@loadDisputeNegotiation')->name('buyer.dispute.negotiation');
Route::get('dispute/amount', 'HomeController@getActivitiesCount')->name('buyer.dispute.amount');
Route::post('hire/employees/dispute/offer/work/bid', 'HomeController@hireEmploydisputeofferworkbid')->name('buyer.dispute.offer.work.bid');
Route::post('work/dispute/{encbuyerCounterOfferDataId}', 'HomeController@workDispute')->name('work.dispute');
Route::post('work/intervals/dispute', 'HomeController@disputeIntervals')->name('buyer.intervals.dispute');
//service dispute
Route::post('service/dispute/create/{orderNumber}', 'HomeController@createServiceDispute')->name('service.dispute.create');


Route::post('hire/employees/dispute/work/bid', 'HomeController@hireEmploydisputeworkbid')->name('buyer.dispute.work.bid');
Route::post('hire/employees/dispute/work/offer', 'HomeController@hireEmploydisputeworkoffer')->name('buyer.dispute.work.offer');
Route::post('hire/employees/dispute/offer/work/bid/accept', 'HomeController@hireEmploydisputeofferworkbidaccept')->name('buyer.dispute.offer.work.bid.accept');
Route::post('hire/employees/dispute/offer/work/bid/decline', 'HomeController@hireEmploydisputeofferworkbiddecline')->name('buyer.dispute.offer.work.bid.decline');
Route::post('hire/employees/dispute/offer/work/bid/sendtoadmin', 'HomeController@hireEmploydisputeofferworkbidsendtoadmin')->name('buyer.dispute.offer.work.bid.sendtoadmin');
Route::post('buyer/service/dispute/offer/accept', 'HomeController@acceptbuyerservicedisputeoffer')->name('buyer.dispute.offer.servie.accept');
Route::post('buyer/service/dispute/offer/decline', 'HomeController@declinebuyerservicedisputeoffer')->name('buyer.dispute.offer.servie.decline');
Route::post('buyer/service/dispute/offer/new', 'HomeController@newbuyerservicedisputeoffer')->name('buyer.dispute.offer.servie.new');
Route::post('buyer/service/dispute/offer/sendadmin', 'HomeController@sendadminbuyerservicedisputeoffer')->name('buyer.dispute.offer.servie.sendadmin');

Route::get('hire/employees/details/{id}', 'HomeController@hireEmployDetails')->name('buyer.hire.employ.details');
//Job
Route::get('job/create', [JobController::class, 'create'])->name('job.create');
Route::get('job/edit/{encryptedJobId}', [JobController::class, 'editJob'])->name('job.edit');
Route::get('delete/extra/skill/{id}', [JobController::class, 'removeExtraSkill'])->name('job.extra.skill.delete');

Route::post('job/store', [JobController::class, 'store'])->name('job.store');
Route::get('job/index',  [JobController::class, 'index'])->name('job.index');

Route::prefix('list/ajax')->group(function () {
    Route::get('manage-jobs', [JobController::class, 'allJob'])->name('buyer.job.all');
});

//Route::get('job/edit/{slug}/{id}', 'JobController@edit')->name('job.edit');
Route::get('job/delete/{id}', 'JobController@delete')->name('job.delete');
Route::post('job/update/{id}', 'JobController@update')->name('job.update');
Route::post('job/cancel', 'JobController@cancelBy')->name('job.cancel');
Route::post('job/completed', 'JobController@addedtocompleted')->name('job.completed');
//job response created by maaz
Route::get('job/response', 'JobController@jobresponse')->name('job.response');
Route::get('job/assignjoblist', 'JobController@assignjoblist')->name('job.assignjoblist');
Route::get('job/completejoblist', 'JobController@assignjoblist')->name('job.completejoblist');

Route::get('job/responsedetail/{id}', 'JobController@jobresponsedetail')->name('job.responsedetail');
Route::post('job/assignjob', 'JobController@assignjob')->name('job.assignjob');
Route::get('job/completedjob', 'JobController@completed_job')->name('job.completedjob');
Route::post('/manual-log/response', [WorkspaceController::class, 'handleManualLogResponse'])->name('buyer.manual.log.response');

// Withdraw
Route::get('/withdraw', 'HomeController@withdrawMoney')->name('buyer.withdraw');
Route::post('/withdraw', 'HomeController@withdrawStore')->name('buyer.withdraw.money');
Route::get('/withdraw/preview', 'HomeController@withdrawPreview')->name('buyer.withdraw.preview');
Route::post('/withdraw/preview', 'HomeController@withdrawSubmit')->name('buyer.withdraw.submit');
Route::get('/withdraw/history', 'HomeController@withdrawLog')->name('buyer.withdraw.history');


Route::get('paypal', [PayPalController::class, 'index'])->name('buyer.paypal');

Route::get('paypal/payment', [PayPalController::class, 'payment'])->name('buyer.paypal.payment');

Route::get('paypal/payment/success', [PayPalController::class, 'paymentSuccess'])->name('buyer.paypal.payment.success');

Route::get('paypal/payment/cancel', [PayPalController::class, 'paymentCancel'])->name('buyer.paypal.payment.cancel');

Route::get('change-password', 'HomeController@changePassword')->name('buyer.change.password');
Route::post('change-password', 'HomeController@submitPassword');
