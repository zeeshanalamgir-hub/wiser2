@extends('templates.basic.layouts.wiserlance.dashboard-layout-job')
@section('content')
    <p class="text-bc fs-14">
        <a class="text-3gray" href="{{ route('user.home') }}"><i class="fa-solid fa-house fs-14"></i></a>
        <i class="fa-solid fa-chevron-right fs-12"></i> Freelancer Bid Packages
    </p> <!--Breadcrumbs End-->
    <div class="row mt-3">
        <div class="col-12">
            <h5 class="text-dgreen2 ms-2">Freelancer Bids Packages</h5>
        </div>
    </div>
    <div class="row gx-2 mt-md-3">
        @foreach ($bidspakages as $m)
            <div class="membership-card col-md-6 col-lg-4 col-xl-6 mt-4 mt-md-0">
                <div class="border-secondary shadow rounded">
                    <ul class="list-group fs-14">
                        {{-- Dynamic Background Class for Title --}}
                        <li
                            class="list-group-item text-center fw-bold p-2 fs-5 text-white
                        {{ $m->title == 'Try it free' ? 'bg-secondary' : ($m->title == 'Basic' ? 'bg-dgreen2' : ($m->title == 'STANDARD' ? 'bg-green' : 'bg-dgreen')) }}">
                            <span class="fs-14 fw-normal">{{ $m->title }}</span>
                        </li>

                        {{-- Price --}}
                        <li class="list-group-item fs-5 fw-bold text-green text-center bg-light">
                            {{ $m->price == 0 ? 'Free' : '$' . $m->price }}
                        </li>

                        {{-- Dynamic Details --}}
                        <li class="list-group-item">Bids <span class="float-end">{{ $m->bids }}</span></li>
                        <li class="list-group-item">Services <span class="float-end">{{ $m->services }}</span></li>
                        <li class="list-group-item">Featured Services<span class="float-end">{{ $m->f_services }}</span></li>
                        <li class="list-group-item">Featured Jobs Bids <span
                                class="float-end">{{ $m->f_services == 0 ? 'No' : 'Yes' }}</span></li>
                    </ul>
                </div>
            </div>
        @endforeach
        <div class="col-md-6 col-lg-4 col-xl-6">
            <div class="priceboxnew">
                <form method="POST" action="{{ url('/user/seller/bidspayment') }}">
                    @csrf
                    <input name="id" value="{{ $id }}" type="hidden" />
                    <div class="modal-body">
                        <div class="form-group">
                            <h5>@lang('How you want to pay')</h5>
                            <select class="form-control" name="payment">
                                <option value="wallet">{{ __($general->sitename) }} @lang('wallet')</option>
                                <!--<option value="checkout">@lang('Checkout')</option>-->
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- ✅ FIXED: Removed stretched-link class --}}
                        <button type="submit" class="btn btn-success borders">
                            @lang('Pay')
                            <i class="lni lni-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>


    </div>
@endsection

