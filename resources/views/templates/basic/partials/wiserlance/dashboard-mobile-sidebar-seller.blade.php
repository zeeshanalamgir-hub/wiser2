@php
    if (Auth::check()) {
        $status = Auth::user()->status;
        $user = Auth::user();
    }
@endphp

<div class="mt-2 d-lg-none">
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button rounded-0 bg-dgreen text-white collapsed"
                    type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseSidebar">
                    <span class="fs-14">Sidebar</span>
                </button>
            </h2>

            <div id="collapseSidebar" class="accordion-collapse collapse">
                <div class="accordion-body">

                    <!-- Dashboard -->
                    <ul class="nav flex-column">
                        <li>
                            <a class="nav-link py-1 text-dark px-0" href="{{ route('user.home') }}">
                                <i class="fa-solid fa-chart-line"></i> Dashboard
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0" href="{{ $status == 1 ? route('user.service.index') : '' }}">
                                <i class="fa-solid fa-table-list"></i> Manage Services
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0" href="{{ $status == 1 ? route('user.service.create') : '' }}">
                                <i class="fa-regular fa-square-plus"></i> Create Service
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0" href="{{ $status == 1 ? route('user.seller.dispute') : '' }}">
                                <i class="fa-regular fa-comment-dots"></i> Dispute
                            </a>
                        </li>
                    </ul>

                    <hr>

                    <!-- Sales -->
                    <h6>Sales</h6>
                    <ul class="nav flex-column">
                        <li>
                            <a class="nav-link py-1 text-dark px-0" href="{{ $status == 1 ? route('user.seller-booking-service') : '' }}">
                                <i class="fa-regular fa-hand-pointer"></i> Service Booking
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0" href="{{ $status == 1 ? route('user.job.vacancy') : '' }}">
                                <i class="fa-solid fa-list-ul"></i> Projects
                            </a>
                        </li>
                    </ul>

                    <hr>

                    <!-- Basics -->
                    <h6>Basics</h6>
                    <ul class="nav flex-column">
                        <li>
                            <a class="nav-link py-1 text-dark px-0" href="{{ $status == 1 ? route('user.conversation.inbox') : '' }}">
                                <i class="fas fa-inbox"></i> Inbox
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0" href="{{ $status == 1 ? route('user.seller.report-logs') : '' }}">
                                <i class="far fa-list-alt"></i> Reports Log
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0" href="{{ $status == 1 ? route('user.deposit') : '' }}">
                                <i class="far fa-money-bill-alt"></i> Deposit Money
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0" href="{{ $status == 1 ? route('user.deposit.history') : '' }}">
                                <i class="fas fa-history"></i> Deposit History
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0" href="{{ $status == 1 ? route('user.withdraw') : '' }}">
                                <i class="fas fa-hand-holding-usd"></i> Withdraw Money
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0" href="{{ $status == 1 ? route('user.withdraw.history') : '' }}">
                                <i class="fas fa-history"></i> Withdraw History
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0" href="{{ $status == 1 ? route('ticket.open') : '' }}">
                                <i class="far fa-plus-square"></i> New Ticket
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0" href="{{ $status == 1 ? route('ticket') : '' }}">
                                <i class="fa fa-ticket-alt"></i> My Ticket
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0" href="{{ $status == 1 ? route('user.seller.transactions') : '' }}">
                                <i class="fas fa-history"></i> Transaction History
                            </a>
                        </li>
                    </ul>

                    <hr>

                    <!-- Upgrade -->
                    <h6>Upgrade</h6>
                    <ul class="nav flex-column">
                        <li>
                            <a class="nav-link py-1 text-dark px-0" href="{{ $status == 1 ? url('/user/seller/freelancermembership') : '' }}">
                                <i class="fas fa-id-card"></i> Membership Packages
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0" href="{{ $status == 1 ? url('/user/seller/bidpackages') : '' }}">
                                <i class="far fa-folder-open"></i> Buy Bids
                            </a>
                        </li>
                    </ul>

                    <hr>

                    <!-- Settings -->
                    <ul class="nav flex-column">
                        <li>
                            <a class="nav-link py-1 text-dark px-0" href="{{ route('user.home1') }}">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0" href="{{ route('help.support.center') }}">
                                <i class="fas fa-question-circle"></i> Help Center
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
</div>