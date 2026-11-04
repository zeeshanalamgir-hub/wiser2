@extends('templates.basic.layouts.wiserlance.dashboard-layout-job')
<style>
    .nicEdit-main {
        min-height: 200px !important;
    }
</style>

@section('content')
    <h5 class="text-dgreen2 mt-2">Create Services</h5>
    <!--Box 1-->
    <form action="{{ route('user.service.store') }}" method="POST" enctype="multipart/form-data" id="serviceForm">
        @csrf
        <div class="bg-white p-3 p-md-4 mt-3 shadow-sm border fs-14 rounded-2">
            <h6 class="mb-0 fw-bold text-dgreen">PORTFOLIO</h6>
            <p class="pt-3 fs-16 text-secondary">You must add at least one file to your project*</p>

            <div class="row g-3">
                <!-- Primary Image -->
                <div class="col-md-4">
                    <div class="border-dash-g2 text-center position-relative" style="height: 200px;" id="main-img-box">
                        <label for="main-image" id="drop-area"
                            class="w-100 h-100 d-flex align-items-center justify-content-center flex-column"
                            style="cursor: pointer;">
                            <input type="file" accept="image/*" id="main-image" name="image" hidden>
                            <input type="hidden" id="main_image_temp" name="main_image_temp"
                                value="{{ old('main_image_temp') }}">
                            <div id="main-img-view" class="text-center">
                                @if(old('main_image_temp'))
                                    <img src="{{ asset('storage/temp-service-images/' . old('main_image_temp')) }}"
                                        style="max-width:100%;max-height:180px;">
                                @else
                                    <p class="fw-medium m-0">Drag & Drop Image Or <span class="text-primary">Browse</span></p>
                                @endif
                            </div>
                        </label>
                        <button id="remove-main-img"
                            class="btn btn-sm btn-danger position-absolute top-0 end-git0 m-2 d-none">&times;</button>
                    </div>
                </div>

                <!-- Additional Images Upload with Preview -->
                <div class="col-md-8">
                    <div class="border-dash-g2 p-3 h-100">
                        <label for="more-images" class="w-100 text-center d-block" style="cursor: pointer;">
                            <input type="file" accept="image/*" id="more-images" name="optional_image[]" hidden multiple>
                            <div>
                                <p class="text-muted small mb-2">Add up to 5 <span class="text-primary">More
                                        Images</span><span class="text-muted small"> Drag or click — single or
                                        multiple</span></p>
                                <i class="fa fa-plus-circle text-success fs-3"></i>
                            </div>
                        </label>

                        <!-- Preview Container -->
                        <div id="more-images-preview" class="d-flex flex-wrap gap-2 mt-3 justify-content-start">
                            @if(old('optional_images_temp'))
                                @foreach(old('optional_images_temp', []) as $filename)
                                    <div class="position-relative d-inline-block me-2 mb-2">
                                        <img src="{{ asset('storage/temp-service-images/' . $filename) }}" width="100"
                                            class="me-2 mb-1">
                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                            style="z-index:2" onclick="removeTempImage(this, '{{ $filename }}')">&times;</button>
                                        <input type="hidden" name="optional_images_temp[]" value="{{ $filename }}">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div id="optional-imgs-hidden"></div>
                    </div>
                </div>
            </div>
            <p class="text-secondary mt-2 fs-14">Each image will be resized to 920x470.</p>
        </div>

        <!--Box 2-->
        <div class="bg-white p-3 p-md-4 mt-3 shadow-sm border rounded-2">
            <form class="form-group">
                <h6 class="fw-bold mb-3 text-dgreen">SERVICE DETAILS</h6>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="title" id="tt" maxlength="90" value="{{ old('title') }}"
                        placeholder="@lang(key: 'Titles for displaying on your services (e.g., Expert IOS mobile application developer)')"
                        required>

                    <label>@lang('Title')*
                        <small class="text-danger">(max limit 90 characters)</small>
                        <span id="ttcount">0</span>/90</span>
                    </label>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label mt-2">Description*
                        <small class="text-danger">(max 2000 characters)</small>
                    </label>

                    <textarea class="form-control nicEdit" name="description"
                        id="description">{{ old('description') }}</textarea>

                    <div class="text-end text-secondary mt-1" id="descCounterBox">
                        <span id="descCharCount">0</span>/2000 characters
                        <span id="descOverMsg" style="display:none;" class="ms-2 text-danger fs-13">Max 2000 characters
                            allowed!</span>
                    </div>
                </div>


                <select name="priority" id="priority" class="form-select" aria-label="priority" required>
                    <option value="" disabled {{ old('priority') === null ? 'selected' : '' }}>
                        @lang('Post as a')*
                    </option>

                    @if ($userservices[0]->normal_services > 0)
                        <option value="0" {{ old('priority') == '0' ? 'selected' : '' }}>Normal</option>
                    @endif

                    @if ($userservices[0]->priority_services > 0)
                        <option value="1" {{ old('priority') == '1' ? 'selected' : '' }}>Priority</option>
                    @endif

                    @if ($userservices[0]->featured_services > 0)
                        <option value="2" {{ old('priority') == '2' ? 'selected' : '' }}>Featured</option>
                    @endif
                </select>

        </div>
        <!--Box 3-->
        <div class="bg-white p-3 p-md-4 mt-3 shadow-sm border rounded-2">
            <h6 class="fw-bold mb-3 text-dgreen">SERVICE CATEGORIZATION</h6>
            <p class="fs-16 text-secondary">Choose a category so clients can easily find your Service.*</p>

            <div class="row g-1 g-md-3">
                <div class="col-4">
                    <select class="form-select" name="category" id="category" aria-label="@lang('Select Category Level 1')"
                        required>
                        <option selected="" disabled="">@lang('Select Category Level 1')</option>
                        @foreach ($categoryss as $category)
                            <option value="{{ __($category->id) }}" {{ old('category') == $category->id ? 'selected' : '' }}>
                                {{ __($category->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-4">
                    <select name="subcategory" class="form-select col-6 mySubCatgry getchild" id="subCategorys" required></select>
                </div>

                <div class="col-4">
                    <div class="form-group myChildCatgry">
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <!--Box 4 Features-->
        <div class="bg-white p-3 p-md-4 mt-3 shadow-sm border fs-14 rounded-2">
            <h6 class="fw-bold text-dgreen">SERVICE FEATURES</h6>
            <p class="pt-2 fs-16 text-secondary">Choose features that best describe your service.</p>

            <div id="skills_result" class="col-xl-12 col-lg-12 form-group">
                @if(old('skills'))
                    @foreach(old('skills') as $skill_id)
                        @php
                            $skillName = \App\Models\Skill::find($skill_id)->name ?? $skill_id;
                        @endphp
                        <button type="button" onclick="unselectskill({{ $skill_id }})" id="skill_btn{{ $skill_id }}"
                            value="{{ $skill_id }}" class="btn gray ft-small apply-btn fs-sm rounded mt-1">
                            {{ $skillName }} <i class="fa fa-times ml-2"></i>
                        </button>
                        <input type="hidden" name="skills[]" id="job_skills{{ $skill_id }}" value="{{ $skill_id }}">
                    @endforeach
                @endif
            </div>

            <div class="col-xl-12 col-lg-12 ">
                <ul class="nav nav-tabs " role="tablist" id="result">
                </ul>
                <div class="tab-content" id="demos">
                </div>
            </div>
        </div>
        <!--Box 5-->
        <div class="bg-white p-3 p-md-4 mt-3 shadow-sm border fs-14 rounded-2 mb-3">
            <h6 class="fw-bold text-dgreen">Keywords for search</h6>
            <!-- <p class="pt-2 fs-16 text-secondary">Find pre-defined Keywords or Type to add (Max. 5 Keywords).</p> -->
            <div id="skills_result_extra" class="col-xl-12 col-lg-12 form-group">
                @if(old('newskills'))
                    @foreach(old('newskills') as $skill_id)
                        @php
                            // Agar Skill model hai, warna $skill_id print kar do
                            $skillName = \App\Models\Skill::find($skill_id)->name ?? $skill_id;
                        @endphp
                        <button type="button" onclick="unselectskill({{ $skill_id }})" id="skill_btn{{ $skill_id }}"
                            value="{{ $skill_id }}" class="btn gray ft-small apply-btn fs-sm rounded mt-1">
                            {{ $skillName }} <i class="fa fa-times ml-2"></i>
                        </button>&nbsp;
                        <input type="hidden" name="newskills[]" id="job_skills{{ $skill_id }}" value="{{ $skill_id }}">
                    @endforeach
                @endif
            </div>
            <div class="form-floating mb-3">
                <input onkeyup="getSkillbuname(this.value)" id="search_skill" class="form-control" type="text"
                    autocomplete="off" placeholder="Type to Search ...">
                <label for="floatingInput"> Type to search... </label>
            </div>

            <div id="skil_result" class="col-xl-12 col-lg-12 form-group">
            </div>
            <h5 class="fs-14"><em>5 tags maximum.</em></h5>
        </div>
        <!--Box 6-->
        <div id="tiers-box" style="display: none;">
            <div class="filtered-tiers bg-white p-3 p-md-4 mt-3 shadow-sm border fs-14 rounded-2 mb-3">
            </div>
        </div>

        <!-- Box 7 -->
        <div id="extra-options-box" class="bg-white p-3 p-md-4 mt-3 shadow-sm border fs-14 rounded-2 mb-3"
            style="display: none;">
            <h6 class="fw-bold text-dgreen">Add Extra (Optional)</h6>
            <div class="extra_options bg-white p-3 p-md-4 mt-3 shadow-sm border fs-14 rounded-2 mb-3"></div>
        </div>



        <div class="float-end mt-2">
            {{--  <button type="submit" value="yes" name="review" class="btn btn-dark bg-dgreen">Review Services</buton>  --}}
                <button type="submit" class="btn btn-success bg-green border-0 ms-3">Create Services</buton>
        </div>
    </form>
@endsection
@section('script')
    <script src="{{ asset($activeTemplateTrue . 'frontend/js/nicEdit.js') }}"></script>
    <script id="scope-old-values" type="text/javascript">
        window.oldTierTitles = @json([old('title1'), old('title2'), old('title3')]);
        window.oldTierDescs = @json([old('desc1'), old('desc2'), old('desc3')]);
        window.oldTierPrices = @json([old('price1'), old('price2'), old('price3')]);
        window.oldTierTimes = @json([old('time1'), old('time2'), old('time3')]);
        window.oldTierRevisions = @json([old('revision1'), old('revision2'), old('revision3')]);
        window.oldSingleTitle = @json(old('titlesin'));
        window.oldSingleDesc = @json(old('descsin'));
        window.oldSinglePrice = @json(old('pricesin'));
        window.oldSingleTime = @json(old('timesin'));
        window.oldSingleRevision = @json(old('revisionin'));
        window.oldTierServiceValue1 = @json(old('tier_service_value1', []));
        window.oldTierServiceValue2 = @json(old('tier_service_value2', []));
        window.oldTierServiceValue3 = @json(old('tier_service_value3', []));
    </script>

    <script>
        window.restoreTierOldValues = function () {
            // MULTI PACKAGE
            for (let i = 1; i <= 3; i++) {
                if (window.oldTierTitles && window.oldTierTitles[i - 1])
                    $('#t' + i).val(window.oldTierTitles[i - 1]);
                if (window.oldTierDescs && window.oldTierDescs[i - 1])
                    $('textarea[name="desc' + i + '"]').val(window.oldTierDescs[i - 1]);
                if (window.oldTierPrices && window.oldTierPrices[i - 1])
                    $('input[name="price' + i + '"]').val(window.oldTierPrices[i - 1]);
                if (window.oldTierTimes && window.oldTierTimes[i - 1])
                    $('input[name="time' + i + '"]').val(window.oldTierTimes[i - 1]);
                if (window.oldTierRevisions && window.oldTierRevisions[i - 1])
                    $('select[name="revision' + i + '"]').val(window.oldTierRevisions[i - 1]);
            }
            // SINGLE PACKAGE
            if (window.oldSingleTitle) $('input[name="titlesin"]').val(window.oldSingleTitle);
            if (window.oldSingleDesc) $('textarea[name="descsin"]').val(window.oldSingleDesc);
            if (window.oldSinglePrice) $('input[name="pricesin"]').val(window.oldSinglePrice);
            if (window.oldSingleTime) $('input[name="timesin"]').val(window.oldSingleTime);
            if (window.oldSingleRevision) $('select[name="revisionin"]').val(window.oldSingleRevision);

            if (window.oldTierServiceValue1 && Array.isArray(window.oldTierServiceValue1)) {
                $('input[name="tier_service_value1[]"]').each(function () {
                    var val = $(this).val();
                    if (window.oldTierServiceValue1.includes(val)) {
                        $(this).prop('checked', true);
                    }
                });
            }
            if (window.oldTierServiceValue2 && Array.isArray(window.oldTierServiceValue2)) {
                $('input[name="tier_service_value2[]"]').each(function () {
                    var val = $(this).val();
                    if (window.oldTierServiceValue2.includes(val)) {
                        $(this).prop('checked', true);
                    }
                });
            }
            if (window.oldTierServiceValue3 && Array.isArray(window.oldTierServiceValue3)) {
                $('input[name="tier_service_value3[]"]').each(function () {
                    var val = $(this).val();
                    if (window.oldTierServiceValue3.includes(val)) {
                        $(this).prop('checked', true);
                    }
                });
            }
        }
    </script>

    <script type="text/javascript">
        "use strict";
        let oldNewSkills = @json(old('newskills', []));
        let skillsMap = @json($allSkillsMap);
        const oldSkillCategory = @json(old('skillcategory')); // ID
        let oldSkills = @json(old('skills', []));
        function validateMoreImages(input) {
            const maxFiles = 5;
            const files = input.files;
            if (files.length > maxFiles) {
                alert(`You can upload up to ${maxFiles} additional images only.`);
                input.value = ''; // reset input
            }
        }

        function getSkillNameById(id) {
            console.log(skillsMap[id]);
            return skillsMap[id] || '';
        }

        // Main image handling
        const dropArea = document.getElementById('drop-area');
        const inputFile = document.getElementById('main-image');
        const imgView = document.getElementById('main-img-view');
        const removeBtn = document.getElementById('remove-main-img');

        inputFile.addEventListener('change', uploadImage);
        dropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropArea.classList.add('border-success');
        });
        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('border-success');
        });
        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dropArea.classList.remove('border-success');
            inputFile.files = e.dataTransfer.files;
            uploadImage();
        });

        function uploadImage() {
            const file = inputFile.files[0];
            if (file && file.type.match('image.*')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imgView.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
                    removeBtn.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        removeBtn.addEventListener('click', () => {
            inputFile.value = '';
            imgView.innerHTML = `<p class="fw-medium m-0">Drag & Drop Image Or <span>Browse</span> file</p>`;
            removeBtn.style.display = 'none';
        });

        document.addEventListener('DOMContentLoaded', () => {
            // Title char count
            const titleInput = document.getElementById('tt');
            const titleCount = document.getElementById('ttcount');
            titleInput?.addEventListener('input', () => {
                titleCount.textContent = titleInput.value.length;
            });
        });

        bkLib.onDomLoaded(function () {
            const descriptionId = 'description';
            new nicEditor({ fullPanel: true }).panelInstance(descriptionId);

            setInterval(function () {
                let editor = nicEditors.findEditor(descriptionId);
                if (!editor) return;

                let rawHtml = editor.getContent();
                let plainText = rawHtml.replace(/<[^>]*>/g, '');
                const counter = document.getElementById('descCharCount');
                const counterBox = document.getElementById('descCounterBox');
                const overMsg = document.getElementById('descOverMsg');

                counter.textContent = plainText.length;

                if (plainText.length >= 2000) {
                    overMsg.style.display = '';
                } else {
                    overMsg.style.display = 'none';
                }
            }, 400);

            document.getElementById('serviceForm').addEventListener('submit', function (e) {
                let editor = nicEditors.findEditor(descriptionId);
                let plainText = editor.getContent().replace(/<[^>]*>/g, '');
                if (plainText.length > 2000) {
                    e.preventDefault();
                    alert('Service Description cannot exceed 2000 characters!');
                }
            });
        });

        function proPicURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var preview = $(input).parents('.preview-thumb').find('.profilePicPreview');
                    $(preview).css('background-image', 'url(' + e.target.result + ')');
                    $(preview).addClass('has-image');
                    $(preview).hide();
                    $(preview).fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }


        $(".profilePicUpload").on('change', function () {
            proPicURL(this);
        });

        $(".remove-image").on('click', function () {
            $(".profilePicPreview").css('background-image', 'none');
            $(".profilePicPreview").removeClass('has-image');
        });

        $('.addExtra').on('click', function () {
            var html = `<div class="col-lg-12 extraServiceRemove">
                            <div class="row">
                                <div class="col-xl-9 col-lg-12 form-group">
                                    <input type="text" name="extra_title[]" class="form-control" placeholder="@lang('Enter title')" required>
                                </div>
                                <div class="col-xl-2 col-lg-12 form-group">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="extra_price[]" placeholder="@lang('Enter Price')" required="">
                                        <span class="input-group-text" id="basic-addon2">{{ __($general->cur_text) }}</span>
                                    </div>
                                </div>
                                <div class="col-xl-1">
                                    <button type="button" class="btn btn--danger text-white border--rounded btn-sm mt-1 removeBtn">
                                    <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>`;
            $('.addExtraService').append(html);
        });

        $(document).on('click', '.removeBtn', function () {
            $(this).closest('.extraServiceRemove').remove();
        });

        $(document).on('click', '.removeExtraImage', function () {
            $(this).closest('.removeImage').remove();
        });

        (function ($) {
            $(document).on('mouseover ', '.nicEdit-main,.nicEdit-panelContain', function () {
                $('.nicEdit-main').focus();
            });
        })(jQuery);
        $(document).on("change", ".custom-file-input", function () {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        // const initialCategoryId = $('#category').val();
        // if (initialCategoryId) {
        //     console.log(initialCategoryId);
        //     if (initialCategoryId) {
        //         loadSubcategories(initialCategoryId);
        //         // 💥 Wait a bit to allow loadSubcategories to populate subcategories, then manually trigger change
        //         setTimeout(function () {
        //             $('.mySubCatgry').trigger('change');
        //             $('.tierchildcat').trigger('change');
        //         }, 300); // Adjust delay if needed
        //     }
        // }
        $('#category').on('change', function () {
            const selectedCategoryId = $(this).val();
            loadSubcategories(selectedCategoryId);
        });

        const initialSubCategoryId = $('#subCategorys').val();
        if (initialSubCategoryId) {
            // 💥 Wait a bit to allow loadSubcategories to populate subcategories, then manually trigger change
            setTimeout(function () {
                $('.tierchildcat').trigger('change');
            }, 300); // Adjust delay if needed
        }

        $(document).on('change', '.tierchildcat', function () {
            var subcategory = $('#subCategorys').val();
            var childcategory = $('#childCategorys').val();
            console.log(subcategory, childcategory);
            if (subcategory && childcategory)
                gettiers(subcategory, childcategory);
        });

        $(document).on('change', '.mySubCatgry', function () {
            // $('#demos').empty();
            // $('.filtered-tiers').empty();
            // $('#result').empty();
            // $('#skills_result').empty();

            var subcategory = $('#subCategorys').val();
            console.log(subcategory);

            $.ajax({
                type: "GET",
                url: "{{ route('user.service.subcategory') }}", // This route must return child categories
                data: {
                    subcategory: subcategory
                },
                success: function (data) {
                    var html = '';
                    $("#childCategorys").empty();
                    $(".myChildCatgry").empty();
                    $(".extra_options").empty();
                    if (data.error) {
                        // No child categories found, call gettiers directly
                        //alert("here");
                        gettiers(subcategory);
                    } else {
                        $("#tiers-box").hide();
                        // Populate child categories
                        const childcategory = @json(old('childcategory'));
                        html += `<select name="childcategory" class="form-select col-6 tierchildcat" id="childCategorys" required>
                                                                            <option value="" selected disabled>@lang('Select Category Level 3')</option>`;
                        $.each(data, function (index, item) {
                            let selected = childcategory == item.id ? 'selected' : '';
                            html += `<option value="${item.id}" ${selected}>${item.name}</option>`;
                        });
                        html += `</select>`;
                        $(".myChildCatgry").html(html);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching child categories:', error);
                }
            });
        });

        function loadSubcategories(categoryId, selectedSubcat = null, selectedChildcat = null) {
            // $('.myChildCatgry').empty();
            // $('#demos').empty();
            // $('.filtered-tiers').empty();
            // $('#result').empty();
            // $('#skills_result').empty();

            $.ajax({
                type: "GET",
                url: "{{ route('user.service.category') }}",
                data: { category: categoryId },
                success: function (data) {
                    var html = '';
                    $("#subCategorys").empty();
                    if (data.error) {
                        html += `<option value="" selected disabled>${data.error}</option>`;
                        $(".mySubCatgry").html(html);
                    } else {
                        html += `<option value="" selected disabled>@lang('Select Category Level 2')</option>`;
                        $.each(data, function (index, item) {
                            let selected = selectedSubcat == item.id ? 'selected' : '';
                            html += `<option value="${item.id}" ${selected}>${item.name}</option>`;
                        });
                        $(".mySubCatgry").html(html);

                        if (selectedSubcat) {
                            // Set subcategory and trigger change to load childcategories
                            setTimeout(function () {
                                $('#subCategorys').val(selectedSubcat).trigger('change');

                                // Optionally trigger load of childcategories with old value
                                if (selectedChildcat) {
                                    loadChildCategories(selectedSubcat, selectedChildcat);
                                }
                            }, 50);
                        }
                    }
                }
            });
        }

        function setOldSubcategory() {
            const oldSubcategory = @json(old('subcategory'));
            if (oldSubcategory) {
                $('#subCategorys').val(oldSubcategory);
            }
        }

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

        $('#subCategorys').on('change', function () {
            var subcategory = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('user.service.skillcategory') }}",
                data: {
                    subcategory: subcategory
                },
                success: function (data) {
                    var html = '';
                    if (data.error) {
                        html += 'No Skill Category Availble';

                    } else {
                        $.each(data, function (name, id) {
                            html += ' <li class="nav-item"><a onclick="getskills(' + id +
                                ')" type="button" class="nav-link addcl " id="nav-link"  data-toggle="tab" href="#tab-' +
                                id + '" role="tab">' + name + '</a></li>';
                        });
                    }

                    $('#result').html(html);

                    // Auto-click the first tab (if any)
                    setTimeout(function () {
                        $('#result .nav-link').first().trigger('click');
                    }, 100); // slight delay to ensure DOM is ready
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
                url: "{{ route('user.service.getskills') }}",
                data: {
                    c_id: c_id
                },
                success: function (data) {
                    var html = '';
                    if (data.error) {

                        html += 'No Skill  Availble';
                    } else {
                        $.each(data, function (name, id, skill_category_id) {
                            html +=
                                `<div class="tab-pane nbv" style="display:inline;" id="tab-'+c_id+'" role="tabpanel">
                                    <button type="button" onclick="selectskills(${id},'${name}',${c_id},false)" value="${id}"
                                        class="btn gray ft-small apply-btn fs-sm rounded mt-1">${name}<i class="fa fa-plus ml-2"></i>
                                    </button>
                                &nbsp</div>`;
                        });
                    }
                    $('#demos').html(html);
                    //checkload.push(c_id);
                }
            });
        }

        $(".addcl").click(function () {
            $(".nbv").addClass("active");
            $(this).addClass("active");
        });

        function renderSkillsResult() {
            let button_html = '';
            // Old/existing skills
            for (let i = 0; i < skills_arr.length; i++) {
                button_html += `<button type="button" onclick="unselectskill(${skills_arr[i]})" id="skill_btn${skills_arr[i]}" value="${skills_arr[i]}" class="btn gray ft-small apply-btn fs-sm rounded mt-1">${skills_name[i]} <i class="fa fa-times ml-2"></i></button>&nbsp;<input type="hidden" name="skills[]" id="job_skills${skills_arr[i]}" value="${skills_arr[i]}"/>`;
            }
            $('#skills_result').html(button_html);
        }

        function renderExtraSkillsResult() {
            let button_html = '';

            for (let i = 0; i < newskills_arr.length; i++) {
                button_html += `<button type="button" onclick="unselectnewskill(${newskills_arr[i]})" id="newskill_btn${newskills_arr[i]}" value="${newskills_arr[i]}" class="btn gray ft-small apply-btn fs-sm rounded mt-1">${newskills_name[i]} <i class="fa fa-times"></i></button>&nbsp;<input type="hidden" name="newskills[]" id="newjob_skills${newskills_arr[i]}" value="${newskills_name[i]}"/>`;
            }
            $('#skills_result_extra').html(button_html);
        }

        function selectskills(s_id, name, c_id, check) {
            if (skills_arr.indexOf(s_id) !== -1) return;
            skills_arr.push(s_id);
            skills_name.push(name);
            renderSkillsResult();
            if (check == true) {
                $('#skil_result').html('');
                $('#search_skill').val('');
            }
        }

        //select new skill added
        function selectnewskill(name) {
            if (newskills_name.indexOf(name) !== -1) return;
            newskillcount++;
            newskills_arr.push(newskillcount);
            newskills_name.push(name);
            renderExtraSkillsResult();
            $('#skil_result').html('');
            $('#search_skill').val('');
        }

        function unselectskill(s_id) {
            var index = skills_arr.indexOf(s_id);
            if (index !== -1) {
                skills_arr.splice(index, 1);
                skills_name.splice(index, 1);
                renderSkillsResult();
            }
        }

        function unselectnewskill(s_id) {
            var index = newskills_arr.indexOf(s_id);
            if (index !== -1) {
                newskills_arr.splice(index, 1);
                newskills_name.splice(index, 1);
                renderExtraSkillsResult();
            }
        }

        //getting existing skill by search
        function getSkillbuname(search) {
            let html = '';
            if (search == "") {
                $('#skil_result').html('');
                return
            }
            $.ajax({
                type: "GET",
                url: "{{ route('user.service.getskillsbyname') }}",
                data: {
                    search: search
                },
                success: function (data) {
                    var html = '';
                    if (data.error) {

                        html +=
                            `<b><a onclick="selectnewskill('${search}')" style="cursor:pointer;font-size:14px; line-height: 0.7;">${search}</a></b><hr>`;
                    } else {
                        html += "<div  style='height:100px;overflow:scroll; overflow-x: hidden;'>";
                        $.each(data, function (name, id) {
                            html +=
                                `<p><b><a onclick="selectnewskill('${name}')" style="cursor:pointer;font-size:14px; line-height: 0.7;">${name}</a></b></p>`;

                        });
                        html += '</div>';
                        //console.log(data);
                    }

                    $('#skil_result').html(html);
                    // checkload.push(c_id);
                }
            });
        }

        var oldTiers = @json(old('extra_tiers', []));
        var oldPrices = @json(old('addon_price', []));
        var oldDeliveryTimes = @json(old('addon_delivery_time', []));
        var oldFastTiers = @json(old('fast_tier', []));
        var oldFastTierTimes = @json(old('fast_tier_time', []));
        var oldFastTierPrices = @json(old('fast_tier_price', []));

        function gettiers(subcategory, childcategory)
        {
            $.ajax({
                url: "{{ url('/Search_tiers') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    subcategory: subcategory,
                    childcategory: childcategory
                },
                success: function (response) {
                    if (response !== 0) {
                        var result = response;
                        console.log(result);
                        // Render the full HTML view in .filtered-tiers (Box 6)\
                        // alert(result.status);
                        if (result.status === 'true') {
                            $("#tiers-box").show();

                            $('.filtered-tiers').html(result.content_data);
                            $('.filtered-tiers').show();
                        }
                        let oldVarsScript = document.getElementById('scope-old-values');
                        console.log(oldVarsScript);
                        if (oldVarsScript) {
                            eval(oldVarsScript.innerText); // make sure the window.old... variables are always refreshed!
                        }
                        console.log(typeof restoreTierOldValues);
                        if (typeof restoreTierOldValues === "function") {
                            restoreTierOldValues();
                        }

                        // Render only checkbox list in .extra_options (Box 7)
                        if (result.tiers_checkbox && result.tiers_checkbox.length > 0) {
                            let checkboxes = '';
                            let isMulti = $('#msval').is(':checked');

                            result.tiers_checkbox.forEach(function (item, index) {
                                let isChecked = oldTiers.includes(item.id.toString());
                                let price = oldPrices[item.id] || '';
                                let delivery = oldDeliveryTimes[item.id] || '';

                                let isFastDelivery = item.name.toLowerCase().includes('fast delivery');
                                let tierSelectHtml = '';

                                if (isFastDelivery) {
                                    if (isMulti) {
                                        tierSelectHtml = `<div class="multi-package-block">
                                                                ${generateTierBlock('lite', item.id)}
                                                                ${generateTierBlock('plus', item.id)}
                                                                ${generateTierBlock('ultimate', item.id)}
                                                            </div>`;
                                    } else {
                                        tierSelectHtml = `<div class="single-package-block">
                                                                ${generateTierBlock('lite', item.id)}
                                                            </div>`;
                                    }
                                } else {
                                    tierSelectHtml = `<div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label>Delivery Time (in days)</label>
                                                    <input type="number" name="addon_delivery_time[${item.id}]" class="form-control" min="0" value="${delivery}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Extra Cost</label>
                                                    <input type="number" name="addon_price[${item.id}]" class="form-control" min="0" value="${price}">
                                                </div>
                                            </div>`;
                                }

                                checkboxes += `<div class="form-check mb-3 border p-2 rounded">
                                                <input class="form-check-input toggle-addon" type="checkbox" name="extra_tiers[]" value="${item.id}" id="tier_${index}" ${isChecked ? 'checked' : ''}>
                                                <label class="form-check-label fw-bold" for="tier_${index}">
                                                    ${item.name}
                                                </label>
                                                <div class="addon-fields mt-2" id="addon_fields_${index}" style="${isChecked ? 'display:block;' : 'display:none;'}">
                                                    ${tierSelectHtml}
                                                </div>
                                            </div>`;
                            });

                            $('.extra_options').html(checkboxes);
                            $('#extra-options-box').show();
                        }
                        else {
                            $('.extra_options').empty();
                            $('#extra-options-box').hide();
                        }
                    }
                },
                error: function () {
                    alert('Failed to fetch tiers');
                }
            });
        }

        $(document).on('change', '#msval', function () {
            let isMulti = $(this).is(':checked');

            $('.form-check-input.toggle-addon').each(function () {
                let checkbox = $(this);
                let index = checkbox.attr('id').split('_')[1];
                let addonFields = $(`#addon_fields_${index}`);
                let addonId = checkbox.val();
                let isFastDelivery = checkbox.next('label').text().toLowerCase().includes('fast delivery');

                if (isFastDelivery) {
                    let updatedFields = isMulti
                        ? `<div class="multi-package-block">
                                ${generateTierBlock('lite', addonId)}
                                ${generateTierBlock('plus', addonId)}
                                ${generateTierBlock('ultimate', addonId)}
                            </div>`
                        : `<div class="single-package-block">
                                ${generateTierBlock('lite', addonId)}
                            </div>`;
                    addonFields.html(updatedFields);
                }
            });
        });

        $(document).on('change', '.toggle-addon', function () {
            let index = $(this).attr('id').split('_')[1];
            let fields = $(`#addon_fields_${index}`);
            if ($(this).is(':checked')) {
                fields.show();
            } else {
                fields.hide();
            }
        });

        function generateTierBlock(tierName, addonId) {
            let tierIndex = tierName === 'lite' ? 1 : tierName === 'plus' ? 2 : 3;

            let time = oldFastTierTimes[tierIndex] || '';
            let price = oldFastTierPrices[tierIndex] || '';

            return `<div class="tier-addon-block mb-2 border p-2 rounded">
                    <strong class="text-capitalize">${tierName}</strong>
                    <input type="hidden" name="fast_tier[${tierIndex}]" value="${addonId}">
                    <div class="row mt-1">
                        <div class="col-md-6">
                            <label>Delivery Time (in days)</label>
                            <input type="number" name="fast_tier_time[${tierIndex}]" class="form-control" min="0" value="${time}">
                        </div>
                        <div class="col-md-6">
                            <label>Extra Cost</label>
                            <input type="number" name="fast_tier_price[${tierIndex}]" class="form-control" min="0" value="${price}">
                        </div>
                    </div>
                </div>`;
        }

        $(function () {
            $('#colorselector').change(function () {
                $('.colors').hide();
                $('#' + $(this).val()).show();
                if ($(this).val() == 'all') {
                    $('.colors').show();
                }
            });
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        var loadFile2 = function (event, id = null) {
            // console.log(event);
            // console.log(id);
            if (id != null) {
                var image = document.getElementById('amenities-' + id);
            } else {
                var image = document.getElementById('asd3');
            }
            image.src = URL.createObjectURL(event.target.files[0]);
        };

        $(document).ready(function () {
            // Get old values if they exist
            var oldCategory = @json(old('category'));
            var oldSubcategory = @json(old('subcategory'));
            var oldChildcategory = @json(old('childcategory'));

            if (oldCategory) {
                $('#category').val(oldCategory); // Set old category
                loadSubcategories(oldCategory, oldSubcategory, oldChildcategory);
            }

            if (oldSkills && oldSkills.length > 0) {
                oldSkills.forEach(function (skillId) {
                    let skillName = getSkillNameById(skillId);
                    if (skillName) {
                        skills_arr.push(skillId);
                        skills_name.push(skillName);
                    }
                });
            }

            console.log("old new extra skills");
            console.log(oldNewSkills.length);
            if (oldNewSkills && oldNewSkills.length > 0) {
                oldNewSkills.forEach(function (skillName) {
                    newskillcount++;
                    newskills_arr.push(newskillcount);
                    newskills_name.push(skillName);
                });
            }
            // Now, after both, call render ONCE:
            renderSkillsResult();
            renderExtraSkillsResult();
        });

        $('#main-image').on('change', function () {
            let file = this.files[0];
            if (!file) return;
            let formData = new FormData();
            formData.append('image', file);

            $.ajax({
                url: "{{ route('user.service.image.temp.upload') }}",
                method: "POST",
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    $('#main-img-preview').html('<img src="' + res.url + '" width="120">');
                    $('#main_image_temp').val(res.filename);
                }
            });
        });

        $('#more-images').on('change', function () {
            const files = this.files;
            const preview = $('#more-images-preview');
            const hiddenBox = $('#optional-imgs-hidden');
            const max = 5;

            // Check limit
            if ((preview.children().length + files.length) > max) {
                alert("You can upload up to 5 images only.");
                $(this).val('');
                return;
            }

            Array.from(files).forEach(function (file) {
                let formData = new FormData();
                formData.append('image', file);

                // For instant local preview while waiting for AJAX
                const reader = new FileReader();
                reader.onload = function (e) {
                    // We'll replace this later after AJAX, but for snappy UI, do this:
                    const wrapper = $('<div class="position-relative d-inline-block me-2 mb-2"></div>');
                    const img = $('<img>').attr('src', e.target.result).css({ width: '100px', height: 'auto' });
                    const removeBtn = $('<button class="btn btn-sm btn-danger position-absolute top-0 end-0" style="z-index:2">&times;</button>');
                    // Save reference for AJAX
                    wrapper.append(img).append(removeBtn);
                    preview.append(wrapper);

                    // Now AJAX upload
                    $.ajax({
                        url: "{{ route('user.service.image.temp.upload') }}",
                        method: "POST",
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (res) {
                            // Change the preview to the *real* uploaded file, in case you want to show server version
                            img.attr('src', res.url);

                            // Hidden input for this image, used on submit
                            const hiddenInput = $('<input type="hidden" name="optional_images_temp[]">').val(res.filename);
                            hiddenBox.append(hiddenInput);

                            // Remove button logic (remove preview, input, and delete temp file)
                            removeBtn.off('click').on('click', function () {
                                wrapper.remove();
                                hiddenInput.remove();
                                $.ajax({
                                    url: "{{ route('user.service.image.temp.delete') }}",
                                    method: "POST",
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                    data: { filename: res.filename }
                                });
                            });
                        }
                    });
                };
                reader.readAsDataURL(file);
            });
        });

        function removeTempImage(btn, filename) {
            $(btn).closest('div').remove();
            // Remove corresponding hidden input (if not already)
            $('input[name="optional_images_temp[]"][value="' + filename + '"]').remove();
            // Delete from server
            $.ajax({
                url: "{{ route('user.service.image.temp.delete') }}",
                method: "POST",
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: { filename: filename }
            });
        }
    </script>
@endsection
