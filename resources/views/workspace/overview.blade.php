<div class="row ">
    <h5 class="mb-3 text-dgreen">Status Summary</h5>

    <div class="col-md-3 text-center">
        <div class="card shadow-sm">
            @if($job_type == 'hourly')
                <div class="card-header bg-lsgreen">Hourly Rate</div>
                <div class="card-body fs-3 p-4">${{ $totalBudget }}/hr</div>
            @else
                <div class="card-header bg-lsgreen">Budget</div>
                <div class="card-body fs-3 p-4">${{ number_format($totalBudget, 2) }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-3 text-center mt-4 mt-md-0">
        <div class="card shadow-sm">
            <div class="card-header bg-lsgreen">Pending</div>
            <div class="card-body fs-3 p-4">${{ number_format($pendingPayment,2) }}</div>
        </div>
    </div>

    <div class="col-md-3 text-center mt-4 mt-md-0">
        <div class="card shadow-sm">
            <div class="card-header bg-lsgreen">Paid</div>
            <div class="card-body fs-3 p-4">${{ number_format($paidAmount + $bonusAmount,2) ?? 0 }}</div>
            <!-- Using $paidAmount dynamically -->
        </div>
    </div>

    <div class="col-md-3 text-center mt-4 mt-md-0">
        <div class="card shadow-sm">
            <div class="card-header bg-lsgreen">Remaining</div>
            <div class="card-body fs-3 p-4">${{ number_format($remainingAmount,2) }}</div> <!-- Using $remainingAmount dynamically -->
        </div>
    </div>
</div>
