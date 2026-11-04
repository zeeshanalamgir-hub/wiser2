@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                	<form action="{{route('admin.ads.update', $ads->id)}}" method="POST" enctype="multipart/form-data">
                		@csrf
                		<div class="row">
	                		<div class="col-lg-6">
		                		<div class="form-group">
		                			<label for="name" class="font-weight-bold">@lang('Name')</label>
		                			<input type="text" name="name" value="{{$ads->name}}" required="" class="form-control form-control-lg">
		                		</div>
		                	</div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="size" class="font-weight-bold">@lang('Select Ad Size')</label>
                                    <select class="form-control form-control-lg" name="size" id="size">
                                        @if($ads->size == "300x250")
                                            <option value="300x250" selected="">@lang('300x250')</option>
                                            <option value="728x90">@lang('728x90')</option>
                                        @elseif($ads->size == "728x90")
                                            <option value="300x250">@lang('300x250')</option>
                                            <option value="728x90" selected="">@lang('728x90')</option>
                                        @else
                                            <option>@lang('Select One')</option>
                                            <option value="300x250">@lang('300x250')</option>
                                            <option value="728x90">@lang('728x90')</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
		                </div>


		                  @if($ads->type == 1)
		                	<div class="row">
		                        <div class="col-lg-4">
		                            <div class="form-group ru">
		                                <label for="redirect_url" class="font-weight-bold">@lang('Redirect Url')</label>
		                                <input type="text" class="form-control form-control-lg" name="redirect_url" placeholder="@lang('http/https://example.com')" value="{{$ads->redirect_url}}" id="redirect_url">
		                            </div>
		                        </div>

		                        <div class="col-lg-4">
		                			<div class="form-group">
		                                <label for="symbol" class="form-control-label font-weight-bold">@lang('Ad Image')</label>
		                                <div class="custom-file">
		                                    <input type="file" name="adimage" class="custom-file-input" id="customFileLangHTML" required="">
		                                    <label class="custom-file-label" for="customFileLangHTML" data-browse="@lang('Choose Image')">@lang('Image')</label>
		                                </div>
		                            </div>
		                		</div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label font-weight-bold">@lang('Status') </label>
                                        <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                               data-toggle="toggle" data-on="@lang('Active')" @if($ads->status) checked @endif data-off="@lang('Inactive')" name="status"
                                              >
                                    </div>
                                </div>
		                    </div>
                        @else
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="script" class="font-weight-bold">@lang('Ad Script')</label>
                                        <textarea type="text" class="form-control" name="script" id="script">{{$ads->script}}</textarea>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label font-weight-bold">@lang('Status') </label>
                                        <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                               data-toggle="toggle" data-on="@lang('Active')" @if($ads->status) checked @endif data-off="@lang('Inactive')" name="status"
                                              >
                                    </div>
                                </div>
                            </div>
                        @endif

                       	<div class="form-group">
                            <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Update')</button>
                        </div>
	                
                	</form>
                </div>
            </div>
        </div>
    </div>


@push('breadcrumb-plugins')
    <a href="{{route('admin.ads.index')}}" class="btn btn-sm btn--info box--shadow1 text--small"><i class="las la-angle-double-left"></i>@lang('Go Back')</a>
@endpush
@endsection
