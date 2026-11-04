<div class="row pt-3 px-4">
    {{--  <p class="pb-2 fs-14">
        Start date: <span><b>{{ \Carbon\Carbon::parse($startDate)->format('d M, Y') }}</b></span> <!-- Using $startDate dynamically -->
        <span class="ms-5 ps-5">Status: </span>
        <b class="text-green">{{ $status }}</b> <!-- Using $status dynamically -->
    </p>  --}}
    <h5 class="mb-3 text-dgreen">Status Summary</h5>


    <div class="col-md-2 text-center">
        <div class="card shadow-sm">
            @if($job_type == 'hourly')
                <div class="card-header bg-lsgreen"><span style="font-size: 15px">Hourly Rate</span></div>
                <div class="card-body fs-3 p-4"><span style="font-size: 25px">${{ $totalBudget }}/hr</span></div>
            @else
                <div class="card-header bg-lsgreen"><span style="font-size: 15px">Budget</span></div>
                <div class="card-body fs-3 p-4"><span style="font-size: 25px">${{ number_format($totalBudget,2) }}</span></div>
            @endif
        </div>
    </div>

    <div class="col-md-3 text-center mt-4 mt-md-0">
        <div class="card shadow-sm">
            <div class="card-header bg-lsgreen"><span style="font-size: 15px">Pending payment</span></div>
            <div class="card-body fs-3 p-4"><span style="font-size: 25px">${{ number_format($pendingPayment,2) }}</span></div> <!-- Using $pendingPayment dynamically -->
        </div>
    </div>

    <div class="col-md-2 text-center mt-4 mt-md-0">
        <div class="card shadow-sm">
            <div class="card-header bg-lsgreen"><span style="font-size: 15px">Paid</span></div>
            <div class="card-body fs-3 p-4"><span style="font-size: 25px">${{ number_format($paidAmount + $bonusAmount,2) ?? 0}}</span></div> <!-- Using $paidAmount dynamically -->
        </div>
    </div>

    <div class="col-md-2 text-center mt-4 mt-md-0">
        <div class="card shadow-sm">
            <div class="card-header bg-lsgreen"><span style="font-size: 15px">Remaining</span></div>
            <div class="card-body fs-3 p-4"><span style="font-size: 25px">${{ number_format($remainingAmount,2) }}</span></div> <!-- Using $remainingAmount dynamically -->
        </div>
    </div>
    <div class="col-md-3 text-center mt-4 mt-md-0">
        <div class="card shadow-sm">
            <div class="card-header bg-lsgreen"><span style="font-size: 15px">Platform Commission</span></div>
            <div class="card-body fs-3 p-4"><span style="font-size: 25px">${{ number_format($commissionAmount,2) }}</span></div> <!-- Using $remainingAmount dynamically -->
        </div>
    </div>
</div>
{{-- <div class="row pt-2 px-4">
    <div class="col-md-3 text-center mt-4 mt-md-0">
        <div class="card shadow-sm">
            <div class="card-header bg-lsgreen">Platform Commission</div>
            <div class="card-body fs-3 p-4">${{ $commissionAmount }}</div> <!-- Using $remainingAmount dynamically -->
        </div>
    </div>
</div> --}}
