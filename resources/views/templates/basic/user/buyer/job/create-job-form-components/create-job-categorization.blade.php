<div class="racket-forms-outer">

    <div class="racket-forms-input-section">
        <div class="racket-forms-header-outer rounded">
            <div class="racket-forms-header">
                <div class="racket-form-heading poppins-semibold ffs-12">Categorization</div>
            </div>
        </div>
    </div>

    <div class="racket-forms-body py-2">
        <div class="racket-form-body-section">
            <div id="cat-data-6845">
                <div class="row">
                    <div class="col-6">
                        <div class="racket-form-body-section-heading poppins-light ffs-12">
                            Category
                        </div>
                        <div class="form-group">
                            <select class="form-control afacad-regular ffs-12" name="category" id="category" required="">
                            </select>
                        </div>
                        <div id="category_error" class="text-danger" style="display:none;"></div>
                    </div>

                    <div class="col-6">
                        <div class="racket-form-body-section-heading poppins-light ffs-12 ">
                            Subcategory
                        </div>
                        <div class="form-group">
                            <select name="subcategory" required=""
                                class="form-control afacad-regular ffs-12" id="subcategorys">
                            </select>
                            <div id="subcategory_error" class="text-danger" style="display:none;"></div>
                        </div>
                    </div>


                </div>
            </div>
        </div>

    </div>

    <!-- Features -->
    <div class="racket-forms-outer">

        <div class="racket-forms-input-section">
            <div class="racket-forms-header-outer rounded">
                <div class="racket-forms-header">
                    <div class="racket-form-heading poppins-semibold ffs-12">Features</div>
                </div>
            </div>
        </div>
        <div class="racket-forms-body py-2">
            <div class="racket-form-body-section">
                <div class="row">
                    <div class="col-12">
                        <div class="my-2">
                            <div id="skills_result" class="form-group"></div>
                            <div id="skills_error" class="text-danger" style="display:none;"></div>
                        </div>

                        <div class="racket-form-body-section-heading poppins-light ffs-12 ">
                            Choose features that best describe your Job.
                        </div>
                        <div class="border p-2">
                            <ul class="nav nav-tabs " role="tablist" id="result"></ul>
                            <div class="tab-content" id="demos"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="racket-forms-outer">
        <div class="racket-forms-input-section">
            <div class="racket-forms-header-outer rounded">
                <div class="racket-forms-header">
                    <div class="racket-form-heading poppins-semibold ffs-12">Keywords</div>
                </div>
            </div>
        </div>
        <div class="racket-forms-body py-2">
            <div class="racket-form-body-section">
                <div class="row">
                    <div class="col-12">
                        <div class="racket-form-body-section-heading poppins-light ffs-12 ">
                            Find pre-defined Skills or Type to add (Max. 5).
                        </div>
                        <div id="skills_result_extra" class="  form-group m-0 px-0">
                        </div>
                        <div style="    padding-left: 0px;    padding-right: 0px;">
                            <div class=" form-group m-0 search-box">
                                <input onkeyup="getSkillbuname(this.value)" id="search_skill"
                                    class="form-control" type="text" autocomplete="off" name="keyword"
                                    id="keyword" placeholder="Search ...">
                            </div>
                            <div id="keyword_error" class="text-danger" style="display:none;"></div>

                        </div>
                        <div id="skil_result" class=" form-group m-0">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>



</div>
