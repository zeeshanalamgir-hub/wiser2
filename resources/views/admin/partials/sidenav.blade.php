@php
    $staffAccess = Auth::guard('admin')->user()->staff_access;
@endphp
<div class="sidebar {{ sidebarVariation()['selector'] }} {{ sidebarVariation()['sidebar'] }} {{ @sidebarVariation()['overlay'] }} {{ @sidebarVariation()['opacity'] }}"
     data-background="{{getImage('assets/admin/images/sidebar/1.jpg','400x800')}}">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="{{route('admin.dashboard')}}" class="sidebar__main-logo"><img
                    src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="@lang('image')"></a>
            <a href="{{route('admin.dashboard')}}" class="sidebar__logo-shape"><img
                    src="{{getImage(imagePath()['logoIcon']['path'] .'/favicon.png')}}" alt="@lang('image')"></a>
            <button type="button" class="navbar__expand"></button>
        </div>

        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
            @if(in_array('1', $staffAccess))
                <li class="sidebar-menu-item {{menuActive('admin.dashboard')}}">
                    <a href="{{route('admin.dashboard')}}" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang('Dashboard')</span>
                    </a>
                </li>
            @endif

            @if(in_array('9', $staffAccess))
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.users*',3)}}">
                        <i class="menu-icon las la-users"></i>
                        <span class="menu-title">@lang('Manage Users')</span>

                        @if($banned_users_count > 0 || $email_unverified_users_count > 0 || $sms_unverified_users_count > 0)
                            <span class="menu-badge pill bg--primary ml-auto">
                                <i class="fa fa-exclamation"></i>
                            </span>
                        @endif
                    </a>
                    <div class="sidebar-submenu {{menuActive('admin.users*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('admin.users.all')}} ">
                                <a href="{{route('admin.users.all')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All Users')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.users.active')}} ">
                                <a href="{{route('admin.users.active')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Active Users')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.users.banned')}} ">
                                <a href="{{route('admin.users.banned')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Banned Users')</span>
                                    @if($banned_users_count)
                                        <span class="menu-badge pill bg--primary ml-auto">{{$banned_users_count}}</span>
                                    @endif
                                </a>
                            </li>

                            <li class="sidebar-menu-item  {{menuActive('admin.users.email.unverified')}}">
                                <a href="{{route('admin.users.email.unverified')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Email Unverified')</span>

                                    @if($email_unverified_users_count)
                                        <span
                                            class="menu-badge pill bg--primary ml-auto">{{$email_unverified_users_count}}</span>
                                    @endif
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.users.sms.unverified')}}">
                                <a href="{{route('admin.users.sms.unverified')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('SMS Unverified')</span>
                                    @if($sms_unverified_users_count)
                                        <span
                                            class="menu-badge pill bg--primary ml-auto">{{$sms_unverified_users_count}}</span>
                                    @endif
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.users.with.balance')}}">
                                <a href="{{route('admin.users.with.balance')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('With Balance')</span>
                                </a>
                            </li>



                            <li class="sidebar-menu-item {{menuActive('admin.users.email.all')}}">
                                <a href="{{route('admin.users.email.all')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Email to All')</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endif
<!-- Member ship -->
<li class="sidebar-menu-item sidebar-dropdown">
    <a href="javascript:void(0)" class="{{menuActive('admin.users*',3)}}">
        <i class="menu-icon las la-users"></i>
        <span class="menu-title">@lang('Member Ship')</span>
    </a>
    <div class="sidebar-submenu {{menuActive('admin.membership')}} ">
        <ul>
            <li class="sidebar-menu-item {{menuActive('admin.membership')}} ">
                <a href="{{route('admin.addredirect')}}" class="nav-link">
                    <i class="menu-icon las la-dot-circle"></i>
                    <span class="menu-title">@lang('Create Membership')</span>
                </a>
            </li>
            <li class="sidebar-menu-item {{menuActive('admin.membership')}} ">
                <a href="{{route('admin.package')}}" class="nav-link">
                    <i class="menu-icon las la-dot-circle"></i>
                    <span class="menu-title">@lang('Freelancer Pakages')</span>
                </a>
            </li>
            <li class="sidebar-menu-item {{menuActive('admin.employer')}} ">
                <a href="{{route('admin.employer')}}" class="nav-link">
                    <i class="menu-icon las la-dot-circle"></i>
                    <span class="menu-title">@lang('Employer Pakages')</span>
                </a>
            </li>
        </ul>
    </div>
