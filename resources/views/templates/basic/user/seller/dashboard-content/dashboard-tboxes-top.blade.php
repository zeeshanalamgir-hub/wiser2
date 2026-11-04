@php
$tboxes = [
        [
            'imageIcon' => 'assets/wiserlance/assets/img/home/setting.png',
            'title' => 'Available Bids',
            'numberCount' => $availableBids
        ],
        [
            'imageIcon' => 'assets/wiserlance/assets/img/home/total.png',
            'title' => 'Total Services',
            'numberCount' => $normalService + $featuredService
        ],
        [
            'imageIcon' => 'assets/wiserlance/assets/img/home/ongoing-service.png',
            'title' => 'Normal Sevices',
            'numberCount' => $normalService
        ],
        [
            'imageIcon' => 'assets/wiserlance/assets/img/home/Total-jobs.png',
            'title' => 'Portfolio Slots',
            'numberCount' => $portfolioSize
        ],
        [
            'imageIcon' => 'assets/wiserlance/assets/img/home/Active-Jobs.png',
            'title' => 'Featured Sevices',
            'numberCount' => $featuredService
        ],
        [
            'imageIcon' => 'assets/wiserlance/assets/img/home/cuurent-balance.png',
            'title' => 'Current Balance',
            'numberCount' => $balance,
            'currency' => true
        ],
        [
            'imageIcon' => 'assets/wiserlance/assets/img/home/cash.png',
            'title' => 'Total Service Booking',
            'numberCount' => 0
        ],
        [
            'imageIcon' => 'assets/wiserlance/assets/img/home/available-jobs.png',
            'title' => 'Total Withdraw',
            'numberCount' => $withdraw,
            'currency' => true
        ],
        [
            'imageIcon' => 'assets/wiserlance/assets/img/home/regular-service.png',
            'title' => 'Proposals history',
            'numberCount' => $proposalHistory
        ],
        [
            'imageIcon' => 'assets/wiserlance/assets/img/home/completedJobs.png',
            'title' => 'Completed Jobs',
            'numberCount' => $completedjobs
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
