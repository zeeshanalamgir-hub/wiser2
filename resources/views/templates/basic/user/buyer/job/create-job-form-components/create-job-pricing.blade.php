<div class="racket-forms-outer">
    <div class="racket-forms-input-section">
        <div class="racket-forms-header-outer">
            <div class="racket-forms-header">
                <div class="racket-form-heading poppins-semibold ffs-12">PRICING</div>
            </div>
        </div>
    </div>
    <div class="racket-forms-body py-2">
        <div class="racket-form-body-section">

            <div class="row">
                <div class="col-6">
                    <div class="mx-0">
                        <div class="form-group m-0">
                            <select class="form-control rounded" name="exp" id="exp">
                                <option value="1">Entry Level</option>
                                <option value="2">Intermediate</option>
                                <option value="3">Expert</option>
                            </select>
                        </div>
                        <div id="exp_error" class="text-danger" style="display:none;"></div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mx-0">
                        <div class="form-group m-0">
                            <input type="number" class="form-control" min="0" step="1" name="delivery" id="delivery" placeholder="@lang('Delivery Time')">
                        </div>
                        <div id="delivery_error" class="text-danger" style="display:none;"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <select class="form-control" id="budgettype" name="budgettype">
                            <option value="fixed">Fixed</option>
                            <option value="per_hour">Per Hour</option>
                        </select>
                    </div>
                    <div id="budget_type_error" class="text-danger" style="display:none;"></div>
                </div>
                <div class="col-6 budget-field-outer">
                    <div class="form-group m-0">
                        <input type="number" class="form-control" min="0" step="1" id="amount" name="amount" placeholder="@lang('Enter Budget')">
                    </div>
                    <div id="amount_error" class="text-danger" style="display:none;"></div>
                </div>
            </div>
            <div class="row min-max-field-outer">
                <div class="col-6">
                    <div class="mx-0">
                        <div class=" form-group m-0">
                            <input type="number" class="form-control" min="0" step="1"
                                id="minBudget" name="minBudget" placeholder="@lang('Enter Min Budget')">
                        </div>
                        <div id="minBudget_error" class="text-danger" style="display:none;">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mx-0">
                        <div class=" form-group m-0">
                            <input type="number" class="form-control" min="0" step="1"
                                name="maxBudget" id="maxBudget" placeholder="@lang('Enter Max Budget')">
                        </div>
                        <div id="maxBudget_error" class="text-danger" style="display:none;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

            </div>
        </div>
    </div>

</div>
