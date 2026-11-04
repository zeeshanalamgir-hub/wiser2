@php
    if (Auth::check()) {
        $status = Auth::user()->status;
        $user = Auth::user();
    }
@endphp

<div class="mt-2 d-lg-none">
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button rounded-0 bg-dgreen text-white collapsed"
                    type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseTwo">
                    <span class="fs-14">Sidebar</span>
                </button>
            </h2>

            <div id="collapseTwo" class="accordion-collapse collapse">
                <div class="accordion-body p-2">

                    <!-- Top Menu -->
                    <ul class="nav flex-column bg-lgrey p-3 rounded" style="font-size:14px;">
                        <li class="nav-item">
                            <a class="nav-link py-1 text-dark px-0"
                                href="{{ route('user.buyer.dashboard') }}">
                                <i class="fa-solid fa-chart-line"></i> Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link py-1 text-dark px-0"
                                href="{{ $status == 1 ? route('user.job.index') : '' }}">
                                <i class="fa-solid fa-table-list"></i> Manage Jobs
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link py-1 text-dark px-0"
                                href="{{ $status == 1 ? route('user.job.create') : '' }}">
                                <i class="fa-regular fa-square-plus"></i> Create Jobs
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link py-1 text-dark px-0"
                                href="{{ $status == 1 ? route('user.buyer.dispute') : '' }}">
                                <i class="fa-regular fa-comment-dots"></i> Dispute
                            </a>
                        </li>
                    </ul>

                    <hr class="my-2">

                    <!-- Purchase -->
                    <h6 class="mt-2 mb-1 px-1">Purchase</h6>
                    <ul class="nav flex-column px-1" style="font-size:14px;">
                        <li>
                            <a class="nav-link py-1 text-dark px-0"
                                href="{{ $status == 1 ? route('user.service-booked') : '' }}">
                                <i class="fa-regular fa-hand-pointer"></i> Service Booking
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0"
                                href="{{ $status == 1 ? route('user.buyer.hire.open') : '' }}">
                                <i class="fa-solid fa-list-ul"></i> Projects
                            </a>
                        </li>
                    </ul>

                    <hr class="my-2">

                    <!-- Basics -->
                    <h6 class="mt-2 mb-1 px-1">Basics</h6>
                    <ul class="nav flex-column px-1" style="font-size:14px;">
                        <li>
                            <a class="nav-link py-1 text-dark px-0"
                                href="{{ $status == 1 ? route('user.conversation.inbox') : '' }}">
                                <i class="fas fa-inbox"></i> Inbox
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0"
                                href="{{ $status == 1 ? route('user.buyer.report-logs') : '' }}">
                                <i class="far fa-list-alt"></i> Reports Log
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0"
                                href="{{ $status == 1 ? route('user.deposittwo') : '' }}">
                                <i class="far fa-money-bill-alt"></i> Deposit Money
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0"
                                href="{{ $status == 1 ? route('user.deposit.historytwo') : '' }}">
                                <i class="fas fa-history"></i> Deposit History
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0"
                                href="{{ $status == 1 ? route('user.buyer.withdraw') : '#' }}">
                                <i class="fas fa-hand-holding-usd"></i> Withdraw Money
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0"
                                href="{{ $status == 1 ? route('user.buyer.withdraw.history') : '#' }}">
                                <i class="fas fa-history"></i> Withdraw History
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0"
                                href="{{ $status == 1 ? route('ticket.open') : '' }}">
                                <i class="far fa-plus-square"></i> New Ticket
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0"
                                href="{{ $status == 1 ? route('ticket') : '' }}">
                                <i class="fa fa-ticket-alt"></i> My Ticket
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0"
                                href="{{ $status == 1 ? route('user.buyer.transactions') : '' }}">
                                <i class="fas fa-history"></i> Transaction History
                            </a>
                        </li>
                    </ul>

                    <hr class="my-2">

                    <!-- Upgrade -->
                    <h6 class="mt-2 mb-1 px-1">Upgrade</h6>
                    <ul class="nav flex-column px-1" style="font-size:14px;">
                        <li>
                            <a class="nav-link py-1 text-dark px-0"
                                href="{{ $status == 1 ? route('user.buyer.employermembership') : '' }}">
                                <i class="fas fa-id-card"></i> Upgrade Membership
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0"
                                href="{{ $status == 1 ? route('user.jobspackages') : '' }}">
                                <i class="far fa-folder-open"></i> Job Packages
                            </a>
                        </li>
                    </ul>

                    <hr class="my-2">

                    <!-- Bottom -->
                    <ul class="nav flex-column px-1" style="font-size:14px;">
                        <li>
                            <a class="nav-link py-1 text-dark px-0"
                                href="{{ route('user.buyer.profile') }}">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </li>

                        <li>
                            <a class="nav-link py-1 text-dark px-0"
                                href="{{ route('help.support.center') }}">
                                <i class="fas fa-question-circle"></i> Help Center
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
</div>