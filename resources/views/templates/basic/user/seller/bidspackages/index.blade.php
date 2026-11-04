@extends("templates.basic.layouts.wiserlance.dashboard-layout-job")
@section('content')
<div class="row mt-2">
    <div class="col-12">
        <h5 class="text-dgreen2">Freelancer Bids Packages</h5>
    </div>
</div>
<div class="row gx-2">
    @foreach ($bidspakages as $key=>$m)
        <div class="membership-card col-md-6 col-lg-4 col-xl-3 mt-4 mt-md-2"    >
            <div class="border-secondary shadow rounded">
                <ul class="list-group fs-14">
                    {{-- Dynamic Background Class for Title --}}
                    <li class="list-group-item text-center fw-bold p-2 fs-5 text-white
                        {{ $m->title == 'Try it free' ? 'bg-secondary' : ($m->title == 'Basic' ? 'bg-dgreen2' : ($m->title == 'STANDARD' ? 'bg-green' : 'bg-dgreen')) }}">
                        <span class="fs-14 fw-normal">{{ $m->title }}</span>
                    </li>

                    {{-- Price --}}
                    <li class="list-group-item fs-5 fw-bold text-green text-center bg-light">
                        {{ $m->price == 0 ? 'Free' : '$' . $m->price  }}
                    </li>

                    {{-- Dynamic Details --}}
                    <li class="list-group-item">Bids <span class="float-end">{{ $m->bids }}</span></li>
                    <li class="list-group-item">Services <span class="float-end">{{ $m->services }}</span></li>
                    <li class="list-group-item">Featured Services<span class="float-end">{{ $m->f_services }}</span></li>

                    <li class="list-group-item text-center py-3">
                        <a  href="{{url('/user/seller/bidspayment?id='.$m->id)}}">
                            <button class="btn btn-sm btn-success {{ $m->title == 'Basic' ? 'bg-dgreen2' : ($m->title == 'STANDARD' ? 'bg-green' : 'bg-dgreen') }}"> Buy Now</button>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    @endforeach
</div>
@endsection
@section('script')
<script>
(function($){
    "use strict";
    $('.approveBtn').on('click', function() {
    var modal = $('#detailModal');
    var feedback = $(this).data('admin_feedback');
    modal.find('.withdraw-detail').html(`<p> ${feedback} </p>`);
    modal.modal('show');
    });
})(jQuery);
</script>
@endsection