</li>
<!-- Member ship -->
<!-- Packages -->
<li class="sidebar-menu-item sidebar-dropdown">
    <a href="javascript:void(0)" class="{{menuActive('admin.users*',3)}}">
        <i class="menu-icon las la-users"></i>
        <span class="menu-title">@lang('Packages')</span>
    </a>
    <div class="sidebar-submenu {{menuActive('admin.addbidpakage')}} ">
        <ul>
            <li class="sidebar-menu-item {{menuActive('admin.addbidpakage')}} ">
                <a href="{{route('admin.addbidpakage')}}" class="nav-link">
                    <i class="menu-icon las la-dot-circle"></i>
                    <span class="menu-title">@lang('Create Bid Packages')</span>
                </a>
            </li>
            <li class="sidebar-menu-item {{menuActive('admin.bidpakages')}} ">
                <a href="{{route('admin.bidpakages')}}" class="nav-link">
                    <i class="menu-icon las la-dot-circle"></i>
                    <span class="menu-title">@lang('Bid Pakages')</span>
                </a>
            </li>

            <li class="sidebar-menu-item {{menuActive('admin.addjobpakage')}} ">
                <a href="{{route('admin.addjobpakage')}}" class="nav-link">
                    <i class="menu-icon las la-dot-circle"></i>
                    <span class="menu-title">@lang('Create job Packages')</span>
                </a>
            </li>
            <li class="sidebar-menu-item {{menuActive('admin.jobpakages')}} ">
                <a href="{{route('admin.jobpakages')}}" class="nav-link">
                    <i class="menu-icon las la-dot-circle"></i>
                    <span class="menu-title">@lang('Job Pakages')</span>
                </a>
            </li>
        </ul>
    </div>
