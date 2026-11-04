@extends($activeTemplate.'layouts.wiserlance.layout')
@section('content')
@php
$helpers = new \App\Classes\Helpers();
$currency_rates = $helpers->get_currency_rates();
$currencies = $helpers->get_all_currencies();
// echo "<pre>";print_r($currency_rates);echo "</pre>";exit;
@endphp
<!-- ======================= Home Banner ======================== -->
<div class="home-banner1 margin-bottom-0" style="background:#28b661 url(assets/img/banner-3.jpg) no-repeat;height: 200px;" data-overlay="5">
    <div class="container"> 
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10 col-md-12 col-sm-12 col-12">
                <div class="banner_caption text-center mb-2">
                    <h1 class="ft-bold mb-4">{{$cat->name}}</h1>
                    
                </div>
            </div>
        </div> 
    </div>
</div>
</div>  
<!-- ======================= Home Banner ======================== -->
<!-- ======================= Listing ============================ -->
<!-- ======================= Filter Wrap Style 1 ======================== -->
<!--       <section class="py-2 br-bottom br-top">
<div class="container">
    <div class="row align-items-center justify-content-between">
        <div class="col-xl-3 col-lg-4 col-md-5 col-sm-12">
            <h6 class="mb-0 ft-medium fs-sm">302 New Jobs Found</h6>
        </div>
        
        <div class="col-xl-9 col-lg-8 col-md-7 col-sm-12">
            <div class="filter_wraps elspo_wrap d-flex align-items-center justify-content-end">
                <div class="single_fitres mr-2 br-right">
                    <select class="custom-select simple">
                        <option value="1" selected="">Default Sorting</option>
                        <option value="2">Recent jobs</option>
                        <option value="3">Featured jobs</option>
                        <option value="4">Trending Jobs</option>
                        <option value="5">Premium jobs</option>
                    </select>
                </div>
                
            </div>
        </div>
    </div>
    
