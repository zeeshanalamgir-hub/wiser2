<style type="text/css">
    /* #new:hover {
        border: 1px solid transparent !important;
    } */

    #new:focus {
        border: 1px solid transparent !important;
    }

    .navigation-landscape {
        /* background-color: #003F45; */
        padding-left: 20px;
        border-radius: 5px;
    }

    .nav-menu {
        color: white;

    }

    .language-select {
        background: none;
        color: white;
    }


    .bg-img {
        background-image: url(./../img/background.png);
        object-fit: cover;
        /* opacity: 80px; */
        background-color: #0055ff;
    }


    .ntn-content {}
</style>
    
        <div class="row align-items-start">
            <div class="col-lg-7">
                <form action="{{ url('/search') }}"method="get" enctype="multipart/form-data">
                    @csrf
                    <h1 style="font-size:52px; line-height: 1em; color:#037e89; font-family: Poppins, sans-serif;"
                        class="mt-5 font-weight-bold">@lang('banner1') <br> @lang('banner2') <br> @lang('banner3')</h1>
                    <div class="no-gutters mt-5">
                        <h3 class="font-weight-normal fs-5 text-green mb-3"> @lang('searchhead')</h3>
                        
                        <!--Search Bar-->
                        
                        <div class="d-flex">
                            <div class="col-7" style="display: flex; border-radius: 5px; border:1px solid #ccc; height:40px; justify-content:left; background-color:#EAF7F9; align-items: center;">
                                <img style="height: 23px" src="assets/wiserlance/assets/img/home/search.png" alt="First slide">
                                <input type="text" name="keyword" class="form-control align-bottom" id="new" name="" placeholder="I am looking for!"
                                style=" border :none; height: 25px; background-color:#EAF7F9; font-size:14px; font-weight:400; color:#0F5256; font-family:Poppins, sans-serif; width:300px;">
                            </div>
                            <div class="col-lg-2">
                                <button name="type" id="new"
                                    style="border-radius:5px; background-color: #037e89; height:40px;  display: flex; align-items: center; justify-content: center; font-size:18px"
                                    class=" form-control  text-white">
                                    Jobs
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-5 d-flex pt-4" >
                            <button class="btn btn-sm btn-outline-secondary py-1 px-2 m-2"> IT </button>
                            <button class="btn btn-sm btn-outline-secondary py-1 px-2 m-2"> Logo Design </button>
                            <button class="btn btn-sm btn-outline-secondary py-1 px-2 m-2"> Web Site Developer </button>
                            <button class="btn btn-sm btn-outline-secondary py-1 px-2 m-2">  Data Entry </button>
                            <button class="btn btn-sm btn-outline-secondary py-1 px-2 m-2">  Coding </button>        
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-5">
                <img class="img-fluid" src="{{ asset('assets/wiserlance/assets/img/home/banner.png') }}" alt="First slide">
            </div>
        </div>
</div>   

