@extends($activeTemplate.'layouts.wiserlance.dashboard-layout-job')
@section('content')
<link rel="stylesheet" href="{{asset($activeTemplateTrue.'frontend/css/bootstrap-fileinput.css')}}">
<style>
    .withdraw-thumbnail {
        max-width: 220px;
        max-height: 220px
    }
</style>
<div class="dashboard-content">
    <!-- <div class="dashboard-tlbar d-block mb-5">
        <div class="row">
            <div class="colxl-12 col-lg-12 col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item text-muted"><a href="landing2.html">Home</a></li>
                        <li class="breadcrumb-item text-muted"><a href="dashboard.html">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="dashboard-manage-service.html" class="theme-cl">Manage Services</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div> -->

    <div class="dashboard-widg-bar d-block ">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                <img src="{{asset('assets/wiserlance/assets/img/about-1.png')}}" class="img-fluid" style="width:90%;" alt="">
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 col-6 pt-5">
                <form action="{{ route('user.deposit.manual.updatetwo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <h3>{{__($pageTitle)}}</h3>
                    <p>@lang('You have requested')<span class="font-weight-bold text-success"> {{ getAmount($data['amount'])  }} {{__($general->cur_text)}} </span> , @lang('Please pay') <span class="font-weight-bold text-success"> {{getAmount($data['final_amo']) .' '.$data['method_currency'] }} </span> @lang('for successful payment')</p>
                    <h6>@lang('Please follow the instruction below')</h6>
                    <p>@php echo $data->gateway->description @endphp</p>
                    @if($method->gateway_parameter)
                    @foreach(json_decode($method->gateway_parameter) as $k => $v)

                    @if($v->type == "text")
                    <div class="form-group">
                        <label class="text-dark ft-medium">{{__(inputTitle($v->field_level))}} @if($v->validation == 'required')<span class="text-danger">*</span> @endif</label>
                        <input type="text" class="form-control " name="{{$k}}" value="{{old($k)}}" placeholder="{{__($v->field_level)}}">
                    </div>
                    @elseif($v->type == "textarea")
                    <div class="form-group">
                        <label class="text-dark ft-medium">{{__(inputTitle($v->field_level))}} @if($v->validation == 'required') <span class="text-danger">*</span> @endif</label>
                        <textarea name="{{$k}}" class="form-control" placeholder="{{__($v->field_level)}}" cols="30" rows="10">{{old($k)}}</textarea>
                    </div>
                    @elseif($v->type == "file")
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><strong>{{__($v->field_level)}} @if($v->validation == 'required') <span class="text-danger">*</span> @endif</strong></label>
                            <br>

                            <div class="fileinput fileinput-new " data-provides="fileinput">
                                <div class="fileinput-new thumbnail withdraw-thumbnail" data-trigger="fileinput">
                                    <img src="{{ asset(getImage('/')) }}" alt="@lang('Image')">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail wh-200-150"></div>

                                <div class="img-input-div">
                                    <span class="btn btn-info btn-file">
                                        <span class="fileinput-new text-white"> @lang('Select') {{__($v->field_level)}}</span>
                                        <span class="fileinput-exists text-white"> @lang('Change')</span>
                                        <input type="file" name="{{$k}}" accept="image/*">
                                    </span>
                                    <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput"> @lang('Remove')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                    @endif
                    <button type="submit" href="dashboard-deposit-money-history.html" class="btn btn-md ft-medium text-light rounded theme-bg">@lang('Pay Now')</button>
                </form>
            </div>

        </div>
    </div>

    @endsection

    @section('script')
    <script src="{{asset($activeTemplateTrue.'frontend/js/bootstrap-fileinput.js')}}"></script>

    @endsection