<style>
    .searchtop {
        /* height: 35px !important;
        padding: 10px 15px;
        width: 250px;
        font-size: 15px;
        border-radius: 1px;

        border-color: blue; */
    }

    /* .searchtop:focus {
        border-color: #0cb48c !important;
    }

    .searchtop:hover {
        border-color: #0cb48c !important;
    } */

    .ntn-header-search-6789587 {
        position: relative;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: flex-end;
        margin: 0;
    }

    .ntn-header-search-6789587 .search-btn {
        /* position: absolute;
        left: 720px;
        background: transparent;
        border: none;
        top: 12px */
    }

    .ntn-header-search-6789587 .search-input-6789 {}

    .ntn-header-search-6789587 .search-select-6789 {
        position: absolute;
        right: 213px;
        border-radius: 5px;
        top: 20px;
        background: #0cb48c !important;
        color: white;
        border-color: #0cb48c !important;
    }

    .searchtopss1 {
        position: absolute;
        right: -15px;
        background: transparent;
        border: none;
        top: 0px
    }

    .bigSearchIcon {
        background: none;
        padding: 0;
    }

    .bigSearchIcon>img {
        height: 16px;
    }

    .ntn-header-search-6789587 .search-box-outer-6879567{
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
        gap: 8px;
        align-items: center;
        /* border: 1px solid green; */
        border-radius: 5px;
        box-shadow: 0 0 3px var(--secondary-color);
        padding: 6px;
    }

    .ntn-header-search-6789587 .search-box-outer-6879567 > input{
        border: none;
        width: 120px;
    }
    .ntn-header-search-6789587 .search-box-outer-6879567 > input:focus{
        border: none;
    }

    .ntn-header-search-6789587 .search-box-outer-6879567 > .searchtopss-select{
        border: none;
        background-color: var(--dark-green);
        color: var(--white-color);
        width: 80px;
        padding: 6px;
        border-radius: 5px;
    }

    .ntn-header-search-6789587 .search-box-outer-6879567 > .search-icon-679687895789 {
        height: 16px;
    }

</style>

<form action="{{ url('/search') }}" method="get" enctype="multipart/form-data" class="ntn-header-search-6789587">
    @csrf
    <div class="search-box-outer-6879567 showhide">
        <img class="search-icon-679687895789 showhide" src={{ asset('assets/wiserlance/assets/img/logos/search-gray.svg')  }} />
        <input type="text" name="keyword" class="searchtop showhide" placeholder="I am looking for ..."
            aria-label="Username" aria-describedby="addon-wrapping">
        <select name="type" class=" searchtopss-select showhide autoheader1 autoheaderwidth1 ntn-poppins" >
            <option value="services">@lang('Services')</option>
            <option value="jobs">@lang('Jobs')</option>
            <option value="professionals">@lang('Professionals')</option>
            <option value="corporate">@lang('Corporate')</option>
        </select>
    </div>

    <button type="submit" class="searchtopss bigSearchIcon btn" style="">
        <img src="{{ asset('assets/wiserlance/assets/img/logos/search-white.svg') }}">
    </button>
</form>
