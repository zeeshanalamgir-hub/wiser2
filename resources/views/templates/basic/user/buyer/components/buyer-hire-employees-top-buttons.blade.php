<div class="header-top-buttons-10065">
    <a href="{{ route('user.buyer.hire.employ') }}"
        class="btn rounded {{ $activeTab == 'openJob' ? 'sd-button-header-10068' : 'sd-button-header-10067' }}">
        Open jobs
    </a>
    <a href="{{ route('user.buyer.offered.employ') }}"
        class="btn rounded {{ $activeTab == 'offeredJobs' ? 'sd-button-header-10068' : 'sd-button-header-10067' }}">
        Offered jobs
    </a>
    <a href="{{ route('user.buyer.hire.employ.completed') }}"
        class="btn rounded {{ $activeTab == 'comletedJobs' ? 'sd-button-header-10068' : 'sd-button-header-10067' }}">
        Completed
    </a>
    <a href="{{ route('user.buyer.hire.employ.closed') }}"
        class="btn rounded {{ $activeTab == 'closedJob' ? 'sd-button-header-10068' : 'sd-button-header-10067' }}">
        Closed
    </a>
</div>
