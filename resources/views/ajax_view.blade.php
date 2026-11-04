 <style type="text/css">
                                    .hide{
                                        display: none !important;
                                    }
                                    .show{
                                        display: block !important;
                                    }
                                </style>
@if(isset($param) && $param == 'category')
	@foreach($content_data as $key => $item)
		<li class="subcategory-list" id="s-list-{{$item->id}}">
			<input  data-target="#subcat" id="subcat-box-{{ $item->id }}" class="abc checkbox-custom subcategory-checkbox subcategory-checkboxes subcategory-checkboxes-{{ $item->category_id }}" type="checkbox" value="{{ $item->id }}" data-name="{{$item->name }}" data-id="{{$item->id }}" name="{{ $item->id }}" data-cat_id="{{ $item->category_id }}" />
			<label for="subcat-box-{{ $item->id }}" class="checkbox-custom-label"> {{$item->name }}</label>
		</li>
	@endforeach
@endif
@if(isset($param) && $param == 'subcategory')
	@foreach($content_data as $key => $item)
	<div class="single_search_boxed px-4 pt-0 br-bottom childcategory-div childcategory-div-category-{{ $item->category_id }} childcategory-div-subcategory-{{ $item->sub_category_id }}" >
		<div class="widget-boxed-header">
			<h4>
			<a href="#skillscat{{$item->id}}" data-toggle="collapse" aria-expanded="false" role="button" class="ft-medium fs-md pb-0 collapsed skillss" id="skillcattxt-{{$item->id}}">{{ $item->name }}</a>
			</h4>
		</div>
		<div class="widget-boxed-body collapse" id="skillscat{{$item->id}}" data-parent="#skills">
			<div class="side-list no-border">
				<!-- Single Filter Card -->
				<div class="single_filter_card">
					<div class="card-body p-0">
						<div class="inner_widget_link">
							<ul class="no-ul-list filter-list ">
								@php
								$skills = DB::table('skills')->where('skill_category_id', $item->id)->get();
								@endphp
								@foreach($skills as $skey => $sitem)
								<li>
									<input id="childcat-box-{{$sitem->id}}" class="checkbox-custom skills-checkbox skill-checkboxes skillstext" type="checkbox" data-pid="{{$item->id}}" data-name="{{$sitem->name}}" value="{{$sitem->id}}" name="{{$sitem->id}}">
									<label for="childcat-box-{{$sitem->id}}" class="checkbox-custom-label"> {{$sitem->name}}</label>
								</li>
								@endforeach
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endforeach
@endif
<script type="text/javascript">
	          $('.subcategory-checkbox').on('change', function() {
    $('.subcategory-checkbox').not(this).prop('checked', false);  
});
	           $('.skills-checkbox').on('change', function() {
    $('.skills-checkbox').not(this).prop('checked', false);  
});
	          $(document).on('click','.skills-checkbox',function(){
   var $this = $(this);
     var name = $this.attr('data-name');
     var pid = $this.attr('data-pid');
        var firstDropVall = $('#skillcattxt-'+pid).text(name);
        
   
    });



</script>
