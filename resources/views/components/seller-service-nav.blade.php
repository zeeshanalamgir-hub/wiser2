@props(['booking', 'activeTab'])

<div class="col-md-7 col-lg-8 ps-md-5 px-lg-0 d-none d-lg-block">
    <div class="float-end pe-3">

        <button type="button"
            onclick="window.location.href='{{ route('user.booking-service-details', encrypt($booking->id)) }}'"
            class="btn btn-sm ms-2 {{ $activeTab == 'details' ? 'btn-success bg-green text-white' : 'btn-outline-secondary' }}">
            Service Detail
        </button>

        @if($booking->status != '4')

            <button type="button"
                onclick="window.location.href='{{ route('user.booking-service-details-contract', encrypt($booking->id)) }}'"
                class="btn btn-sm ms-2 {{ $activeTab == 'contract' ? 'btn-success bg-green text-white' : 'btn-outline-secondary' }}">
                Contract
            </button>

            <button type="button"
                onclick="window.location.href='{{ route('user.booking-service-details-work-progress', encrypt($booking->id)) }}'"
                class="btn btn-sm ms-2 {{ $activeTab == 'progress' ? 'btn-success bg-green text-white' : 'btn-outline-secondary' }}">
                In Progress
            </button>

            <button type="button"
                onclick="window.location.href='{{ route('user.booking-service-details-invoice', encrypt($booking->id)) }}'"
                class="btn btn-sm ms-2 {{ $activeTab == 'invoice' ? 'btn-success bg-green text-white' : 'btn-outline-secondary' }}">
                Invoice
            </button>

            <button type="button"
                onclick="window.location.href='{{ route('user.booking-service-details-work-completed', encrypt($booking->id)) }}'"
                class="btn btn-sm ms-2 {{ $activeTab == 'completed' ? 'btn-success bg-green text-white' : 'btn-outline-secondary' }}">
                Completed
            </button>

            {{-- <button type="button"
                onclick="window.open('{{ route('user', ['id' => $booking->seller_id]) }}', '_blank')"
                class="btn btn-sm ms-2 {{ $activeTab == 'chat' ? 'btn-success bg-green text-white' : 'btn-outline-secondary' }}">
                Chat with Buyer
            </button> --}}

        @endif
    </div>
</div>
