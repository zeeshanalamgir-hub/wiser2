<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Services\BuyerJobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GlobalController extends Controller
{
    public function __construct(private BuyerJobService $buyerJobService)
    {

    }
    //
    public function getCurrentWalletBalance(Request $request){

        $user = Auth::user();
        $currentBalance = $this->buyerJobService->remainingBuyerWalletBalance($user->id);
        return $currentBalance;

    }
}
