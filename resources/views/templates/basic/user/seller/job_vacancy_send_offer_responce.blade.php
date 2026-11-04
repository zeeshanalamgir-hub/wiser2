@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout')
@section('content')


    <div class="dashboard-content">
        <div class="dashboard-tlbar d-block mb-5">
            <div class="row">
                <!-- <div class="colxl-12 col-lg-12 col-md-12">
                    <h1 class="ft-medium">{{ __($pageTitle) }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-muted"><a href="landing2.html">Home</a></li>
                            <li class="breadcrumb-item text-muted"><a href="dashboard.html">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="dashboard-add-service.html" class="theme-cl">Create Service</a></li>
                        </ol>
                    </nav>
                </div> -->
            </div>
        </div>
        <form action="{{ route('user.job.vacancy.offers.submitresponce') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="dashboard-widg-bar d-block">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <div class="_dashboard_content bg-white rounded mb-4">
                            <div class="_dashboard_content_header br-bottom py-3 px-3">
                                <div class="_dashboard__header_flex">
                                    <h4 class="mb-0 ft-medium fs-md"><i class="fa fa-file mr-1 theme-cl fs-sm"></i>Send
                                        Offer</h4>
                                </div>
                            </div>

                            <div class="_dashboard_content_body py-3 px-3">
                                <div class="row">

                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <p><span class="font-weight-bold">Job Title:</span> {{ $offerdetail->job->title }}
                                        </p>
                                        <p><span class="font-weight-bold">Proposal:</span> {{ $offerdetail->proposal }}</p>
                                        <input type="hidden" value="{{ $offerdetail->job_type }}" name="job_type">
                                        <input type="hidden" value="{{ $offerdetail->id }}" name="bid_id">
                                        <input type="hidden" value="{{ $offerdetail->job->id }}" name="job_id">

                                        <input type="hidden" value="{{ $offerdetail->pro_id }}" name="sender">
                                        <input type="hidden" value="{{ $offerdetail->buyer_id }}" name="reciever">


                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <div class="row">
                                            @if ($offerdetail->job_type == 'per_hour')
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <label class="text-dark ft-medium">@lang('Amount(per hour)')</label>
                                                        <div class="input-group">
                                                            <input type="number" name="hourly_amount" maxlength="255"
                                                                value="{{ $offerdetail->hourlyrate }}" class="form-control"
                                                                placeholder="@lang('Amount')">
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
                                                border: 1px solid #ced4da;">$/hr</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <label class="text-dark ft-medium">@lang('Hours(per day)')</label>
                                                        <div class="input-group">
                                                            <input type="number" name="hours_weekly" maxlength="255"
                                                                value="{{ $offerdetail->hours }}" class="form-control"
                                                                placeholder="@lang('Amount')">
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
                                                border: 1px solid #ced4da;">hr/day</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($offerdetail->job_type == 'fixed')
                                                @php
                                                    $milestones = DB::table('user_offer_milestone')
                                                        ->where('offer_id', $offerdetail->id)
                                                        ->get();
                                                @endphp
                                                @foreach ($milestones as $md)
                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                                                        <div class="form-group">
                                                            <label class="text-dark ft-medium">@lang('Title')</label>
                                                            <input type="text" name="ms_title[]" maxlength="255"
                                                                value="{{ $md->title }}" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                                                        <div class="form-group">
                                                            <label class="text-dark ft-medium">@lang('Amount')</label>
                                                            <div class="input-group">
                                                                <input type="number" name="ms_amount[]" maxlength="255"
                                                                    value="{{ $md->amount }}"
                                                                    class="form-control bidamountmulti">
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
                                                border: 1px solid #ced4da;">$</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                                                        <div class="form-group">
                                                            <label class="text-dark ft-medium">@lang('Days')</label>
                                                            <div class="input-group">
                                                                <input type="number" name="ms_days[]" maxlength="255"
                                                                    value="{{ $md->due_date }}" class="form-control">
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
                                                border: 1px solid #ced4da;">Days</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                            @php
                                                $ms = DB::table('user_offer_milestone')
                                                    ->where('offer_id', $offerdetail->id)
                                                    ->get();
                                                $sum = $ms->sum('amount');
                                                $per = ($sum * $usermembership[0]->fee) / 100;
                                                $total = $offerdetail->job->amount - $per;
                                            @endphp
                                            <input type="hidden" value="{{ showAmount($offerdetail->job->amount) }}"
                                                name="job_price" />
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-4 mt-2 mb-2">
                                                <label>Job Budget</label>
                                                <input type="hidden" value="{{ showAmount($offerdetail->job->amount) }}"
                                                    name="job_price" />
                                                <input type="hidden" id=""
                                                    value="{{ showAmount($usermembership[0]->fee) }}" name="fee" />
                                                <div class="input-group">
                                                    <input id="job_price" readonly
                                                        value="{{ showAmount($offerdetail->job->amount) }}"
                                                        class="form-control">
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
                                                border: 1px solid #ced4da;">$</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-4 mt-2 mb-2">
                                                <label>Platform Fee(%)</label>
                                                <div class="input-group">
                                                    <input id="feemulti" readonly
                                                        value="{{ showAmount($usermembership[0]->fee) }}"
                                                        class="form-control">
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
                                                border: 1px solid #ced4da;">$</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-4 mt-2">
                                                <label>You Will Get Paid</label>
                                                <div class="input-group">
                                                    <input id="totalmulti" value="{{ $total }}" readonly
                                                        class="form-control">
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
                                                border: 1px solid #ced4da;">$</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
                                                <label class="text-dark ft-medium">@lang('Attachment')*</label>
                                                <input type="file" name="file" multiple class="form-control">
                                            </div>
                                            <div class="col-xl-12 col-lg-12 mt-3">
                                                <div class="form-group">
                                                    <label class="text-dark ft-medium">@lang('Cover Letter')*</label>
                                                    <textarea class="form-control with-light nicEdit" name="coverletter"></textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Add Skills -->
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <button type="submit"
                            class="btn btn-md ft-medium text-light rounded theme-bg">@lang('SEND OFFER')</button>
                    </div>
                </div>

            </div>
        </form>
    @endsection
    @section('script')
        <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'frontend/css/select2.min.css') }}">
        <script src="{{ asset($activeTemplateTrue . 'frontend/js/select2.min.js') }}"></script>
        <script src="{{ asset($activeTemplateTrue . 'frontend/js/nicEdit.js') }}"></script>
        <script src="{{ asset($activeTemplateTrue . 'frontend/js/nicEdit.js') }}"></script>
        <script>
            "use strict";
            $(document).ready(function() {
                $('.select2').select2({
                    tags: true
                });
            });
            bkLib.onDomLoaded(function() {
                $(".nicEdit").each(function(index) {
                    $(this).attr("id", "nicEditor" + index);
                    new nicEditor({
                        fullPanel: true
                    }).panelInstance('nicEditor' + index, {
                        hasPanel: true
                    });
                });
            });
            $(document).on("change", ".custom-file-input", function() {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
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
                            html += `<option value="" selected disabled>@lang('Select Sub Category')</option>`;
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
            let newskillcount = 0;
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
                                html +=
                                    '<div class="card"><div  class="card-header text-white"><button style="color:#28b661;background-color: rgba(40,182,97,0.1);" onclick="getskills(' +
                                    id +
                                    ')" type="button" class="btn" data-toggle="collapse" data-target="#demo' +
                                    id + '">' + name +
                                    ' <i class="fa fa-arrow-down"></i></button></div><div id="demo' +
                                    id + '" class="card-body collapse"> </div></div>';
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
                                    `<button type="button" onclick="selectskills(${id},'${name}',false)" value="${id}" class="btn gray ft-small apply-btn fs-sm rounded mt-1">${name}<i class="fa fa-plus ml-2"></i></button>&nbsp;`;
                            });
                        }
                        $('#demo' + c_id).html(html);
                        checkload.push(c_id);
                    }
                });
            }

            function selectskills(s_id, name, check) {
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
                        ' <i class="fa fa-times-circle"></i></button>&nbsp;<input type="hidden" name="skills[]" id="job_skills' +
                        skills_arr[i] + '" value="' + skills_arr[i] + '"/>';

                }
                for (let i = 0; i < newskills_arr.length; i++) {
                    button_html += '<button type="button" onclick="unselectnewskill(' + newskills_arr[i] +
                        ')" id="newskill_btn' + newskills_arr[i] + '" value="' + skills_arr[i] +
                        '" class="btn gray ft-small apply-btn fs-sm rounded mt-1">' + newskills_name[i] +
                        ' <i class="fa fa-times-circle"></i></button>&nbsp;<input type="hidden" name="newskills[]" id="newjob_skills' +
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
                if (newskills_name.indexOf(name) !== -1) {
                    console.log(newskills_arr);
                    return
                }
                newskillcount++;
                //add new skill in array
                newskills_arr.push(newskillcount);
                newskills_name.push(name);
                for (let i = 0; i < skills_arr.length; i++) {
                    button_html += '<button type="button" onclick="unselectskill(' + skills_arr[i] + ')" id="skill_btn' +
                        skills_arr[i] + '" value="' + skills_arr[i] +
                        '" class="btn gray ft-small apply-btn fs-sm rounded mt-1">' + skills_name[i] +
                        ' <i class="fa fa-times-circle"></i></button>&nbsp;<input type="hidden" name="skills[]" id="job_skills' +
                        skills_arr[i] + '" value="' + skills_arr[i] + '"/>';

                }
                for (let i = 0; i < newskills_arr.length; i++) {
                    button_html += '<button type="button" onclick="unselectnewskill(' + newskills_arr[i] +
                        ')" id="newskill_btn' + newskills_arr[i] + '" value="' + skills_arr[i] +
                        '" class="btn gray ft-small apply-btn fs-sm rounded mt-1">' + newskills_name[i] +
                        ' <i class="fa fa-times-circle"></i></button>&nbsp;<input type="hidden" name="newskills[]" id="newjob_skills' +
                        newskills_arr[i] + '" value="' + newskills_name[i] + '"/>';

                }
                $('#skills_result').html(button_html);
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
                                    `<p><b><a onclick="selectskills(${id},'${name}',true)" style="cursor:pointer;font-size:14px; line-height: 0.7;">${name}</a></b></p>`;

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
            $(document).on('keyup', '.bidamountmulti', function() {
                var abcd = 0;
                var jobprice = $('#job_price').val();
                var percent = $('#feemulti').val();
                console.log(percent);
                $('.bidamountmulti').each(function(key, value) {
                    // console.log(key);
                    // console.log(value);
                    var $this = $(this);
                    var thisval = $this.val();
                    console.log(thisval);
                    abcd += parseFloat(thisval);
                });
                var points = (parseFloat(percent) / 100);
                console.log(points);
                var totalpercent = ((points) * parseFloat(abcd));
                var leftprice = jobprice - totalpercent
                // console.log(totalpercent);
                $('#totalmulti').val(leftprice);
            });
        </script>
    @endsection
