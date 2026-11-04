@extends($activeTemplate.'layouts.wiserlance.layout')
@section('content')
@php
$helpers = new \App\Classes\Helpers();
$currency_rates = $helpers->get_currency_rates();
$currencies = $helpers->get_all_currencies();
// echo "<pre>";print_r($currency_rates);echo "</pre>";exit;
@endphp
<!-- ======================= Home Banner ======================== -->
<!-- <div class="home-banner1 margin-bottom-0" style="background:#28b661 url(assets/img/banner-2.jpg) no-repeat; " data-overlay="5">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10 col-md-12 col-sm-12 col-12">
            <div class="banner_caption text-center mb-2">
                <h1 class="ft-bold mb-4">The Most Top Services</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque officia exercitationem ab accusantium ex voluptatibus soluta culpa aut saepe? Delectus illo, optio quisquam inventore possimus vero, impedit quo enim eum.</p>
            </div>
        </div>
    </div>
</div>
</div> -->
<!-- ======================= Home Banner ======================== -->
<!-- ======================= Listing ============================ -->
<!-- ======================= Filter Wrap Style 1 ======================== -->
<!-- <section class="py-2 br-bottom br-top">
<div class="container">
    <div class="row align-items-center justify-content-between">
        <div class="col-xl-3 col-lg-4 col-md-5 col-sm-12">
            <h6 class="mb-0 ft-medium fs-sm">302 New Services Found</h6>
        </div>
        <div class="col-xl-9 col-lg-8 col-md-7 col-sm-12">
            <div class="filter_wraps elspo_wrap d-flex align-items-center justify-content-end">
                <div class="single_fitres mr-2 br-right">
                    <select class="custom-select simple">
                        <option value="1" selected="">Default Sorting</option>
                        <option value="2">Recent Service</option>
                        <option value="3">Featured Service</option>
                        <option value="4">Trending Service</option>
                        <option value="5">Premium Service</option>
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
        <div class="col-lg-3 col-md-12 col-sm-12" style="border-radius:5px;">
            <div class="bg-white rounded" >
                <div class="sidebar_header d-flex align-items-center justify-content-between px-4 py-3 br-bottom">
                    <h4 class="ft-medium fs-lg mb-0">Search Filter</h4>
                    <div class="ssh-header">
                        <!-- <a href="javascript:void(0);" class="clear_all ft-medium text-muted">Clear All</a>
                        <a href="#search_open" data-toggle="collapse" aria-expanded="false" role="button" class="collapsed _filter-ico ml-2"><i class="lni lni-text-align-right"></i></a> -->
                    </div>
                </div>
                <!-- Find New Property -->
                <div class="sidebar-widgets collapse miz_show" id="search_open" data-parent="#search_open">
                    <div class="search-inner">
                        <div class="filter-search-box px-4 pt-3 pb-0">
                            <div class="form-group">
                                <input type="text" name="keyword" class="form-control keyword" placeholder="Search by keywords..." style="margin-bottom: -20px;">
                                <button style="    height: 35px;
                                width: 40px;
                                position: relative;
                                top: -19px;
                                right: 0px !important;
                                float: right;
                                left: 16px;
                                border-radius: 0px !important;background: transparent;" type="submit" class="btn text-light keywordbtn"><i  class="fa fa-search" style="margin-left:-10px;color: #28B661;"></i></button>
                                <input type="text" name="locationss" class="form-control locationss " placeholder="Location, Zip.." style="margin-top: -40px;">
                                <button style="    height: 35px;
                                width: 40px;
                                position: relative;
                                top: -40px;
                                left: 12px;
                                right: 0px !important;
                                float: right;
                                border-radius: 0px !important;background: transparent;" type="submit" class="btn text-light locbtn"><i class="fa fa-search" style="margin-left:-6px !important;color: #28B661;"></i></button>
                            </div>
                            <div class="form-group">
                                
                            </div>
                        </div>
                        <style type="text/css">
                            .widget-boxed-header h4{
                                margin-top: -10px;
                                padding-bottom: 5px;
                                padding-top: 5px;
                            }
                            .widget-boxed-header h4 a{
                                line-height: 4px;
                            }
                            .widget-boxed-header h4 a:before {
    content: "\e622";
    position: absolute;
    width: 20px;
    height: 20px;
    right: 0;
    top: 88%;
    transform: translate(-10%, -52%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f6f8f7;
    font-family: themify;
    font-size: 10px;
    color: #445977;
}
                        </style>
                        <div class="filter_wraps">
                            <!-- Categories Search -->
                            <div class="single_search_boxed px-4 pt-0 br-bottom">
                                <div class="widget-boxed-header">
                                    <h4>
                                    <a href="#category" data-toggle="collapse" aria-expanded="false" role="button" class="ft-medium fs-md pb-0 collapsed" id="cattxt">Categories</a>
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
                                                            <input id="cat-box-{{$category->id}}" class="checkbox-custom category-checkbox category-checkboxes"  type="checkbox" name="{{$category->id}}" value="{{$category->id}}" data-id="{{ $category->id }}" data-name="{{$category->name}}" />
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
                                    <a href="#subcat" data-toggle="collapse" aria-expanded="false" role="button" class="ft-medium fs-md pb-0 collapsed" id="subcattxt">Sub Categories</a>
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
                                        <a href="#skillscat{{$childcategory->id}}" data-toggle="collapse" aria-expanded="false" role="button" class="ft-medium fs-md pb-0 collapsed" ><?= $childcategory->name?></a>
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
                                                            <input id="d1" class="checkbox-custom exp_checkboxes" name="Beginner"  value="Beginner" type="checkbox">
                                                            <label for="d1" class="checkbox-custom-label">Beginner</label>
                                                        </li>
                                                        <li>
                                                            <input id="d2" class="checkbox-custom exp_checkboxes" name="Intermediate" value="Intermediate" type="checkbox">
                                                            <label for="d2" class="checkbox-custom-label">Intermediate</label>
                                                        </li>
                                                        <li>
                                                            <input id="d3" class="checkbox-custom exp_checkboxes" name="Expert" value="Expert" type="checkbox">
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
                                <!-- <div class="widget-boxed-header">
                                    <h4>
                                    <a href="#delivery" data-toggle="collapse" aria-expanded="false" role="button" class="ft-medium fs-md pb-0 collapsed">Delivery Time</a>
                                    </h4>
                                </div>
                                <div class="widget-boxed-body collapse" id="delivery" data-parent="#delivery">
                                    <div class="side-list no-border">
                                        
                                        <div class="single_filter_card">
                                            <div class="card-body p-0">
                                                <div class="inner_widget_link">
                                                    <ul class="no-ul-list filter-list">
                                                        <li>
                                                            <div class="form-group">
                                                                <input type="text" name="delivery_time" class="form-control delivery_time" placeholder="Search Days...">
                                                                <div class="filter_button pt-2">
                                                                    <button type="submit" class="btn btn-md theme-bg text-light rounded full-width delivery_timebtn">Submit</button>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                                <!-- Eng Search -->
                                <div class="single_search_boxed px-4 pt-0 br-bottom">
                                    <div class="widget-boxed-header">
                                        <h4>
                                        <a href="#english" data-toggle="collapse" aria-expanded="false" role="button" class="ft-medium fs-md pb-0 collapsed">English Level</a>
                                        </h4>
                                    </div>
                                    <div class="widget-boxed-body collapse" id="english" data-parent="#english">
                                        <div class="side-list no-border">
                                            <!-- Single Filter Card -->
                                            <div class="single_filter_card">
                                                <div class="card-body p-0">
                                                    <div class="inner_widget_link">
                                                        <ul class="no-ul-list filter-list">
                                                            <li>
                                                                <input id="eng1" class="checkbox-custom lang_level" name="Basic" value="Basic" type="checkbox">
                                                                <label for="eng1" class="checkbox-custom-label">Basic</label>
                                                            </li>
                                                            <li>
                                                                <input id="eng2" class="checkbox-custom lang_level" name="Conversational" value="Conversational" type="checkbox">
                                                                <label for="eng2" class="checkbox-custom-label">Conversational</label>
                                                            </li>
                                                            <li>
                                                                <input id="eng3" class="checkbox-custom lang_level" name="Fluent" value="Fluent" type="checkbox" >
                                                                <label for="eng3" class="checkbox-custom-label">Fluent</label>
                                                            </li>
                                                            
                                                            <li>
                                                                <input id="eng4" class="checkbox-custom lang_level" name="Native" value="Native" type="checkbox">
                                                                <label for="eng4" class="checkbox-custom-label">Native</label>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Other Lang Search -->
                                <div class="single_search_boxed px-4 pt-0 br-bottom">
                                    <div class="widget-boxed-header">
                                        <h4>
                                        <a href="#language" data-toggle="collapse" aria-expanded="false" role="button" class="ft-medium fs-md pb-0 collapsed">Other Languages</a>
                                        </h4>
                                    </div>
                                    <div class="widget-boxed-body collapse" id="language" data-parent="#language">
                                        <div class="side-list no-border">
                                            <!-- Single Filter Card -->
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
                                    
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <!-- Sidebar End -->
                </div>
                <style type="text/css">
                .item-love{
                display: none;
                }
                .service-hov:hover .item-love{
                display: block;
                }
                </style>
                <!-- Item Wrap Start -->
                <div class="col-lg-9 col-md-12 col-sm-12 services-filter-wrp">
                    <!-- row -->
                    
                    <div class="row align-items-center">
                        <!-- Single -->
                        @foreach($services as $service)
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12" style="border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;">
                            <div class="job_grid border rounded service-hov" style="border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;">
                                @if($service->priority == 2)
                                <div class="feature text-uppercase">@lang('Featured')</div>
                                @endif
                                <!--  <a title="add to favourites" href="javascript:void(0)" class="item-love me-2 loveHeartAction" data-serviceid="{{$service->id}}"><h6 class="font-weight-bold" style="font-size:20px;color: red; !important;position: absolute;top: 18px;left: 10px;"><i class="fa fa-heart"></i></h6></a> -->
                                @if($service->favorite > 0) 
                                <a href="javascript:void(0)">
                                    <div class="position-absolute ab-left"><button type="button" class="p-3 border circle d-flex align-items-center justify-content-center bg-white text-danger"><i data-serviceid="{{$service->id}}" class="lni lni-heart-filled position-absolute snackbar-wishlist loveHeartAction"></i><span style="position: absolute;right:6px;top:2px;font-size:10px;">{{__($service->favorite)}}</span></button></div>
                                </a>
                                @else
                                <a href="javascript:void(0)">
                                    <div class="position-absolute ab-left"><button type="button" style="padding: 10px;" class=" border circle d-flex align-items-center justify-content-center bg-white text-gray"><i data-serviceid="{{$service->id}}" class="lni lni-heart-filled position-absolute snackbar-wishlist loveHeartAction"></i></button></div>
                                </a>
                                @endif
                                @if(@$service->optionalImage->count() > 0)
                                <div id="servicescarousel{{$service->id}}" class="carousel slide" data-ride="carousel">
                                    <ol class="carousel-indicators">
                                        @foreach($service->optionalImage as $index => $value)
                                        <li data-target="#servicescarousel" data-slide-to="{{$index}}" class="<?php echo ($index == '0') ? 'active' : ''; ?>"></li>
                                        @endforeach
                                    </ol>
                                    <div class="carousel-inner">
                                        @foreach($service->optionalImage as $key => $value)
                                        <div class="carousel-item <?php echo ($key == '0') ? 'active' : ''; ?>">
                                            <a href="{{route('service.details', [slug($service->title), encrypt($service->id)])}}"><img class="d-block w-100" src="{{getImage('assets/images/optionalService/'.$value->image, imagePath()['optionalService']['size']) }}" alt="@lang('item-banner')" style="height:165px;object-fit: cover;border-top-right-radius: 5px;border-top-left-radius: 5px;"></a>
                                        </div>
                                        @endforeach
                                        <!-- <div class="carousel-item">
                                            <img class="d-block w-100" src="{{asset('assets/wiserlance/assets/img/images1.jpeg')}}" alt="Second slide" style="max-height:165px;object-fit: cover;">
                                        </div>
                                        <div class="carousel-item">
                                            <img class="d-block w-100" src="{{asset('assets/wiserlance/assets/img/images3.jpeg')}}" style="max-height:165px;object-fit: cover;" alt="Third slide">
                                        </div> -->
                                    </div>
                                    <a class="carousel-control-prev controls" href="#servicescarousel{{$service->id}}" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon " aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next controls" href="#servicescarousel{{$service->id}}" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                                @else
                                <a href="{{route('service.details', [slug($service->title), encrypt($service->id)])}}"><img class="d-block w-100" src="{{getImage('assets/images/service/'.$service->image,imagePath()['service']['size'])}}" alt="@lang('Service Image')" style="height:165px;object-fit: cover;border-top-right-radius: 5px;border-top-left-radius: 5px;"></a>
                                @endif
                                @php
                                $rate1=DB::table('review_ratings')->where('pro_id',$service->user->id)->avg('rating');
                                $rate_count=DB::table('review_ratings')->where('pro_id',$service->user->id)->count();

                                $rate2=DB::table('review_ratings')->where('service_id',$service->id)->avg('rating');
                                $rate_count2=DB::table('review_ratings')->where('service_id',$service->id)->count();

                                $rate3=DB::table('review_ratings')->where('provider_id',$service->user->id)->avg('rating');
                                $rate_count3=DB::table('review_ratings')->where('provider_id',$service->user->id)->count();
                                @endphp
                                <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2 text-right">
                                    
                                </div> -->
                                <div class="row no-gutters text-left pr-2 pt-3">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-3">
                                        @if($service->user->image == '')
                                        <div class="" >
                                            <a href="{{route('profile', [slug($service->user->username), encrypt($service->user->id)])}}" class="d-inline-flex text-center  " style="border:none !important;"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSoMIkoUpzbBzDvTP_ePhyi0SsKSE57dvr89InSN1Ud_0-UDC7WRoTm3noAVg4c97VS538&usqp=CAU" class="img-fluid " style="border: 1px solid black;width: 40px;height: 40px;border-radius: 100%;object-fit: cover;margin-top: -10px;" alt="{{__($service->user->username)}}" /></a>
                                        </div>
                                        @else
                                        <div class="" >
                                            <a style="border:none !important;" href="{{route('profile', [slug($service->user->username), encrypt($service->user->id)])}}" class="d-inline-flex text-center  circle border p-2"><img src="{{ userDefaultImage(imagePath()['profile']['user']['path'].'/'. $service->user->image, 'profile_image') }}" class="img-fluid circle" style="border: 1px solid black;width: 40px;height: 40px;border-radius: 100%;object-fit: cover;margin-top: -10px;" alt="{{__($service->user->username)}}" /></a>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-lg-9 col-md-9 col-sm-9 col-9 text-left " >
                                    <div class="row">
                                        <div class="col-lg-7 col-md-7 col-sm-7 col-7">
                                        <h4 style="margin-left:-10px;" class="mb-0 mt-1 ft-medium medium"><a href="{{route('profile', [slug($service->user->username), encrypt($service->user->id)])}}" class="text-dark fs-md"> {{__($service->user->username)}}</a></h4>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-5 pt-2">
                                        <div class="jbl_location" style="line-height: 4px !important;font-size:12px;color: gray !important;"><!-- Seller level --> <i style="color:gold;" class="fa fa-star mr-1"></i><span>{{round(($rate1+$rate3)/2,1)}} ({{$rate_count+$rate_count3}})<!-- 3.4 (123) --></span></div>
                                    </div>
                                    </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 px-2" >
                                        <div style="height: 55px !important;">
                                            <p class="mb-0 ft-medium medium"><a href="{{route('service.details', [slug($service->title), encrypt($service->id)])}}" class="text-dark fs-md"> {{substr($service->title,0,45)}} ...</a></p>
                                            <!-- <a style="line-height: 4px !important;font-size:12px;color: gray !important;" href="{{route('service.details', [slug($service->title), encrypt($service->id)])}}"><p>{{substr($service->description,0,75)}}...</p></a> --> 
                                        </div>
                                    </div>
                                </div>
                                {{--<div class="job_grid_footer " >
                                    <ul class="p-0 skills_tag text-center">
                                        @if($service->tag > 0)
                                        @foreach(array_slice($service->tag,0,3) as $index => $tags)
                                        <li><span class="px-2 py-1 medium skill-bg rounded text-dark">{{__($tags)}}</span></li>
                                        @endforeach
                                        <br>
                                        @if(count($service->tag) > 3)
                                        <!-- <li><span  id="more" class="px-2 py-1 medium theme-bg rounded text-light">{{count($service->tag) - 3}} More</span></li> -->
                                        @endif
                                        
                                        @else
                                        no tags available
                                        @endif
                                    </ul>
                                </div>--}}
                                
                                @php
                                $n = DB::table('services_details')->where('service_id', $service->id)->select('price1', 'price2', 'price3', 'title1', 'title2', 'title3', 'desc1', 'desc2', 'desc3', 'time1', 'time2', 'time3')->first();
                                @endphp
                                <div class="row pt-2 no-gutters text-center bg-light px-2">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-left pl-1">
                                        <span  style="font-size:15px;color: gray !important;">Starting From</span>
                                        <h6 class="font-weight-bold" style="font-size:14px;color: black; !important;"> {{ Session::get('currency_symbol') }} {{
                                        number_format($helpers->convert_to_currency(Session::get('currency'),
                                        @$n->price1),2) }}</h6>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right pr-2">
                                        <span style="font-size:15px;color: gray !important;">@lang('Delivery Time')</span>
                                        <h6 class="font-weight-bold" style="font-size:14px;color: #28B661 !important;">{{@$n->time1}} @lang('Days')</h6>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 mb-3">
                                        
                                        <div>
                                            <a href="javascript:void(0)" class="btn p-1 px-0 full-width hover-theme text-light bg-dark " style="font-size: 12px;width:95%;cursor: none;">
                                                @if($service->user->income < $rank1->amount)
                                                Seller Level 1
                                                @elseif($service->user->income < $rank2->amount)
                                                Seller Level 2
                                                @elseif($service->user->income < $rank3->amount)
                                                Seller Level 3
                                                @elseif($service->user->income < $rank4->amount)
                                                Seller Level 4
                                                @elseif($service->user->income > $rank5->amount)
                                                Seller Level 5
                                                @endif
                                                
                                            </a></div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 mb-3">
                                            <div>
                                                <a href="{{route('service.details', [slug($service->title), encrypt($service->id)])}}" class="btn p-1 px-0 full-width bg-dark " style="font-size: 12px;background: #28B661 !important;width:95%;">Order Now</a>
                                            </div>
                                        </div>
                                        
                                        
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- row -->
                        <!-- <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <ul class="pagination">
                                    <li class="page-item">
                                        <a class="page-link" href="#" aria-label="Previous">
                                            <span class="fas fa-arrow-circle-right"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                    </li>
                                    {{$services->links()}}
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span class="fas fa-arrow-circle-right"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul>
                        </div> -->
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
    // alert(category_id);
    var name = $this.attr('data-name');
        var txt = $('#cattxt').html(name);
      $(txt).text(function(index, currentText) {
    if(currentText.length > 20){
        return currentText.substr(0, 18)+" ...";
    }
});
    
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
     var name = $this.attr('data-name');
        var firstDropVal = $('#subcattxt').html(name);
         $(firstDropVal).text(function(index, currentText) {
    if(currentText.length > 20){
        return currentText.substr(0, 18)+" ...";
    }
});
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
    $(document).on('click',' .category-checkbox,.subcategory-checkbox,.childcategory-checkbox,.skills-checkbox,.keywordbtn,.locbtn, .delivery_timebtn',function(){
    get_jobs();
    });
    $(document).on('keyup',' #minbudget,#maxbudget',function(){
    get_jobs();
    });
    function get_jobs(){
    var keyword = $('.keyword').val();
    // alert (keyword);
    // var otherlang = $('.otherlang').val();
    var delivery_time = $('.delivery_time').val();
    var location = $('.locationss').val();
    var price = $('.pricerange:checked').val();
    var category = [], subcategory = [], childcategory = [], skills=[], exp = [], fix = [],lang_level=[],otherlang = [];
    $('.exp_checkboxes').each(function(key, value){
    // alert("category");
    var $this = $(this);
    if($this.is(':checked')){
    var item_id = $this.attr('name');
    exp.push(item_id);
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
    // alert(maxbudget);
    $("#loading").show();
    $.ajax({
    url: "{{url('/filter_services')}}",
    method: "POST",
    //dataType: 'json',
    data: { "_token": "{{csrf_token()}}",'category':category,'subcategory':subcategory,'childcategory':childcategory,'skills':skills,  'minbudget':minbudget, 'maxbudget':maxbudget, 'keyword':keyword ,'location':location , 'exp':exp,  'otherlang':otherlang, 'delivery_time':delivery_time, 'fix':fix, 'lang_level':lang_level},
    success:function(response){
    if(response != 0){
    var result = jQuery.parseJSON(response);
    if(result.status == 'true'){
    // $('.strains-current-count').html('12');
    $('.count').html(result.count+ '<strong> Result Found </strong>');
    $('.services-filter-wrp').html(result.content_data);
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
    var $checkboxsub = $('.subcategory-checkbox');
    var $collapseContainer2 = $('#subcat');
    var $collapseContainer =$('#category');
    $checkbox2.change(function() {
    $collapseContainer2.collapse('toggle');
    $collapseContainer.collapse('toggle');
    });
    </script>
    <script type="text/javascript">
    var $checkboxsub = $('.subcategory-checkbox');
    var $collapseContainer22 = $('#subcat');
    if($collapseContainer22){
    
    }
    $checkboxsub.change(function() {
    alert()
    $collapseContainer22.collapse('toggle');
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
    <script type="text/javascript">
    $(document).on("click", ".loveHeartAction", function() {
    let id = $(this).data('serviceid');
    $.ajax({
    headers: {
    "X-CSRF-TOKEN": "{{ csrf_token() }}",
    },
    url: "{{ route('user.favorite.service') }}",
    method: "POST",
    dataType: "json",
    data: {
    id: id
    },
    success: function(response) {
    if (response.success) {
    notify('success', response.success);
    } else {
    $.each(response, function(i, val) {
    notify('error', val);
    });
    }
    }
    });
    });
    </script>
    <script type="text/javascript">
        
        var receiptNos2 = $(".category-checkboxes:checkbox:checked").map(function () {
    $('#cattxt').text($(this).data('id'))
}).get();
        $('.category-checkbox').on('change', function() {
    $('.category-checkbox').not(this).prop('checked', false);  
});
                $('.abc').on('change', function() {
    $('.abc').not(this).prop('checked', false);  
});
    </script>
    <script type="text/javascript">
    
    $(".skillstext").text(function(index, currentText) {
        if(currentText.length > 2){
    return currentText.substr(0, 1);
}
});
</script>
    @endsection