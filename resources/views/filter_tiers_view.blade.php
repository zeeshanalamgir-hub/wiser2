<style>
    @media screen and (max-width: 600px) {
        body {
            font-size: 10px;
        }

        h6,
        p,
        input,
        textarea,
        select {
            font-size: 12px !important;
            padding: 2px !important;
        }

        .fs-14 {
            font-size: 10px;
        }
    }
</style>
<div class="">
    <h6 class="fw-bold text-dgreen w-100">SCOPE & PRICE
        <span class="float-end fs-16 text-secondary fw-normal">
            <label class="switch">
                <input type="checkbox" id="msval" name="msval" value="1" checked>
                <span class="slider round"></span>
            </label>
            <span class="fs-14">Multi Packages
            </span>
        </span>
    </h6>
    <p class="pt-2 fs-14 text-secondary"> Choose from 1 or 3 pricing planes for your service</p>

    <div class="row mt-3" id="a">
        <div class="col-lg-12 col-md-12">
            <div class=" bg-light rounded mb-4">

                <div class=" py-3 px-3 ">
                    <div class="">
                        <div class="row">
                            <div class="col-3 px-0  ">
                            </div>
                            <div class="col-3 pe-0   text-center">
                                <p class=" bg-lgreen p-1 fs-16 fw-bold mb-0">Lite</p>
                            </div>
                            <div class=" col-3  pe-0 text-center">
                                <p class=" bg-lgreen p-1 fs-16 fw-bold mb-0">Plus</p>
                            </div>
                            <div class=" col-3 pe-0 text-center">
                                <p class=" bg-lgreen  fs-16 fw-bold p-1 mb-0">Ultimate</p>
                            </div>
                        </div>
                    </div>

                    <!-- title -->
                    <div class="row mt-2">
                        <div class="col-3 px-0">
                            <h6 class="">Title <small class="fs-14 text-secondary">(max limit 60
                                    characters)</small>
                            </h6>
                        </div>
                        <div class="col-3 pe-0">
                            <div class="form-group">
                                <input type="text" class="form-control rounded" maxlength="60" placeholder="Title"
                                    id="t1" name="title1">
                            </div>
                        </div>
                        <div class=" col-3 pe-0">
                            <input type="text" id="t2" class="form-control rounded" maxlength="60"
                                placeholder="Title" name="title2">
                        </div>
                        <div class=" col-3 pe-0">
                            <input type="text" id="t3" class="form-control rounded" maxlength="60"
                                placeholder="Title" name="title3">
                        </div>
                    </div>
                    <!-- title -->

                    <!-- description -->
                    <div class="row border-top mt-2 pt-2 ">
                        <div class="col-3 px-0 ">
                            <h6 class="mb-0 ft-medium fs-md">Description</h6>
                        </div>
                        <div class="col-3 pe-0">
                            <div class="form-group">
                                <textarea name="desc1" class="form-control with-light" placeholder="Description"></textarea>
                            </div>
                        </div>
                        <div class=" col-3 pe-0">
                            <textarea name="desc2" class="form-control with-light" placeholder="Description"></textarea>
                        </div>
                        <div class=" col-3 pe-0">
                            <textarea name="desc3" class="form-control with-light" placeholder="Description"></textarea>
                        </div>
                    </div>
                    <!-- description -->

                    @foreach ($pageData['tiers_select'] as $a)
                        <div class="row border-top mt-2 pt-2">
                            <div class="col-3 px-0">
                                <p class="mb-0 ft-medium fs-md">{{ $a->name }}</p>
                                <input type="hidden" name="tier_id[]" value="{{ $a->id }}">
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <select class="form-control" name="tier_service_value1[]">
                                        <option>select {{ $a->name }}</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="unlimited">Unlimited</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <div class="form-group">
                                    <select class="form-control" name="tier_service_value2[]">
                                        <option>select {{ $a->name }}</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="unlimited">Unlimited</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <div class="form-group">
                                    <select class="form-control" name="tier_service_value3[]">
                                        <option>select {{ $a->name }}</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="unlimited">Unlimited</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    @endforeach


                    @foreach ($pageData['tiers_input'] as $a)
                        <div class="row border-top mt-2 pt-2 ">
                            <div class="col-3 px-0">
                                <h6 class="mb-0 ft-medium fs-md">{{ $a->name }}</h6>
                                <input type="hidden" name="tier_id[]" value="{{ $a->id }}">
                            </div>
                            <div class="col-3 text-center">
                                <div class="form-group">
                                    <div class="form-group">

                                        <input type="text" class="form-control rounded"
                                            placeholder="{{ $a->name }}" name="tier_service_value1[]">
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <div class="form-group">
                                    <div class="form-group">

                                        <input type="text" class="form-control rounded"
                                            placeholder="{{ $a->name }}" name="tier_service_value2[]">
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <div class="form-group">
                                    <div class="form-group">

                                        <input type="text" class="form-control rounded"
                                            placeholder="{{ $a->name }}" name="tier_service_value3[]">
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach
                    @foreach ($pageData['tiers_checkbox'] as $index => $a)
                        <div class="row border-top mt-2 pt-2 ">
                            <div class="col-3 px-0">
                                <h6 class="mb-0 ft-medium fs-md">{{ $a->name }}</h6>
                                <input type="hidden" name="tier_id[]" value="{{ $a->id }}">
                            </div>
                            <div class="col-3 text-center">
                                <div class="form-group">
                                    <input type="checkbox" name="tier_service_value1[{{ $a->id }}]" id="tier_service_value1_{{ $a->id }}" value=""
                                        {{ is_array(old('tier_service_value1')) && in_array($a->id, old('tier_service_value1', [])) ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <div class="form-group">
                                    <input type="checkbox" name="tier_service_value2[{{ $a->id }}]" id="tier_service_value2_{{ $a->id }}" value=""
                                        {{ is_array(old('tier_service_value2')) && in_array($a->id, old('tier_service_value2', [])) ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <div class="form-group">
                                    <input type="checkbox" name="tier_service_value3[{{ $a->id }}]" id="tier_service_value3_{{ $a->id }}" value=""
                                        {{ is_array(old('tier_service_value3')) && in_array($a->id, old('tier_service_value3', [])) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    @endforeach


                    <!-- Price -->
                    <div class="row border-top mt-2 pt-2">
                        <div class="col-3 px-0">
                            <h6 class="mb-0 ft-medium fs-md">Price</h6>
                        </div>
                        <div class="col-3 pe-0 text-center">
                            <div class="form-group">
                                <input type="number" class="form-control rounded" placeholder="Price"
                                    name="price1">
                            </div>
                        </div>
                        <div class=" col-3 pe-0 text-center">
                            <input type="number" class="form-control rounded" placeholder="Price" name="price2">
                        </div>
                        <div class=" col-3 pe-0 text-center">
                            <input type="number" class="form-control rounded" placeholder="Price" name="price3">
                        </div>
                    </div>
                    <!-- Price -->

                    <!-- revisions -->
                    <div class="row border-top mt-2 pt-2 ">
                        <div class="col-3 px-0 pt-2">
                            <h6 class="mb-0 ft-medium fs-md">Revisions</h6>
                        </div>
                        <div class="col-3 pe-0 text-center">
                            <div class="form-group">
                                <select class="form-control" name="revision1">
                                    <option>Select Revisions</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="unlimited">Unlimited</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3 pe-0 text-center">
                            <div class="form-group">
                                <select class="form-control" name="revision2">
                                    <option>Select Revisions</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="unlimited">Unlimited</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3 pe-0 text-center">
                            <div class="form-group">
                                <select class="form-control" name="revision3">
                                    <option>Select Revisions</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="unlimited">Unlimited</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <!-- revisions -->
                    <!-- Delivery Time -->
                    <div class="row border-top mt-2 pt-2 ">
                        <div class="col-3 px-0 pt-2">
                            <h6 class="mb-0 ft-medium fs-md">Delivery Time</h6>
                        </div>
                        <div class="col-3 pe-0 col-3">
                            <div class="form-group">
                                <input type="text" class="form-control rounded" placeholder="Delivery Time"
                                    name="time1">
                            </div>
                        </div>
                        <div class="col-3 pe-0">
                            <input type="text" class="form-control rounded" placeholder="Delivery Time"
                                name="time2">
                        </div>
                        <div class="col-3 pe-0">
                            <input type="text" class="form-control rounded" placeholder="Delivery Time"
                                name="time3">
                        </div>
                    </div>
                    <!-- Delivery Time -->
                </div>
            </div>
        </div>
    </div>

    <!---------- single ---------------->

    <div class="row mt-3" id="b" style="display: none;">
        <div class="col-lg-12 col-md-12">
            <div class="bg-white rounded mb-4">
                <div class=" py-3 px-3 ">
                    <div class="">
                        <div class="row">
                            <div class="col-3 px-0">
                            </div>
                            <div class="col-3 pe-0 text-center">
                                <p class="bg-lgreen fw-bold mb-0 fs-16 fw-bold p-1">Lite</p>
                            </div>

                        </div>
                    </div>

                    <!-- title -->
                    <div class="row mt-2">
                        <div class="col-3 px-0">
                            <h6 class="mb-0 ft-medium fs-md">Title <small class="fs-14 text-secondary">(max limit 60
                                    characters)</small></h6>
                        </div>
                        <div class="col-lg-3 pe-0 col-md-3 col-sm-3 col-4">
                            <div class="form-group">
                                <input type="text"
                                    class="form-control rounded {{ $errors->has('titlesin') ? 'is-invalid' : '' }}"
                                    placeholder="Title" name="titlesin" value="{{ old('titlesin') }}"
                                    maxlength="20">
                            </div>
                        </div>
                    </div>
                    <!-- title -->

                    <!-- description -->
                    <div class="row border-top mt-2 pt-2 ">
                        <div class="col-3 px-0">
                            <h6 class="mb-0 ft-medium fs-md">Description</h6>
                        </div>
                        <div class="col-3 pe-0">
                            <div class="form-group">
                                <textarea name="descsin" class="form-control with-light {{ $errors->has('descsin') ? 'is-invalid' : '' }}"
                                    placeholder="Description" maxlength="30">{{ old('descsin') }}</textarea>
                            </div>
                        </div>

                    </div>
                    <!-- description -->

                    @foreach ($pageData['tiers_select'] as $a)
                        <div class="row border-top mt-2 pt-2 ">
                            <div class="col-3 px-0 ">
                                <h6 class="mb-0 ft-medium fs-md">{{ $a->name }}</h6>
                                <input type="hidden" name="tier_id[]" value="{{ $a->id }}">
                            </div>
                            <div class="col-3 text-center">
                                <div class="form-group">
                                    <select class="form-control" name="tier_service_value1[]">
                                        <option>select {{ $a->name }}</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="unlimited">Unlimited</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @foreach ($pageData['tiers_input'] as $a)
                        <div class="row border-top mt-2 pt-2 ">
                            <div class="col-3 px-0">
                                <h6 class="mb-0 ft-medium fs-md">{{ $a->name }}</h6>
                                <input type="hidden" name="tier_id[]" value="{{ $a->id }}">
                            </div>
                            <div class="col-3 text-center">
                                <div class="form-group">
                                    <div class="form-group">
                                        <input type="text" class="form-control rounded"
                                            placeholder="{{ $a->name }}" name="tier_service_value1[]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @foreach ($pageData['tiers_checkbox'] as $a)
                        <div class="row border-top mt-2 pt-2 ">
                            <div class="col-3 px-0">
                                <h6 class="mb-0 ft-medium fs-md">{{ $a->name }}</h6>
                                <input type="hidden" name="tier_id[]" value="{{ $a->id }}">
                            </div>
                            <div class="col-3 text-center">
                                <div class="form-group">
                                    {{-- Use keyed array [id] with value="yes" so store() can look up by tier ID as key --}}
                                    <input type="checkbox" name="tier_service_value1[{{ $a->id }}]" value="yes"
                                        {{ is_array(old('tier_service_value1')) && isset(old('tier_service_value1')[$a->id]) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Price -->
                    <div class="row border-top mt-2 pt-2 ">
                        <div class="col-3 px-0 pt-2">
                            <h6 class="mb-0 ft-medium fs-md">Price</h6>
                        </div>
                        <div class="col-3 pe-0">
                            <div class="form-group">
                                <input type="number"
                                    class="form-control rounded {{ $errors->has('pricesin') ? 'is-invalid' : '' }}"
                                    placeholder="Price" name="pricesin" value="{{ old('pricesin') }}">
                            </div>
                        </div>
                    </div>
                    <!-- Price -->
                    <!-- revisions -->
                    <div class="row border-top mt-2 pt-2 ">
                        <div class="col-3 px-0 pt-1">
                            <h6 class="mb-0 ft-medium fs-md">Revisions</h6>
                        </div>
                        <div class="col-3 pe-0">
                            <div class="form-group">
                                <select class="form-control" name="revisionin">
                                    <option>Select Revisions</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="unlimited">Unlimited</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <!-- revisions -->
                    <!-- Delivery Time -->
                    <div class="row border-top mt-2 pt-2 ">
                        <div class="col-3 px-0 pt-2">
                            <h6 class="mb-0 ft-medium fs-md">Delivery Time</h6>
                        </div>
                        <div class="col-3 pe-0">
                            <div class="form-group">
                                <input type="text"
                                    class="form-control rounded {{ $errors->has('timesin') ? 'is-invalid' : '' }}"
                                    placeholder="Delivery Time" name="timesin">
                            </div>
                        </div>
                    </div>
                    <!-- Delivery Time -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        let $tier1Checkboxes1 = $('input[type="checkbox"][name^="tier_service_value1"]').each(function() {
            $(this).val($(this).is(':checked') ? 'yes' : 'no');
        });
        let $tier1Checkboxes2 = $('input[type="checkbox"][name^="tier_service_value2"]').each(function() {
            $(this).val($(this).is(':checked') ? 'yes' : 'no');
        });
        let $tier1Checkboxes3 = $('input[type="checkbox"][name^="tier_service_value3"]').each(function() {
            $(this).val($(this).is(':checked') ? 'yes' : 'no');
        });

        $tier1Checkboxes1.change(function() {
            var tierId = $(this).attr('name').match(/\d+/)[0]; // Extract tier ID from name
            if ($(this).is(':checked')) {
                $(this).val('yes');
            } else {
                $(this).val('no');
            }
        });

        // Handle checkbox values for tier_service_value2
        $tier1Checkboxes2.change(function() {
            var tierId = $(this).attr('name').match(/\d+/)[0]; // Extract tier ID from name
            if ($(this).is(':checked')) {
                $(this).val('yes');
            } else {
                $(this).val('no');
            }
        });

        // Handle checkbox values for tier_service_value3
        $tier1Checkboxes3.change(function() {
            var tierId = $(this).attr('name').match(/\d+/)[0]; // Extract tier ID from name
            if ($(this).is(':checked')) {
                $(this).val('yes');
            } else {
                $(this).val('no');
            }
        });

        // Toggle between single/multi package
        $("#msval").change(function() {
            if (this.checked) {
                $("#a").css("display", "block");
                $("#b").css("display", "none");
            } else {
                $("#a").css("display", "none");
                $("#b").css("display", "block");
            }
        });

        // Form validation
        $('#serviceForm').on('submit', function(e) {
            const isMultiPackage = $('#msval').is(':checked');
            let isValid = true;

            // Validate based on package type
            if (isMultiPackage) {
                // Validate all 3 packages
                for (let i = 1; i <= 3; i++) {
                    if (!$('#t' + i).val().trim()) {
                        $('#t' + i).addClass('is-invalid');
                        isValid = false;
                    }
                    if (!$('textarea[name="desc' + i + '"]').val().trim()) {
                        $('textarea[name="desc' + i + '"]').addClass('is-invalid');
                        isValid = false;
                    }
                    if (!$('input[name="price' + i + '"]').val().trim()) {
                        $('input[name="price' + i + '"]').addClass('is-invalid');
                        isValid = false;
                    }
                    if (!$('input[name="time' + i + '"]').val().trim()) {
                        $('input[name="time' + i + '"]').addClass('is-invalid');
                        isValid = false;
                    }
                }
            } else {
                // Validate single package
                if (!$('input[name="titlesin"]').val().trim()) {
                    $('input[name="titlesin"]').addClass('is-invalid');
                    isValid = false;
                }
                if (!$('textarea[name="descsin"]').val().trim()) {
                    $('textarea[name="descsin"]').addClass('is-invalid');
                    isValid = false;
                }
                if (!$('input[name="pricesin"]').val().trim()) {
                    $('input[name="pricesin"]').addClass('is-invalid');
                    isValid = false;
                }
                if (!$('input[name="timesin"]').val().trim()) {
                    $('input[name="timesin"]').addClass('is-invalid');
                    isValid = false;
                }
            }

            if (!isValid) {
                e.preventDefault();
                $('.is-invalid').first().focus();
                //alert('Please fill all required fields marked with *');
            }
        });

        // Clear validation on input
        $('input, textarea').on('input', function() {
            $(this).removeClass('is-invalid');
        });

        // Clear validation on input
        $('.required-field').on('input', function() {
            if ($(this).val().trim()) {
                $(this).removeClass('is-invalid');
            }
        });
    });
</script>
