<div class="col-xl-3 col-lg-3 mb-30">
        <div class="dashboard-sidebar">
            <button type="button" class="dashboard-sidebar-close"><i class="fas fa-times"></i></button>
            <div class="dashboard-sidebar-inner">
                <div class="dashboard-sidebar-wrapper-inner">
                    <h5 class="menu-header-title">@lang('Freelancer Account')</h5>
                    <ul id="sidebar-main-menu" class="sidebar-main-menu">
                        <li class="sidebar-single-menu nav-item {{request()->routeIs('user.home')?'open':''}} ">
                            <a href="{{route('user.home')}}">
                                <i class="lab la-buffer"></i> <span class="title">@lang('Freelancer Dashboard')</span>
                            </a>
                        </li>
                        <li class="sidebar-single-menu nav-item {{request()->routeIs('user.service.index') || request()->routeIs('user.service.edit') ?'open':''}}">
                            <a href="{{route('user.service.index')}}">
                                <i class="las la-list"></i> <span class="title">@lang('Manage Services')</span>
                            </a>
                        </li>
                        <li class="sidebar-single-menu nav-item {{request()->routeIs('user.service.create')?'open':''}}">
                            <a href="{{route('user.service.create')}}">
                                <i class="las la-plus"></i> <span class="title">@lang('Create Service')</span>
                            </a>
                        </li>
                        <li class="sidebar-single-menu nav-item {{request()->routeIs('user.software.index') || request()->routeIs('user.software.edit')?'open':''}}">
                            <a href="{{route('user.software.index')}}">
                                <i class="lab la-microsoft"></i> <span class="title">@lang('Manage Software')</span>
                            </a>
                        </li>
                        <li class="sidebar-single-menu nav-item {{request()->routeIs('user.software.create')?'open':''}}">
                            <a href="{{route('user.software.create')}}">
                                <i class="las la-plus"></i> <span class="title">@lang('Upload Software')</span>
                            </a>
                        </li>
                        <li class="sidebar-single-menu nav-item {{request()->routeIs('user.software.create')?'open':''}}">
                            <a href=" {{route('user.job.jobapplied')}}">

                                <i class="las la-eye"></i> <span class="title">@lang('Proposal History')</span>
                            </a>
                        </li>
                        <li class="sidebar-single-menu nav-item {{request()->routeIs('user.seller_dispute')?'open':''}}">
                            <a href=" {{route('user.seller.dispute')}}">
                                 <i class="las la-eye"></i> <span class="title">Dispute</span>
                            </a>
                        </li>
                    </ul>
                    <h5 class="menu-header-title">@lang('Sales')</h5>
                    <ul id="sidebar-main-menu" class="sidebar-main-menu">
                        <li class="sidebar-single-menu nav-item {{request()->routeIs('user.booking.service') || request()->routeIs('user.booking.service.details') ?'open':''}}">
                            <a href="{{route('user.booking.service')}}">
                                <i class="las la-exchange-alt"></i> <span class="title">@lang('Service Booking')</span>
                            </a>
                        </li>
                        <li class="sidebar-single-menu nav-item {{request()->routeIs('user.software.sales')?'open':''}}">
                            <a href="{{ route('user.software.sales') }}">
                                <i class="las la-history"></i> <span class="title">@lang('Software Sales')</span>
                            </a>
                        </li>
                        <li class="sidebar-single-menu nav-item {{request()->routeIs('user.job.vacancy') || request()->routeIs('user.seller.job.list.details')?'open':''}}">
                            <a href="{{route('user.job.vacancy')}}">
                                <i class="las la-caret-square-up"></i> <span class="title">@lang('Job List')</span>
                            </a>
                        </li>

                         <li class="sidebar-single-menu nav-item {{request()->routeIs('user.seller.transactions') ?'open':''}}">
                            <a href="{{route('user.seller.transactions')}}">
                                <i class="las la-money-check-alt"></i> <span class="title">@lang('Reports Log')</span>
                            </a>
                        </li>
                         <li class="sidebar-single-menu nav-item {{request()->routeIs('user.deposit') ?'open':''}}">
                            <a href="{{route('user.deposit')}}">
                                <i class="las la-money-check-alt"></i> <span class="title">@lang('Deposit Money')</span>
                            </a>
                        </li>

                        <li class="sidebar-single-menu nav-item {{request()->routeIs('user.withdraw')?'open':''}}">
                            <a href="{{route('user.withdraw')}}">
                                <i class="las la-money-check-alt"></i> <span class="title">@lang('Withdraw Money')</span>
                            </a>
                        </li>
                        <li class="sidebar-single-menu nav-item {{request()->routeIs('user.withdraw.history')?'open':''}}">
                            <a href="{{route('user.withdraw.history')}}">
                                <i class="las la-history"></i> <span class="title">@lang('Withdraw History')</span>
                            </a>
                        </li>

                         <li class="sidebar-single-menu nav-item {{request()->routeIs('ticket') || request()->routeIs('ticket.view')?'open':''}}">
                            <a href="{{route('ticket')}}">
                                <i class="las la-ticket-alt"></i> <span class="title">@lang('Support Ticket')</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
