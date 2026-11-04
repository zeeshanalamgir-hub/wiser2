@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <style type="text/css">
        .nav-tabs .nav-item {
            width: auto;
        }

        .nav-link {
            display: block;
            padding: .6rem .6rem;
            background: #F1FDF7;
            font-weight: 500;
            font-size: 14px;
            margin-right: 0.8rem;
        }

        .nav-tabs {
            border-bottom: 1px solid transparent !important;
        }

        .nav-tabs .nav-link {
            text-align: center;
            color: #28b661 !important;
            border: 1px solid transparent;
            border-radius: .25rem;
        }

        .nav-tabs .nav-link {
            border: 1px solid transparent;
            border-radius: .25rem;
            background: #F1FDF7;
        }

        .nav-tabs .nav-link:hover,
        .nav-tabs .nav-link:focus {
            border: 1px solid transparent;
            border-radius: .25rem;
            background: #F1FDF7;
            cursor: pointer;
        }

        .nav-tabs .nav-link.active {
            color: #ffffff !important;
            background: #28b661;
            border-color: #28b661;
        }

        button:focus {
            outline: none;
            outline: none;
        }

        .nav-tabs.simple_tab_links .nav-link {
            margin: 0 5px;
            padding: 0.5rem 0.5rem;
            border-radius: 0;
            background: transparent;
            border: none;
            font-size: 13px;
            font-weight: 500;
            opacity: 0.6;
            text-transform: uppercase;
        }

        .nav-tabs.simple_tab_links .nav-link:hover,
        .nav-tabs.simple_tab_links .nav-link:focus {
            border: none;
            border-radius: 0;
            background: transparent;
            cursor: pointer;
        }

        .nav-tabs.simple_tab_links .nav-link.active {
            color: #000000;
            background: transparent;
            border-bottom: 2px dashed black;
            opacity: 1;
        }
    </style>
    <div class="dashboard-content">
        <!-- <div class="dashboard-tlbar d-block mb-5">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <h1 class="ft-medium">{{ __($pageTitle) }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-muted"><a href="landing2.html">Home</a></li>
                            <li class="breadcrumb-item text-muted"><a href="dashboard.html">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="dashboard-add-service.html" class="theme-cl">Update Job</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div> -->
        @if (@$review == 'true')
        @else
            <form class="user-profile-form" action="{{ route('user.job.update', $job->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
        @endif


        <div class="dashboard-widg-bar d-block">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="_dashboard_content rounded mb-4">
                        <div class="_dashboard_content_header br-bottom py-2 px-2">
                            <div class="_dashboard__header_flex">
                                <h2 class="mb-0 ft-medium font-weight-bold" style="font-weight: bold;"><i
                                        class="mr-1 theme-cl fs-sm"></i>
                                    @if (@$review == 'true')
                                        Review Job
                                    @else
                                        Edit Job
                                    @endif
                                </h2>
                            </div>
                        </div>

                    </div>
                    <div class="_dashboard_content_body py-3 px-3">
                        <div class="row">
                            <!-- <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                        <div class="custom-file avater_uploads">
                                            <input type="file" class="custom-file-input" id="customFile">
                                            <label class="custom-file-label" for="customFile"><i class="fa fa-user"></i></label>
                                        </div>
                                        <p class="mt-2" style="font-size:10px;color:red;">Supported files: jpeg, jpg, png. Image will be resized into 590x300 px</p>
                                        <div class="custom-file-wrapper removeImage">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="image" id="customFile" required>
                                                <label class="custom-file-label" for="customFile">@lang('Choose file')</label>
                                                <small>@lang('Supported files: jpeg, jpg, png. Image will be resized into 590x300 px')</small>
                                            </div>
                                        </div>
                                    </div>
                                    -->
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="row">

                                    <div
                                        style="width: 100%;display: flex;flex-wrap: wrap;background-color: white;padding-top: 20px;border-radius: 5px; ">

                                        <div class="col-xl-12 col-lg-12">
                                            <div class="form-group"
                                                style="background-color: #F0F0F0;padding: 10px 15px; color:#003F45; font-weight: 700">
                                                JOB DETAILS
                                            </div>
                                            <div class="form-group">
                                                <label class="text-dark ft-medium">@lang('Title')*</label>
                                                @if ($review == 'true')
                                                    <input type="text" name="title" maxlength="255"
                                                        value="{{ __($job->title) }}" id="tt" class="form-control"
                                                        placeholder="@lang('Enter Title')" readonly>
                                                @else
                                                    <input type="text" name="title" maxlength="255"
                                                        value="{{ __($job->title) }}" id="tt" class="form-control"
                                                        placeholder="@lang('Enter Title')" required="">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xl-12 col-lg-12">
                                            <div class="form-group">
                                                <label class="text-dark ft-medium">@lang('Post as a')*</label>
                                                <select class="form-control rounded" name="priority" id="priority"
                                                    @if ($review == 'true') disabled @else @endif>
                                                    @if ($userservices[0]->normal_jobs > 0 || $job->priority == 0)
                                                        <option {{ $job->priority == 0 ? 'selected' : '' }} value="0">
                                                            Normal</option>
                                                    @endif
                                                    @if ($userservices[0]->priority_jobs > 0 || $job->priority == 1)
                                                        <option {{ $job->priority == 1 ? 'selected' : '' }} value="1">
                                                            Priority</option>
                                                    @endif
                                                    @if ($userservices[0]->featured_jobs > 0 || $job->priority == 2)
                                                        <option {{ $job->priority == 2 ? 'selected' : '' }} value="2">
                                                            Featured</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-12 col-lg-12">
                                            <div class="form-group">
                                                <label class="text-dark ft-medium">@lang('Description')*</label><br>
                                                @if ($review == 'true')
                                                    @php echo $job->description @endphp
                                                @else
                                                    <div id="description-editor-container" style="height: 300px;"></div>
                                                    <textarea class="form-control with-light" placeholder="{{ old('description') }}" name="description"
                                                        style="display: none;">@php echo $job->description @endphp</textarea>

                                                    <!-- <textarea class="form-control with-light nicEdit" placeholder="{{ old('description') }}" name="description">@php echo $job->description @endphp</textarea> -->
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xl-12 col-lg-12">
                                            <div class="form-group">
                                                <label class="text-dark ft-medium">@lang('Requirement')*</label><br>
                                                @if ($review == 'true')
                                                    @php echo $job->requirements @endphp
                                                @else
                                                    <div id="requirement-editor-container" style="height: 300px;"></div>
                                                    <textarea class="form-control with-light" placeholder="Requirement" name="requirement" style="display: none;">@php echo $job->requirements @endphp</textarea>

                                                    <!-- <textarea class="form-control with-light nicEdit" placeholder="Requirement" name="requirement">@php echo $job->requirements @endphp</textarea> -->
                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <br>




                                <!-- <hr style="border:1px solid #fa7610 !important;"> -->
                                <div class="row">

                                    <div
                                        style="width: 100%;display: flex;flex-wrap: wrap;background-color: white;padding-top: 20px;border-radius: 5px; ">


                                        <div class="col-lg-12">
                                            <div class="form-group"
                                                style="background-color: #F0F0F0;padding: 10px 15px; color:#003F45; font-weight: 700">
                                                JOB CATEGORIZATION
                                            </div>
                                            <!-- <h5>Job Categorisation</h5> -->
                                            <p>Choose a category so professional can easily find your Job.</p>
                                        </div>
                                        <div class="col-xl-6 col-lg-6">
                                            <div class="form-group">
                                                <label class="text-dark ft-medium">@lang('Category Level 1')*</label>
                                                <select class="form-control rounded" name="category" id="category"
                                                    required="" @if ($review == 'true') disabled @else @endif>
                                                    <option selected="" disabled="">@lang('Select Category Level 1')</option>
                                                    @foreach ($categoryss as $category)
                                                        <option @if ($category->id == $job->category_id) selected @endif
                                                            value="{{ __($category->id) }}">{{ __($category->name) }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-12 col-lg-12 form-group select2Tag" hidden>
                                            <label>@lang('Find skills or add yours')*</label>
                                            @if (isset($job->skill))
                                                <select hidden class="form-control select2 tags" id="t"
                                                    multiple="multiple" required=""
                                                    @if ($review == 'true') disabled @else @endif>
                                                    @foreach ($job->skill as $name)
                                                        <option value="{{ $name }}" selected="true">
                                                            {{ __($name) }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                        @php
                                            $subs = DB::table('sub_categories')
                                                ->where('category_id', $job->category_id)
                                                ->get();
                                            $childs = DB::table('child_categories')->get();
                                        @endphp
                                        <div class="col-xl-6 col-lg-6">
                                            <div class="form-group">
                                                <label class="text-dark ft-medium">@lang('Category Level 2')*</label>
                                                <select name="subcategory" class="form-control rounded mySubCatgry"
                                                    id="subCategorys"
                                                    @if ($review == 'true') disabled @else @endif>
                                                    @foreach ($subs as $category)
                                                        <option
                                                            {{ $job->sub_category_id == $category->id ? 'selected' : '' }}
                                                            value="{{ __($category->id) }}">{{ __($category->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- <div class="col-xl-12 col-lg-12">
                                                <div class="form-group search-box">
                                                    <label class="text-dark ft-medium">@lang('Find skills or add yours')*</label>
                                                    <input onkeyup="getSkillbuname(this.value)" id="search_skill"  class="form-control" type="text" autocomplete="off" placeholder="Search ...">
                                                </div>
                                            </div>
                                            <div id="skil_result" class="col-xl-12 col-lg-12 form-group">
                                            </div>
                                            <div id="skills_result1" class="col-xl-12 col-lg-12 form-group">

                                            </div>
                                            <div id="skills_result" class="col-xl-12 col-lg-12 form-group">
                                            </div>
                                            <div class="col-xl-12 col-lg-12 form-group">
                                                <div id="result">

                                                </div>
                                            </div> -->
                                    </div>
                                </div>
                                <br>

                                <!-- <hr style="border:1px solid #fa7610 !important;"> -->
                                <!-- <div class="row">
                                        <div style="width: 100%;display: flex;flex-wrap: wrap;background-color: white;padding-top: 20px;border-radius: 5px; ">

                                              <div class="col-lg-12"> -->
                                <!-- <h5>Selected Job Features</h5> -->
                                <!-- <p>Choose features that best describe your Job.</p> -->
                                <!-- </div>
                                            <div id="skills_result1" class="col-xl-12 col-lg-12 form-group">
                                            </div>
                                        </div>
                                        </div> -->
                                <!-- <br> -->


                                @if ($review == 'true')
                                @else
                                    <div class="row">
                                        <div
                                            style="width: 100%;display: flex;flex-wrap: wrap;background-color: white;padding-top: 20px;border-radius: 5px; ">

                                            <div class="col-lg-12">
                                                <div class="form-group"
                                                    style="background-color: #F0F0F0;padding: 10px 15px; color:#003F45; font-weight: 700">
                                                    JOB FEATURES
                                                </div>

                                                <!-- <h5>Job Features</h5> -->
                                                <p>Choose features that best describe your Job.</p>
                                            </div>
                                            <div id="skills_result" class="col-xl-12 col-lg-12 form-group">
                                            </div>
                                        </div>
                                    </div>
                                    <br>


                                    <div class="col-xl-12 col-lg-12 ">
                                        <ul class="nav nav-tabs " role="tablist" id="result">
                                        </ul>
                                        <div class="tab-content" id="demos">
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div
                                            style="width: 100%;display: flex;flex-wrap: wrap;background-color: white;padding-top: 20px;border-radius: 5px; ">

                                            <div class="col-lg-12">
                                                <div class="form-group"
                                                    style="background-color: #F0F0F0;padding: 10px 15px; color:#003F45; font-weight: 700">
                                                    KEYWORDS FOR SEARCH
                                                </div>
                                                <!-- <h5>Keywords for search</h5> -->
                                                <p>Find pre-defined Skills or Type to add (Max. 5).</p>
                                            </div>
                                            <div id="skills_result_extra" class="col-xl-12 col-lg-12 form-group">
                                            </div>
                                            <div class="col-xl-12 col-lg-12">
                                                <div class="form-group search-box">
                                                    <label class="text-dark ft-medium">@lang('Find skills or add yours')*</label>
                                                    <input onkeyup="getSkillbuname(this.value)" id="search_skill"
                                                        class="form-control" type="text" autocomplete="off"
                                                        placeholder="Search ..."
                                                        @if ($review == 'true') readonly @else @endif>
                                                </div>
                                            </div>

                                            <div id="skil_result" class="col-xl-12 col-lg-12 form-group">
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                @endif

                                <div class="row">
                                    <div
                                        style="width: 100%;display: flex;flex-wrap: wrap;background-color: white;padding-top: 20px;border-radius: 5px; ">

                                        <div class="col-lg-12">
                                            <div class="form-group"
                                                style="background-color: #F0F0F0;padding: 10px 15px; color:#003F45; font-weight: 700">
                                                PRICING
                                            </div>
                                            <!-- <h5>Pricing</h5> -->
                                            <p>Pricing of Job.</p>
                                        </div>

                                        <div class="col-xl-6 col-lg-6">
                                            <div class="form-group">
                                                <label class="text-dark ft-medium">@lang('Budget')*</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="amount"
                                                        value="{{ getAmount($job->amount) }}"
                                                        placeholder="@lang('Enter Budget')" required=""
                                                        style="border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;"
                                                        @if ($review == 'true') disabled @else @endif>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text "
                                                            style="    display: -webkit-box;
                                                            display: -ms-flexbox;
                                                            display: flex;
                                                            -webkit-box-align: center;
                                                            -ms-flex-align: center;
                                                            align-items: center;
                                                            height: 40px !important;
                                                            padding: 6px 15px;
                                                            font-size: 15px;
                                                            margin-bottom: 0;
                                                            font-weight: 400;
                                                            line-height: 1.5;
                                                            color: #495057;
                                                            text-align: center;
                                                            white-space: nowrap;
                                                            background-color: #e9ecef;
                                                        border: 1px solid #ced4da;
                                                        border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">USD</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6">
                                            <div class="form-group">
                                                <label class="text-dark ft-medium">@lang('Budget Type')</label>
                                                <select class="form-control rounded" name="budgettype" required
                                                    @if ($review == 'true') disabled @else @endif>
                                                    <option value="">Select</option>
                                                    <option {{ $job->budgettype == 'per_hour' ? 'selected' : '' }}
                                                        value="per_hour">Per Hour</option>
                                                    <option {{ $job->budgettype == 'fixed' ? 'selected' : '' }}
                                                        value="fixed">Fixed</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6">
                                            <div class="form-group">
                                                <label class="text-dark ft-medium">@lang('Delivery Time')</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="delivery"
                                                        value="{{ $job->delivery_time }}"
                                                        placeholder="@lang('Delivery Time')" required=""
                                                        style="border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;"
                                                        @if ($review == 'true') disabled @else @endif>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text "
                                                            style="    display: -webkit-box;
                                                            display: -ms-flexbox;
                                                            display: flex;
                                                            -webkit-box-align: center;
                                                            -ms-flex-align: center;
                                                            align-items: center;
                                                            height: 40px !important;
                                                            padding: 6px 15px;
                                                            font-size: 15px;
                                                            margin-bottom: 0;
                                                            font-weight: 400;
                                                            line-height: 1.5;
                                                            color: #495057;
                                                            text-align: center;
                                                            white-space: nowrap;
                                                            background-color: #e9ecef;
                                                        border: 1px solid #ced4da;
                                                        border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">Days</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6">
                                            <div class="form-group">
                                                <label class="text-dark ft-medium">@lang('Level Of Experience')</label>
                                                <select class="form-control rounded" name="exp" required
                                                    @if ($review == 'true') disabled @else @endif>
                                                    <option value="">Select</option>
                                                    <option {{ $job->exp_type == '1' ? 'selected' : '' }} value="1">
                                                        Entry Level</option>
                                                    <option {{ $job->exp_type == '2' ? 'selected' : '' }} value="2">
                                                        Intermidiate</option>
                                                    <option {{ $job->exp_type == '3' ? 'selected' : '' }} value="3">
                                                        Expert</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <br>



                            </div>







                        </div>

                    </div>
                </div>
            </div>
        </div>




        <!-- Add Skills -->
        <div class="row mb-5 text-right">
            <div class="col-lg-12 col-md-12">
                <div class="form-group">
                    @if (@$review == 'true')
                        <a href="{{ route('user.job.edit', [$job->title, $job->id]) }}"
                            class="btn btn-md ft-medium text-light rounded bg-dark mr-1">Edit Job</a>
                    @else
                    @endif
                    @if (@$review == 'true')
                        <a href="{{ url('/user/buyer/dashboard') }}"
                            class="btn btn-md ft-medium text-light rounded theme-bg">Post Job</a>
                    @else
                        <button type="submit" class="btn btn-md ft-medium text-light rounded theme-bg">Update
                            Job</button>
                    @endif
                </div>

            </div>

        </div>

    </div>
    @if (@$review == 'true')
    @else
        </form>
    @endif
@endsection
@section('script')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'frontend/css/select2.min.css') }}">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <!-- <script src="{{ asset($activeTemplateTrue . 'frontend/js/select2.min.js') }}"></script>
        <script src="{{ asset($activeTemplateTrue . 'frontend/js/nicEdit.js') }}"></script> -->
    <script>
        "use strict";
        $(document).ready(function() {
            $('.select2').select2({
                tags: true
            });
        });

        var descriptionQuill = new Quill('#description-editor-container', {
            theme: 'snow',
            placeholder: 'Description*'
        });

        // Get HTML content from Quill editor for description and set it to the textarea
        descriptionQuill.on('text-change', function(delta, oldDelta, source) {
            var descriptionTextarea = document.querySelector('textarea[name="description"]');
            descriptionTextarea.value = descriptionQuill.root.innerHTML;
        });

        // Initialize Quill editor for requirement
        var requirementQuill = new Quill('#requirement-editor-container', {
            theme: 'snow',
            placeholder: 'Requirement*'
        });

        // Get HTML content from Quill editor for requirement and set it to the textarea
        requirementQuill.on('text-change', function(delta, oldDelta, source) {
            var requirementTextarea = document.querySelector('textarea[name="requirement"]');
            requirementTextarea.value = requirementQuill.root.innerHTML;
        });

        // bkLib.onDomLoaded(function() {
        // $( ".nicEdit" ).each(function( index ) {
        // $(this).attr("id","nicEditor"+index);
        // new nicEditor({fullPanel : true}).panelInstance('nicEditor'+index,{hasPanel : true});
        // });
        // });
        (function($) {
            $(document).on('mouseover ', '.nicEdit-main,.nicEdit-panelContain', function() {
                $('.nicEdit-main').focus();
            });
        })(jQuery);
        $(document).on("change", ".custom-file-input", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
        $('.tags').ready(function(s_id) {
            var t = document.getElementById('t');
            t.style.visibility = 'hidden';
            let button_html = '';

            var tags = $('.tags').val();
            console.log(tags.length);
            if (tags.indexOf(s_id) !== -1) {
                //alert("Value exists!")
                return
            }
            for (let i = 0; i < tags.length; i++) {

                button_html += '<button type="button" onclick="unselectnewskill(' + [i] + ')" id="newskill_btn' + [
                        i] + '" value="' + tags[i] + '" class="btn gray ft-small apply-btn fs-sm rounded mt-1">' +
                    tags[i] +
                    ' <i class="fa fa-times-circle"></i></button>&nbsp;<input type="hidden" name="newskills[]" id="newjob_skills' +
                    [i] + '" value="' + tags[i] + '"/>';

            }
            $('#skills_result1').html(button_html);
        });

        $('#category').on('change', function() {
            $('#result').empty();
            $('#skills_result').empty();
            var category = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('user.category') }}",
                data: {
                    category: category
                },
                success: function(data) {
                    var html = '';
                    if (data.error) {
                        $("#subCategorys").empty();
                        html += `<option value="" selected disabled>${data.error}</option>`;
                        $(".mySubCatgry").html(html);
                    } else {
                        $("#subCategorys").empty();
                        html += `<option value="" selected disabled>@lang('Select Category Level 2')</option>`;
                        $.each(data, function(index, item) {
                            html += `<option value="${item.id}">${item.name}</option>`;
                            $(".mySubCatgry").html(html);
                        });
                    }
                }
            });
        });
        const checkload = [];
        const skills_arr = [];
        const skills_name = [];
        const newskills_arr = [];
        const newskills_name = [];
        const exskills_arr = [];
        const exskills_name = [];
        const newexskills_arr = [];
        const newexskills_name = [];
        let newskillcount = 0;
        let newexskillcount = 0;
        //let button_html = '';
        $('#subCategorys').on('change', function() {
            var subcategory = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('user.skillcategory') }}",
                data: {
                    subcategory: subcategory
                },
                success: function(data) {
                    var html = '';
                    if (data.error) {

                        html += 'No Skill Category Availble';

                    } else {
                        $.each(data, function(name, id) {
                            console.log(id);
                            //alert(name);
                            // html+='<div class="card"><div  class="card-header text-white"><button style="color:#28b661;background-color: rgba(40,182,97,0.1);" onclick="getskills('+id+')" type="button" class="btn" data-toggle="collapse" data-target="#demo'+id+'">'+name+' <i class="fa fa-arrow-down"></i></button></div><div id="demo'+id+'" class="card-body collapse"> </div></div>';
                            html += ' <li class="nav-item"><a onclick="getskills(' + id +
                                ')" type="button" class="nav-link addcl " id="nav-link"  data-toggle="tab" href="#tab-' +
                                id + '" role="tab">' + name + '</a></li>';
                        });
                    }

                    $('#result').html(html);
                }
            });
        });

        function getskills(c_id) {
            if (checkload.indexOf(c_id) !== -1) {
                //alert("Value exists!")
                return
            }
            $.ajax({
                type: "GET",
                url: "{{ route('user.getskills') }}",
                data: {
                    c_id: c_id
                },
                success: function(data) {
                    var html = '';
                    if (data.error) {

                        html += 'No Skill  Availble';
                    } else {
                        $.each(data, function(name, id) {
                            html +=
                                `<div class="tab-pane nbv " style="display:inline;" id="tab-'+c_id+'" role="tabpanel"><button type="button" onclick="selectskills(${id},'${name}',${c_id},false)" value="${id}" class="btn gray ft-small apply-btn fs-sm rounded mt-1">${name}<i class="fa fa-plus ml-2"></i></button>&nbsp</div>`;
                        });
                    }
                    $('#demos').html(html);
                    //checkload.push(c_id);
                }
            });
        }

        function selectskills(s_id, name, c_id, check) {
            //alert(c_id);
            let button_html = '';
            //check if skill already selected
            if (skills_arr.indexOf(s_id) !== -1) {
                //alert("Value exists!")
                return
            }
            //initialize note at first
            // button_html+='<p><b>Click to unselect the skill</b></p>';
            skills_arr.push(s_id);
            skills_name.push(name);
            //console.log(skills_arr);
            for (let i = 0; i < skills_arr.length; i++) {
                button_html += '<button type="button" onclick="unselectskill(' + skills_arr[i] + ')" id="skill_btn' +
                    skills_arr[i] + '" value="' + skills_arr[i] +
                    '" class="btn gray ft-small apply-btn fs-sm rounded mt-1">' + skills_name[i] +
                    ' <i class="fa fa-times ml-2"></i></button>&nbsp;<input type="hidden" name="skills[]" id="job_skills' +
                    skills_arr[i] + '" value="' + skills_arr[i] + '"/>';
            }
            for (let i = 0; i < newskills_arr.length; i++) {
                button_html += '<button type="button" onclick="unselectnewskill(' + newskills_arr[i] +
                    ')" id="newskill_btn' + newskills_arr[i] + '" value="' + skills_arr[i] +
                    '" class="btn gray ft-small apply-btn fs-sm rounded mt-1">' + newskills_name[i] +
                    ' <i class="fa fa-times"></i></button>&nbsp;<input type="hidden" name="newskills[]" id="newjob_skills' +
                    newskills_arr[i] + '" value="' + newskills_name[i] + '"/>';

            }
            $('#skills_result').html(button_html);
            if (check == true) {
                $('#skil_result').html('');
                $('#search_skill').val('');
            }

        }
        //select new skill added
        function selectnewskill(name) {
            let button_html = '';

            //check if skill already selected
            if (newexskills_name.indexOf(name) !== -1) {
                console.log(newexskills_arr);
                return
            }
            newexskillcount++;
            //add new skill in array
            newexskills_arr.push(newexskillcount);
            newexskills_name.push(name);
            for (let i = 0; i < exskills_arr.length; i++) {
                button_html += '<button type="button" onclick="unselectskill(' + exskills_arr[i] + ')" id="skill_btn' +
                    exskills_arr[i] + '" value="' + exskills_arr[i] +
                    '" class="btn gray ft-small apply-btn fs-sm rounded mt-1">' + exskills_name[i] +
                    ' <i class="fa fa-times-circle"></i></button>&nbsp;<input type="hidden" name="skills[]" id="job_skills' +
                    exskills_arr[i] + '" value="' + exskills_arr[i] + '"/>';

            }
            for (let i = 0; i < newexskills_arr.length; i++) {
                button_html += '<button type="button" onclick="unselectnewskill(' + newexskills_arr[i] +
                    ')" id="newskill_btn' + newexskills_arr[i] + '" value="' + exskills_arr[i] +
                    '" class="btn gray ft-small apply-btn fs-sm rounded mt-1">' + newexskills_name[i] +
                    ' <i class="fa fa-times-circle"></i></button>&nbsp;<input type="hidden" name="newskills[]" id="newjob_skills' +
                    newexskills_arr[i] + '" value="' + newexskills_name[i] + '"/>';

            }
            $('#skills_result_extra').html(button_html);
            $('#skil_result').html('');
            $('#search_skill').val('');
        }

        function unselectskill(s_id) {
            //removing skill from skill array
            var index = skills_arr.indexOf(s_id);
            if (index !== -1) {
                skills_arr.splice(index, 1);
                skills_name.splice(index, 1);
            }
            $('#skill_btn' + s_id).remove();
            $('#job_skills' + s_id).remove();

        }

        function unselectnewskill(s_id) {
            //removing skill from skill array
            var index = newskills_arr.indexOf(s_id);
            if (index !== -1) {
                newskills_arr.splice(index, 1);
                newskills_name.splice(index, 1);
            }
            $('#newskill_btn' + s_id).remove();
            $('#newjob_skills' + s_id).remove();
            console.log(newskills_arr);

        }
        //getting existing skill by search
        function getSkillbuname(search) {
            let html = '';
            //console.log(search);
            if (search == "") {
                $('#skil_result').html('');
                return
            }
            $.ajax({
                type: "GET",
                url: "{{ route('user.getskillsbyname') }}",
                data: {
                    search: search
                },
                success: function(data) {
                    var html = '';
                    if (data.error) {

                        html +=
                            `<b><a onclick="selectnewskill('${search}')" style="cursor:pointer;font-size:14px; line-height: 0.7;">${search}</a></b><hr>`;
                    } else {
                        html += "<div  style='height:100px;overflow:scroll; overflow-x: hidden;'>";
                        $.each(data, function(name, id) {
                            html +=
                                `<p><b><a onclick="selectextraskills(${id},'${name}',true)" style="cursor:pointer;font-size:14px; line-height: 0.7;">${name}</a></b></p>`;

                        });
                        html += '</div>';
                        //console.log(data);
                    }

                    $('#skil_result').html(html);
                    // checkload.push(c_id);
                }
            });
        }
    </script>
    <script type="text/javascript">
        $('#tt').bind('keyup', function(e) {
            var a = $(this).val().length;
            //alert(a);
            $('#ttcount').html(a);
        });
    </script>
@endsection
