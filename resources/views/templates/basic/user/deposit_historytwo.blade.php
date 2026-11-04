@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
<div class="row mt-2 ">
    <div class="col-md-4 col-lg-4 col-xl-5">
        <h5 class="text-dgreen2">Deposit History</h5>
    </div>
    <div class="col-sm-8 col-md-8 col-xl-7 d-flex flex-row-reverse">
        <button class="btn btn-sm btn-success bg-green ms-2" onclick="location.href='{{ route('user.deposittwo') }}';"> Deposit Now</button>
        <form class="d-flex flex-row" role="search">
            <input class="form-control bg-white" style="height:30px; font-size: 14px;" name='search' value="{{request()->search ?? ''}}" type="search" placeholder="Search " aria-label="Search">
            <button class="btn btn-sm btn-success ms-1 bg-dgreen2 border-0 " type="submit" style="height: 30px;"><img src="{{ asset('assets/wiserlance/assets/img/icons/icons8-search.svg') }}" width="20"></button>
        </form>
    </div>
</div>
<div class="card shadow-sm mt-3 p-3" style="border: solid 1px #d2d2d2">
    <!-- Card-->
    @forelse($logs as $k=>$data)
        <div class="row border-bottom mt-3">
            <div class="col-md-8 col-xl-9">
                <ul class="nav pb-3 fs-14">
                    <li class="nav-item me-3 "><span class="text-dgreen2 fw-bold">Gateway:</span> {{ __(@$data->gateway->name) }} </li>
                    <li class="nav-item me-3 "><span class="text-dgreen2 fw-bold">Transaction ID:</span> {{ $data->trx }} </li>
                    <li class="nav-item me-3"><span class="text-dgreen2 fw-bold">Initiated:</span> <span class="text-secondary"> <i class="fa-solid fa-calendar-days"></i> {{ showDateTime($data->created_at) }}</li>
                    <li class="nav-item me-3"><span class="text-dgreen2 fw-bold"> Amount:</span> ${{ getAmount($data->amount) }} +<span class="text-danger"> ${{ getAmount($data->charge) }} </span> = ${{ getAmount($data->amount + $data->charge) }}</li>
                    {{-- <li class="nav-item me-3 me-md-0"><span class="text-dgreen2 fw-bold"> Conversion:</span> $1USD = $1USD, $77.20 </li> --}}
                </ul>
            </div>

            <div class="col-md-4 col-xl-3 float-end pb-3 pb-md-0 ">
                @if ($data->status == 1)
                    <span class="btn btn-green rounded px-1 fs-14"> <i class="fa-solid fa-check"></i> @lang('Complete') </span>
                @elseif($data->status == 2)
                    <span class="btn btn-warning text-white py-0 rounded px-1 fs-14"> <i class="fa-regular fa-clock"></i> @lang('Pending') </span>
                @elseif($data->status == 3)
                    <span class="btn btn-danger text-white py-0 rounded px-1 fs-14"> <i class="fa-regular fa-circle"></i> @lang('Cancel') </span>
                @endif
                @if ($data->admin_feedback != null)
                    <a href="javascript:void(0);" class="btn btn-outline-success py-0 btn-sm ms-3 detailBtn" data-admin_feedback="{{ $data->admin_feedback }}"> <i class="fa-regular fa-eye text-green"></i> View </a>
                @endif

            </div>
        </div>
    @empty
        <div class="row border-bottom">
            {{-- <ul class="nav pb-3 fs-14">
                <li class="nav-item me-3 me-md-0"><span class="text-dgreen2 fw-bold"> Conversion:</span> $1USD = $1USD, $77.20 </li>
            </ul> --}}
            <td colspan="100%">{{ __($emptyMessage) }}</td>
        </div>
    @endforelse
    <!-- Card End-->
</div>
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title text-white" id="ModalLabel">@lang('Deposite Details')</h4>
                    <button onclick="closemodel()" type="button" class="btn-close" data-dismiss="detailModal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="withdraw-detail"></div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="closemodel()" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        (function($) {
            "use strict";
            $('.approveBtn').on('click', function() {
                var modal = $('#approveModal');
                modal.find('.withdraw-amount').text($(this).data('amount'));
                modal.find('.withdraw-charge').text($(this).data('charge'));
                modal.find('.withdraw-after_charge').text($(this).data('after_charge'));
                modal.find('.withdraw-rate').text($(this).data('rate'));
                modal.find('.withdraw-payable').text($(this).data('payable'));
                var list = [];
                var details = Object.entries($(this).data('info'));

                var ImgPath = "{{ asset(imagePath()['verify']['deposit']['path']) }}/";
                var singleInfo = '';
                for (var i = 0; i < details.length; i++) {
                    if (details[i][1].type == 'file') {
                        singleInfo +=
                            `<li class="list-group-item">
                            <span class="font-weight-bold ">
                                ${details[i][0].replaceAll('_', " ")}
                            </span> : <img src="${ImgPath}/${details[i][1].field_name}" alt="@lang('Image')" class="w-100">
                        </li>`;
                    } else {
                        singleInfo +=
                            `<li class="list-group-item">
                            <span class="font-weight-bold ">
                                ${details[i][0].replaceAll('_', " ")}
                            </span> : <span class="font-weight-bold ml-3">${details[i][1].field_name}</span>
                        </li>`;
                    }
                }
                if (singleInfo) {
                    modal.find('.withdraw-detail').html(
                        `<br><strong class="my-3">@lang('Payment Information')</strong>  ${singleInfo}`);
                } else {
                    modal.find('.withdraw-detail').html(`${singleInfo}`);
                }
                modal.modal('show');
            });
            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');
                var feedback = $(this).data('admin_feedback');
                modal.find('.withdraw-detail').html(`<p> ${feedback} </p>`);
                modal.modal('show');
            });

           
        })(jQuery);
         function closemodel() {
            $('#detailModal').modal('hide');
        }
    </script>
@endsection
