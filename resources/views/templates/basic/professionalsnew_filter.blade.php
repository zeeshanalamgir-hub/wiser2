@extends($activeTemplate . 'layouts.wiserlance.layout')
@section('content')
    <!-- ======================= Home Banner ======================== -->

    <!-- <div class="home-banner1 margin-bottom-0" style="background:#28b661 url(assets/img/banner-3.jpg) no-repeat; " data-overlay="5">
    <div class="container-fluid">
    <div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10 col-md-12 col-sm-12 col-12">
    <div class="banner_caption text-center mb-2">
    <h1 class="ft-bold mb-4">The Most Exciting Jobs</h1>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque officia exercitationem ab accusantium ex voluptatibus soluta culpa aut saepe? Delectus illo, optio quisquam inventore possimus vero, impedit quo enim eum.</p>
    </div>
    </div>

    </div>
    </div>
    </div>
    </div> -->
    <!-- ======================= Home Banner ======================== -->
    <!-- ======================= Listing ============================ -->
    <!-- ======================= Filter Wrap Style 1 ======================== -->
    <!--       <section class="py-2 br-bottom br-top">
                    <div class="container-fluid">
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <div class="bg-white rounded">

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
                                        <input type="text" name="keyword" class="form-control keyword"
                                            placeholder="Search by keywords...">
                                        <div class="filter_button pt-2">
                                            <button type="submit"
                                                class="btn btn-md theme-bg text-light rounded full-width keywordbtn">Submit</button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="location" class="form-control location"
                                            placeholder="Location, Zip..">
                                        <div class="filter_button pt-2">
                                            <button type="submit"
                                                class="btn btn-md theme-bg text-light rounded full-width locbtn">Submit</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="filter_wraps">



                                    <!-- Categories Search -->
                                    <div class="single_search_boxed px-4 pt-0 br-bottom">
                                        <div class="widget-boxed-header">
                                            <h4>
                                                <a href="#category" data-toggle="collapse" aria-expanded="false"
                                                    role="button" class="ft-medium fs-md pb-0 collapsed">Categories</a>
                                            </h4>

                                        </div>
                                        <div class="widget-boxed-body collapse" id="category" data-parent="#category">
                                            <div class="side-list no-border">
                                                <!-- Single Filter Card -->
                                                <div class="single_filter_card">
                                                    <div class="card-body p-0">
                                                        <div class="inner_widget_link">
                                                            <ul class="no-ul-list filter-list">
                                                                @foreach ($categoryes as $category)
                                                                    <li>
                                                                        <input id="cat-box-{{ $category->id }}"
                                                                            class="checkbox-custom category-checkbox"
                                                                            type="checkbox" name="{{ $category->id }}"
                                                                            value="{{ $category->id }}">
                                                                        <label for="cat-box-{{ $category->id }}"
                                                                            class="checkbox-custom-label">{{ $category->name }}</label>
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
                                    <div class="single_search_boxed px-4 pt-0 br-bottom subcategory-div">
                                        <div class="widget-boxed-header">
                                            <h4>
                                                <a href="#subcat" data-toggle="collapse" aria-expanded="false"
                                                    role="button" class="ft-medium fs-md pb-0 collapsed">Sub Categories</a>
                                            </h4>

                                        </div>
                                        <div class="widget-boxed-body collapse" id="subcat" data-parent="#subcat">
                                            <div class="side-list no-border">
                                                <!-- Single Filter Card -->
                                                <div class="single_filter_card">
                                                    <div class="card-body p-0">
                                                        <div class="inner_widget_link">
                                                            <ul class="no-ul-list filter-list filteredsubs">

                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Skill Search -->
                                    <div class="single_search_boxed px-4 pt-0 br-bottom left-bar-childcategory-div"
                                        style="display:<?= isset($childcategories) ? 'block' : 'none' ?>;">
                                        <div class="widget-boxed-header">
                                            <h4>
                                                <a href="#skillscat" data-toggle="collapse" aria-expanded="false"
                                                    role="button" class="ft-medium fs-md pb-0 collapsed">Attributes</a>
                                            </h4>

                                        </div>
                                        <div class="widget-boxed-body collapse" id="skillscat" data-parent="#skills">
                                            <div class="side-list no-border">
                                                <!-- Single Filter Card -->
                                                <div class="single_filter_card">
                                                    <div class="card-body p-0">
                                                        <div class="inner_widget_link">
                                                            <ul class="no-ul-list filter-list filteredchild">
                                                                <?php if(isset($childcategories)){ ?>
                                                                <?php foreach($childcategories as $childcategory){ ?>
                                                                <li>
                                                                    <input id="childcat-box-<?= $childcategory->id ?>"
                                                                        class="checkbox-custom childcategory-checkbox"
                                                                        type="checkbox" value="<?= $childcategory->id ?>"
                                                                        name="<?= $childcategory->id ?>">
                                                                    <label for="childcat-box-<?= $childcategory->id ?>"
                                                                        class="checkbox-custom-label">
                                                                        <?= $childcategory->name ?></label>
                                                                </li>
                                                                <?php } ?>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Service Search -->





                                    <!-- Skill Search -->
                                    <div class="single_search_boxed px-4 pt-0 br-bottom left-bar-skills-div"
                                        style="display:<?= isset($skills) ? 'block' : 'none' ?>;">
                                        <div class="widget-boxed-header">
                                            <h4>
                                                <a href="#skills" data-toggle="collapse" aria-expanded="false"
                                                    role="button" class="ft-medium fs-md pb-0 collapsed">Skills</a>
                                            </h4>

                                        </div>
                                        <div class="widget-boxed-body collapse" id="skills" data-parent="#skills">
                                            <div class="side-list no-border">
                                                <!-- Single Filter Card -->
                                                <div class="single_filter_card">
                                                    <div class="card-body p-0">
                                                        <div class="inner_widget_link">
                                                            <ul class="no-ul-list filter-list filteredskills">
                                                                <?php if(isset($skills)){ ?>
                                                                <?php foreach($skills as $skill){ ?>
                                                                <li>
                                                                    <input id="skills-box-<?= $skill->id ?>"
                                                                        class="checkbox-custom skills-checkbox"
                                                                        type="checkbox" value="<?= $skill->id ?>"
                                                                        name="<?= $skill->id ?>">
                                                                    <label for="skills-box-<?= $skill->id ?>"
                                                                        class="checkbox-custom-label">
                                                                        <?= $skill->name ?></label>
                                                                </li>
                                                                <?php } ?>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Service Search -->


                                    <!-- Expected Salary Search -->
                                    <div class="single_search_boxed px-4 pt-0 br-bottom">
                                        <div class="widget-boxed-header">
                                            <h4>
                                                <a href="#budget" data-toggle="collapse" aria-expanded="false"
                                                    role="button" class="ft-medium fs-md pb-0 collapsed">Per Hour
                                                    Rate</a>
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
                                                                        <input type="text" id="minbudget"
                                                                            class="form-control" placeholder="Min">
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6 col-cm-6 col-6">
                                                                        <input type="text" id="maxbudget"
                                                                            class="form-control" placeholder="Max">
                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                        <div class="filter_button pt-2">
                                                                            <button type="submit"
                                                                                class="btn btn-md theme-bg text-light rounded full-width"
                                                                                onclick="get_jobs()">Submit</button>
                                                                        </div>
                                                                    </div>
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
                                                <a href="#experience" data-toggle="collapse" aria-expanded="false"
                                                    role="button" class="ft-medium fs-md pb-0 collapsed">Experience
                                                    Level</a>
                                            </h4>

                                        </div>
                                        <div class="widget-boxed-body collapse" id="experience"
                                            data-parent="#experience">
                                            <div class="side-list no-border">
                                                <!-- Single Filter Card -->
                                                <div class="single_filter_card">
                                                    <div class="card-body p-0">
                                                        <div class="inner_widget_link">
                                                            <ul class="no-ul-list filter-list">
                                                                <li>
                                                                    <input id="d1"
                                                                        class="checkbox-custom exp_checkboxes"
                                                                        name="Beginner" value="Beginner" type="checkbox">
                                                                    <label for="d1"
                                                                        class="checkbox-custom-label">Beginner</label>
                                                                </li>
                                                                <li>
                                                                    <input id="d2"
                                                                        class="checkbox-custom exp_checkboxes"
                                                                        name="Intermediate" value="Intermediate"
                                                                        type="checkbox">
                                                                    <label for="d2"
                                                                        class="checkbox-custom-label">Intermediate</label>
                                                                </li>
                                                                <li>
                                                                    <input id="d3"
                                                                        class="checkbox-custom exp_checkboxes"
                                                                        name="Expert" value="Expert" type="checkbox">
                                                                    <label for="d3"
                                                                        class="checkbox-custom-label">Expert</label>
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
                                                <a href="#delivery" data-toggle="collapse" aria-expanded="false"
                                                    role="button" class="ft-medium fs-md pb-0 collapsed">Delivery
                                                    Time</a>
                                            </h4>

                                        </div>
                                        <div class="widget-boxed-body collapse" id="delivery" data-parent="#delivery">
                                            <div class="side-list no-border">
                                                <!-- Single Filter Card -->
                                                <div class="single_filter_card">
                                                    <div class="card-body p-0">
                                                        <div class="inner_widget_link">
                                                            <ul class="no-ul-list filter-list">
                                                                <li>
                                                                    <div class="form-group">
                                                                        <input type="text" name="delivery_time"
                                                                            class="form-control delivery_time"
                                                                            placeholder="Search Days...">
                                                                        <div class="filter_button pt-2">
                                                                            <button type="submit"
                                                                                class="btn btn-md theme-bg text-light rounded full-width delivery_timebtn">Submit</button>
                                                                        </div>
                                                                </li>

                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Eng Search -->
                                    <div class="single_search_boxed px-4 pt-0 br-bottom">
                                        <div class="widget-boxed-header">
                                            <h4>
                                                <a href="#english" data-toggle="collapse" aria-expanded="false"
                                                    role="button" class="ft-medium fs-md pb-0 collapsed">English
                                                    Level</a>
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
                                                                    <input id="eng1"
                                                                        class="checkbox-custom lang_level" name="Basic"
                                                                        value="Basic" type="checkbox">
                                                                    <label for="eng1"
                                                                        class="checkbox-custom-label">Basic</label>
                                                                </li>
                                                                <li>
                                                                    <input id="eng2"
                                                                        class="checkbox-custom lang_level"
                                                                        name="Conversational" value="Conversational"
                                                                        type="checkbox">
                                                                    <label for="eng2"
                                                                        class="checkbox-custom-label">Conversational</label>
                                                                </li>
                                                                <li>
                                                                    <input id="eng3"
                                                                        class="checkbox-custom lang_level" name="Fluent"
                                                                        value="Fluent" type="checkbox">
                                                                    <label for="eng3"
                                                                        class="checkbox-custom-label">Fluent</label>
                                                                </li>

                                                                <li>
                                                                    <input id="eng4"
                                                                        class="checkbox-custom lang_level" name="Native"
                                                                        value="Native" type="checkbox">
                                                                    <label for="eng4"
                                                                        class="checkbox-custom-label">Native</label>
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
                                                <a href="#language" data-toggle="collapse" aria-expanded="false"
                                                    role="button" class="ft-medium fs-md pb-0 collapsed">Other
                                                    Languages</a>
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
                                                                        <input type="text" name="otherlang"
                                                                            class="form-control otherlang"
                                                                            placeholder="Search ...">
                                                                        <div class="filter_button pt-2">
                                                                            <button type="submit"
                                                                                class="btn btn-md theme-bg text-light rounded full-width otherlangbtn">Submit</button>
                                                                        </div>
                                                                </li>


                                                            </ul>
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


                <!-- Item Wrap Start -->
                <div class="col-lg-9 col-md-12 col-sm-12 pro-filter-wrp">

                    <!-- row -->
                    <div class="row align-items-center mx-1">

                        @foreach ($professionals as $pro)
                            <!-- Single -->
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-4 service-hov">
                                <div class="row px-3 py-3  bg-white">
                                    <div class="col-xl-9 col-lg-9 text-left">
                                        <div class="d-block mb-2 position-relative">
                                            <span
                                                class="text-muted medium"><!-- <img src="assets/img/t-8.png" class="img-fluid circle mr-2" width="40" alt="" /> --></i>{{ $pro->firstname }}
                                                {{ $pro->lastname }}</span>
                                            <!-- <span class="muted medium ml-2 theme-cl"><i class="fa fa-check mr-1"></i>Verified</span> -->
                                            @if ($pro->isOnline())
                                                <span class="muted medium ml-2 theme-cl"><i
                                                        class="fa fa-dot-circle  mr-1"></i>Online</span>
                                            @else
                                                <span class="muted medium ml-2 text-danger"><i
                                                        class="fa fa-dot-circle  mr-1"></i>Offline</span>
                                            @endif
                                        </div>
                                        <h6 class="font-weight-bold">{{ $pro->designation }}</h6>
                                        <p>{{ $pro->about_me }}</p>
                                        <div class="jbd-details mb-1">

                                            <ul class="p-0 skills_tag text-left">
                                                @foreach (@$pro->skillsprofilenew as $as)
                                                    <li><span
                                                            class="px-2 py-1 medium skill-bg rounded text-dark">{{ __($as->name) }}</span>
                                                    </li>
                                                @endforeach
                                                @foreach (@$pro->skillsprofile as $as)
                                                    @php
                                                        $name = DB::table('skills')
                                                            ->where('id', $as->skill_id)
                                                            ->first();
                                                    @endphp
                                                    <li><span
                                                            class="px-2 py-1 medium skill-bg rounded text-dark">{{ @$name->name }}</span>
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 ">
                                        <p><i class="fa fa-award    mr-2"></i>Expert</p>

                                        <p><i class="fa fa-dollar-sign mr-2"></i>${{ $pro->per_hour_rate }}/hour</p>
                                        <p><i class="fa fa-map mr-2"></i>{{ $pro->address->city }},
                                            {{ $pro->address->country }}</p>
                                        <a href="{{ route('profile', [slug($pro->username), encrypt($pro->id)]) }}"
                                            class="btn theme-bg text-light ft-medium apply-btn fs-sm rounded full-width">Hire
                                            Now<i class="lni lni-arrow-right-circle ml-1"></i></a>
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
                            </ul>
                        </div>
                    </div> -->

                </div>

            </div>
        </div>
    </section>
    <!-- ============================ Main Section End ================================== -->

    <!-- ======================= Listing ============================ -->
@endsection
@section('script')
    <script type="text/javascript">
        $(document).on('click', '.category-checkbox', function() {
            category_checkbox();

        });

        function category_checkbox() {
            $("#loading").show();
            var category = [];
            $('.category-checkbox').each(function(key, value) {
                var $this = $(this);
                if ($this.is(':checked')) {
                    category.push($this.val());
                }
            });

            // console.log(category);
            $.ajax({
                url: "{{ url('/filterpro_subcats') }}",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'category': category
                },
                success: function(response) {
                    if (response != 0) {
                        var result = jQuery.parseJSON(response);
                        $('.filteredsubs').html(result.content_data);
                        if ($('.subcategory-checkbox').length == 0) {
                            $('.subcategory-div').hide();
                        } else {
                            $('.subcategory-div').show();
                        }
                        get_jobs();
                        $("#loading").hide();
                    } else {
                        $("#loading").hide();
                        alert('Something went wrong');
                    }
                },
                error: function() {
                    $("#loading").hide();
                }
            });
        }
    </script>


    <script type="text/javascript">
        $(document).on('click', '.subcategory-checkbox', function() {
            subcategory_checkbox();
        });

        function subcategory_checkbox() {
            $("#loading").show();
            var subcategory = [];
            $('.subcategory-checkbox').each(function(key, value) {
                var $this = $(this);
                if ($this.is(':checked')) {
                    subcategory.push($this.val());
                }
            });

            // console.log(category);
            $.ajax({
                url: "{{ url('/filterpro_childcats') }}",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'subcategory': subcategory
                },
                success: function(response) {
                    if (response != 0) {
                        var result = jQuery.parseJSON(response);
                        $('.filteredchild').html(result.content_data);
                        if ($('.childcategory-checkbox').length == 0) {
                            $('.left-bar-childcategory-div').hide();
                        } else {
                            $('.left-bar-childcategory-div').show();
                        }
                        get_jobs();
                        $("#loading").hide();
                    } else {
                        $("#loading").hide();
                        alert('Something went wrong');
                    }
                },
                error: function() {
                    $("#loading").hide();
                }
            });
        }
    </script>


    <script type="text/javascript">
        $(document).on('click', '.childcategory-checkbox', function() {
            childcategory_checkbox();
        });

        function childcategory_checkbox() {
            $("#loading").show();
            var childcategory = [];
            $('.childcategory-checkbox').each(function(key, value) {
                var $this = $(this);
                if ($this.is(':checked')) {
                    childcategory.push($this.val());
                }
            });

            // console.log(category);
            $.ajax({
                url: "{{ url('/filterpro_skills') }}",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'childcategory': childcategory
                },
                success: function(response) {
                    if (response != 0) {
                        var result = jQuery.parseJSON(response);
                        $('.filteredskills').html(result.content_data);
                        if ($('.skills-checkbox').length == 0) {
                            $('.left-bar-skills-div').hide();
                        } else {
                            $('.left-bar-skills-div').show();
                        }
                        get_jobs();
                        $("#loading").hide();
                    } else {
                        $("#loading").hide();
                        alert('Something went wrong');
                    }
                },
                error: function() {
                    $("#loading").hide();
                }
            });
        }
    </script>




    <script type="text/javascript">
        $(document).on('click',
            ' .skills-checkbox,
            function() {
                get_jobs();
            });

        function get_jobs() {

            var keyword = $('.keyword').val();
            var otherlang = $('.otherlang').val();
            var delivery_time = $('.delivery_time').val();

            var location = $('.location').val();
            var price = $('.pricerange:checked').val();

            var category = [],
                subcategory = [],
                childcategory = [],
                skills = [],
                exp = [],
                fix = [],
                lang_level = [];

            $('.exp_checkboxes').each(function(key, value) {
                // alert("category");
                var $this = $(this);
                if ($this.is(':checked')) {
                    var item_id = $this.attr('name');
                    exp.push(item_id);
                }
            });

            $('.lang_level').each(function(key, value) {
                // alert("category");
                var $this = $(this);
                if ($this.is(':checked')) {
                    var item_id = $this.attr('name');
                    lang_level.push(item_id);
                }
            });

            $('.fixhour_checkboxes').each(function(key, value) {
                // alert("category");
                var $this = $(this);
                if ($this.is(':checked')) {
                    var item_id = $this.attr('name');
                    fix.push(item_id);
                }
            });


            // alert(lang);die;
            $('.category-checkbox').each(function(key, value) {

                var $this = $(this);
                if ($this.is(':checked')) {
                    var item_id = $this.attr('name');
                    category.push(item_id);
                    // alert(category);
                }
            });
            $('.subcategory-checkbox').each(function(key, value) {
                // alert("category");
                var $this = $(this);
                if ($this.is(':checked')) {
                    var item_id = $this.attr('name');
                    subcategory.push(item_id);
                }
            });
            $('.childcategory-checkbox').each(function(key, value) {
                // alert(childcategory);
                var $this = $(this);
                if ($this.is(':checked')) {
                    childcategory.push($this.val());
                }
            });
            $('.skills-checkbox').each(function(key, value) {
                // alert("category");
                var $this = $(this);
                if ($this.is(':checked')) {
                    var item_id = $this.attr('name');
                    skills.push(item_id);
                }
            });



            var year = $('#selectedyear').val();
            var minprice = $('#minprice').val();
            var maxprice = $('#maxprice').val();
            var minbudget = $('#minbudget').val();
            var maxbudget = $('#maxbudget').val();
            // alert(maxbudget);
            $("#loading").show();
            $.ajax({
                url: "{{ url('/filter_pros') }}",
                method: "POST",
                //dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'category': category,
                    'subcategory': subcategory,
                    'childcategory': childcategory,
                    'skills': skills,
                    'minbudget': minbudget,
                    'maxbudget': maxbudget,
                    'keyword': keyword,
                    'location': location,
                    'exp': exp,
                    'otherlang': otherlang,
                    'delivery_time': delivery_time,
                    'fix': fix,
                    'lang_level': lang_level
                },
                success: function(response) {
                    if (response != 0) {
                        var result = jQuery.parseJSON(response);
                        if (result.status == 'true') {
                            // $('.strains-current-count').html('12');
                            $('.count').html(result.count + '<strong> Result Found </strong>');
                            $('.pro-filter-wrp').html(result.content_data);
                            // $("#loading").hide();
                        } else {

                        }
                    } else {

                    }
                    $("#loading").hide();

                },
                error: function(data) {
                    alert('failure');
                    $("#loading").hide();
                }
            });
            /* Ajax Call */
        }
    </script>
@endsection
