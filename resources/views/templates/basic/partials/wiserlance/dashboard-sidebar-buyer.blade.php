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
    if (Auth::check()) {
        $status = Auth::user()->status;
        $user = Auth::user();
    }
@endphp
<div id="sidebar" class="col-2 p-4 px-lg-2 px-xl-4 shadow-sm d-none rounded-bottom d-lg-block">
    <div style="height:auto;" class="">
        <h5 class="pt-1 text-success"> Buyer Account</h5>
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
                <a href="javascript:void(0);" data-url="{{ route('user.buyer.dashboard') }}"
                    class="ajax-link nav-link p-0 py-1 {{ $pageTitle == 'Dashboard' ? 'text-success' : 'text-dark' }}"
                    aria-current="page" href="#">
                    <i class="fa-solid fa-chart-line"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ $status == 1 ? route('user.job.index') : '' }}"
                    data-url="{{ $status == 1 ? route('user.job.index') : '' }}"
                    class="ajax-link nav-link  p-0 py-1 {{ $pageTitle == 'Manage Jobs' ? 'text-success' : 'text-dark' }}"
                    href="#"> <i class="fa-solid fa-table-list"></i> Manage
                    Jobs</a>
            </li>
            <li class="nav-item">
                <a href="{{ $status == 1 ? route('user.job.create') : '' }}" class="nav-link text-dark p-0 py-1 "
                    href="#"> <i class="fa-regular fa-square-plus"></i> Create
                    Jobs</a>
            </li>

            <li class="nav-item">
                <a href="{{ $status == 1 ? route('user.buyer.dispute') : '' }}"
                    class="nav-link text-dark p-0 py-1 {{ $pageTitle == 'Dispute' ? 'active' : '' }}" href="#"> <i
                        class="fa-regular fa-comment-dots"></i>
                    Dispute</a>
            </li>
        </ul>
        <hr>
        <h6 class="mt-3">Purchase</h6>
        <ul class="nav flex-column ms-2 " style="font-size:14px;">
            <li class="nav-item">
                <a href="{{ $status == 1 ? route('user.service-booked') : '' }}" class="nav-link text-dark p-0 py-1"
                    href="#"> <i class="fa-regular fa-hand-pointer"></i>
                    Booked
                    Services</a>
            </li>
            <li class="">
                <a href="{{ $status == 1 ? route('user.buyer.hire.open') : '' }}" style="text-decoration: none"
                    data-url="{{ $status == 1 ? route('user.buyer.hire.open') : '' }}" class=" text-dark p-0 py-1"> <i
                        class="fa-solid fa-list-ul"></i> Posted Jobs & Bids</a>
            </li>
        </ul>
        <hr>
        <h6 class="mt-3 ">Basics</h6>
        <ul class="nav flex-column ms-2 " style="font-size:14px;">
            {{-- <li class="nav-item">
                <a href="{{ $status == 1 ? route('user.conversation.inbox') : '' }}"
                    class="nav-link text-dark p-0 py-1" href="#"> <i class="fas fa-inbox"></i> Inbox</a>
            </li> --}}
            <li>
                <a href="{{ $status == 1 ? route('user.buyer.report-logs') : '' }}" class="nav-link text-dark p-0 py-1"
                    href="#"> <i class="far fa-list-alt"></i> Reports Log</a>
            </li>

            <li>
                <a href="{{ $status == 1 ? route('user.deposittwo') : '' }}" class="nav-link text-dark p-0 py-1 "
                    href="#"> <i class="far fa-money-bill-alt"></i> Deposit
                    Money</a>
            </li>

            <li>
                <a href="{{ $status == 1 ? route('user.deposit.historytwo') : '' }}"
                    class="nav-link text-dark p-0 py-1" href="#"> <i class="fas fa-history"></i> Deposit
                    History</a>
            </li>

            <li class=" {{ $pageTitle == 'Withdraw Money' ? 'active' : '' }}">
                <a class="nav-link text-dark p-0 py-1" href="{{ $status == 1 ? route('user.buyer.withdraw') : '#' }}">
                    <i class="fas fa-hand-holding-usd"></i> Withdraw
                    Money</a>
            </li>

            <li class=" {{ $pageTitle == 'Withdraw History' ? 'active' : '' }}">
                <a href="{{ $status == 1 ? route('user.buyer.withdraw.history') : '#' }}"
                    class="nav-link text-dark p-0 py-1"> <i class="fas fa-history"></i> @lang('Withdraw History')</a>
            </li>

            <li>
                <a class="nav-link text-dark p-0 py-1" href="{{ $status == 1 ? route('ticket.open') : '' }}"> <i
                        class="far fa-plus-square"></i> New Ticket</a>
            </li>

            <li>
                <a class="nav-link text-dark p-0 py-1" href="{{ $status == 1 ? route('ticket') : '' }}"> <i
                        class="fa fa-ticket-alt"></i> My Ticket</a>
            </li>

            <li>
                <a class="nav-link text-dark p-0 py-1"
                    href="{{ $status == 1 ? route('user.buyer.transactions') : '' }}"> <i class="fas fa-history"></i>
                    Transaction History</a>
            </li>
        </ul>
        <hr>
        <h6 class="mt-3">Upgrade</h6>
        <ul class="nav flex-column ms-2 " style="font-size:14px;">
            <li class="nav-item"><a class="nav-link text-dark p-0 py-1"
                    href="{{ $status == 1 ? route('user.buyer.employermembership') : '' }}"> <i
                        class="fas fa-id-card"></i>
                    Upgrade Membership</a></li>
            <li>
                <a class="nav-link text-dark p-0 py-1 {{ $pageTitle == 'Employer Packages' ? 'active' : '' }}"
                    href="{{ $status == 1 ? route('user.jobspackages') : '' }}">
                    <i class="far fa-folder-open"></i> @lang('Job Packages')
                </a>
            </li>
        </ul>
        <hr>
        <ul class="nav flex-column ms-2 mt-3 " style="font-size:14px;">
            <li class="nav-item">
                <a class="nav-link text-dark p-0 py-1" href="{{ route('user.buyer.profile') }}"> <i
                        class="fas fa-cog"></i> Settings</a>
            </li>
            <li>
                <a class="nav-link text-dark p-0 py-1" href="{{ route('help.support.center') }}"> <i
                        class="fas fa-question-circle"></i> Help
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