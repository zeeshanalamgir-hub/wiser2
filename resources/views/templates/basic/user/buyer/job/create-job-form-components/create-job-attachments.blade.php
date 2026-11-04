<div class="racket-forms-outer">

    <div class="racket-forms-input-section">
        <div class="racket-forms-header-outer rounded">
            <div class="racket-forms-header">
                <div class="racket-form-heading poppins-semibold ffs-12">Attachments</div>
            </div>
        </div>
    </div>

    <div class="racket-forms-body py-2">
        <div class="racket-form-body-section">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="racket-form-body-section-heading poppins-light ffs-12">
                        Attachment.
                    </div>
                    <div class="racket-attachment-box-outer">
                        <div class="racket-attachment-file-name-outer"><span id="racket-attachment-file-name" class="poppins-light ffs-12">Choose file (png, jpg, pdf, doc)</span></div>
                        <div class="racket-attachment-file-icon">
                            <img src="{{ asset('assets/wiserlance/assets/img/icons/upload-icon.png') }}" id="upload-icon">
                        </div>
                        <input type="file" name="file[]" class="" id="attachment-files" style="display: none" multiple>
                    </div>
                    <div id="selected-files-list" class="poppins-light ffs-12"></div>
                    <div class="racket-attachment-hint-below poppins-light ffs-12">Note: Maximum 5 files can be uploaded. Maximum file size should be 250M</div>
                </div>

                <div id="file_error" class="text-danger" style="display:none;">
                </div>
            </div>
        </div>
    </div>

</div>