</div> 
</section> -->
<!-- ============================= Filter Wrap ============================== -->
<!-- ============================ Main Section Start ================================== -->
<section class="bg-light">
<div class="container">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12">
            <div class="bg-white rounded">
                <div class="sidebar_header d-flex align-items-center justify-content-between px-4 py-3 br-bottom">
                    <h4 class="ft-medium fs-lg mb-0">Search Filter</h4>
                    <div class="ssh-header">
                        <!-- <a href="javascript:void(0);" class="clear_all ft-medium text-muted">Clear All</a> -->
                        <a href="#search_open" data-toggle="collapse" aria-expanded="false" role="button" class="collapsed _filter-ico ml-2"><i class="lni lni-text-align-right"></i></a>
                    </div>
                </div>
               <!-- Find New Property -->
                <div class="sidebar-widgets collapse miz_show" id="search_open" data-parent="#search_open">
                    <div class="search-inner">
                        <div class="filter-search-box px-4 pt-3 pb-0">
                            <div class="form-group">
                               <input type="text" name="keyword" class="form-control keyword" placeholder="Search by keywords..." style="margin-bottom: -30px;">
                                <button style="    height: 35px;
                                width: 40px;
                                position: relative;
                                top: -8px;
                                right: -3px !important;
                                float: right;
                                border-radius: 0px !important;background: transparent;" type="submit" class="btn text-light keywordbtn"><i  class="fa fa-search" style="margin-left:-10px;color: #28B661;"></i></button>
                                <!-- <input type="text" name="locationss" class="form-control locationss" placeholder="Location, Zip.." style="margin-top: -40px;">
                                <button style="    height: 35px;
                                width: 40px;
                                position: relative;
                                top: -38px;
                                right: 1px !important;
                                float: right;
                                border-radius: 0px !important;background: transparent;" type="submit" class="btn text-light locbtn"><i class="fa fa-search" style="margin-left:-6px !important;color: #28B661;"></i></button> -->
                            </div>
                            <div class="form-group">
                                
                            </div>
                        </div>
                        <div class="filter_wraps " style="margin-top: 40px;">
                            <!-- Categories Search -->
                            <div class="single_search_boxed px-4 pt-0 br-bottom">
                                <div class="widget-boxed-header">
                                    <h4>
                                    <a href="#category" data-toggle="collapse" aria-expanded="false" role="button" class="ft-medium fs-md pb-0 collapsed">Categories</a>
                                    </h4>
                                </div>
                                <div class="widget-boxed-body collapse" id="category" data-parent="#category">
                                    <div class="side-list no-border">
                                        <!-- Single Filter Card -->
                                        <div class="single_filter_card">
                                            <div class="card-body p-0">
                                                <div class="inner_widget_link">
                                                    <ul class="no-ul-list filter-list">
                                                        @foreach($categoryes as $category)
                                                        <li>
                                                            <input id="cat-box-{{$category->id}}" class="checkbox-custom category-checkbox category-checkboxes"  type="checkbox" name="{{$category->id}}" value="{{$category->id}}" data-id="{{ $category->id }}" />
                                                            <label for="cat-box-{{$category->id}}" class="checkbox-custom-label">{{ $category->name}}</label>
                                                        </li>
                                                        @endforeach
                                                        
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Sub Categories Search -->
                            <div class="single_search_boxed px-4 pt-0 br-bottom subcategory-div" style="display:none;">
                                <div class="widget-boxed-header">
                                    <h4>
                                    <a href="#subcat" data-toggle="collapse" aria-expanded="false" role="button" class="ft-medium fs-md pb-0 collapsed">Sub Categories</a>
                                    </h4>
                                </div>
                                <div class="widget-boxed-body collapse" id="subcat" data-parent="#subcat">
                                    <div class="side-list no-border">
                                        <!-- Single Filter Card -->
                                        <div class="single_filter_card">
                                            <div class="card-body p-0">
                                                <div class="inner_widget_link">
                                                    <ul class="no-ul-list filter-list filteredsubs">
                                                        {{--
                                                        @foreach($subcategoryes as $key=>$subcategory)
                                                        <li>
                                                            <input id="subcat-box-<?= $subcategory->id?>" class="checkbox-custom subcategory-checkbox subcategory-checkboxes subcategory-checkboxes-{{ $subcategory->category_id }}" type="checkbox" value="<?= $subcategory->id?>" name="<?= $subcategory->id?>" data-cat_id="{{ $subcategory->category_id }}" >
                                                            <label for="subcat-box-<?= $subcategory->id?>" class="checkbox-custom-label"> <?= $subcategory->name?></label>
                                                        </li>
                                                        @endforeach
                                                        --}}
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Skill Search -->
                            <div class="filteredchild" >
                                <?php if(isset($childcategories)){ ?>
                                <?php foreach($childcategories as $childcategory){ ?>
                                <div class="single_search_boxed px-4 pt-0 br-bottom childcategory-div childcategory-div-{{ $childcategory->sub_category_id }}" style="display:none;" >
                                    <div class="widget-boxed-header">
                                        <h4>
                                        <a href="#skillscat{{$childcategory->id}}" data-toggle="collapse" aria-expanded="false" role="button" class="ft-medium fs-md pb-0 collapsed"><?= $childcategory->name?></a>
                                        </h4>
                                    </div>
                                    <div class="widget-boxed-body collapse" id="skillscat{{$childcategory->id}}" data-parent="#skills">
                                        <div class="side-list no-border">
                                            <!-- Single Filter Card -->
                                            <div class="single_filter_card">
                                                <div class="card-body p-0">
                                                    <div class="inner_widget_link">
                                                        <ul class="no-ul-list filter-list ">
                                                            
                                                            @php
                                                            $skills=DB::table('skills')->where('skill_category_id', $childcategory->id)->get();
                                                            @endphp
                                                            @foreach($skills as $skill)
                                                            <li>
                                                                <input id="childcat-box-{{$skill->id}}" class="checkbox-custom skills-checkbox skill-checkboxes" type="checkbox" value="{{$skill->name}}" name="{{$skill->name}}">
                                                                <label for="childcat-box-{{$skill->id}}" class="checkbox-custom-label"> {{$skill->name}}</label>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php } ?>
                            </div>
                            <!-- Service Search -->
                            <!-- Skill Search -->
                            <!-- <div class="single_search_boxed px-4 pt-0 br-bottom left-bar-skills-div" style="display:<?= (isset($skills) )?'block':'none';?>;" >
                                <div class="widget-boxed-header">
                                    <h4>
                                    <a href="#skills" data-toggle="collapse" aria-expanded="false" role="button" class="ft-medium fs-md pb-0 collapsed">Skills</a>
                                    </h4>
                                </div>
                                <div class="widget-boxed-body collapse" id="skills" data-parent="#skills">
                                    <div class="side-list no-border">
                                        
                                        <div class="single_filter_card">
                                            <div class="card-body p-0">
                                                <div class="inner_widget_link">
                                                    <ul class="no-ul-list filter-list filteredskills">
                                                        <?php if(isset($skills)){ ?>
                                                        <?php foreach($skills as $skill){ ?>
                                                        <li>
                                                            <input id="skills-box-<?= $skill->id?>" class="checkbox-custom skills-checkbox" type="checkbox" value="<?= $skill->id?>" name="<?= $skill->id?>">
                                                            <label for="skills-box-<?= $skill->id?>" class="checkbox-custom-label"> <?= $skill->name?></label>
                                                        </li>
                                                        <?php } ?>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>  -->
                            <!-- Service Search -->
                            
                            
                             <!-- Type Search -->
                            <div class="single_search_boxed px-4 pt-0 br-bottom">
                                <div class="widget-boxed-header">
                                    <h4>
                                    <a href="#jobtype" data-toggle="collapse" aria-expanded="false" role="button" class="ft-medium fs-md pb-0 collapsed">Job Type</a>
                                    </h4>
                                </div>
                                <div class="widget-boxed-body collapse" id="jobtype" data-parent="#jobtype">
                                    <div class="side-list no-border">
                                        <!-- Single Filter Card -->
                                        <div class="single_filter_card">
                                            <div class="card-body p-0">
                                                <div class="inner_widget_link">
                                                    <ul class="no-ul-list filter-list">
                                                        <li>
                                                            <input id="d1w" class="checkbox-custom jtype_checkboxes" name="fixed"  value="fixed" type="checkbox">
                                                            <label for="d1w" class="checkbox-custom-label">Fixed</label>
                                                        </li>
                                                        <li>
                                                            <input id="d2w" class="checkbox-custom jtype_checkboxes" name="per_hour" value="per_hour" type="checkbox">
                                                            <label for="d2w" class="checkbox-custom-label">Per Hour</label>
                                                        </li>
                                                        
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Expected Salary Search -->
                            <div class="single_search_boxed px-4 pt-0 br-bottom">
                                <div class="widget-boxed-header">
                                    <h4>
                                    <a href="#budget" data-toggle="collapse" aria-expanded="false" role="button" class="ft-medium fs-md pb-0 collapsed">Budget</a>
                                    </h4>
                                </div>
                                <div class="widget-boxed-body collapse" id="budget" data-parent="#budget">
                                    <div class="side-list no-border">
                                        <!-- Single Filter Card -->
                                        <div class="single_filter_card">
                                            <div class="card-body p-0">
                                                <div class="inner_widget_link">
                                                    <ul class="no-ul-list filter-list">
                                                        <!-- <li>
                                                            <input id="g1" class="checkbox-custom" name="ADA" type="checkbox" checked="">
                                                            <label for="g1" class="checkbox-custom-label">$120k - $140k </label>
                                                        </li>
                                                        <li>
                                                            <input id="g2" class="checkbox-custom" name="Parking" type="checkbox">
                                                            <label for="g2" class="checkbox-custom-label">$140k - $150k </label>
                                                        </li>
                                                        <li>
                                                            <input id="g3" class="checkbox-custom" name="Coffee" type="checkbox">
                                                            <label for="g3" class="checkbox-custom-label">$150k - $170k </label>
                                                        </li>
                                                        <li>
                                                            <input id="g4" class="checkbox-custom" name="Mother" type="checkbox">
                                                            <label for="g4" class="checkbox-custom-label">$170k - $190k </label>
                                                        </li>
                                                        <li>
                                                            <input id="g5" class="checkbox-custom" name="Outdoor" type="checkbox">
                                                            <label for="g5" class="checkbox-custom-label">$200k - $250k </label>
                                                        </li>
                                                        <li>
                                                            <input id="g6" class="checkbox-custom" name="Pet" type="checkbox">
                                                            <label for="g6" class="checkbox-custom-label">$250k - $300k </label>
                                                        </li>
                                                        <li> -->
                                                            <div class="row">
                                                                <div class="col-lg-6 col-md-6 col-cm-6 col-6">
                                                                    <input type="text" id="minbudget" class="form-control" placeholder="Min">
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-cm-6 col-6">
                                                                    <input type="text" id="maxbudget" class="form-control" placeholder="Max">
                                                                </div>
                                                                <!-- <div class="col-lg-12">
                                                                    <div class="filter_button pt-2">
                                                                        <button type="submit"  class="btn btn-md theme-bg text-light rounded full-width" onclick="get_jobs()">Submit</button>
                                                                    </div>
                                                                </div> -->
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Experience Search -->
                            <div class="single_search_boxed px-4 pt-0 br-bottom">
                                <div class="widget-boxed-header">
                                    <h4>
                                    <a href="#experience" data-toggle="collapse" aria-expanded="false" role="button" class="ft-medium fs-md pb-0 collapsed">Experience Level</a>
                                    </h4>
                                </div>
                                <div class="widget-boxed-body collapse" id="experience" data-parent="#experience">
                                    <div class="side-list no-border">
                                        <!-- Single Filter Card -->
                                        <div class="single_filter_card">
                                            <div class="card-body p-0">
                                                <div class="inner_widget_link">
                                                    <ul class="no-ul-list filter-list">
                                                        <li>
                                                            <input id="d1" class="checkbox-custom exp_checkboxes" name="1"  value="1" type="checkbox">
                                                            <label for="d1" class="checkbox-custom-label">Entry Level</label>
                                                        </li>
                                                        <li>
                                                            <input id="d2" class="checkbox-custom exp_checkboxes" name="2" value="2" type="checkbox">
                                                            <label for="d2" class="checkbox-custom-label">Intermediate</label>
                                                        </li>
                                                        <li>
                                                            <input id="d3" class="checkbox-custom exp_checkboxes" name="3" value="3" type="checkbox">
                                                            <label for="d3" class="checkbox-custom-label">Expert</label>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Delivery Search -->
                            <div class="single_search_boxed px-4 pt-0 br-bottom">
                                <div class="widget-boxed-header">
                                    <h4>
                                    <a href="#delivery" data-toggle="collapse" aria-expanded="false" role="button" class="ft-medium fs-md pb-0 collapsed">Delivery Days</a>
                                    </h4>
                                </div>
                                <div class="widget-boxed-body collapse" id="delivery" data-parent="#delivery">
                                    <div class="side-list no-border">
                                        
                                        <div class="single_filter_card">
                                            <div class="card-body p-0">
                                                <div class="inner_widget_link">
                                                    <ul class="no-ul-list filter-list">
                                                       <!--  <li>
                                                            <div class="form-group">
                                                                <input type="text" name="delivery_time" class="form-control delivery_time" placeholder="Search Days...">
                                                                <div class="filter_button pt-2">
                                                                    <button type="submit" class="btn btn-md theme-bg text-light rounded full-width delivery_timebtn">Submit</button>
                                                                </div>
                                                            </li> -->
                                                              <div class="row">
                                                                <div class="col-lg-6 col-md-6 col-cm-6 col-6">
                                                                    <input type="text" id="mindays" class="form-control" placeholder="Min">
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-cm-6 col-6">
                                                                    <input type="text" id="maxdays" class="form-control" placeholder="Max">
                                                                </div>
                                                                
                                                            </div>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                                <!-- Other Lang Search -->
                                <!-- <div class="single_search_boxed px-4 pt-0 br-bottom">
                                    <div class="widget-boxed-header">
                                        <h4>
                                        <a href="#language" data-toggle="collapse" aria-expanded="false" role="button" class="ft-medium fs-md pb-0 collapsed">Other Languages</a>
                                        </h4>
                                    </div>
                                    <div class="widget-boxed-body collapse" id="language" data-parent="#language">
                                        <div class="side-list no-border">
                                            
                                            <div class="single_filter_card">
                                                <div class="card-body p-0">
                                                    <div class="inner_widget_link">
                                                        <ul class="no-ul-list filter-list">
                                                            <li>
                                                                <div class="form-group">
                                                                    <input type="text" name="otherlang" class="form-control otherlang" id="lang-search-input" placeholder="Search ...">
                                                                    
                                                                </li>
                                                            </ul>
                                                            
                                                            <div class="navigation-search-div search-dropdown-wraper" style="display: none;">
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Sidebar End -->
                </div>
                
                <!-- Item Wrap Start -->
                <div class="col-lg-9 col-md-12 col-sm-12 jobs-filter-wrp">
                    <!-- row -->
                    <div class="row align-items-center mx-1">
                        <!-- Single -->
                        @foreach($jobs as $job)
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-4 service-hov">
                            {{--<!-- <div class="row px-3 py-3  bg-white">
                                <div class="col-xl-9 col-lg-9 text-left">
                                    <div class="d-block mb-2 position-relative">
                                        <span class="text-muted medium"><i class="fa fa-user mr-1"></i>{{$job->user->username}}</span>
                                        @if(isset($user->status) && $user->status == 1)
                                        <span class="muted medium ml-2 theme-cl"><i class="fa fa-check mr-1"></i>Verified</span>
                                        @else
                                        <span class="muted medium ml-2 text-danger"><i class="fa fa-times mr-1"></i>UnVerified</span>
                                        @endif
                                    </div>
                                    <h6 class="font-weight-bold">{{__($job->title)}}</h6>
                                    <p>{!!$job->description!!}</p>
                                    <div class="jbd-details mb-1">
                                        <ul class="p-0 skills_tag text-left">
                                            @if($job->skill > 0)
                                            @foreach($job->skill as $skill)
                                            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">{{__($skill)}}</span></li>
                                            @endforeach
                                            @else
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 ">
                                    @if(@$job->exp_type == 1)
                                    <p><i class="fa fa-award    mr-2"></i>Entry Level</p>
                                    @elseif(@$job->exp_type == 2)
                                    <p><i class="fa fa-award    mr-2"></i>Intermediate</p>
                                    @else(@$job->exp_type == 3)
                                    <p><i class="fa fa-award    mr-2"></i>Expert</p>
                                    @endif
                                    <p><i class="fa fa-clock mr-2"></i>{{$job->delivery_time}} @lang('Days')</p>
                                    @if($job->budgettype == 'per_hour')
                                    <p><i class="fa fa-dollar-sign mr-2"></i>{{$general->cur_sym}}{{showAmount($job->amount)}}/hour</p>
                                    @else
                                    <p><i class="fa fa-dollar-sign mr-2"></i>{{$general->cur_sym}}{{showAmount($job->amount)}}</p>
                                    @endif
                                   <!--  <p><i class="fa fa-map mr-2"></i>{{@$job->user->address->country}}</p> -->
                                    <a href="{{route('job.details', [slug($job->title), encrypt($job->id)])}}" class="btn theme-bg text-light ft-medium apply-btn fs-sm rounded full-width">@lang('View Details')<i class="lni lni-arrow-right-circle ml-1"></i></a>
                                </div>
                            </div> --> --}}

                            <div class="row bg-white">
                        <div class="col-xl-9 col-lg-9 text-left px-3 py-3  ">
                            <div class="d-block mb-2 position-relative">
                                <span class="text-muted medium"><i class="fa fa-user mr-1"></i>{{$job->user->username}}
                                    @if($job->priority  == 2)
                                    <span style="float: right;" class="feature text-uppercase">featured</span>
                                    @endif
                                </span>
                                @if(isset($user->status) && $user->status == 1)
                                <span class="muted medium ml-2 theme-cl"><i class="fa fa-check mr-1"></i>Verified</span>
                                @else
                                <span class="muted medium ml-2 text-danger"><i class="fa fa-times mr-1"></i>UnVerified</span>
                                @endif
                            </div>
                            <h6 class="font-weight-bold">{{__($job->title)}}</h6>
                            <p >{!!$job->description!!}</p>
                            <div class="jbd-details mb-1">
                                <ul class="p-0 skills_tag text-left">
                                    @if($job->skill > 0)
                                    @foreach($job->skill as $skill)
                                    <li><span class="px-2 py-1 medium skill-bg rounded text-dark">{{__($skill)}}</span></li>
                                    @endforeach
                                    @else
                                    @endif
                                </ul>
                            </div>
                        </div>
                        @php
                        $bids=DB::table('user_biding')->where('job_id',$job->id)->count();
                        $offers=DB::table('user_offers')->where('job_id',$job->id)->count();
                        @endphp
                        <div class="col-xl-3 col-lg-3 px-3 py-3  " style="background:rgba(106, 5, 106, 0.05);" >
                            @if(@$job->exp_type == 1)
                            <p style="margin-top:-10px;"><i class="fa fa-user  mr-2" style="color:#007bff !important;"></i>Entry Level</p>
                            @elseif(@$job->exp_type == 2)
                            <p style="margin-top:-10px;"><i class="fa fa-user  mr-2" style="color:#007bff !important;"></i>Intermediate</p>
                            @else(@$job->exp_type == 3)
                            <p style="margin-top:-10px;"><i class="fa fa-user  mr-2" style="color:#007bff !important;"></i>Expert</p>
                            @endif
                            <p style="margin-top:-10px;"><i class="fa fa-map-marker-alt mr-2" style="color:#6f42c1 !important;"></i>{{$job->country}} </p>
                            <!-- <p style="margin-top:-10px;"><i class="fa fa-clock mr-2"></i>{{$job->delivery_time}} @lang('Days')</p> -->
                            @if($job->budgettype == 'per_hour')
                            <p style="margin-top:-10px;"><i class="fa fa-clock mr-2" style="color:#ffc107 !important;"></i>Hourly</p>
                            @else
                            <p style="margin-top:-10px;"><i class="fa fa-clock mr-2" style="color:#ffc107 !important;"></i>Fixed</p>
                            @endif
                            @if($job->budgettype == 'per_hour')
                            <p style="margin-top:-10px;"><i class="fa fa-dollar-sign mr-2" style="color:#17a2b8 !important;"></i>{{ Session::get('currency_symbol') }} {{
                                number_format($helpers->convert_to_currency(Session::get('currency'),
                            @$job->amount),2) }}/hour</p>
                            @else
                            <p style="margin-top:-10px;"><i class="fa fa-dollar-sign mr-2" style="color:#17a2b8 !important;"></i>{{ Session::get('currency_symbol') }} {{
                                number_format($helpers->convert_to_currency(Session::get('currency'),
                            @$job->amount),2) }}</p>
                            
                            @endif
                            <p style="margin-top:-10px;"><i class="fa fa-calendar mr-2" style="color:#dc3545 !important;"></i>@lang('Posted '): {{diffforhumans($job->created_at)}}</p>
                            <p style="margin-top:-10px;"><i class="fa fa-file-alt mr-2" style="color:#28a745 !important"></i>@lang('Bids'): {{$bids}}</p>
                            <p style="margin-top:-10px;"><i class="fa fa-file-alt mr-2" style="color:#7393B3 !important;"></i>@lang('Offers'): {{$offers}}</p>
                            <!--  <p><i class="fa fa-map mr-2"></i>{{@$job->user->address->country}}</p> -->
                            <a href="{{route('job.details', [slug($job->title), encrypt($job->id)])}}" class="btn theme-bg text-light ft-medium apply-btn fs-sm rounded full-width">@lang('View Details')<i class="lni lni-arrow-right-circle ml-1"></i></a>
                        </div>
                    </div> 
                        </div> 
                        @endforeach 
                    </div>
                    <!-- row -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                           {{ $jobs->links() }}
                           <!--  <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span class="fas fa-arrow-circle-right"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item active"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">...</a></li>
                                <li class="page-item"><a class="page-link" href="#">18</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span class="fas fa-arrow-circle-right"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ============================ Main Section End ================================== -->
    <!-- ======================= Listing ============================ -->
    @endsection
    @section('script')
    <script type="text/javascript">
            function left_menu_ajax_call(param = null, param_id = null){
                $.ajax({
                    url: "{{url('/filters_left_menu')}}",
                    method: "POST",
                    data: {"_token": "{{csrf_token()}}", 'param' : param, 'param_id' : param_id},
                    success: function(response){
                        if(response != 0){
                            var result = jQuery.parseJSON(response);
                            if(result.status == 'true'){
                                if(param == 'category'){
                                    $('.subcategory-div').show();
                                    $('.filteredsubs').append(result.content_data);
                                }else if(param == 'subcategory'){
                                    $('.filteredchild').append(result.content_data);
                                }
                            }else{
                                alert(result.message);
                            }
                            get_jobs();
                            $("#loading").hide();
                        }else{
                            $("#loading").hide();
                            alert('Something went wrong');
                        }
                    },
                    error: function(){
                        $("#loading").hide();
                    }
                });
            }
        $(document).on('click','.category-checkbox',function(){
                var $this = $(this);
                var category_id = $this.val();
                // console.log(category_id);
                if($this.is(':checked')){
                    // console.log('yes');
                    left_menu_ajax_call('category', category_id);
                }else{
                    // console.log('no');
                    $('.subcategory-checkboxes-'+category_id).parent().remove();
                    $('.childcategory-div-category-'+category_id).remove();
                }
                if($('.subcategory-checkbox').length == 0){
                    $('.subcategory-div').hide();
                }
        });
        $(document).on('click','.subcategory-checkbox',function(){
                var $this = $(this);
                var subcategory_id = $this.val();
                // console.log(subcategory_id);
                if($this.is(':checked')){
                    // console.log('yes');
                    left_menu_ajax_call('subcategory', subcategory_id);
                }else{
                    // console.log('no');
                    $('.childcategory-div-subcategory-'+subcategory_id).remove();
                }
        });
        /** /
        function category_checkbox(){
        $("#loading").show();
        var category = [];
        $('.category-checkbox').each(function(key, value){
        var $this = $(this);
        if($this.is(':checked')){
        category.push($this.val());
        }
        });
        // console.log(category);
        $.ajax({
        url: "{{url('/filterpro_subcats')}}",
        method: "POST",
        data: {"_token": "{{csrf_token()}}",'category' : category},
        success: function(response){
        if(response != 0){
        var result = jQuery.parseJSON(response);
        $('.filteredsubs').html(result.content_data);
        if($('.subcategory-checkbox').length == 0){
        $('.subcategory-div').hide();
        }else{
        $('.subcategory-div').show();
        }
        get_jobs();
        $("#loading").hide();
        }else{
        $("#loading").hide();
        alert('Something went wrong');
        }
        },
        error: function(){
        $("#loading").hide();
        }
        });
        }
        /**/
        </script>
        <script type="text/javascript">
        // $(document).on('click','.subcategory-checkbox',function(){
        // subcategory_checkbox();
        // });
        function subcategory_checkbox(){
        // alert();
        $('.childcategory-div').hide();
        $('.subcategory-checkboxes').each(function(){
        $this = $(this);
        var subcat_id = $this.val();
        if($this.is(':checked')){
        // console.log('123');
        $('.childcategory-div-'+subcat_id).show();
        }else{
        // console.log('321');
        $('.childcategory-div-'+subcat_id).hide();
        }
        });
        }
        /** /
        function subcategory_checkbox(){
        $("#loading").show();
        var subcategory = [];
        $('.subcategory-checkbox').each(function(key, value){
        var $this = $(this);
        if($this.is(':checked')){
        subcategory.push($this.val());
        }
        });
        // console.log(category);
        $.ajax({
        url: "{{url('/filterpro_childcats')}}",
        method: "POST",
        data: {"_token": "{{csrf_token()}}",'subcategory' : subcategory},
        success: function(response){
        if(response != 0){
        var result = jQuery.parseJSON(response);
        $('.filteredchild').html(result.content_data);
        if($('.childcategory-checkbox').length == 0){
        $('.left-bar-childcategory-div').hide();
        }else{
        $('.left-bar-childcategory-div').show();
        }
        get_jobs();
        $("#loading").hide();
        }else{
        $("#loading").hide();
        alert('Something went wrong');
        }
        },
        error: function(){
        $("#loading").hide();
        }
        });
        }
        /**/
        </script>
        <script type="text/javascript">
        // $(document).on('click','.childcategory-checkbox',function(){
        // childcategory_checkbox();
        // });
        function childcategory_checkbox(){
        $("#loading").show();
        var childcategory = [];
        $('.childcategory-checkbox').each(function(key, value){
        var $this = $(this);
        if($this.is(':checked')){
        childcategory.push($this.val());
        }
        });
        // console.log(category);
        $.ajax({
        url: "{{url('/filterpro_skills')}}",
        method: "POST",
        data: {"_token": "{{csrf_token()}}",'childcategory' : childcategory},
        success: function(response){
        if(response != 0){
        var result = jQuery.parseJSON(response);
        $('.filteredskills').html(result.content_data);
        if($('.skills-checkbox').length == 0){
        $('.left-bar-skills-div').hide();
        }else{
        $('.left-bar-skills-div').show();
        }
        get_jobs();
        $("#loading").hide();
        }else{
        $("#loading").hide();
        alert('Something went wrong');
        }
        },
        error: function(){
        $("#loading").hide();
        }
        });
        }
        </script>
        <script type="text/javascript">
        $(document).on('click',' .category-checkbox,.subcategory-checkbox,.childcategory-checkbox,.skills-checkbox,.keywordbtn,.locbtn, .exp_checkboxes, .jtype_checkboxes, .fixhour_checkboxes, .lang_level, .otherlangbtn, .delivery_timebtn',function(){
        get_jobs();
        });
        $(document).on('keyup',' #minbudget,#maxbudget',function(){
        get_jobs();
        });
        $(document).on('keyup',' #mindays,#maxdays',function(){
        get_jobs();
        });
        function get_jobs(){
        
        var keyword = $('.keyword').val();
        // alert (keyword);
        // var otherlang = $('.otherlang').val();
        var delivery_time = $('.delivery_time').val();
        var location = $('.locationss').val();
        var price = $('.pricerange:checked').val();
        var category = [], subcategory = [], childcategory = [], skills=[], exp = [], jtype = [], fix = [],lang_level=[],otherlang = [];
        
        $('.exp_checkboxes').each(function(key, value){
        // alert("category");
        var $this = $(this);
        if($this.is(':checked')){
        var item_id = $this.attr('name');
        exp.push(item_id);
        }
        });
        $('.jtype_checkboxes').each(function(key, value){
        // alert("category");
        var $this = $(this);
        if($this.is(':checked')){
        var item_id = $this.attr('name');
        jtype.push(item_id);
        }
        });
        $('.lang_level').each(function(key, value){
        // alert("category");
        var $this = $(this);
        if($this.is(':checked')){
        var item_id = $this.attr('name');
        lang_level.push(item_id);
        }
        });
        $('.fixhour_checkboxes').each(function(key, value){
        // alert("category");
        var $this = $(this);
        if($this.is(':checked')){
        var item_id = $this.attr('name');
        fix.push(item_id);
        }
        });
        
        
        // alert(lang);die;
        $('.category-checkbox').each(function(key, value){
        
        var $this = $(this);
        if($this.is(':checked')){
        var item_id = $this.attr('name');
        category.push(item_id);
        // alert(category);
        }
        });
        $('.subcategory-checkbox').each(function(key, value){
        // alert("category");
        var $this = $(this);
        if($this.is(':checked')){
        var item_id = $this.attr('name');
        subcategory.push(item_id);
        }
        });
        $('.childcategory-checkbox').each(function(key, value){
        // alert(childcategory);
        var $this = $(this);
        if($this.is(':checked')){
        childcategory.push($this.val());
        }
        });
        $('.skills-checkbox').each(function(key, value){
        // alert(skills);
        var $this = $(this);
        if($this.is(':checked')){
        var item_id = $this.attr('name');
        skills.push(item_id);
        }
        });
        $('.otherlangbtn').each(function(key, value){
        // alert(skills);
        var $this = $(this);
        if($this.is(':checked')){
        var item_id = $this.attr('name');
        otherlang.push(item_id);
        }
        });
        
        var year =     $('#selectedyear').val();
        var minprice = $('#minprice').val();
        var maxprice = $('#maxprice').val(); 
        var minbudget = $('#minbudget').val();
        var maxbudget = $('#maxbudget').val();
        var mindays = $('#mindays').val();
        var maxdays = $('#maxdays').val();
        // alert(maxbudget);
        $("#loading").show();
        $.ajax({
        url: "{{url('/filter_jobs')}}",
        method: "POST", 
        //dataType: 'json',
        data: { "_token": "{{csrf_token()}}",'category':category,'subcategory':subcategory,'childcategory':childcategory,'skills':skills,  'minbudget':minbudget, 'maxbudget':maxbudget, 'mindays':mindays, 'maxdays':maxdays, 'keyword':keyword ,'location':location , 'exp':exp , 'jtype':jtype,  'otherlang':otherlang, 'delivery_time':delivery_time, 'fix':fix, 'lang_level':lang_level},
        success:function(response){
        if(response != 0){
        var result = jQuery.parseJSON(response);
        if(result.status == 'true'){
        // $('.strains-current-count').html('12');
        $('.count').html(result.count+ '<strong> Result Found </strong>');
        $('.jobs-filter-wrp').html(result.content_data);
        // $("#loading").hide();
        }else{
        
        }
        }else{
        
        }
        $("#loading").hide();
        
        },
        error:function(data){
        alert('failure');
        $("#loading").hide();
        }
        });
        /* Ajax Call */
        }
        </script>
        <script type="text/javascript">
        var $checkbox2 = $('.category-checkbox');
        var $collapseContainer2 = $('#subcat');
        var $collapseContainer =$('#category');
        $checkbox2.change(function() {
        $collapseContainer2.collapse('toggle');
        $collapseContainer.collapse('toggle');
        });
        </script>
        <script type="text/javascript">
        var $checkboxsub = $('.subcategory-checkbox');
        var $collapseContainer2 = $('#subcat');
        $checkboxsub.change(function() {
        $collapseContainer2.collapse('toggle');
        });
        </script>
        <script type="text/javascript">
        /*
        // duration of scroll animation
        var scrollDuration = 300;
        // paddles
        var dataId = $(this).attr("data-id");
        var leftPaddle = document.getElementsByClassName('left-paddle');
        var rightPaddle = document.getElementsByClassName('right-paddle');
        // get items dimensions
        var itemsLength = $('.item').length;
        var itemSize = $('.item').outerWidth(true);
        // get some relevant size for the paddle triggering point
        var paddleMargin = 80;
        // get wrapper width
        var getMenuWrapperSize = function() {
        return $('.menu-wrapper').outerWidth();
        }
        var menuWrapperSize = getMenuWrapperSize();
        // the wrapper is responsive
        $(window).on('resize', function() {
        menuWrapperSize = getMenuWrapperSize();
        });
        // size of the visible part of the menu is equal as the wrapper size
        var menuVisibleSize = menuWrapperSize;
        // get total width of all menu items
        var getMenuSize = function() {
        return itemsLength * itemSize;
        };
        var menuSize = getMenuSize();
        // get how much of menu is invisible
        var menuInvisibleSize = menuSize - menuWrapperSize;
        // get how much have we scrolled to the left
        var getMenuPosition = function() {
        return $('.menu').scrollLeft();
        };
        // finally, what happens when we are actually scrolling the menu
        $('.menu').on('scroll', function() {
        // get how much of menu is invisible
        menuInvisibleSize = menuSize - menuWrapperSize;
        // get how much have we scrolled so far
        var menuPosition = getMenuPosition();
        var menuEndOffset = menuInvisibleSize - paddleMargin;
        // show & hide the paddles
        // depending on scroll position
        if (menuPosition <= paddleMargin) {
        $(leftPaddle).addClass('hidden');
        $(rightPaddle).removeClass('hidden');
        } else if (menuPosition < menuEndOffset) {
        // show both paddles in the middle
        $(leftPaddle).removeClass('hidden');
        $(rightPaddle).addClass('hidden');
        } else if (menuPosition >= menuEndOffset) {
        $(leftPaddle).removeClass('hidden');
        $(rightPaddle).addClass('hidden');
        }
        // print important values
        $('#print-wrapper-size span').text(menuWrapperSize);
        $('#print-menu-size span').text(menuSize);
        $('#print-menu-invisible-size span').text(menuInvisibleSize);
        $('#print-menu-position span').text(menuPosition);
        });
        // scroll to left
        $(rightPaddle).on('click', function() {
        $('.menu').animate( { scrollLeft: menuInvisibleSize}, scrollDuration);
        });
        // scroll to right
        $(leftPaddle).on('click', function() {
        $('.menu').animate( { scrollLeft: '0' }, scrollDuration);
        });
        */
        // scroll to left
        $('.right-paddle').on('click', function() {
            var $this = $(this);
            var id = $this.data('id');
            var inner = $('#parentscrol-'+id);
            $('#parentscrol-'+id).animate({
                scrollLeft: inner.width()
                }, 1000
            );
            $('.right-paddle-'+id).hide();
            $('.left-paddle-'+id).show();
        });
        $('.left-paddle').on('click', function() {
            var $this = $(this);
            var id = $this.data('id');
            var inner = $('#parentscrol-'+id);
            $('#parentscrol-'+id).animate({
                scrollLeft: 0
                }, 1000
            );
            $('.left-paddle-'+id).hide();
            $('.right-paddle-'+id).show();
        });
        </script>
        <script type="text/javascript">
        $(document).on('keyup','#lang-search-input',function(){
        
        var search = $('#lang-search-input').val();
        if(search.length >= 3){
        $.ajax({
        url: "{{url('/search_other_langs')}}",
        method: "POST",
        data: { "_token": "{{csrf_token()}}", 'search' : search},
        success: function(response){
        if(response != 0){
        var result = jQuery.parseJSON(response);
        $('.search-dropdown-wraper').show();
        $('.search-dropdown-wraper').html(result.content_data);
        }
        }
        });
        }
        });
        </script>
    @endsection