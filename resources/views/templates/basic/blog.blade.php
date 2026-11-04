@extends($activeTemplate.'layouts.wiserlance.layout')
@section('content')
<!-- ======================= Searchbar Banner ======================== -->
<!-- 			<div class="bg-cover py-5" style="background:#28b661 url(assets/img/bn-2.jpg) no-repeat;" data-overlay="2">
		<div class="ht-200"></div>
</div> -->
<!-- ======================= Searchbar Banner ======================== -->

<section class="middle">
	<div class="container">
		
		<div class="row justify-content-center">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				<div class="sec_title position-relative text-center mb-5">
					<h6 class="text-muted mb-0">News Updates</h6>
					<h2 class="ft-bold">Latest<span class="theme-cl">News</span></h2>
				</div>
			</div>
		</div>
		
		<div class="row">
			
			
			@foreach($blogs as $blog)
			<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
				<div class="_blog_wrap">
					<div class="_blog_thumb mb-2">
						<a href="{{ route('blog.details',[$blog->id,slug($blog->data_values->title)]) }}" class="d-block"><img src="{{ getImage('assets/images/frontend/blog/thumb_'.$blog->data_values->blog_image_1,'728x465') }}" class="img-fluid rounded" alt="blog" width="100%" /></a>
					</div>
					<div class="_blog_caption">
						<span class="text-muted">{{showDateTime($blog->created_at, 'd')}} {{showDateTime($blog->created_at, 'M')}} {{showDateTime($blog->created_at, 'Y')}}</span>
						<h5 class="bl_title lh-1"><a href="{{ route('blog.details',[$blog->id,slug($blog->data_values->title)]) }}">{{__($blog->data_values->title)}}</a></h5>
						<p>{{str_limit(strip_tags(@$blog->data_values->description), 100)}}</p>
						<a href="{{ route('blog.details',[$blog->id,slug($blog->data_values->title)]) }}" class="text-dark fs-sm">@lang('Read More')</a>
					</div>
				</div>
			</div>
			
			@endforeach
		</div>
		
		<!-- <div class="row justify-content-center">
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
						<div class="position-relative text-center">
								<a href="javascript:void(0);" class="btn stretched-link borders">Load More Blogs<i class="lni lni-arrow-right ml-2"></i></a>
						</div>
				</div>
		</div> -->
		
	</div>
</section>
@endsection
@section('script')
@endsection