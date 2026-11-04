<style>
    .balance-badge {
        font-size: 12px;
        font-weight: 400;
    }

    .balance-sync-icon {
        width: 20px;
        height: 20px;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .rotating {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
</style>

@php
    $status = Auth::user()->status;
    $user = Auth::user();
@endphp
<div id="sidebar" class="col-2 p-4 px-lg-2 px-xl-4 shadow-sm d-none rounded-bottom d-lg-block">
    <div style="height:auto;" class="">
        <h5 class="text-success pt-1"> Seller Account</h5>
        <div class="d-flex align-items-center gap-2">
            <small>
                <span id="balanceBadge" class="badge bg-dgreen2 text-white px-2 py-1 balance-badge">
                    Balance: USD {{ number_format(Auth::user()->balance, 2) }}
                </span>
            </small>

            <img
                id="conversionButton"
                src="{{ asset('assets/images/syncing.svg') }}"
                alt="Sync Balance"
                class="balance-sync-icon rounded-circle"
            >
        </div>
        <ul class="nav flex-column ms-2 mt-2" style="font-size:14px;">
            <li class="nav-item">
                <a style="text-decoration: none"
                    class="p-0 py-1 {{ $pageTitle == 'Dashboard' ? 'text-success' : 'text-dark' }}"
                    aria-current="page" href="{{ route('user.home') }}">
                    <i class="fa-solid fa-chart-line"></i> Dashboard
                </a>
            </li>
            <li class="nav-item {{ $pageTitle == 'Manage service' ? 'active' : '' }}">
                <a href="{{ $status == 1 ? route('user.service.index') : '' }}" class="nav-link  p-0 py-1 text-dark"
                    href="#"> <i class="fa-solid fa-table-list"></i>
                    Manage Services</a>
            </li>
            <li class="nav-item {{ $pageTitle == 'Create service' ? 'active' : '' }}">
                <a href="{{ $status == 1 ? route('user.service.create') : '' }}" class="nav-link text-dark p-0 py-1 "
                    href="#"> <i class="fa-regular fa-square-plus"></i> Create Service</a>
            </li>

            <li class="nav-item {{ $pageTitle == 'Job Proposals' ? 'active' : '' }}">
                <a href="{{ $status == 1 ? route('user.seller.dispute') : '' }}" class="nav-link text-dark p-0 py-1"
                    href="#"> <i class="fa-regular fa-comment-dots"></i>
                    Dispute</a>
            </li>
        </ul>
        <hr>
        <h6 class="mt-3">Sales</h6>
        <ul class="nav flex-column ms-2 " style="font-size:14px;">
            <li class="nav-item {{ $pageTitle == 'Service booking list' ? 'active' : '' }}">
                <a href="{{ $status == 1 ? route('user.seller-booking-service') : '' }}" class="nav-link text-dark p-0 py-1"
                    href="#"> <i class="fa-regular fa-hand-pointer"></i> Service Booking</a>
            </li>
            <li>
                <a href="{{ $status == 1 ? route('user.job.vacancy') : '' }}" style="text-decoration: none"
                    data-url="{{ $status == 1 ? route('user.job.vacancy') : '' }}"
                    class="{{ $pageTitle == 'Bids' ? 'text-success' : 'text-dark' }} p-0 py-1"> <i
                        class="fa-solid fa-list-ul"></i> @lang('Projects')</a>
            </li>
            {{--  <li>
                <a href="javascript:void(0);" data-url="{{ $status == 1 ? route('user.job.vacancy') : '' }}" class="ajax-link nav-link {{ $pageTitle == 'Bids' ? 'text-success' : 'text-dark' }} p-0 py-1"> <i
                        class="fa-solid fa-list-ul"></i> @lang('Projects')</a>
            </li>  --}}
        </ul>
        <hr>
        <h6 class="mt-3 ">Basics</h6>
        <ul class="nav flex-column ms-2 " style="font-size:14px;">
            {{-- <li class="nav-item {{ $pageTitle == 'Transaction Log' ? 'active' : '' }}">
                <a href="{{ $status == 1 ? route('user.conversation.inbox') : '' }}"
                    class="nav-link text-dark p-0 py-1" href="#"> <i class="fas fa-inbox"></i>
                    @lang('Inbox')</a>
            </li> --}}
            <li class=" {{ $pageTitle == 'Transaction Log' ? 'active' : '' }}">
                <a href="{{ $status == 1 ? route('user.seller.report-logs') : '#' }}" style="display: flex;gap:4px;align-items:center"
                    class="nav-link text-dark p-0 py-1"> <i class="far fa-list-alt"></i><div>@lang('Reports Log')</div></a>
            </li>

            <li class=" {{ $pageTitle == 'Deposit Methods' ? 'active' : '' }}">
                <a href="{{ $status == 1 ? route('user.deposit') : '#' }}" class="nav-link text-dark p-0 py-1"> <i
                        class="far fa-money-bill-alt"></i> @lang('Deposit Money')</a>
            </li>

            <li class=" {{ $pageTitle == 'Deposit History' ? 'active' : '' }}">
                <a href="{{ $status == 1 ? route('user.deposit.history') : '#' }}"
                    class="nav-link text-dark p-0 py-1"> <i class="fas fa-history"></i> @lang('Deposit History')</a>
            </li>

            <li class=" {{ $pageTitle == 'Withdraw Money' ? 'active' : '' }}">
                <a href="{{ $status == 1 ? route('user.withdraw') : '#' }}" class="nav-link text-dark p-0 py-1"> <i
                        class="fas fa-hand-holding-usd"></i> @lang('Withdraw Money')</a>
            </li>

            <li class=" {{ $pageTitle == 'Withdraw Log' ? 'active' : '' }}">
                <a class="nav-link text-dark p-0 py-1"
                    href="{{ $status == 1 ? route('user.withdraw.history') : '#' }}"> <i class="fas fa-history"></i>
                    @lang('Withdraw History')</a>
            </li>

            <li class=" {{ $pageTitle == 'New Ticket' ? 'active' : '' }}">
                <a class="nav-link text-dark p-0 py-1" style="display: flex;gap:4px;align-items:center" href="{{ $status == 1 ? route('ticket.open') : '' }}"> <i
                        class="far fa-plus-square"></i>@lang('New Ticket')</a>
            </li>

            <li class=" {{ $pageTitle == 'MY Ticket' ? 'active' : '' }}">
                <a class="nav-link text-dark p-0 py-1" href="{{ $status == 1 ? route('ticket') : '' }}"> <i
                        class="fa-solid fa-ticket-alt"></i> @lang('My Ticket')</a>
            </li>

            <li class="nav-item">
                <a class="nav-link p-0 py-1 {{ $pageTitle == 'Transaction History' ? 'text-success' : 'text-dark' }}"
                    href="{{ $status == 1 ? route('user.seller.transactions') : '#' }}"> <i class="fas fa-history"></i>
                    @lang('Transaction History')</a>
            </li>
        </ul>
        <hr>
        <h6 class="mt-3">Upgrade</h6>
        <ul class="nav flex-column ms-2 " style="font-size:14px;">
            <li class="nav-item class=" {{ $pageTitle == 'Freelancer Membership' ? 'active' : '' }}"">
                <a class="nav-link text-dark p-0 py-1"
                    href="{{ $status == 1 ? url('/user/seller/freelancermembership') : '#' }}"> <i
                        class="fas fa-id-card"></i> Membership Packages</a>
            </li>
            <li class=" {{ $pageTitle == 'Freelancer Bids Pakages' ? 'active' : '' }}">
                <a class="nav-link text-dark p-0 py-1"
                    href="{{ $status == 1 ? url('/user/seller/bidpackages') : '#' }}"> <i
                        class="far fa-folder-open"></i> Buy Bids</a>
            </li>
        </ul>
        <hr>
        <ul class="nav flex-column ms-2 mt-3 " style="font-size:14px;">
            <li class="nav-item {{ $pageTitle == 'Freelancer Membership' ? 'active' : '' }}">
                <a class="nav-link text-dark p-0 py-1" href="{{route('user.home1')}}"> <i class="fas fa-cog"></i> Settings</a>
            </li>
            <li class=" {{ $pageTitle == 'Freelancer Bids Pakages' ? 'active' : '' }}">
                <a class="nav-link text-dark p-0 py-1" href="{{route('help.support.center')}}"> <i class="fas fa-question-circle"></i>
                    Help
                    Center</a>
            </li>
        </ul>
    </div>
</div>


<script>
        
    $('#conversionButton').on('click', function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $('#conversionButton').addClass('rotating');

        $.ajax({
            url: '/convert-balance',
            method: 'POST',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (data) {
                if (data.success) {
                    $('#balanceBadge').text("Balance : " + data.converted_balance);
                } else {
                    console.log(data.message);
                }
            },
            error: function (xhr) {
                const message = xhr.responseJSON?.message || xhr.statusText;
                console.log("Error:", message);
            },
            complete: function () {
                $('#conversionButton').removeClass('rotating');
            }
        });
    });
</script>