@props(['booking', 'activeTab'])

<div class="col-md-7 col-lg-8 ps-md-5 px-lg-0 d-none d-lg-block">
    <div class="float-end pe-3">
        <button type="button"
            class="btn btn-sm {{ $activeTab == 'details' ? 'btn-success bg-green text-white' : 'btn-outline-secondary' }}"
            onclick="location.href='{{ route('user.service-booked-details', encrypt($booking->id)) }}'">
            Service Details
        </button>

        @if ($booking->status != '4')
            <button type="button"
                class="btn btn-sm ms-2 {{ $activeTab == 'contract' ? 'btn-success bg-green text-white' : 'btn-outline-secondary' }}"
                onclick="location.href='{{ route('user.service-booked-details-contract', encrypt($booking->id)) }}'">
                Contract
            </button>

            <button type="button"
                class="btn btn-sm ms-2 {{ $activeTab == 'progress' ? 'btn-success bg-green text-white' : 'btn-outline-secondary' }}"
                onclick="location.href='{{ route('user.service-booked-details-workprogress', encrypt($booking->id)) }}'">
                In Progress
            </button>

            <button type="button"
                class="btn btn-sm ms-2 {{ $activeTab == 'invoice' ? 'btn-success bg-green text-white' : 'btn-outline-secondary' }}"
                onclick="location.href='{{ route('user.service-booked-details-workcompleted', encrypt($booking->id)) }}'">
                Invoice
            </button>

            <button type="button"
                class="btn btn-sm ms-2 {{ $activeTab == 'completed' ? 'btn-success bg-green text-white' : 'btn-outline-secondary' }}"
                onclick="location.href='{{ route('user.service-booked-details-workcompleted1', encrypt($booking->id)) }}'">
                Completed
            </button>


            {{-- <a href="{{ route('user', ['id' => $booking->seller_id]) }}" target="_blank"
                class="btn btn-sm btn-outline-secondary ms-2">
                Chat with Seller
            </a> --}}
        @endif
    </div>
</div>
