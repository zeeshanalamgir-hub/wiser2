@extends('templates.basic.layouts.wiserlance.dashboard-layout-job', [
'scripts' => ['assets/templates/basic/frontend/js/select2.min.js', 'assets/templates/basic/scripts/js/data-ajax.js', 'assets/templates/basic/scripts/js/user/buyer/create-job.js'],
'styles' => ['assets/templates/basic/frontend/css/select2.min.css', 'assets/templates/basic/styles/css/user/buyer/create-job.css'],
])
@section('content')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<div class="dashboard-content">
    <form action="{{ route('user.job.store') }}" method="POST" enctype="multipart/form-data" id="create-job-form-5432346">
        @csrf

        <div class="container-header-table-100095">
            <div class="table-container-985632">
                <div class="table-heading-10002">
                    <div>Create Job</div>
                </div>
                <div class="form-section-67894564">
                    <div class="form-responsive-heading-98563">
                        <div class=" form-group m-0 form-heading   table-head-col-10005">
                            JOB DETAILS
                        </div>
                    </div>
                    <div class="form-responsive-98523">
                        <div class="form-group m-0">
                            <input type="text" name="title" value="{{ old('title') }}" id="tt" style="height: 40px" maxlength="30" class="form-control" placeholder="@lang('Title*')" required="">
                        </div>
                        <div class="form-group m-0">
                            <div id="description-editor-container" style="height: 300px;"></div>
                            <textarea class="form-control with-light" name="description" style="display: none;" value="{{ old('description') }}"></textarea>
                        </div>
                        <div class="form-group m-0">
                            <select class="custom-select form-control rounded" name="priority" id="priority" placeholder="{{ old('Post as a*') }}" required="">
                                <option value="" disabled {{ old('priority') == '' ? 'selected' : '' }}>Choose a
                                    priority for your job.</option>
                                <option value="2" {{ old('priority') == '2' ? 'selected' : '' }}>
                                    Featured
                                </option>
                                <option value="1" {{ old('priority') == '1' ? 'selected' : '' }}>
                                    Regular
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-section-67894564">
                    <div class="form-responsive-heading-98563">
                        <div class=" form-group m-0 form-heading table-head-col-10005">
                            @lang('Requirement')
                        </div>
                    </div>
                    <div class="form-responsive-98523">
                        <div class=" form-group m-0">
                            <!-- <label class="text-dark ft-medium">@lang('Requirement')*</label> -->
                            <div id="requirement-editor-container" style="height: 300px;"></div>
                            <textarea class="form-control with-light" name="requirement" style="display: none;"></textarea>
                            <!-- <textarea class="form-control with-light nicEdit" placeholder="Requirement" name="requirement"></textarea> -->
                        </div>
                    </div>
                </div>
                <div class="form-section-67894564">
                    <div class="form-responsive-heading-98563">
                        <div class=" form-group m-0 form-heading   table-head-col-10005">
                            @lang('Attachmints')
                        </div>
                    </div>
                    <div class="form-responsive-98523">
                        <p>You must add at least one file to your project*</p>
                        @include('templates.basic.user.buyer.job.components.create-job-file-upload')

                    </div>
                </div>
                <div class="form-section-67894564">
                    <div class="form-responsive-heading-98563">
                        <div class=" form-group m-0 form-heading   table-head-col-10005">
                            JOB CATEGORIZATION
                        </div>
                    </div>
                    <div class="form-responsive-98523">
                        <p>Choose a category so professional can easily find your Job.</p>
                        <div style="display: flex; align-items: center; justify-content:space-between; gap: 16px">
                            <div class=" form-group m-0" style="width: 50%">
                                <select class="form-control rounded" style="height: 40px" name="category" id="category" required="">
                                    <option value="" disabled selected>
                                        @lang('Category')
                                    </option>
                                    @foreach ($categoryss as $category)
                                    <option value="{{ __($category->id) }}">
                                        {{ __($category->name) }}
                                    </option>
                                    @endforeach
                                </select>

                            </div>
                            <div class=" form-group m-0" style="width: 50%">
                                <select name="subcategory" required="" style="height:40px" class="form-control rounded mySubCatgry" id="subCategorys">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-section-67894564">
                    <div class="form-responsive-heading-98563">
                        <div class=" form-group m-0 form-heading   table-head-col-10005">
                            JOB FEATURES
                        </div>
                    </div>
                    <div class="form-responsive-98523">
                        <p>Choose features that best describe your Job.</p>
                        <div id="skills_result" class="form-group"></div>
                        <ul class="nav nav-tabs " role="tablist" id="result">
                        </ul>
                        <div class="tab-content" id="demos">
                        </div>
                    </div>
                </div>
                <div class="form-section-67894564">
                    <div class="form-responsive-heading-98563">
                        <div class=" form-group m-0 form-heading   table-head-col-10005">
                            Keywords
                        </div>
                    </div>
                    <div class="form-responsive-98523">
                        <p>Find pre-defined Skills or Type to add (Max. 5).</p>
                        <div id="skills_result_extra" class="  form-group m-0 px-0">
                        </div>
                        <div style="    padding-left: 0px;    padding-right: 0px;">
                            <div class=" form-group m-0 search-box">
                                <input onkeyup="getSkillbuname(this.value)"
                                id="search_skill" class="form-control"
                                 type="text" autocomplete="off" placeholder="Search ...">
                            </div>
                        </div>
                        <div id="skil_result" class=" form-group m-0">
                        </div>
                    </div>
                    <div class="form-section-67894564">
                        <div class="form-responsive-heading-98563">
                            <div class=" form-group m-0 form-heading   table-head-col-10005">
                                PRICING
                            </div>
                        </div>
                        <div class="form-responsive-98523">
                            <p>Pricing of Job. </p>
                            <div style="display: flex;
                                        align-items: center;
                                        justify-content: flex-start;
                                        flex-wrap: wrap;
                                        gap:16px">
                                <div style="width: 49%;">
                                    <div class=" form-group m-0">
                                        <select style="height:40px"
                                        class="form-control rounded" name="budgettype" required>
                                            @if (!old('budgettype'))
                                            <option value="" disabled selected>Please
                                                select Job
                                                Type</option>
                                            @endif
                                            <option value="per_hour" {{ old('budgettype') == 'per_hour' ? 'selected' : '' }}>
                                                Per
                                                Hour</option>
                                            <option value="fixed" {{ old('budgettype') == 'fixed' ? 'selected' : '' }}>
                                                Fixed
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div style="width: 49%;">
                                    <div class=" form-group m-0" style="height:40px">
                                        <div class="input-group" style="height:40px">
                                            <input type="number" class="form-control" min="0" step="1" name="delivery" style="height:40px" oninput="if(this.value < 1) { this.value = 1; }" value="{{ old('delivery') }}" placeholder="@lang('Delivery Time')" required="" style="border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;">
                                            <div class="input-group-append">
                                                <span class="input-group-text " style="    display: -webkit-box;
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
                                                        border: 1px solid #ced4da;border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">Days</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style="width: 49%;">
                                    <div class=" form-group m-0" style="height:40px" id="amount">
                                        <div class="input-group">
                                            <input type="number" class="form-control" min="0" style="height:40px" oninput="if(this.value < 1) { this.value = 1; }" step="1" name="amount" value="{{ old('amount') }}" placeholder="@lang('Enter Budget')" style="border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;">
                                            <div class="input-group-append">
                                                <span class="input-group-text " style="    display: -webkit-box;
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
                                                        border: 1px solid #ced4da;border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">USD</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class=" form-group m-0" id="minMaxBudgetFields" style="display: none;">
                                        <div class="input-group">
                                            <input type="number" class="form-control" min="0" style="height:40px" oninput="if(this.value < 1) { this.value = 1; }" step="1" name="minBudget" value="{{ old('minBudget') }}" placeholder="@lang('Enter Min Budget')" style="border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;">
                                            <div class="input-group-append">
                                                <span class="input-group-text " style="    display: -webkit-box;
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
                                                        border: 1px solid #ced4da;border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">USD</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style="width: 49%;">
                                    <div class=" form-group m-0">
                                        <div class="input-group" id="maxBudgetField" style="display: none;">
                                            <input type="number" class="form-control" min="0" style="height:40px" oninput="if(this.value < 1) { this.value = 1; }" step="1" name="maxBudget" value="{{ old('maxBudget') }}" placeholder="@lang('Enter Max Budget')" style="border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;">
                                            <div class="input-group-append">
                                                <span class="input-group-text " style=" display: -webkit-box;
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
                                                                    border: 1px solid #ced4da;border-top-right-radius: 5px !important;  border-bottom-right-radius: 5px !important;">
                                                    USD
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style="width: 49%;">
                                    <div class=" form-group m-0" style="height:40px">
                                        <select class="form-control rounded" name="exp" required style="height:40px">
                                            <option value="">Select</option>
                                            <option value="1">Entry Level</option>
                                            <option value="2">Intermidiate</option>
                                            <option value="3">Expert</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="display: flex;width: 100%;justify-content: end;">
                    <div class=" form-group m-0" style="width: 50%;
                                display: flex;
                                align-items: center;
                                justify-content: flex-end;
                                 gap: 16px;">
                        {{-- <button type="submit" value="yes" name="review"
                                class="btn btn-md ft-medium text-light rounded mr-1 review-job-78563"
                                style=" background-color: #003F45; width:50%">
                                Review Job
                            </button> --}}
                        <button type="submit" class="btn btn-md ft-medium text-light rounded" style=" background-color: #00C535; width:50%">Create
                            Job
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@php

$oldValueDescription = old('description') ?? '';
$oldValueRequirement = old('requirement') ?? '';

@endphp
