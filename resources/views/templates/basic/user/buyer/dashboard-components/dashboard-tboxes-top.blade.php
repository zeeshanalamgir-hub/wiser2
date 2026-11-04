@php
    $tboxes = [
        [
            'imageIcon' => 'assets/wiserlance/assets/img/home/Total-jobs.png',
            'title' => 'Total jobs',
            'numberCount' => $totalJob
        ],
        [
            'imageIcon' => 'assets/wiserlance/assets/img/home/setting.png',
            'title' => 'Total Hiring',
            'numberCount' => $hireEmploys
        ],
        [
            'imageIcon' => 'assets/wiserlance/assets/img/home/total.png',
            'title' => 'Booked Services ',
            'numberCount' => $serviceBookings
        ],
        [
            'imageIcon' => 'assets/wiserlance/assets/img/home/ongoing-service.png',
            'title' => 'Ongoing Services',
            'numberCount' => $ongoingService
        ],
        [
            'imageIcon' => 'assets/wiserlance/assets/img/home/Active-Jobs.png',
            'title' => 'Ongoing Jobs',
            'numberCount' => $ongoingJob
        ],
        [
            'imageIcon' => 'assets/wiserlance/assets/img/home/cuurent-balance.png',
            'title' => 'Current Balance',
            'numberCount' => $currentBalance,
            'currency' => true
        ],
        [
            'imageIcon' => 'assets/wiserlance/assets/img/home/cash.png',
            'title' => 'Total Transactions',
            'numberCount' => $totaltransactions,
            'currency' => true
        ],
        [
            'imageIcon' => 'assets/wiserlance/assets/img/home/available-jobs.png',
            'title' => 'Available Jobs',
            'numberCount' => $availableJob >= 100000 ? 'Unlimited' : $availableJob
        ],
        [
            'imageIcon' => 'assets/wiserlance/assets/img/home/regular-service.png',
            'title' => 'Regular Job Quote',
            'numberCount' => $regularJob >= 100000 ? 'Unlimited' : $regularJob
        ],
        [
            'imageIcon' => 'assets/wiserlance/assets/img/home/Total-jobs.png',
            'title' => 'Featured Job Quote',
            'numberCount' => $featuredJob
        ],
        [
            'imageIcon' => 'assets/wiserlance/assets/img/home/Total-jobs.png',
            'title' => 'Completed Jobs',
            'numberCount' => $completedJobs
        ],
    ];

@endphp


@foreach ($tboxes as $tbox)
    <div class="col-6 col-md-4 col-lg-2 mb-3">
        <div class="card h-100 p-3 shadow-sm border-green d-flex flex-column align-items-center justify-content-center">
            <img src="{{ asset($tbox['imageIcon']) }}" height="50" class="opacity-5 mb-2" alt="">

            <h6 class="fs-14 text-center mb-2">@lang($tbox['title'])</h6>

            <span class="badge-count bg-dgreen2 py-1 px-3 rounded text-white d-inline-block text-center">
                @if(isset($tbox['currency']) && $tbox['currency'])
                    ${{ $tbox['numberCount'] }}
                @else
                    {{ $tbox['numberCount'] }}
                @endif
            </span>
        </div>
    </div>
@endforeach

