<div class="col-xl-3 col-lg-3 mb-30">
    <div class="sidebar">
        <div class="widget mb-30">
            <h3 class="widget-title">@lang('CATEGORIES')</h3>
            <ul class="category-list">
                @foreach($categorys as $category)
                    <li><a href="{{route('service.category', [slug($category->name),$category->id])}}">{{__($category->name)}}</a></li>
                @endforeach
            </ul>
        </div>

        <form action="" method="GET">
            <div class="widget mb-30">
                <h3 class="widget-title">@lang('FILTER BY LEVEL')</h3>
                @foreach($ranks as $rank)
                    <div class="form-group custom-check-group">
                        <input type="checkbox" id="{{$rank->id}}.'s'" name="level[]" value="{{$rank->id}}" class="userLevel" 
                        @if(!empty($level))
                            @foreach($level as $val)
                                @if($val == $rank->id)
                                    checked
                                @endif
                            @endforeach
                        @endif
                        >
                        <label for="{{$rank->id}}.'s'">{{__($rank->level)}}</label>
                    </div>
                @endforeach
            </div>

            <div class="widget mb-30">
                <h3 class="widget-title">@lang('SERVICE INCLUDES')</h3>
                @foreach($features as $feature)
                    <div class="form-group custom-check-group">
                        <input type="checkbox" id="{{$feature->id}}.'f'" name="feature[]" value="{{$feature->id}}" class="featureService"
                        @if(!empty($featuresData))
                            @foreach($featuresData as $val)
                                @if($val == $feature->id)
                                    checked
                                @endif
                            @endforeach
                        @endif
                    >
                        <label for="{{$feature->id}}.'f'">{{__($feature->name)}}</label>
                    </div>
                @endforeach
            </div>

            <div class="widget mb-30">
                <h3 class="widget-title">@lang('FILTER BY PRICE')</h3>
                <div class="widget-range-area">
                    <div id="slider-range"></div>
                    <div class="price-range">
                        <div class="filter-btn">
                            <button type="submit" class="btn--base active">@lang('Filter Now')</button>
                        </div>
                        <input type="text" id="amount" name="price" readonly>
                    </div>
                </div>
            </div>
        </form>

        @include($activeTemplate.'partials.left_ad')

        <div class="widget mb-30">
            <h3 class="widget-title">@lang('FEATURED SERVICE')</h3>
            <ul class="small-item-list" id="featuredService">
                @foreach($fservices as $ser)
                    <li class="small-single-item">
                        <div class="thumb">
                            <img src="{{getImage('assets/images/service/'.$ser->image, imagePath()['service']['size']) }}" alt="@lang('service image')">
                        </div>
                        <div class="content">
                            <h5 class="title"><a href="{{route('service.details', [slug($ser->title), encrypt($ser->id)])}}">{{__($ser->title)}}</a></h5>
                            <div class="ratings">
                                <i class="fas fa-star text--warning"></i>
                                <span class="rating">({{$ser->rating}})</span>
                                <p class="author-like d-inline-flex flex-wrap align-items-center ms-2"><span class="las la-thumbs-up text--base"></span> ({{__($ser->likes)}})</p>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="widget-btn text-center mb-30">
            @if($fservices->total() > 4)
                <a href="javascript:void(0)" class="btn--base readMore" data-page="2" data-link="{{route('home')}}?page=">@lang('Show More')</a>
            @endif
        </div>

        @include($activeTemplate.'partials.left_ad')
    </div>
</div>
@push('script')
<script>
    'use strict';
    $('.userLevel').on('click', function(){
        this.form.submit();
    });
    $('.featureService').on('click', function(){
        this.form.submit();
    });
    
    $('.readMore').on('click',function(){
       var link = $(this).data('link');
       var page = $(this).data('page');
       var href = link + page;
       var featuredServiceCount = {{$fservices->total()}};
       $.get(href, function(response){
            var html = $(response).find("#featuredService").html();
            $("#featuredService").append(html);
            var loadMoreCount = 4 * page;
            if(loadMoreCount >= featuredServiceCount){
                $('.readMore').hide()
            }
       });
       $(this).data('page', (parseInt(page) +1));
        
    });

    @if(session()->has('range'))
        var data1 = {{session('range')[0]}};
        var data2 = {{session('range')[1]}};
    @else
        var data1 = 0;
        var data2 = {{$general->search_max}};
    @endif  
       
    $("#slider-range").slider({
      range: true,
      min: 0,
      max: {{$general->search_max}},
      values: [data1, data2],
      slide: function (event, ui) {
        $("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
        $('input[name=min_price]').val(ui.values[0]);
        $('input[name=max_price]').val(ui.values[1]);
      },
      change: function () {
        var brand = [];
        $('.brand-filter input:checked').each(function () {
          brand.push(parseInt($(this).attr('value')));
        });
      }
    });
    $("#amount").val("$" + $("#slider-range").slider("values", 0) + " - $" + $("#slider-range").slider("values", 1));
</script>
@endpush