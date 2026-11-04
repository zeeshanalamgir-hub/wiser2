<div class="header-top-buttons-10065">
    <a href="{{ route('user.job.vacancy') }}"
        class="btn rounded {{ $activeTab == 'openJob' ? 'sd-button-header-10068' : 'sd-button-header-10067' }}">
        Bids Submitted
    </a>

    <a href="{{ route('user.job.vacancy.offers') }}"
        class="btn rounded {{ $activeTab == 'offerRecived' ? 'sd-button-header-10068' : 'sd-button-header-10067' }}">
        Offeres Received
    </a>
    <a href="{{ route('user.job.vacancy.completed') }}"
        class="btn rounded {{ $activeTab == 'completeJobs' ? 'sd-button-header-10068' : 'sd-button-header-10067' }}">
        Jobs Completed
    </a>
</div>

