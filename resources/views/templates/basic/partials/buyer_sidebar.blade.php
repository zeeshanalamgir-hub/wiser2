<div class="col-xl-3 col-lg-3 mb-30">
    <div class="dashboard-sidebar">
        <button type="button" class="dashboard-sidebar-close"><i class="fas fa-times"></i></button>
        <div class="dashboard-sidebar-inner">
            <div class="dashboard-sidebar-wrapper-inner">
                <h5 class="menu-header-title">@lang('Buyer Account')</h5>
                <ul id="sidebar-main-menu" class="sidebar-main-menu">
                    <li
                        class="sidebar-single-menu nav-item {{ request()->routeIs('user.buyer.dashboard') ? 'open' : '' }} ">
                        <a href="{{ route('user.buyer.dashboard') }}">
                            <i class="lab la-buffer"></i> <span class="title">@lang('Buyer Dashboard')</span>
                        </a>
                    </li>
                    <li
                        class="sidebar-single-menu nav-item {{ request()->routeIs('user.job.index') || request()->routeIs('user.job.edit') ? 'open' : '' }}">
                        <a href="{{ route('user.job.index') }}">
                            <i class="las la-list"></i> <span class="title">@lang('Manage Job')</span>
                        </a>
                    </li>
                    <li class="sidebar-single-menu nav-item {{ request()->routeIs('user.job.create') ? 'open' : '' }}">
                        <a href="{{ route('user.job.create') }}">
                            <i class="las la-plus"></i> <span class="title">@lang('Create Job')</span>
                        </a>
                    </li>
                    <li
                        class="sidebar-single-menu nav-item {{ request()->routeIs('user.service.favorite') ? 'open' : '' }}">
                        <a href="{{ route('user.service.favorite') }}">
                            <i class="las la-crown"></i> <span class="title">@lang('Favorite Service')</span>
                        </a>
                    </li>
                    <li
                        class="sidebar-single-menu nav-item {{ request()->routeIs('user.software.favorite') ? 'open' : '' }}">
                        <a href="{{ route('user.software.favorite') }}">
                            <i class="las la-heart"></i> <span class="title">@lang('Favorite Software')</span>
                        </a>
                    </li>

                    <li class="sidebar-single-menu nav-item {{ request()->routeIs('user.dispute') ? 'open' : '' }}">
                        <a href="{{ route('user.dispute') }}">
                            <i class="las la-heart"></i> <span class="title">Dispute</span>
                        </a>
                    </li>
                </ul>


                <h5 class="menu-header-title">@lang('PURCHASES')</h5>
                <ul id="sidebar-main-menu" class="sidebar-main-menu">

                    <li
                        class="sidebar-single-menu nav-item {{ request()->routeIs('user.buyer.hire.employ') || request()->routeIs('user.buyer.hire.employ.details') ? 'open' : '' }}">
                        <a href="{{ route('user.buyer.hire.employ') }}">
                            <i class="lab la-hire-a-helper"></i> <span class="title">@lang('Employees List')</span>
                        </a>
                    </li>

                    <li
                        class="sidebar-single-menu nav-item {{ request()->routeIs('user.buyer.service.booked') || request()->routeIs('user.buyer.service.booked.details') ? 'open' : '' }}">
                        <a href="{{ route('user.buyer.service.booked') }}">
                            <i class="las la-exchange-alt"></i> <span class="title">@lang('Service Booked')</span>
                        </a>
                    </li>
                    <li
                        class="sidebar-single-menu nav-item {{ request()->routeIs('user.software.purchases') ? 'open' : '' }}">
                        <a href="{{ route('user.software.purchases') }}">
                            <i class="las la-history"></i> <span class="title">@lang('Software Purchases')</span>
                        </a>
                    </li>

                    <li
                        class="sidebar-single-menu nav-item {{ request()->routeIs('user.buyer.transactions') ? 'open' : '' }}">
                        <a href="{{ route('user.buyer.transactions') }}">
                            <i class="las la-money-check-alt"></i> <span class="title">@lang('Report Log')</span>
                        </a>
                    </li>
                    <li class="sidebar-single-menu nav-item {{ request()->routeIs('user.deposit') ? 'open' : '' }}">
                        <a href="{{ route('user.deposit') }}">
                            <i class="las la-money-check-alt"></i> <span class="title">@lang('Deposit Money')</span>
                        </a>
                    </li>
                    <li
                        class="sidebar-single-menu nav-item {{ request()->routeIs('user.deposit.history') ? 'open' : '' }}">
                        <a href="{{ route('user.deposit.history') }}">
                            <i class="las la-history"></i> <span class="title">@lang('Deposit History')</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
