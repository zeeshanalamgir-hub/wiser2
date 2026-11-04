@extends($activeTemplate.'layouts.wiserlance.layout')
@section('content')
@php
    $content = getContent('contact_us.content', true);
@endphp
<!-- ======================= Searchbar Banner ======================== -->
           <!--  <div class="bg-cover py-5" style="background:#28b661 url(assets/img/bn-2.jpg) no-repeat;" data-overlay="2">
                <div class="ht-200"></div>
            </div> -->
            <!-- ======================= Searchbar Banner ======================== -->
            
                <section class="middle">
                <div class="container">
                
                        <div class="row justify-content-center">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="sec_title position-relative text-center mb-5">
<h6 class="text-muted mb-0">Send us your feedback</h6>
<h2 class="ft-bold">Contact<span class="theme-cl">Us</span></h2>
</div>
</div>
</div>
                    
                    <div class="row align-items-start justify-content-between">
                    
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                            <div class="card-wrap-body mb-4 gray rounded p-3">
                                <h4 class="ft-medium mb-3 theme-cl">@lang('Address')</h4>
                                <p>{{__($content->data_values->contact_details)}}</p>
                                <p class="lh-1"><span class="text-dark ft-medium">@lang('Email Address'):</span> {{__($content->data_values->email_address)}}</p>
                            </div>
                            
                            <div class="card-wrap-body mb-3 gray rounded p-3">
                                <h4 class="ft-medium mb-3 theme-cl">@lang('Phone Number')</h4>
                                <h6 class="ft-medium mb-1">Customer Care:</h6>
                                <p class="mb-2">{{__($content->data_values->contact_number)}}</p>
                                <h6 class="ft-medium mb-1">Careers:</h6>
                                <p>{{__($content->data_values->contact_number)}}</p>
                            </div>
                            
                            <div class="card-wrap-body mb-3 gray rounded p-3">
                                <h4 class="ft-medium mb-3 theme-cl">@lang('Email Address')</h4>
                                <p>Fill out our form and we will contact you within 24 hours.</p>
                                <p class="lh-1 text-dark">{{__($content->data_values->email_address)}}</p>
                            </div>
                        </div>
                        
                        <div class="col-xl-7 col-lg-8 col-md-12 col-sm-12">
                            <form class="row contact-form" method="post" action="">
                                @csrf
                                    
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label class="small text-dark ft-medium">Your Name *</label>
                                         <input type="text" class="form-control" value="@if(auth()->user()) {{ auth()->user()->fullname }} @else {{ old('name') }} @endif" class="form-control" placeholder="@lang('Enter name')" required @if(auth()->user()) readonly @endif>
                                    </div>
                                </div>
                                
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label class="small text-dark ft-medium">Your Email *</label>
                                        <input ype="email" name="email" value="@if(auth()->user()) {{ auth()->user()->email }} @else {{old('email')}} @endif"  @if(auth()->user()) readonly @endif class="form-control" placeholder="@lang('Enter email')" required>
                                    </div>
                                </div>
                                
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label class="small text-dark ft-medium">Subject</label>
                                        <input type="text" name="subject" class="form-control"
                                            placeholder="@lang('Enter subject')" value="{{old('subject')}}" required>
                                    </div>
                                </div>
                                
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label class="small text-dark ft-medium">{{old('message')}}</label>
                                        <textarea  name="message" placeholder="@lang('Enter Message')" required class="form-control ht-80">{{old('message')}}</textarea>
                                    </div>
                                </div>
                                
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-dark">@lang('Send Message')</button>
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                        
                    </div>
                </div>
            </section>

@endsection
@section('script')

@endsection