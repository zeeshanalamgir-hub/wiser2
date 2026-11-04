<style>
    .ntn-container-popular-categories {
      background-color: #EAF7F9;
      padding-top: 60px;
      padding-bottom: 40px
    }

    .ntn-cb-heading-5432653 {
      color: #009971;
      font-size: 42px
    }

    .ntn-jobs {
      display: flex;
      flex-wrap: wrap;
      flex-direction: column;
      align-items: center;
      justify-content: space-around;
    }

    .ntn-job-numbers {
      /* display: flex; */
      /* */
      justify-content: center;
      /*  height: 13px; */
      width: 30px;
      background-color: #0F5256;
      border-radius: 35px;
      /* margin-top: -15px;*/
    }

    .ntn-name {
      color: #1E1E1E;
      text-wrap: wrap;
      overflow: hidden;
      width: 170px;
    }
</style>
<div class="ntn-container-popular-categories">

    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <h1 class="font-weight-bold mb-2 ntn-cb-heading-5432653 poppins-bold">Popular Categories</h1>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-12 text-right">
                <span class="font-weight-light "
                    style="font-size: 28px; font-weight:300; margin-top: 20px !important;">Browse all categories
                    <img src="{{ asset('assets/wiserlance/assets/img/home/right-arrowgreen.png') }}"
                        style="position: relative;top: 1px;right: -4px;width: 26px;">
                </span>
            </div>
        </div>
        <div class="row text-center  mt-5 py-3">
            @foreach ($cat as $cate)
                @php
                    $ser = DB::table('jobs')
                        ->where('status', 1)
                        ->where('category_id', $cate->id)
                        ->count();
                @endphp
            @endforeach
            @foreach ($cat as $cate)
                @php
                    $ser = DB::table('jobs')
                        ->where('status', 1)
                        ->where('category_id', $cate->id)
                        ->count();
                @endphp
                <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4 ntn-jobs mt-4">
                    <div style="height:61px">
                        <img class="img-fluid mb-2" style="height:60px;"
                            src="{{ getImage('assets/images/category/' . $cate->image) }}">
                    </div>
                    <div style="height: 65px;display: flex;align-items: center;justify-content: center;">
                        <h6 class="ntn-name">{{ $cate->name }}</h6>
                    </div>
                    <div style="display: flex;height: 13px;justify-content: center;; align-items: center">
                        <div class="ntn-job-numbers">
                            <a href="{{ route('category.services', [encrypt($cate->id)]) }}" class="text-white" style="font-size: 10px; height: 13px">
                                 {{ $ser }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
