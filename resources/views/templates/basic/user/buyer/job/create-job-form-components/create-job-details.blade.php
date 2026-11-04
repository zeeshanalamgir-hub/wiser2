<div class="racket-forms-outer">

    <div class="racket-forms-input-section">
        <div class="racket-forms-header-outer rounded">
            <div class="racket-forms-header">
                <div class="racket-form-heading poppins-semibold ffs-12">Job Details</div>
            </div>
        </div>
    </div>
    <div class="racket-forms-body py-2">
        <div class="racket-form-body-section">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <div class="racket-form-body-section-heading poppins-light ffs-12">@lang('Title')
                        </div>
                        <input type="text" name="title" maxlength="255" min="1"
                            class="form-control afacad-regular ffs-12" id="title" placeholder="@lang('Title')">
                        <div id="job_title_error" class="text-danger" style="display: none"></div>
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <div class="racket-form-body-section-heading poppins-light ffs-12 ">
                        Description
                    </div>
                    <div class="form-group">
                        <textarea rows="7" class="form-control racket-forms-text-area nicEdit" id="description" name="description"></textarea>
                        <div id="description_error" class="text-danger" style="display:none;"></div>
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <div class="racket-form-body-section-heading poppins-light ffs-12 ">
                        Priority
                    </div>
                    <div class="form-group">
                        <select class="form-control afacad-regular ffs-12" name="priority" id="priority"
                            placeholder="Post as a*" required="">
                            {{-- <option value="" disabled {{ old('priority') == '' ? 'selected' : '' }}>Choose a
                                priority for your job.</option> --}}
                            <option value="1">Regular</option>
                            <option value="2">Featured</option>

                        </select>
                        <div id="priority_error" class="text-danger" style="display:none;"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