</li>
<!-- Packages -->
            @if(in_array('2', $staffAccess))

                 <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.booking.service*',3)}}">
                        <i class="menu-icon las la-shopping-bag"></i>
                        <span class="menu-title">@lang('Service Booking')</span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('admin.booking.service*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('admin.booking.service.index')}} ">
                                <a href="{{route('admin.booking.service.index')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All')</span>
                                </a>
                            </li>

                             <li class="sidebar-menu-item {{menuActive('admin.booking.service.pending')}} ">
                                <a href="{{route('admin.booking.service.pending')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending')</span>
                                </a>
                            </li>

                             <li class="sidebar-menu-item {{menuActive('admin.booking.service.completed')}} ">
                                <a href="{{route('admin.booking.service.completed')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Completed')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.booking.service.delivered')}} ">
                                <a href="{{route('admin.booking.service.delivered')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Delivered')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.booking.service.inProgress')}} ">
                                <a href="{{route('admin.booking.service.inProgress')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('In-progress')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.booking.service.dispute')}} ">
                                <a href="{{route('admin.booking.service.dispute')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Dispute')</span>
                                </a>
                            </li>


                            <li class="sidebar-menu-item {{menuActive('admin.booking.service.expired')}} ">
                                <a href="{{route('admin.booking.service.expired')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Admin Dispute')</span>
                                </a>
                            </li>
                             <li class="sidebar-menu-item {{menuActive('admin.booking.service.dispute.resolved')}} ">
                                <a href="{{route('admin.booking.service.dispute.resolved')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Admin Dispute Resolved')</span>
                                </a>
                            </li>


                            <li class="sidebar-menu-item {{menuActive('admin.booking.service.cacnel')}} ">
                                <a href="{{route('admin.booking.service.cacnel')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Cancel')</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endif

            @if(in_array('3', $staffAccess))
                <li class="sidebar-menu-item  {{menuActive('admin.sales.software.index')}}">
                    <a href="{{route('admin.sales.software.index')}}" class="nav-link"
                       data-default-url="{{ route('admin.sales.software.index') }}">
                        <i class="menu-icon las la-shopping-cart"></i>
                        <span class="menu-title">@lang('Sales Software')</span>
                    </a>
                </li>
            @endif


            @if(in_array('4', $staffAccess))
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.hire*',3)}}">
                        <i class="menu-icon las la-user-secret"></i>
                        <span class="menu-title">@lang('Hire Employees')</span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('admin.hire*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('admin.hire.index')}} ">
                                <a href="{{route('admin.hire.index')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.hire.completed')}} ">
                                <a href="{{route('admin.hire.completed')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Completed')</span>
                                </a>
                            </li>


                            <li class="sidebar-menu-item {{menuActive('admin.hire.delivered')}} ">
                                <a href="{{route('admin.hire.delivered')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Delivered')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.hire.inprogress')}} ">
                                <a href="{{route('admin.hire.inprogress')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('In-progress')</span>
                                </a>
                            </li>

                              <li class="sidebar-menu-item {{menuActive('admin.hire.expired')}} ">
                                <a href="{{route('admin.hire.expired')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Expired')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif



            @if(in_array('5', $staffAccess))
                 <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.service*',3)}}">
                        <i class="menu-icon lab la-servicestack"></i>
                        <span class="menu-title">@lang('Manage Service')</span>
                        @if($servicePending > 0)
                            <span class="menu-badge pill bg--primary ml-auto">
                                <i class="fa fa-exclamation"></i>
                            </span>
                        @endif
                    </a>
                    <div class="sidebar-submenu {{menuActive('admin.service*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('admin.service.index')}} ">
                                <a href="{{route('admin.service.index')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.service.pending')}} ">
                                <a href="{{route('admin.service.pending')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending')</span>
                                    @if($servicePending)
                                        <span class="menu-badge pill bg--primary ml-auto">{{$servicePending}}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('admin.service.approved')}} ">
                                <a href="{{route('admin.service.approved')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Approved')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('admin.service.cancel')}} ">
                                <a href="{{route('admin.service.cancel')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Cancel')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            @if(in_array('6', $staffAccess))
                 <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.software*',3)}}">
                        <i class="menu-icon  lab la-gitlab"></i>
                        <span class="menu-title">@lang('Manage Software')</span>
                        @if($softwarePending > 0)
                            <span class="menu-badge pill bg--primary ml-auto">
                                <i class="fa fa-exclamation"></i>
                            </span>
                        @endif
                    </a>
                    <div class="sidebar-submenu {{menuActive('admin.software*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('admin.software.index')}} ">
                                <a href="{{route('admin.software.index')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.software.pending')}} ">
                                <a href="{{route('admin.software.pending')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending')</span>
                                     @if($softwarePending)
                                        <span class="menu-badge pill bg--primary ml-auto">{{$softwarePending}}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('admin.software.approved')}} ">
                                <a href="{{route('admin.software.approved')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Approved')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('admin.software.cancel')}} ">
                                <a href="{{route('admin.software.cancel')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Cancel')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            @if(in_array('7', $staffAccess))
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.job*',3)}}">
                        <i class="menu-icon las la-tasks"></i>
                        <span class="menu-title">@lang('Manage Job')</span>
                         @if($jobPending > 0)
                            <span class="menu-badge pill bg--primary ml-auto">
                                <i class="fa fa-exclamation"></i>
                            </span>
                        @endif
                    </a>
                    <div class="sidebar-submenu {{menuActive('admin.job*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('admin.job.index')}} ">
                                <a href="{{route('admin.job.index')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.job.pending')}} ">
                                <a href="{{route('admin.job.pending')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending')</span>
                                    @if($jobPending)
                                        <span class="menu-badge pill bg--primary ml-auto">{{$jobPending}}</span>
                                    @endif
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.job.approved')}} ">
                                <a href="{{route('admin.job.approved')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Approved')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.job.closed')}} ">
                                <a href="{{route('admin.job.closed')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Closed')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.job.cancel')}} ">
                                <a href="{{route('admin.job.cancel')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Cancel')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.hire.dispute')}} ">
                                <a href="{{route('admin.hire.dispute')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Dispute')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.job.earnings')}} ">
                                <a href="{{route('admin.job.earnings')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Earnings')</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endif

            @if(in_array('34', $staffAccess))
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.staff*',3)}}">
                        <i class="menu-icon las la-user-lock"></i>
                        <span class="menu-title">@lang('Manage Staff')</span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('admin.staff*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('admin.staff.index')}} ">
                                <a href="{{route('admin.staff.index')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.staff.create')}} ">
                                <a href="{{route('admin.staff.create')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Create')</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endif

            @if(in_array('8', $staffAccess))
                <li class="sidebar-menu-item  {{menuActive('admin.ads.index')}}">
                    <a href="{{route('admin.ads.index')}}" class="nav-link"
                       data-default-url="{{ route('admin.ads.index') }}">
                        <i class="menu-icon las la-tags"></i>
                        <span class="menu-title">@lang('Manage Advertises') </span>
                    </a>
                </li>
            @endif

             @if(in_array('33', $staffAccess))
                 <li class="sidebar-menu-item  {{menuActive('admin.rank.index')}}">
                    <a href="{{route('admin.rank.index')}}" class="nav-link"
                       data-default-url="{{ route('admin.rank.index') }}">
                        <i class="menu-icon las la-level-up-alt"></i>
                        <span class="menu-title">@lang('Manage Rank') </span>
                    </a>
                </li>
            @endif

            @if(in_array('10', $staffAccess))
                <li class="sidebar-menu-item  {{menuActive('admin.coupon.index')}}">
                    <a href="{{route('admin.coupon.index')}}" class="nav-link"
                       data-default-url="{{ route('admin.coupon.index') }}">
                        <i class="menu-icon lab la-discourse"></i>
                        <span class="menu-title">@lang('Setup Coupon') </span>
                    </a>
                </li>
            @endif

            @if(in_array('11', $staffAccess))
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.category*',3)}}">
                        <i class="menu-icon las la-bible"></i>
                        <span class="menu-title">@lang('Manage Category')</span>

                    </a>
                    <div class="sidebar-submenu {{menuActive('admin.category*',2)}} ">
                        <ul>

                            <li class="sidebar-menu-item {{menuActive('admin.category.index')}} ">
                                <a href="{{route('admin.category.index')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Category')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('admin.category.subcategory.index')}} ">
                                <a href="{{route('admin.category.subcategory.index')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Sub Category')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item  {{menuActive('admin.category.childcategory.index')}} ">
                                <a href="{{route('admin.category.childcategory.index')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Child Category')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('admin.category.skillcategory.index')}} ">
                                <a href="{{route('admin.category.skillcategory.index')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Attributes')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('admin.skill.index')}} ">
                                <a href="{{route('admin.skill.index')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Skills')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('admin.frontend.templates')}}">
                    <a href="{{route('admin.frontend.managetiers')}}" class="nav-link ">
                        <i class="menu-icon las la-dot-circle"></i>
                        <span class="menu-title">@lang('Manage Tiers')</span>
                    </a>
                </li>
                        </ul>
                    </div>
                </li>
            @endif

            @if(in_array('12', $staffAccess))
                <li class="sidebar-menu-item  {{menuActive('admin.features.index')}}">
                    <a href="{{route('admin.features.index')}}" class="nav-link"
                       data-default-url="{{ route('admin.features.index') }}">
                        <i class="menu-icon las la-exchange-alt"></i>
                        <span class="menu-title">@lang('Features') </span>
                    </a>
                </li>
            @endif

            @if(in_array('13', $staffAccess))
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.gateway*',3)}}">
                        <i class="menu-icon las la-credit-card"></i>
                        <span class="menu-title">@lang('Payment Gateways')</span>

                    </a>
                    <div class="sidebar-submenu {{menuActive('admin.gateway*',2)}} ">
                        <ul>

                            <li class="sidebar-menu-item {{menuActive('admin.gateway.automatic.index')}} ">
                                <a href="{{route('admin.gateway.automatic.index')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Automatic Gateways')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('admin.gateway.manual.index')}} ">
                                <a href="{{route('admin.gateway.manual.index')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Manual Gateways')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            @if(in_array('14', $staffAccess))
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.deposit*',3)}}">
                        <i class="menu-icon las la-credit-card"></i>
                        <span class="menu-title">@lang('Deposits')</span>
                        @if(0 < $pending_deposits_count)
                            <span class="menu-badge pill bg--primary ml-auto">
                                <i class="fa fa-exclamation"></i>
                            </span>
                        @endif
                    </a>
                    <div class="sidebar-submenu {{menuActive('admin.deposit*',2)}} ">
                        <ul>

                            <li class="sidebar-menu-item {{menuActive('admin.deposit.pending')}} ">
                                <a href="{{route('admin.deposit.pending')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending Deposits')</span>
                                    @if($pending_deposits_count)
                                        <span class="menu-badge pill bg--primary ml-auto">{{$pending_deposits_count}}</span>
                                    @endif
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.deposit.approved')}} ">
                                <a href="{{route('admin.deposit.approved')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Approved Deposits')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.deposit.successful')}} ">
                                <a href="{{route('admin.deposit.successful')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Successful Deposits')</span>
                                </a>
                            </li>


                            <li class="sidebar-menu-item {{menuActive('admin.deposit.rejected')}} ">
                                <a href="{{route('admin.deposit.rejected')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Rejected Deposits')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.deposit.list')}} ">
                                <a href="{{route('admin.deposit.list')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All Deposits')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            @if(in_array('15', $staffAccess))
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.withdraw*',3)}}">
                        <i class="menu-icon la la-bank"></i>
                        <span class="menu-title">@lang('Withdrawals') </span>
                        @if(0 < $pending_withdraw_count)
                            <span class="menu-badge pill bg--primary ml-auto">
                                <i class="fa fa-exclamation"></i>
                            </span>
                        @endif
                    </a>
                    <div class="sidebar-submenu {{menuActive('admin.withdraw*',2)}} ">
                        <ul>

                            <li class="sidebar-menu-item {{menuActive('admin.withdraw.method.index')}}">
                                <a href="{{route('admin.withdraw.method.index')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Withdrawal Methods')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.withdraw.pending')}} ">
                                <a href="{{route('admin.withdraw.pending')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending Log')</span>

                                    @if($pending_withdraw_count)
                                        <span class="menu-badge pill bg--primary ml-auto">{{$pending_withdraw_count}}</span>
                                    @endif
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.withdraw.approved')}} ">
                                <a href="{{route('admin.withdraw.approved')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Approved Log')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.withdraw.rejected')}} ">
                                <a href="{{route('admin.withdraw.rejected')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Rejected Log')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.withdraw.log')}} ">
                                <a href="{{route('admin.withdraw.log')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Withdrawals Log')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif


            @if(in_array('16', $staffAccess))
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.ticket*',3)}}">
                        <i class="menu-icon la la-ticket"></i>
                        <span class="menu-title">@lang('Support Ticket') </span>
                        @if(0 < $pending_ticket_count)
                            <span class="menu-badge pill bg--primary ml-auto">
                                <i class="fa fa-exclamation"></i>
                            </span>
                        @endif
                    </a>
                    <div class="sidebar-submenu {{menuActive('admin.ticket*',2)}} ">
                        <ul>

                            <li class="sidebar-menu-item {{menuActive('admin.ticket')}} ">
                                <a href="{{route('admin.ticket')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All Ticket')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('admin.ticket.pending')}} ">
                                <a href="{{route('admin.ticket.pending')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending Ticket')</span>
                                    @if($pending_ticket_count)
                                        <span
                                            class="menu-badge pill bg--primary ml-auto">{{$pending_ticket_count}}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('admin.ticket.closed')}} ">
                                <a href="{{route('admin.ticket.closed')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Closed Ticket')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('admin.ticket.answered')}} ">
                                <a href="{{route('admin.ticket.answered')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Answered Ticket')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif


            @if(in_array('17', $staffAccess))
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.report*',3)}}">
                        <i class="menu-icon la la-list"></i>
                        <span class="menu-title">@lang('Report') </span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('admin.report*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive(['admin.report.transaction','admin.report.transaction.search'])}}">
                                <a href="{{route('admin.report.transaction')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Transaction Log')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive(['admin.report.login.history','admin.report.login.ipHistory'])}}">
                                <a href="{{route('admin.report.login.history')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Login History')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.report.email.history')}}">
                                <a href="{{route('admin.report.email.history')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Email History')</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endif

            <li class="sidebar-menu-item sidebar-dropdown">
                <a href="javascript:void(0)" class="{{menuActive('admin.help-center*',3)}}">
                    <i class="menu-icon las la-headset"></i>
                    <span class="menu-title">@lang('Help Center') </span>
                </a>
                <div class="sidebar-submenu {{menuActive('admin.help-center*',2)}} ">
                    <ul>
                        <li class="sidebar-menu-item {{ request('type') == 'buyer' ? 'active' : '' }}">
                            <a href="{{ route('admin.help-center.index', ['type' => 'buyer']) }}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Buyer Help Center')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ request('type') == 'seller' ? 'active' : '' }}">
                            <a href="{{ route('admin.help-center.index', ['type' => 'seller']) }}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Seller Help Center')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ request('type') == 'policy' ? 'active' : '' }}">
                            <a href="{{ route('admin.help-center.index', ['type' => 'policy']) }}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Privacy Policy')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ request('type') == 'terms' ? 'active' : '' }}">
                            <a href="{{ route('admin.help-center.index', ['type' => 'terms']) }}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Terms & Conditions')</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>


            @if(in_array('18', $staffAccess))
                <li class="sidebar-menu-item  {{menuActive('admin.subscriber.index')}}">
                    <a href="{{route('admin.subscriber.index')}}" class="nav-link"
                       data-default-url="{{ route('admin.subscriber.index') }}">
                        <i class="menu-icon las la-thumbs-up"></i>
                        <span class="menu-title">@lang('Subscribers') </span>
                    </a>
                </li>
            @endif

            <li class="sidebar-menu-item {{menuActive('admin.contact.us.index')}}">
                <a href="{{route('admin.contact.us.index')}}" class="nav-link">
                    <i class="menu-icon la la-comment"></i>
                    <span class="menu-title">@lang('Contact Us')</span>
                </a>
            </li>


                <li class="sidebar__menu-header">@lang('Settings')</li>

            @if(in_array('19', $staffAccess))
                <li class="sidebar-menu-item {{menuActive('admin.setting.index')}}">
                    <a href="{{route('admin.setting.index')}}" class="nav-link">
                        <i class="menu-icon las la-life-ring"></i>
                        <span class="menu-title">@lang('General Setting')</span>
                    </a>
                </li>
            @endif

            @if(in_array('20', $staffAccess))
                <li class="sidebar-menu-item {{menuActive('admin.setting.logo.icon')}}">
                    <a href="{{route('admin.setting.logo.icon')}}" class="nav-link">
                        <i class="menu-icon las la-images"></i>
                        <span class="menu-title">@lang('Logo & Favicon')</span>
                    </a>
                </li>
            @endif

            @if(in_array('21', $staffAccess))
                <li class="sidebar-menu-item {{menuActive('admin.extensions.index')}}">
                    <a href="{{route('admin.extensions.index')}}" class="nav-link">
                        <i class="menu-icon las la-cogs"></i>
                        <span class="menu-title">@lang('Extensions')</span>
                    </a>
                </li>
            @endif

            @if(in_array('22', $staffAccess))
                <li class="sidebar-menu-item  {{menuActive(['admin.language.manage','admin.language.key'])}}">
                    <a href="{{route('admin.language.manage')}}" class="nav-link"
                       data-default-url="{{ route('admin.language.manage') }}">
                        <i class="menu-icon las la-language"></i>
                        <span class="menu-title">@lang('Language') </span>
                    </a>
                </li>
            @endif

            @if(in_array('23', $staffAccess))
                <li class="sidebar-menu-item {{menuActive('admin.seo')}}">
                    <a href="{{route('admin.seo')}}" class="nav-link">
                        <i class="menu-icon las la-globe"></i>
                        <span class="menu-title">@lang('SEO Manager')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{menuActive('')}}">
                    <a href="{{route('admin.email_detected')}}" class="nav-link">
                        <i class="menu-icon las la-globe"></i>
                        <span class="menu-title">@lang('Email Detected')</span>
                    </a>
                </li>
            @endif

            @if(in_array('24', $staffAccess))
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.email.template*',3)}}">
                        <i class="menu-icon la la-envelope-o"></i>
                        <span class="menu-title">@lang('Email Manager')</span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('admin.email.template*',2)}} ">
                        <ul>

                            <li class="sidebar-menu-item {{menuActive('admin.email.template.global')}} ">
                                <a href="{{route('admin.email.template.global')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Global Template')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive(['admin.email.template.index','admin.email.template.edit'])}} ">
                                <a href="{{ route('admin.email.template.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Email Templates')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.email.template.setting')}} ">
                                <a href="{{route('admin.email.template.setting')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Email Configure')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            @if(in_array('25', $staffAccess))
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.sms.template*',3)}}">
                        <i class="menu-icon la la-mobile"></i>
                        <span class="menu-title">@lang('SMS Manager')</span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('admin.sms.template*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('admin.sms.template.global')}} ">
                                <a href="{{route('admin.sms.template.global')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Global Setting')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('admin.sms.templates.setting')}} ">
                                <a href="{{route('admin.sms.templates.setting')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('SMS Gateways')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive(['admin.sms.template.index','admin.sms.template.edit'])}} ">
                                <a href="{{ route('admin.sms.template.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('SMS Templates')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            @if(in_array('26', $staffAccess))
                <li class="sidebar-menu-item {{menuActive('admin.setting.cookie')}}">
                    <a href="{{route('admin.setting.cookie')}}" class="nav-link">
                        <i class="menu-icon las la-cookie-bite"></i>
                        <span class="menu-title">@lang('GDPR Cookie')</span>
                    </a>
                </li>
            @endif

                <li class="sidebar__menu-header">@lang('Frontend Manager')</li>

            @if(in_array('27', $staffAccess))
                <li class="sidebar-menu-item {{menuActive('admin.frontend.templates')}}">
                    <a href="{{route('admin.frontend.templates')}}" class="nav-link ">
                        <i class="menu-icon la la-html5"></i>
                        <span class="menu-title">@lang('Manage Templates')</span>
                    </a>
                </li>
            @endif


            @if(in_array('28', $staffAccess))
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.frontend.sections*',3)}}">
                        <i class="menu-icon la la-html5"></i>
                        <span class="menu-title">@lang('Manage Section')</span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('admin.frontend.sections*',2)}} ">
                        <ul>
                            @php
                               $lastSegment =  collect(request()->segments())->last();
                            @endphp
                            @foreach(getPageSections(true) as $k => $secs)
                                @if($secs['builder'])
                                    <li class="sidebar-menu-item  @if($lastSegment == $k) active @endif ">
                                        <a href="{{ route('admin.frontend.sections',$k) }}" class="nav-link">
                                            <i class="menu-icon las la-dot-circle"></i>
                                            <span class="menu-title">{{__($secs['name'])}}</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </li>
            @endif

  @if(in_array('33', $staffAccess))


                <li class="sidebar-menu-item {{menuActive('admin.frontend.templates')}}">
                    <a href="{{route('admin.frontend.managelanguagesprofile')}}" class="nav-link ">
                        <i class="menu-icon la la-html5"></i>
                        <span class="menu-title">@lang('Profile Languages')</span>
                    </a>
                </li>


                <li class="sidebar-menu-item {{menuActive('admin.frontend.templates')}}">
                    <a href="{{route('admin.frontend.managedegreeprofile')}}" class="nav-link ">
                        <i class="menu-icon la la-html5"></i>
                        <span class="menu-title">@lang('Profile Degree')</span>
                    </a>
                </li>
                {{-- <li class="sidebar-menu-item {{menuActive('admin.frontend.templates')}}">
                    <a href="{{route('admin.frontend.chatdata-detail')}}" target="_blank" class="nav-link ">
                        <i class="menu-icon la la-html5"></i>
                        <span class="menu-title">@lang('Chat Data')</span>
                    </a>
                </li> --}}


            @endif




                <li class="sidebar__menu-header">@lang('Extra')</li>

            @if(in_array('29', $staffAccess))
                <li class="sidebar-menu-item  {{menuActive('admin.system.info')}}">
                    <a href="{{route('admin.system.info')}}" class="nav-link"
                       data-default-url="{{ route('admin.system.info') }}">
                        <i class="menu-icon las la-server"></i>
                        <span class="menu-title">@lang('System Information') </span>
                    </a>
                </li>
            @endif

            @if(in_array('30', $staffAccess))
                <li class="sidebar-menu-item {{menuActive('admin.setting.custom.css')}}">
                    <a href="{{route('admin.setting.custom.css')}}" class="nav-link">
                        <i class="menu-icon lab la-css3-alt"></i>
                        <span class="menu-title">@lang('Custom CSS')</span>
                    </a>
                </li>
            @endif

            @if(in_array('31', $staffAccess))
                <li class="sidebar-menu-item {{menuActive('admin.setting.optimize')}}">
                    <a href="{{route('admin.setting.optimize')}}" class="nav-link">
                        <i class="menu-icon las la-broom"></i>
                        <span class="menu-title">@lang('Clear Cache')</span>
                    </a>
                </li>
            @endif

            @if(in_array('32', $staffAccess))
                <li class="sidebar-menu-item  {{menuActive('admin.request.report')}}">
                    <a href="{{route('admin.request.report')}}" class="nav-link"
                       data-default-url="{{ route('admin.request.report') }}">
                        <i class="menu-icon las la-bug"></i>
                        <span class="menu-title">@lang('Report & Request') </span>
                    </a>
                </li>
            @endif
            </ul>
            <div class="text-center mb-3 text-uppercase">
                <span class="text--primary">{{__(systemDetails()['name'])}}</span>
                <span class="text--success">@lang('V'){{systemDetails()['version']}} </span>
            </div>
        </div>
    </div>
</div>
<!-- sidebar end -->
