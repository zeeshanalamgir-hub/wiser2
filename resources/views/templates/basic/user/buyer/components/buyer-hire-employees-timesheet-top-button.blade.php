<div class="header-top-buttons-10065">
    <a href="{{ route('user.buyer.hire.employ.offers', encrypt($singlejob->id)) }}"
        class="btn rounded sd-button-header-10067 ">
        Offers
    </a>
    <a href="{{ route('user.buyer.hire.employ.acceptedbidoffer', encrypt($singlejob->id)) }}"
        class="btn rounded sd-button-header-10067 ">
        Bid Overview
    </a>
    <a href="{{ route('user.buyer.hire.employ.acceptedbidoffer', encrypt($singlejob->id)) }}"
        class="btn rounded sd-button-header-10067 ">
        Offer Overview
    </a>
    <a href="{{ route('user.buyer.hire.employ.timesheet', encrypt($singlejob->id)) }}"
        class="btn rounded sd-button-header-10067 ">
        Hourly Timesheet
    </a>
    <a href="{{ route('user.buyer.hire.employ.workspace', encrypt($singlejob->id)) }}"
        class="btn rounded sd-button-header-10067 ">
        Workspace
    </a>
    <a href="#" class="btn rounded sd-button-header-10067 ">
        Chat with seller
    </a>
    <a href="{{ route('user.buyer.hire.employ.invoicetab', encrypt($singlejob->id)) }}"
        class="btn rounded sd-button-header-10067 ">
        Invoice
    </a>
    <a href="{{ route('user.buyer.hire.employ.rateandreview', encrypt($singlejob->id)) }}"
        class="btn rounded sd-button-header-10067 ">
        Rating & review
    </a>
</div>


<!-- <div class="col-lg-12 pb-3">

    @php

        $accepted1 = DB::table('user_biding')
            ->where('job_id', $singlejob->id)
            ->where('seller_offer_responce', '2')
            ->first();
        $accepted2 = DB::table('user_offers')
            ->where('job_id', $singlejob->id)
            ->where('buyer_offer_responce', '2')
            ->first();
    @endphp
    @if (@$accepted1->seller_offer_responce == '2')
<a href="{{ route('user.buyer.hire.employ.bids', encrypt($singlejob->id)) }}" class="btn rounded  theme-bg-light theme-cl fs-sm ft-medium "><i class="fa fa-angle-double-left"></i>Back</a>
@elseif(@$accepted2->buyer_offer_responce == '2')
<a href="{{ route('user.buyer.hire.employ.offers', encrypt($singlejob->id)) }}" class="btn rounded  theme-bg-light theme-cl fs-sm ft-medium ml-2">Offers</a>
@endif
    @if (@$accepted1->seller_offer_responce == '2' || @$accepted2->buyer_offer_responce == '2')
@if (@$accepted1->seller_offer_responce == '2')
<a href="{{ route('user.buyer.hire.employ.acceptedbidoffer', encrypt($singlejob->id)) }}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Bid Overview</a>
@elseif(@$accepted2->buyer_offer_responce == '2')
<a href="{{ route('user.buyer.hire.employ.acceptedbidoffer', encrypt($singlejob->id)) }}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Offer Overview</a>
@endif
@endif
    @if (@$accepted1->seller_offer_responce == '2' || @$accepted2->buyer_offer_responce == '2')
@if (@$accepted1->job_type == 'hourly' || @$accepted2->job_type == 'per_hour')
<a href="{{ route('user.buyer.hire.employ.timesheet', encrypt($singlejob->id)) }}" class="btn rounded  theme-bg text-light fs-sm ft-medium ml-2">Hourly Timesheet</a>
@else
<a href="{{ route('user.buyer.hire.employ.workspace', encrypt($singlejob->id)) }}" class="btn rounded  theme-bg text-light fs-sm ft-medium ml-2">Workspace</a>
@endif

    <a href="#" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Chat with
        seller</a>
    <a href="{{ route('user.buyer.hire.employ.invoicetab', encrypt($singlejob->id)) }}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Invoice</a>

    <a href="{{ route('user.buyer.hire.employ.rateandreview', encrypt($singlejob->id)) }}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Rating & review</a>
@endif
</div> -->
