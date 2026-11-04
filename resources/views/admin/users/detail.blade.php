@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-3 col-lg-5 col-md-5 mb-30">

            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body p-0">
                    <div class="p-3 bg--white">
                        @if(!is_null($user->image))
                            <div class="text-center">
                                <img src="{{ Storage::url($user->image)}}" alt="@lang('Profile Image')" style="border-radius: 50%;width:85px;height:85px;object-fit:cover">
                            </div>
                        @else
                            <div class="d-flex justify-content-center">
                                <div class="rounded-circle border border-secondary text-white bg-green d-flex justify-content-center align-items-center fw-bold"
                                    style="height:70px;width:70px;font-size:20px">
                                    {{ strtoupper(substr($user->firstname ?? 'A', 0, 1)) }} {{ strtoupper(substr($user->lastname ?? 'A', 0, 1)) }}
                                </div>
                            </div>
                        @endif
                        <div class="mt-15">
                            <h4 class="">{{$user->fullname}}</h4>
                            <span class="text--small">@lang('Joined At') <strong>{{showDateTime($user->created_at,'d M, Y h:i A')}}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('User information')</h5>
                    <ul class="list-group">

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span class="font-weight-bold">{{$user->username}}</span>
                        </li>


                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            @if($user->status == 1)
                                <span class="badge badge-pill bg--success">@lang('Active')</span>
                            @else
                                <span class="badge badge-pill bg--danger">@lang('Deactive')</span>
                            @endif
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Balance')
                            <span class="font-weight-bold">{{getAmount($user->balance)}}  {{__($general->cur_text)}}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('User action')</h5>
                    <a data-toggle="modal" href="#addSubModal" class="btn btn--success btn--shadow btn-block btn-lg">
                        @lang('Add/Subtract Balance')
                    </a>
                    <a href="{{ route('admin.users.login.history.single', $user->id) }}"
                       class="btn btn--primary btn--shadow btn-block btn-lg">
                        @lang('Login Logs')
                    </a>
                    <a href="{{route('admin.users.email.single',$user->id)}}"
                       class="btn btn--info btn--shadow btn-block btn-lg">
                        @lang('Send Email')
                    </a>
                    <a href="{{route('admin.users.login',$user->id)}}" target="_blank" class="btn btn--dark btn--shadow btn-block btn-lg">
                        @lang('Login as User')
                    </a>
                    <a href="{{route('admin.users.email.log',$user->id)}}" class="btn btn--warning btn--shadow btn-block btn-lg">
                        @lang('Email Log')
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-7 col-md-7 mb-30">

            <div class="row mb-none-30">
                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--deep-purple b-radius--10 box-shadow has--link">
                        <a href="{{route('admin.users.deposits',$user->id)}}" class="item--link"></a>
                        <div class="icon">
                            <i class="fa fa-credit-card"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="currency-sign"> {{__($general->cur_sym)}}</span>
                                <span class="amount">{{getAmount($totalDeposit)}}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Total Deposit')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->


                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--indigo b-radius--10 box-shadow has--link">
                        <a href="{{route('admin.users.withdrawals',$user->id)}}" class="item--link"></a>
                        <div class="icon">
                            <i class="fa fa-wallet"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="currency-sign">{{__($general->cur_sym)}}</span>
                                <span class="amount">{{getAmount($totalWithdraw)}}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Total Withdraw')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->

                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--12 b-radius--10 box-shadow has--link">
                        <a href="{{route('admin.users.transactions',$user->id)}}" class="item--link"></a>
                        <div class="icon">
                            <i class="la la-exchange-alt"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{$totalTransaction}}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Total Transaction')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->

                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--10 b-radius--10 box-shadow has--link">
                        <a href="{{ route('admin.users.service', $user->id) }}" class="item--link"></a>
                        <div class="icon">
                            <i class="lab la-servicestack"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{$totalService}}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Total Service')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->

                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--3 b-radius--10 box-shadow has--link">
                        <a href="{{ route('admin.users.software', $user->id) }}" class="item--link"></a>
                        <div class="icon">
                            <i class="lab la-gitlab"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{$totalSoftware}}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Total Software')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->

                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--13 b-radius--10 box-shadow has--link">
                        <a href="{{ route('admin.users.job', $user->id) }}" class="item--link"></a>
                        <div class="icon">
                            <i class="las la-user-secret"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{$totalJob}}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Total Job')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->

                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--15 b-radius--10 box-shadow has--link">
                        <a href="{{route('admin.users.service.booking', $user->id)}}" class="item--link"></a>
                        <div class="icon">
                            <i class="las la-shopping-bag"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{$totalServiceBooking}}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Total Service Booking')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->

                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--18 b-radius--10 box-shadow has--link">
                        <a href="{{route('admin.users.software.purchases', $user->id)}}" class="item--link"></a>
                        <div class="icon">
                            <i class="las la-shopping-cart"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{$totalPurchaseSoftware}}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Total Purchase Software')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->
            </div>
            <div class="card mt-50">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2">@lang('Information of') {{$user->fullname}}</h5>
                    <form action="{{route('admin.users.update',[$user->id])}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('First Name')<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="firstname" value="{{$user->firstname}}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Last Name') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="lastname" value="{{$user->lastname}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Email') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="email" name="email" value="{{$user->email}}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Mobile Number') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="mobile" value="{{$user->mobile}}">
                                </div>
                            </div>
                        </div>
                        @if($user->is_freelancer == 1)
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <label class="form-control-label font-weight-bold">@lang('Education') </label>
                                    </div>
                                </div>
                               @foreach($user->educations as $as)

                                    @php
                                        $file = $as->degrecertificate;
                                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                    @endphp

                                    <div class="col-lg-4 col-md-4 col-sm-6 col-6 mb-3 text-center">

                                        @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                                            <!-- Image Preview -->
                                            <div style="width:288px;height:164px;display:flex;align-items:center;justify-content:center;border:1px solid #eee;border-radius:6px">
                                                <img src="{{ asset('storage/' . $file) }}"
                                                    style="width: 288px; height: 164px; object-fit: cover;"
                                                    alt="Degree Certificate">
                                            </div>

                                        @elseif($ext == 'pdf')
                                            <!-- PDF Icon -->
                                            <div style="width:288px;height:164px;display:flex;align-items:center;justify-content:center;border:1px solid #eee;border-radius:6px">
                                                <a href="{{ asset('storage/' . $file) }}" target="_blank">
                                                    <i class="fa-solid fa-file-pdf" style="font-size:80px;color:red"></i>
                                                </a>
                                            </div>

                                        @elseif(in_array($ext, ['doc','docx']))
                                            <!-- Word Icon -->
                                            <div style="width:288px;height:164px;display:flex;align-items:center;justify-content:center;border:1px solid #eee;border-radius:6px">
                                                <a href="{{ asset('storage/' . $file) }}" target="_blank">
                                                    <i class="fa fa-file-word-o" style="font-size:80px;color:#2b579a"></i>
                                                </a>
                                            </div>

                                        @else
                                            <!-- Default File Icon -->
                                            <div style="width:288px;height:164px;display:flex;align-items:center;justify-content:center;border:1px solid #eee;border-radius:6px">
                                                <a href="{{ asset('storage/' . $file) }}" target="_blank">
                                                    <i class="fa fa-file" style="font-size:80px;color:gray"></i>
                                            </a>
                                        @endif

                                        <h6>{{ $as->institue }}, {{ $as->countryinstitue }}</h6>
                                        <p>{{ $as->degree }} - {{ $as->yeardegree }}</p>

                                    </div>

                                    @endforeach
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <label class="form-control-label font-weight-bold">@lang('Certification') </label>
                                    </div>
                                </div>
                               @foreach(@$user->certifications as $as)

                                    @php
                                        $file = $as->certificate;
                                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                    @endphp

                                    <div class="col-lg-4 col-md-4 col-sm-6 col-6 mb-3 text-center">

                                        <a href="{{ asset('storage/'.$file) }}" target="_blank">

                                            @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                                                <!-- Image Preview -->
                                                <img src="{{ asset('storage/'.$file) }}"
                                                    style="width:288px;height:164px;object-fit:cover;"
                                                    alt="Certificate">

                                            @elseif($ext == 'pdf')
                                                <!-- PDF Icon -->
                                                <div style="width:288px;height:164px;display:flex;align-items:center;justify-content:center;border:1px solid #eee;border-radius:6px">
                                                    <i class="fa-solid fa-file-pdf" style="font-size:70px;color:red"></i>
                                                </div>

                                            @elseif(in_array($ext,['doc','docx']))
                                                <!-- Word Icon -->
                                                <div style="width:288px;height:164px;display:flex;align-items:center;justify-content:center;border:1px solid #eee;border-radius:6px">
                                                    <i class="fa-solid fa-file-word" style="font-size:70px;color:#2b579a"></i>
                                                </div>

                                            @else
                                                <!-- Default Icon -->
                                                <div style="width:288px;height:164px;display:flex;align-items:center;justify-content:center;border:1px solid #eee;border-radius:6px">
                                                    <i class="fa-solid fa-file" style="font-size:70px;color:gray"></i>
                                                </div>
                                            @endif

                                        </a>

                                        <h6>{{$as->namecertificate}}, {{$as->nameinstitue}}</h6>
                                        <p>{{$as->yearcertificate}}</p>

                                    </div>

                                    @endforeach
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <label class="form-control-label font-weight-bold">@lang('Portfolio') </label>
                                    </div>
                                </div>
                                @foreach(@$user->portfolioprofile as $as)
                                <div class="col-lg-4 col-md-4 col-sm-6 col-6 mb-3 text-center">
                                    <img src="{{asset('storage/'.$as->image)}}" style="width: 288px; height: 164px; object-fit: cover;"/>
                                    <h6>{{$as->title}} </h6>
                                    <p>{!! $as->description !!}</p>
                                </div>
                                @endforeach

                            </div>
                        @endif
                        {{-- @if($user->is_employer == 1)
                            @if(@$user->userCompany)
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="form-group ">
                                            <h5 class="card-title border-bottom pb-2">@lang('Business Details')</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="form-control-label font-weight-bold">@lang('Company Name') </label>
                                            <p>{{@$user->userCompany->companyname}}</p>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="form-control-label font-weight-bold">@lang('Company web') </label>
                                            <p>{{@$user->userCompany->companyweb}}</p>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="form-control-label font-weight-bold">@lang('Company Industry') </label>
                                            <p>{{@$user->userCompany->companyind}}</p>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="form-control-label font-weight-bold">@lang('Company contact') </label>
                                            <p>{{@$user->userCompany->contact}}</p>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="form-control-label font-weight-bold">@lang('Company Timezone') </label>
                                            <p>{{@$user->userCompany->timezone}}</p>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="form-control-label font-weight-bold">@lang('Company State') </label>
                                            <p>{{@$user->userCompany->state}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="form-control-label font-weight-bold">@lang('Company Address') </label>
                                            <p>{{@$user->userCompany->address}}</p>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="form-control-label font-weight-bold">@lang('Company Zipcode') </label>
                                            <p>{{@$user->userCompany->zip}}</p>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="form-control-label font-weight-bold">@lang('Company Designation') </label>
                                            <p>{{@$user->userCompany->director}}</p>

                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group ">
                                            <label class="form-control-label font-weight-bold">@lang('Company Description') </label>
                                            <p>{!! $user->userCompany->description !!}</p>

                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif --}}
                        @if($user->is_employer == 1)
                        <div class="row mt-2">
                            <div class="col-xl-3 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('City') </label>
                                    <input class="form-control" type="text" name="city" value="{{$user->address->city}}" readonly>
                                </div>
                            </div>
                            {{-- @dd($user); --}}

                            <div class="col-xl-4 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('State') </label>
                                    <input class="form-control" type="text" name="state" value="{{@$user->address->state}}" readonly>
                                </div>
                            </div>

                            {{-- <div class="col-xl-4 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Zip/Postal') </label>
                                    <input class="form-control" type="text" name="zip" value="{{@$user->address->zip}}">
                                </div>
                            </div> --}}

                            <div class="col-xl-4 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Country') </label>
                                    <select name="country" class="form-control">
                                        @foreach($countries as $key => $country)
                                            <option value="{{ $country->country }}" 
                                                {{ $country->country == @$user->address->country ? 'selected' : '' }}>
                                                {{ __($country->country) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @elseif($user->is_freelancer == 1)
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Address') </label>
                                    <input class="form-control" type="text" name="address" value="{{@$user->address->address}}">
                                </div>
                            </div>

                            <!-- <div class="col-xl-3 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('City') </label>
                                    <input class="form-control" type="text" name="city" value="{{@$user->address->city}}">
                                </div>
                            </div> -->

                            <div class="col-xl-4 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('State') </label>
                                    <input class="form-control" type="text" name="state" value="{{@$user->address->state}}">
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Zip/Postal') </label>
                                    <input class="form-control" type="text" name="zip" value="{{@$user->address->zip}}">
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Country') </label>
                                    <select name="country" class="form-control">
                                        @foreach($countries as $key => $country)
                                            <option value="{{ $key }}" @if($country->country == @$user->address->country ) selected @endif>{{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="form-group col-xl-4 col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('Status') </label>
                                <input type="checkbox" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Deactive')" data-width="100%"
                                       name="status"
                                       @if($user->status == 1) checked @endif>
                            </div>

                            <div class="form-group  col-xl-4 col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('Email Verification') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="ev"
                                       @if($user->ev) checked @endif>

                            </div>

                            <div class="form-group  col-xl-4 col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('SMS Verification') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="sv"
                                       @if($user->sv) checked @endif>

                            </div>
                            <div class="form-group  col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('2FA Status') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Deactive')" name="ts"
                                       @if($user->ts) checked @endif>
                            </div>

                            <div class="form-group  col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('2FA Verification') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="tv"
                                       @if($user->tv) checked @endif>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Changes')
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    {{-- Add Sub Balance MODAL --}}
    <div id="addSubModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add / Subtract Balance')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.users.add.sub.balance', $user->id)}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Add Balance')" data-off="@lang('Subtract Balance')" name="act" checked>
                            </div>


                            <div class="form-group col-md-12">
                                <label>@lang('Amount')<span class="text-danger">*</span></label>
                                <div class="input-group has_append">
                                    <input type="text" name="amount" class="form-control" placeholder="@lang('Please provide positive amount')">
                                    <div class="input-group-append">
                                        <div class="input-group-text">{{ __($general->cur_sym) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--success">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
