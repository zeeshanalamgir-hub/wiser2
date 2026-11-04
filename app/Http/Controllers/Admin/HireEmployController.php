<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Buyercounteroffer;
use App\Models\Buyercounterofferdata;
use App\Models\GeneralSetting;
use App\Models\Jobdisputebid;
use App\Models\Jobworkdeliverybid;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Userbiding;
use App\Models\WorkDelivery;
use App\Services\MembershipService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HireEmployController extends Controller
{
    public function __construct(private MembershipService $membershipService) {}

    public function details($id)
    {
        $pageTitle = "Hire Employees Details";
        $emptyMessage = "No data found";

        // Fetch data from buyer_counter_offers_data and related tables
        $offerData = Buyercounterofferdata::with(['jobWorkDeliveryBid', 'buyerCounterOffer.job'])->findOrFail($id);
        $dispute = Jobdisputebid::with('disputeNegotiations')->where('offer_id', $offerData->id)->first();
        // Fetch booking data related to the job_id
        $booking = Booking::where('job_id', $offerData->buyerCounterOffer->job->id ?? null)
            ->with(['buyer', 'seller'])
            ->first();

        $booking = Booking::where('job_id', $booking->job_id)->first();
        $buyerId = $booking->buyer->id ?? null;
        $sellerId = $booking->seller->id ?? null;

        $messages = DB::table('ch_messages')
            ->where('ch_messages.job_id', $booking->job_id)
            ->join('users as from_user', 'from_user.id', '=', 'ch_messages.from_id')
            ->join('users as to_user', 'to_user.id', '=', 'ch_messages.to_id')
            ->select(
                'ch_messages.body',
                'ch_messages.from_id',
                'ch_messages.to_id',
                'ch_messages.created_at',
                'from_user.name as sender_name',
                'to_user.name as receiver_name'
            )
            ->orderBy('ch_messages.created_at', 'asc')
            ->get();
        return view('admin.hire.details', compact('pageTitle', 'emptyMessage', 'offerData', 'dispute', 'booking', 'messages','buyerId'));
    }

    protected function getJobMessages($jobId)
    {
        $booking = Booking::where('job_id', $jobId)->firstOrFail();
        $buyerId = $booking->buyer->id ?? null;
        $sellerId = $booking->seller->id ?? null;

        if (!$buyerId || !$sellerId) {
            return response()->json(['error' => 'No participants found for this job.'], 404);
        }

        $messages = DB::table('ch_messages')
            ->where('ch_messages.job_id', $jobId)
            ->join('users as from_user', 'from_user.id', '=', 'ch_messages.from_id')
            ->join('users as to_user', 'to_user.id', '=', 'ch_messages.to_id')
            ->select(
                'ch_messages.body',
                'ch_messages.from_id',
                'ch_messages.to_id',
                'ch_messages.created_at',
                'from_user.name as sender_name',
                'to_user.name as receiver_name'
            )
            ->orderBy('ch_messages.created_at', 'asc')
            ->get();

        return [$messages,$buyerId,$sellerId];
    }


    public function index()
    {
        $pageTitle = "Hire Employees list";
        $emptyMessage = "No data found";
        $bookings = Booking::where('status', '!=', '0')->whereNotNull('job_id')->with('buyer', 'seller')->latest()->paginate(getPaginate());
        return view('admin.hire.index', compact('pageTitle', 'emptyMessage', 'bookings'));
    }

    public function completed()
    {
        $pageTitle = "Work completed  list";
        $emptyMessage = "No data found";
        $bookings = Booking::where('status', '!=', '0')
            ->whereIn('working_status', [1, 4, 6]) // Fetch records with working_status 1 or 4
            ->whereNotNull('job_id')
            ->with(['buyer', 'seller', 'buyerCounterOffers.buyerCounterOfferData' => function ($query) {
                $query->where('milestone_status', 3); // Filter BuyerCounterOfferData with milestone_status = 3 (Paid)
            }])
            ->whereHas('buyerCounterOffers.buyerCounterOfferData', function ($query) {
                $query->where('milestone_status', 3); // Ensure milestone_status = 3 exists
            })
            ->latest()
            ->paginate(getPaginate());
        return view('admin.hire.index', compact('pageTitle', 'emptyMessage',  'bookings'));
    }
    public function delivered()
    {
        $pageTitle = "Work delivered list";
        $emptyMessage = "No data found";
        $bookings = Booking::where('status', '!=', '0')->where('working_status', 4)->whereNotNull('job_id')
            ->with(['buyer', 'seller', 'buyerCounterOffers.buyerCounterOfferData' => function ($query) {
                $query->where('milestone_status', 2); // Filter Buyercounterofferdata with milestone_status = 2
            }])->whereHas('buyerCounterOffers.buyerCounterOfferData', function ($query) {
                $query->where('milestone_status', 2);
            })->latest()->paginate(getPaginate());
        return view('admin.hire.index', compact('pageTitle', 'emptyMessage', 'bookings'));
    }
    public function inprogress()
    {
        $pageTitle = "Work inprogress list";
        $emptyMessage = "No data found";
        $bookings = Booking::where('status', '!=', '0')
            ->where('working_status', 4)
            ->whereNotNull('job_id')
            ->with(['buyer', 'seller', 'buyerCounterOffers.buyerCounterOfferData' => function ($query) {
                $query->whereIn('milestone_status', [0, 1]); // Fetch BuyerCounterOfferData with milestone_status 0 or 1
            }])
            ->whereHas('buyerCounterOffers.buyerCounterOfferData', function ($query) {
                $query->whereIn('milestone_status', [0, 1]); // Filter for milestone_status 0 or 1
            })
            ->latest()
            ->paginate(getPaginate());
        return view('admin.hire.index', compact('pageTitle', 'emptyMessage', 'bookings'));
    }
    // this Job can hadled by
    public function expired()
    {
        $pageTitle = "Work expired list";
        $emptyMessage = "No data found";
        // $bookings = Booking::where('status', '!=', '0')->where('working_status', 5)->with('user', 'biding.user')->latest()->paginate(getPaginate());
        $bookings = Booking::where('working_status', 10)->with('user', 'biding.user')->latest()->paginate(getPaginate());
        $bookings_bid = DB::table('accepted_bid')->where('status', '4')->paginate(getPaginate());
        return view('admin.hire.index', compact('pageTitle', 'emptyMessage', 'bookings_bid', 'bookings'));
    }

    public function dispute()
    {
        $pageTitle = "Work Dispute List";
        $emptyMessage = "No data found";

        $bookings = Booking::query()
            ->where('status', '!=', '0')
            ->whereIn('working_status', [4, 6])
            ->whereNotNull('job_id')
            ->with([
                'buyer',
                'seller',
                'buyerCounterOffers.buyerCounterOfferData' => function ($query) {
                    $query->where('milestone_status', 6); // Fetch BuyerCounterOfferData with milestone_status 6
                },
                'job.jobWorkDeliveryHourlyBids.intervals' => function ($query) {
                    $query->where('status', 6); // Fetch intervals with status 6
                }
            ])
            ->where(function ($query) {
                $query->whereHas('buyerCounterOffers.buyerCounterOfferData', function ($subQuery) {
                    $subQuery->where('milestone_status', 6); // Filter for milestone_status 6
                })
                    ->orWhereHas('job.jobWorkDeliveryHourlyBids.intervals', function ($subQuery) {
                        $subQuery->where('status', 6); // Filter for job_work_intervals with status 6
                    });
            })
            ->orderByDesc('created_at') // Order by latest created records
            ->paginate(getPaginate());
        return view('admin.hire.index', compact('pageTitle', 'emptyMessage', 'bookings'));
    }

    public function workFileDownload($id)
    {
        $work = Jobworkdeliverybid::where('id', $id)->firstOrFail();
        $file = $work->file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path . '/' . $file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="' . $file . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($full_path);
    }

    public function disputeFileDownload($id)
    {
        $work = Jobdisputebid::where('id', $id)->firstOrFail();
        $file = $work->file;
        $path = imagePath()['disputes']['path'];
        $full_path = $path . '/' . $file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="' . $file . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($full_path);
    }

    public function payment(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:job_dispute_bid,id',
            'payment' => 'required|in:seller,buyer'
        ]);

        $general = GeneralSetting::first();
        $dispute = Jobdisputebid::find($request->id);

        if (!$dispute) {
            $notify[] = ['error', 'Dispute not found.'];
            return back()->withNotify($notify);
        }
        $dispute->status = 1;
        $dispute->save();
        // Fetch booking info using job_id
        $booking = Booking::where('job_id', $dispute->job_id)->firstOrFail();

        if ($request->payment == "seller") {
            $booking->status = 3; // Paid to Seller
            //$booking->working_status = 6; // Dispute Resolved
            $booking->status_updated_at = Carbon::now();
            $booking->save();

            $totalAmount = $dispute->dispute_amount;

            // Calculate platform fee
            $platformFeePercentage = $this->membershipService->getMembershipFee($booking->seller_id);
            $platformFee = round($totalAmount * $platformFeePercentage / 100, 2);
            $payableAmount = $totalAmount - $platformFee;

            // Update seller balance
            $seller = User::where('id', $booking->seller_id)->firstOrFail();
            $seller->balance += $payableAmount;
            $seller->income += $payableAmount;
            $seller->save();

            rankUser($seller->id);

            // Transaction for seller earnings
            $transactionSellerEarnings = new Transaction();
            $transactionSellerEarnings->user_id = $booking->seller->id;
            $transactionSellerEarnings->to_user = $seller->id;
            $transactionSellerEarnings->booking_id = $booking->id;
            $transactionSellerEarnings->amount = $payableAmount;
            $transactionSellerEarnings->charge = 0;
            $transactionSellerEarnings->post_balance = $seller->balance;
            $transactionSellerEarnings->trx_type = '+';
            $transactionSellerEarnings->trx = $booking->order_number;
            $transactionSellerEarnings->details = "Earnings for " . $booking->order_number;
            $transactionSellerEarnings->save();

            // Transaction for platform fee (deducted from seller)
            $transactionPlatformFee = new Transaction();
            $transactionSellerEarnings->user_id = $booking->seller->id;
            $transactionSellerEarnings->to_user = $seller->id;
            $transactionSellerEarnings->booking_id = $booking->id;
            $transactionPlatformFee->amount = $platformFee;
            $transactionPlatformFee->charge = 0;
            $transactionPlatformFee->post_balance = $seller->balance;
            $transactionPlatformFee->trx_type = '-';
            $transactionPlatformFee->trx = $booking->order_number;
            $transactionPlatformFee->details = "Platform fee for " . $booking->order_number;
            $transactionPlatformFee->save();

            notify($seller, 'PAYMENT_SELLER', [
                'amount' => getAmount($payableAmount),
                'currency' => $general->cur_text,
                'order_number' => $booking->order_number,
                'post_balance' => getAmount($seller->balance)
            ]);

            sendJobEmailWithNotification(Auth::guard('admin')->id(), $dispute->seller_id, $dispute->job_id, 'ADMIN_RESOLVED_DISPUTE', true, jobDisputeBidId: $dispute->id, is_job_service: 'job');
            sendJobEmailWithNotification(Auth::guard('admin')->id(), $dispute->buyer_id, $dispute->job_id, 'ADMIN_RESOLVED_DISPUTE', true, jobDisputeBidId: $dispute->id, is_job_service: 'job');

            $notify[] = ['success', 'Seller payment complete.'];
            return back()->withNotify($notify);
        } elseif ($request->payment == "buyer") {
            $booking->status = 4; // Refunded to Buyer
            //$booking->working_status = 6; // Dispute Resolved
            $booking->status_updated_at = Carbon::now();
            $booking->save();

            $totalAmount = $dispute->dispute_amount;

            // Add the full amount to the buyer's wallet
            $buyer = User::where('id', $booking->buyer_id)->firstOrFail();
            $buyer->balance += $totalAmount;
            $buyer->save();

            // Transaction for refund to buyer
            $transactionRefund = new Transaction();
            $transactionRefund->user_id = $buyer->id;
            $transactionRefund->to_user = $buyer->id;
            $transactionRefund->booking_id = $booking->id;
            $transactionRefund->amount = $totalAmount;
            $transactionRefund->post_balance = $buyer->balance;
            $transactionRefund->trx_type = '+';
            $transactionRefund->trx = $booking->order_number;
            $transactionRefund->details = "Refund Money " . $booking->order_number;
            $transactionRefund->save();

            notify($buyer, 'MONEY_REFUND', [
                'amount' => getAmount($totalAmount),
                'currency' => $general->cur_text,
                'order_number' => $booking->order_number,
                'post_balance' => getAmount($buyer->balance)
            ]);

            sendJobEmailWithNotification(Auth::guard('admin')->id(), $dispute->seller_id, $dispute->job_id, 'ADMIN_RESOLVED_DISPUTE', true, jobDisputeBidId: $dispute->id, is_job_service: 'job');
            sendJobEmailWithNotification(Auth::guard('admin')->id(), $dispute->buyer_id, $dispute->job_id, 'ADMIN_RESOLVED_DISPUTE', true, jobDisputeBidId: $dispute->id, is_job_service: 'job');


            $notify[] = ['success', 'Refund money to the buyer.'];
            return back()->withNotify($notify);
        }
    }

    public function search(Request $request, $scope)
    {
        $search = $request->search;
        $bookings = Booking::where('status', '!=', '0')->whereNotNull('job_biding_id')
            ->whereHas('user', function ($q) use ($search) {
                $q->where('username', 'like', "%$search%");
            })
            ->orWhereHas('biding.user', function ($q) use ($search) {
                $q->where('username', 'like', "%$search%");
            });

        $pageTitle = '';
        switch ($scope) {
            case 'completed':
                $pageTitle .= 'Completed ';
                $bookings = $bookings->where('working_status', 1);
                break;
            case 'delivered':
                $pageTitle .= 'Delivered ';
                $bookings = $bookings->where('working_status', 2);
                break;
            case 'inprogress':
                $pageTitle .= 'Inprogress ';
                $bookings = $bookings->where('working_status', 4);
                break;
            case 'expired':
                $pageTitle .= 'Expired ';
                $bookings = $bookings->where('working_status', 5);
                break;
            case 'dispute':
                $pageTitle .= 'Dispute ';
                $bookings = $bookings->where('working_status', 6);
                break;
        }
        $bookings = $bookings->latest()->paginate(getPaginate());
        $pageTitle .= 'Hire Employs search by - ' . $search;
        $emptyMessage = 'No data found';
        return view('admin.hire.index', compact('pageTitle', 'search', 'scope', 'emptyMessage', 'bookings'));
    }
}