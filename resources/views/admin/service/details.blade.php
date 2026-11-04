@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-3 col-lg-5 col-md-5 col-sm-12">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body p-0">
                    <div class="p-3 bg--white">
                        <div>
                            <img src="{{ getImage('assets/images/service/' . $service->image)}}" alt="@lang('Service image')"
                                class="b-radius--10 w-100">
                        </div>
                        <div class="mt-10">
                            <h4 class="">{{__($service->title)}}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Seller Information')</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span class="font-weight-bold">{{__($service->user->username)}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            @if($service->user->status == 1)
                                <span class="badge badge-pill bg--success">@lang('Active')</span>
                            @elseif($service->user->status == 0)
                                <span class="badge badge-pill bg--danger">@lang('Banned')</span>
                            @endif
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Balance')
                            <span class="font-weight-bold">{{getAmount($service->user->balance)}}
                                {{__($general->cur_text)}}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-7 col-md-7 col-sm-12 mt-10">
            @if($service->optionalImage->count() != 0)
                <div class="row mb-30">
                    <div class="col-lg-12">
                        <div class="card border--dark">
                            <h5 class="card-header bg--dark">@lang('Optional Image')</h5>
                            <div class="card-body">
                                <div class="row my-2">
                                    @foreach($service->optionalImage as $optional)
                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                            <a href="{{ getImage('assets/images/optionalService/' . $optional->image)}}"
                                                target="_blank">
                                                <img src="{{ getImage('assets/images/optionalService/' . $optional->image)}}"
                                                    class="b-radius--10 w-80 ml-2 my-3" alt="@lang('Optional Image')">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row mb-30">
                <div class="col-lg-6 mt-2">
                    <div class="card border--dark">
                        <h5 class="card-header bg--dark">@lang('Service Main Information')</h5>
                        <div class="card-body">
                            <ul class="list-group">
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                    @lang('Category Level 1')
                                    <span>{{__($service->category->name)}}</span>
                                </li>
                                @if(!empty($service->sub_category_id))
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                        @lang('Category Level 2')
                                        <span>{{__($service->subCategory->name)}}</span>
                                    </li>
                                @endif
                                @if(!empty($service->childcat_id))
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                        @lang('Category Level 3')
                                        <span>{{__(@$service->childCategory->name)}}</span>
                                    </li>
                                @endif

                                <!-- <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                      @lang('Service Price')
                                      <span>{{getAmount($service->price)}} {{$general->cur_text}}</span>
                                    </li> -->

                                <!-- <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                      @lang('Delivery Time')
                                        <span>{{$service->delivery_time}} @lang('Days')</span>
                                    </li> -->

                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                    @lang('Last Update')
                                    <span>{{diffforhumans($service->updated_at)}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mt-2">
                    <div class="card border--dark">
                        <h5 class="card-header bg--dark">@lang('Service Other Information')</h5>
                        <div class="card-body">
                            <ul class="list-group">
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                    @lang('Status')
                                    @if($service->status == 1)
                                        <span class="font-weight-normal badge--success badge--sm">@lang('Approved')</span>
                                    @elseif($service->status == 2)
                                        <span class="font-weight-normal badge--danger badge--sm">@lang('Cancel')</span>
                                    @else
                                        <span class="font-weight-normal badge--primary badge--sm">@lang('Pending')</span>
                                    @endif
                                </li>

                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                    @lang('Featured Item')
                                    @if($service->featured == 0)
                                        <span class="font-weight-normal badge--warning badge--sm">@lang('Not Include')</span>
                                    @else
                                        <span class="font-weight-normal badge--primary badge--sm">@lang('Include')</span>
                                    @endif
                                </li>
                                @php
                                    $rating = DB::table('review_ratings')->where('service_id', $service->id)->avg('rating');
                                @endphp
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                    @lang('Rating')
                                    <span>{{getAmount($rating)}}</span>
                                </li>

                                <!-- <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                      @lang('Like')
                                      <span>{{$service->likes}}</span>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                      @lang('Dislike')
                                        <span>{{$service->dislike}}</span>
                                    </li> -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>




            <div class="row mb-30">
                <!--  <div class="col-lg-6 mt-2">
                        <div class="card border--dark">
                            <h5 class="card-header bg--dark">@lang('Features')</h5>
                            <div class="card-body">
                                <ul>
                                    @foreach($service->featuresService as $features)
                                        <li class="font-weight-bold">{{$loop->iteration}}. {{__($features->name)}}</li>
                                    @endforeach 
                                </ul>
                            </div>
                        </div>
                    </div> -->
                @php
                    $newextra = DB::table('seller_detail_skill_new_extra')->where('service_id', $service->id)->where('user_id', $service->user_id)->get();
                    // $newextrac = DB::table('seller_detail_skill_new_extra')->where('service_id', $service->id)->where('user_id', $service->user_id)->count();
                @endphp
                <div class="col-lg-6 mt-2">
                    <div class="card border--dark">
                        <h5 class="card-header bg--dark">@lang('Keywords for search')</h5>
                        <div class="card-body">
                            <ul>
                                @if($keywords->count() > 0)
                                    @foreach($keywords as $tag)
                                        <li class="font-weight-bold">{{$tag->name}}</li>
                                    @endforeach
                                @else
                                    <li class="font-weight-bold">no tags available</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                @php
                    $prnt = DB::table('seller_detail_skill_parent')->where('service_id', $service->id)->where('user_id', $service->user_id)->groupBy('parent')->get();
                @endphp
                <div class="col-lg-6 mt-2">
                    <div class="card border--dark">
                        <h5 class="card-header bg--dark">@lang('Skills & Experties')</h5>
                        <div class="card-body">
                            @foreach($prnt as $asd)
                                                    @php
                                                        $parent = DB::table('skill_categories')->where('id', $asd->parent)->first();
                                                        $child = DB::table('seller_detail_skill_parent')->where('service_id', $service->id)->where('user_id', $service->user_id)->where('parent', $asd->parent)->get();
                                                    @endphp
                                                    <!-- <h6>{{$parent->name}}</h6> -->
                                                    <ul>
                                                        @foreach($child as $chl)
                                                            <li class="font-weight-bold">{{$chl->child}}</li>
                                                        @endforeach
                                                    </ul>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            @if($mergedCheckbox->where('addons', '!=', collect())->count() > 0)
                <div class="row mb-30">
                    <div class="col-lg-12">
                        <div class="card border--dark">
                            <h5 class="card-header bg--dark">@lang('Service Addons')</h5>
                            <div class="card-body">
                                <div class="table-responsive--md table-responsive">
                                    <table class="table table--light style--two">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>@lang('Title')</th>
                                                <th>@lang('Delivery Time')</th>
                                                <th>@lang('Price')</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($mergedCheckbox as $tier)
                                                @if($tier->addons->count() > 0)
                                                    @foreach($tier->addons as $addon)
                                                        <tr>
                                                            <td data-label="@lang('Title')">
                                                                {{ $tier->name }}
                                                            </td>

                                                            <td data-label="@lang('Delivery Time')">
                                                                {{ $addon->delivery_time }} @if($addon->delivery_time > 1) @lang('Days') @else @lang('Day') @endif
                                                            </td>
                                                            <td data-label="@lang('Price')">
                                                                {{ getAmount($addon->price) }} {{ $general->cur_text }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- @if($service->extraService->count() != 0)
                <div class="row mb-30">
                    <div class="col-lg-12">
                        <div class="card border--dark">
                            <h5 class="card-header bg--dark">@lang('Extra Service')</h5>
                            <div class="card-body">
                                <div class="table-responsive--md  table-responsive">
                                    <table class="table table--light style--two">
                                        <thead>
                                            <tr>
                                                <th scope="col">@lang('Title')</th>
                                                <th scope="col">@lang('Price')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($service->extraService as $extra_service)
                                                <tr>
                                                    <td data-label="@lang('Title')">
                                                        {{$extra_service->title}}
                                                    </td>
                                                    <td data-label="@lang('Price')">{{getAmount($extra_service->price)}}
                                                        {{$general->cur_text}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif --}}

            <div class="row mb-30">
                <div class="col-lg-12">
                    <div class="card border--dark">
                        <h5 class="card-header bg--dark">@lang('Description')</h5>
                        <div class="card-body">
                            @php echo $service->description @endphp
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
        $serviceDetails = DB::table('services_details')->where('service_id', $service->id)->where('is_active', 1)->get();
    @endphp
    <div class="row mb-30">
        <div class="col-lg-12 mt-4 mb-3">
            <div class="card border--dark">
                <h5 class="card-header bg--dark">@lang('Service Tiers')</h5>
            </div>
        </div>
        @foreach($serviceDetails as $index => $detail)
            @php
                $packageName = $index === 0 ? 'Lite' : ($index === 1 ? 'Plus' : 'Ultimate');
                $tiers = $tierValues[$detail->id] ?? collect();
                $addOns = $addons[$detail->id] ?? collect();
            @endphp

            <div class="col-lg-4 mt-2">
                <div class="card border--dark">
                    <h5 class="card-header bg--dark">{{ $packageName }}</h5>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                Title:
                                {{ $detail->title }}
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                Description:
                                {{ $detail->desc }}
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                Price
                                <span>${{ $detail->price }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                Time
                                <span>{{ $detail->delivery_time }} Days</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                Revisions
                                <span>{{ $detail->revision }}</span>
                            </li>

                            {{-- Tiers --}}
                            @foreach($tiers as $tierId => $value)
                                @php
                                    $tierInfo = DB::table('service_tiers')->where('id', $tierId)->first();
                                @endphp

                                @if($tierInfo && $tierInfo->type === 'checkbox' && $value === 'yes')
                                    <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                        {{ $tierInfo->name }}
                                        <span><i class="fa fa-check text-success"></i></span>
                                    </li>
                                @elseif($tierInfo && in_array($tierInfo->type, ['input', 'select']))
                                    <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                        {{ $tierInfo->name }}
                                        <span>{{ $value }}</span>
                                    </li>
                                @endif
                            @endforeach

                            {{-- Add-ons --}}
                            @foreach($addOns as $addon)
                                <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                    Add-on (ID: {{ $addon->services_addson_id }})
                                    <span>{{ $addon->delivery_time }} days / ${{ $addon->price }}</span>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
            </div>
        @endforeach

        
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.service.index') }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i
            class="la la-fw la-backward"></i>@lang('Go Back')</a>
@endpush