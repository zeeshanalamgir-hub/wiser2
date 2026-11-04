<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="{{asset('assets/wiserlance/assets/css/styles.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'frontend/css/select2.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- intlTelInput -->
    <title>Bizlance - Seller Profile</title>
    <style type="text/css">
    .adddelbtn{
    -webkit-appearance: button;
    width: 30px;
    margin-top: 10px;
    color: white;
    background: #28b661 !important;
    border: 1px solid #28b661 !important;
    }
    .nav-tabs .nav-item{
    width: auto;
    }
    .nav-link {
    display: block;
    padding: .6rem .6rem;
    background: #F1FDF7;
    font-weight:500;
    font-size: 14px;
    margin-right: 0.8rem;
    }
    .nav-tabs {
    border-bottom: 1px solid transparent !important ;
    }
    .nav-tabs .nav-link {
    text-align: center;
    color:#28b661 !important;
    border: 1px solid transparent;
    border-radius: .25rem;
    }
    .nav-tabs .nav-link {
    border: 1px solid transparent;
    border-radius: .25rem;
    background: #F1FDF7;
    }
    .nav-tabs .nav-link:hover, .nav-tabs .nav-link:focus {
    border:1px solid transparent;
    border-radius: .25rem;
    background:#F1FDF7;
    cursor:pointer;
    }
    .nav-tabs .nav-link.active {
    color:#ffffff !important  ;
    background:#28b661;
    border-color: #28b661;
    }
    button:focus {
    outline:none;
    outline:none;
    }
    .nav-tabs.simple_tab_links .nav-link {
    margin:0 5px;
    padding:0.5rem 0.5rem;
    border-radius: 0;
    background: transparent;
    border: none;
    font-size: 13px;
    font-weight: 500;
    opacity:0.6;
    text-transform: uppercase;
    }
    .nav-tabs.simple_tab_links .nav-link:hover, .nav-tabs.simple_tab_links .nav-link:focus {
    border:none;
    border-radius:0;
    background:transparent;
    cursor:pointer;
    }
    .nav-tabs.simple_tab_links .nav-link.active {
    color: #000000;
    background: transparent;
    border-bottom: 2px dashed black;
    opacity: 1;
    }
    </style>
  </head>
  <body>
    @php
    $lang=DB::table('profile_languages')->get();
    $degree=DB::table('profile_degrees')->get();
    @endphp
    <div class="container-fluid profileheader">
      <div class="row">
        <div class="col-lg-6 ">
          <a class="nav-brand fixed-logo pt-2" href="{{route('home')}}"><img src="{{asset('assets/wiserlance/assets/img/logos/1.JPG')}}" alt=""></a>
        </div>
        <div class="col-lg-6 text-right">
          <a href="{{route('user.home')}}" class="btn theme-bg text-light ft-medium apply-btn fs-sm rounded">@lang('Back To Dashboard')</a>
        </div>
      </div>
    </div>
    <form action="{{url('selling_profile_update',$user->id)}}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="container">
        <div class="col-lg-12 mt-3">
          <div class="row" >
            <div class="col-lg-3">
              <ul class="nav mb-4 profile-tabs" role="tablist">
                <li class="nav-item1">
                  <a class="nav-link1 active" id="nav-link1" data-toggle="tab" href="#2" role="tab">Profile Image
                  </a>
                </li>
                <li class="nav-item1">
                  <a class="nav-link1 " id="nav-link1" data-toggle="tab" href="#1" role="tab">Personal Information</a>
                </li>
                <li class="nav-item1">
                  <a class="nav-link1 " id="nav-link1" data-toggle="tab" href="#7" role="tab">Occupational information
                  </a>
                </li>
                <li class="nav-item1">
                  <a class="nav-link1 " id="nav-link1" data-toggle="tab" href="#3" role="tab">Languages</a>
                </li>
                <li class="nav-item1">
                  <a class="nav-link1 " id="nav-link1" data-toggle="tab" href="#4" role="tab">Educational information</a>
                </li>
                <li class="nav-item1">
                  <a class="nav-link1 " id="nav-link1" data-toggle="tab" href="#5" role="tab">Certification information</a>
                </li>
                <li class="nav-item1">
                  <a class="nav-link1 " id="nav-link1" data-toggle="tab" href="#6" role="tab">Skills and expertise</a>
                </li>

                <li class="nav-item1">
                  <a class="nav-link1 " id="nav-link1" data-toggle="tab" href="#8" role="tab">Portfolio Samples</a>
                </li>
                <li class="nav-item1">
                  <a class="nav-link1 " id="nav-link1" data-toggle="tab" href="#10" role="tab">Notification Alerts</a>
                </li>
                <li class="nav-item1">
                  <a class="nav-link1 " id="nav-link1" data-toggle="tab" href="#9" role="tab">Submit</a>
                </li>
                </ul><!-- Tab panes -->
              </div>
              <div class="col-lg-9">
                <div class="tab-content ">
                  <div class="tab-pane " id="1">
                    <h4 class="text-center mb-3">Personal Information</h4>
                    <div class="row">
                      <div class="col-lg-5 col-md-5 col-sm-4 col-12 mt-4">
                        <img src="{{asset('assets/wiserlance/assets/img/about-1.png')}}" class="img-fluid">
                      </div>
                      <div class="col-lg-7 col-md-7 col-sm-8 col-12">
                        <div class="col-lg-12">

                          <div class="form-group">
                            <label class="control-label">@lang('First Name')*</label>
                            <input type="text" id="firstname" name="firstname" value="{{__($user->firstname)}}" class="form-control"/>
                          </div>
                          <div class="form-group">
                            <label class="control-label">@lang('Last Name')*</label>
                            <input type="text" id="lastname" name="lastname" value="{{__($user->lastname)}}" class="form-control"/>
                          </div>
                          <div class="form-group">
                            <label class="control-label">@lang('Designation')*</label>
                            <input type="text" id="designation" name="designation" value="{{__($user->designation)}}" class="form-control" placeholder="e.g.Graphic Designer | Web Developer" />
                          </div>
                          <div class="form-group">
                            <label class="control-label">@lang('Address')*</label>
                            <input type="text"  id="address" name="address" value="{{__($user->address->address)}}" class="form-control"/>
                          </div>
                          <div class="form-group">
                            <label class="control-label">@lang('State')*</label>
                            <input type="text" id="state" name="state" class="form-control" value="{{__($user->address->state)}}"/>
                          </div>
                          <div class="form-group">
                            <label class="control-label">@lang('Zip Code')*</label>
                            <input type="text" id="zip" name="zip" class="form-control" value="{{__($user->address->zip)}}"/>
                          </div>
                          <div class="form-group">
                            <label class="control-label">@lang('City')*</label>
                            <input type="text" id="city" name="city" class="form-control" value="{{__($user->address->city)}}"/>
                          </div>
                          <div class="form-group">
                            <label class="control-label">@lang('Country')*</label>
                            <input type="text" id="country" name="country" class="form-control" value="{{__($user->address->country)}}"/>
                          </div>




                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane active" id="2">
                    <h4 class="text-center mb-3">Profile Image</h4>
                    <div class="row">
                      <div class="col-lg-5 col-md-5 col-sm-4 col-12 mt-4">
                        <img src="{{asset('assets/wiserlance/assets/img/about-1.png')}}"  class="img-fluid">
                      </div>
                      <div class="col-lg-7 col-md-7 col-sm-8 col-12">
                        <div class="col-lg-12">

                          <div class="form-group">
                            <label>Profile Image*</label>
                            @if($user->image == '')
                            <div id="business_logo_error"></div>
                            <div class="business_logo" data-background="{{ userDefaultImage(imagePath()['profile']['user']['path'].'/'. $user->image,'profile_image') }}"  style="background:url('https://w7.pngwing.com/pngs/535/334/png-transparent-computer-icons-add-button-logo-number-add-button.png') no-repeat center center; background-size:cover; border:5px solid #f1f1f1; box-shadow:0px 0px 5px 0px #999;">
                              <img src="https://i.redd.it/18np5e06w1s41.png" class="img-fluid" id="page-image" />
                              <input type="file" accept="image/*" style="display:none" onchange="change_my_image(this)" id="image-input" name="image" class="form-control" />
                            </div>
                            @else
                            <div id="business_logo_error"></div>
                            <div class="business_logo" data-background="{{ userDefaultImage(imagePath()['profile']['user']['path'].'/'. $user->image,'profile_image') }}"  style="background:url('https://w7.pngwing.com/pngs/535/334/png-transparent-computer-icons-add-button-logo-number-add-button.png') no-repeat center center; background-size:cover; border:5px solid #f1f1f1; box-shadow:0px 0px 5px 0px #999;">
                              <img src="https://i.redd.it/18np5e06w1s41.png" class="img-fluid" id="page-image" />
                              <input type="file" class="form-control" accept="image/*" style="display:none" onchange="change_my_image(this)" id="image-input" value="{{ userDefaultImage(imagePath()['profile']['user']['path'].'/'. $user->image,'profile_image') }}" name="image" />
                            </div>
                            @endif

                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane" id="10">
                    <h4 class="text-center mb-3">Notification Alerts</h4>
                    <div class="row">
                      <div class="col-lg-5 col-md-5 col-sm-4 col-12 mt-4">
                        <img src="{{asset('assets/wiserlance/assets/img/about-1.png')}}"  class="img-fluid">
                      </div>
                      <div class="col-lg-7 col-md-7 col-sm-8 col-12">
                        <div class="row">
                          <div class="col-lg-12" style="margin-top:10px;">
                            <h6>Service Side Notifications</h6>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">

                            <div class="form-group">

                              <input {{  $noti->servicebooked_seller == '1' ? 'checked' : '' }}  type="checkbox" value="1" name="servicebooked_seller" >
                              &nbsp <span style="position: absolute;top: -2px;">Your service is booked.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">

                            <div class="form-group">

                              <input {{  $noti->serviceworkyes_seller == '1' ? 'checked' : '' }}  type="checkbox" value="1" name="serviceworkyes_seller" >
                              &nbsp <span style="position: absolute;top: -2px;">Work delivery accepted.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">

                            <div class="form-group">

                              <input {{  $noti->serviceworkno_seller == '1' ? 'checked' : '' }}  type="checkbox" value="1" name="serviceworkno_seller" >
                              &nbsp <span style="position: absolute;top: -2px;">Work delivery rejected.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">

                            <div class="form-group">

                              <input {{  $noti->servicebookdispute_seller == '1' ? 'checked' : '' }}  type="checkbox" value="1" name="servicebookdispute_seller" >
                              &nbsp <span style="position: absolute;top: -2px;">Service booking work in dispute.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">

                            <div class="form-group">

                              <input {{  $noti->servicebookdisputeoffer_seller == '1' ? 'checked' : '' }}  type="checkbox" value="1" name="servicebookdisputeoffer_seller" >
                              &nbsp <span style="position: absolute;top: -2px;">Service booking dispute offer.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">

                            <div class="form-group">

                              <input {{  $noti->servicebookdisputeofferyes_seller == '1' ? 'checked' : '' }}  type="checkbox" value="1" name="servicebookdisputeofferyes_seller" >
                              &nbsp <span style="position: absolute;top: -2px;">Service booking dispute offer accepted.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">

                            <div class="form-group">

                              <input {{  $noti->servicebookdisputeofferno_seller == '1' ? 'checked' : '' }}  type="checkbox" value="1" name="servicebookdisputeofferno_seller" >
                              &nbsp <span style="position: absolute;top: -2px;">Service booking dispute offer rejected.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">

                            <div class="form-group">

                              <input {{  $noti->servicebookdisputeadmin_seller == '1' ? 'checked' : '' }}  type="checkbox" value="1" name="servicebookdisputeadmin_seller" >
                              &nbsp <span style="position: absolute;top: -2px;">Service booking dispute send to admin.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">

                            <div class="form-group">

                              <input type="checkbox"  {{  $noti->servicebookrr_seller == '1' ? 'checked' : '' }} value="1" name="servicebookrr_seller" >
                              &nbsp <span style="position: absolute;top: -2px;">Service booking rating & review.</span>
                            </div>
                          </div>
                          <input type="hidden" name="notiid" value="{{$noti->id}}">

                        </div>
                        <div class="row">
                          <div class="col-lg-12" style="margin-top:10px;">
                            <h6>Job Side Notifications</h6>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">

                            <div class="form-group">

                              <input type="checkbox" {{  $noti->jobbid_seller == '1' ? 'checked' : '' }}  value="1" name="jobbid_seller" >
                              &nbsp <span style="position: absolute;top: -2px;">Job bid recieved.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">

                            <div class="form-group">

                              <input type="checkbox" {{  $noti->jobbidcounter_seller == '1' ? 'checked' : '' }}  value="1" name="jobbidcounter_seller" >
                              &nbsp <span style="position: absolute;top: -2px;">Job bid counter offer response.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">

                            <div class="form-group">

                              <input type="checkbox" {{  $noti->joboffercounter_seller == '1' ? 'checked' : '' }}  value="1" name="joboffercounter_seller" >
                              &nbsp <span style="position: absolute;top: -2px;">Counter offer of job offer.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">

                            <div class="form-group">

                              <input type="checkbox" {{  $noti->jobwork_seller == '1' ? 'checked' : '' }}  value="1" name="jobwork_seller" >
                              &nbsp <span style="position: absolute;top: -2px;">Job work recieved.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">

                            <div class="form-group">

                              <input type="checkbox" {{  $noti->jobdisputeoffer_seller == '1' ? 'checked' : '' }}  value="1" name="jobdisputeoffer_seller" >
                              &nbsp <span style="position: absolute;top: -2px;">Job dispute offer.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">

                            <div class="form-group">

                              <input type="checkbox" {{  $noti->jobdisputeofferyes_seller == '1' ? 'checked' : '' }}  value="1" name="jobdisputeofferyes_seller" >
                              &nbsp <span style="position: absolute;top: -2px;">Job dispute offer accepted.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">

                            <div class="form-group">

                              <input type="checkbox" {{  $noti->jobdisputeofferno_seller == '1' ? 'checked' : '' }}  value="1" name="jobdisputeofferno_seller" >
                              &nbsp <span style="position: absolute;top: -2px;">Job dispute offer rejected.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">

                            <div class="form-group">

                              <input type="checkbox" {{  $noti->jobdisputeadmin_seller == '1' ? 'checked' : '' }}  value="1" name="jobdisputeadmin_seller" >
                              &nbsp <span style="position: absolute;top: -2px;">Job dispute send to admin.</span>
                            </div>
                          </div>

                        </div>
                        <!--  <div class="row">
                          <div class="col-lg-12" style="margin-top:10px;">
                            <input type="hidden" name="notiid" value="{{$noti->id}}">
                            <div class="form-group">

                              <input {{  $noti->jobposted == '1' ? 'checked' : '' }} type="checkbox" value="1" name="jobposted" >
                              &nbsp <span style="position: absolute;top: -2px;">A job is posted or modified.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">
                            <div class="form-group">
                              <input {{  $noti->proposalrecieved == '1' ? 'checked' : '' }}  type="checkbox" value="1" name="proposalrecieved" >
                              &nbsp <span style="position: absolute;top: -2px;">A proposal is received.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">
                            <div class="form-group">
                              <input {{  $noti->offermodified == '1' ? 'checked' : '' }} type="checkbox" value="1" name="offermodified" >
                              &nbsp <span style="position: absolute;top: -2px;">An offer terms are modified.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">
                            <div class="form-group">
                              <input {{  $noti->offerdecline == '1' ? 'checked' : '' }} type="checkbox" value="1" name="offerdecline" >
                              &nbsp <span style="position: absolute;top: -2px;">An offer is declined or withdrawn.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">
                            <div class="form-group">
                              <input {{  $noti->offeraccept == '1' ? 'checked' : '' }} type="checkbox" value="1" name="offeraccept" >
                              &nbsp <span style="position: absolute;top: -2px;">An offer is accepted.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">
                            <div class="form-group">
                              <input {{  $noti->jobpostexpiresoon == '1' ? 'checked' : '' }} type="checkbox" value="1" name="jobpostexpiresoon" >
                              &nbsp <span style="position: absolute;top: -2px;">A job posting will expire soon.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">
                            <div class="form-group">
                              <input {{  $noti->jobpostexpired == '1' ? 'checked' : '' }} type="checkbox" value="1" name="jobpostexpired" >
                              &nbsp <span style="position: absolute;top: -2px;">A job posting expired.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">
                            <div class="form-group">
                              <input {{  $noti->contractstart == '1' ? 'checked' : '' }} type="checkbox" value="1" name="contractstart" >
                              &nbsp <span style="position: absolute;top: -2px;">A contract begins.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">
                            <div class="form-group">
                              <input {{  $noti->contractmodify == '1' ? 'checked' : '' }} type="checkbox" value="1" name="contractmodify" >
                              &nbsp <span style="position: absolute;top: -2px;">Contract terms are modified.</span>
                            </div>
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">
                            <div class="form-group">
                              <input {{  $noti->contractend == '1' ? 'checked' : '' }} type="checkbox" value="1" name="contractend" >
                              &nbsp <span style="position: absolute;top: -2px;">A contract ends.</span>
                            </div>
                          </div>
                        </div> -->
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane" id="3">
                    <h4 class="text-center mb-3">Select Languages</h4>
                    <div class="row">
                      <div class="col-lg-5 col-md-5 col-sm-4 col-12 mt-4">
                        <img src="{{asset('assets/wiserlance/assets/img/about-1.png')}}" class="img-fluid">
                      </div>
                      <div class="col-lg-7 col-md-7 col-sm-8 col-12">
                        @foreach($ul as $d)
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-6 ">
                            <label for="year">Language</label>

                            <select style="border-radius: 5px !important;" class="form-control" name="langname[]" id="langname">
                              <option value="" >Select Language</option>
                              @foreach($lang as $langs)
                              <option value="{{$langs->name}}" {{$d->name == $langs->name  ? 'selected' : ''}}>{{$langs->name}}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-6 ">
                            <label for="team-name">Language Level</label>

                            <select style="border-radius: 5px !important;" class="form-control" name="langvalue[]" id="langvalue">
                              <option value="" >Select Language Level</option>
                              <option value="Basic" {{$d->type == 'Basic'  ? 'selected' : ''}}>Basic</option>
                              <option value="Conversational" {{$d->type == 'Conversational'  ? 'selected' : ''}}>Conversational</option>
                              <option value="Fluent" {{$d->type == 'Fluent'  ? 'selected' : ''}}>Fluent</option>
                              <option value="Native" {{$d->type == 'Native'  ? 'selected' : ''}}>Native</option>
                            </select>
                          </div>
                        </div>
                        @endforeach
                        <div class=" property-fields__rows">
                          <div id="property-fields__row-1" class=" property-fields__row property-fields__row-item">

                            <div class="row" style="margin-top: 10px;">
                              <div class="col-lg-6 col-md-6 col-sm-6 col-6 ">
                                <div class="line-item-property__field line-item-property__year">
                                  <label for="year">Language</label>

                                  <select style="border-radius: 5px !important;" class="form-control" name="langname[]" id="langname">
                                    <option value="" >Select Language</option>
                                    @foreach($lang as $langs)
                                    <option value="{{$langs->name}}" >{{$langs->name}}</option>
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                              <div class="col-lg-6 col-md-6 col-sm-6 col-6 ">
                                <div class=" line-item-property__field line-item-property__team">
                                  <label for="team-name">Language Level</label>

                                  <select style="border-radius: 5px !important;" class="form-control" name="langvalue[]" id="langvalue">
                                    <option value="" >Select Language Level</option>
                                    <option value="Basic">Basic</option>
                                    <option value="Conversational">Conversational</option>
                                    <option value="Fluent">Fluent</option>
                                    <option value="Native">Native</option>
                                  </select>
                                </div>
                              </div>
                            </div>


                          </div>

                          <div class="line-item-property__actions">
                            <input type="button" class="adddelbtn" id="btnAdd" value="+" />
                            <input type="button" class="adddelbtn" id="btnDel" value="-" />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane" id="4">
                    <h4 class="text-center mb-3">Education</h4>
                    <div class="row">
                      <div class="col-lg-5 col-md-5 col-sm-4 col-12 mt-4">
                        <img src="{{asset('assets/wiserlance/assets/img/about-1.png')}}" class="img-fluid">
                      </div>
                      <div class="col-lg-7 col-md-7 col-sm-8 col-12">
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-6 " >
                            <h6 class="text-left">Add Education</h6>
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-6 float-right text-right" style="float:right !important;">
                            <button style="width:120px;font-size: 12px !important; " id="hideextra" type="button" class="btn theme-bg text-light addedu"><i class="las la-plus"></i> @lang('Add New')</button>
                          </div>
                        </div>
                        @foreach($educations as $edu)
                        <div class="row" id="img-{{$edu->id}}">
                          <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-12 " >
                            <span style=" position: relative;color: #e40c0c;font-size: 28px;" ><i class="fa fa-times-circle-o" onclick="deleteimage({{$edu->id}})"></i> </span>
                          </div> -->

                          <div class="col-lg-6 col-md-6 col-sm-6 col-6 " style="margin-top: 10px;">
                            <label for="team-name">Country of College/Univeristy</label>
                            <input type="text" id="edu1" value="{{$edu->countryinstitue}}" disabled class="form-control">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-6 " style="margin-top: 10px;">
                            <label for="team-name">Name of College/Univeristy</label>
                            <input type="text" id="edu2" value="{{$edu->institue}}" disabled class="form-control">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-6 " style="margin-top: 10px;">
                            <label for="team-name">Degree Name</label>
                            <input type="text" value="{{$edu->degree}}" id="edu3" name="namedegree[]" class="form-control" readonly>
                            <!-- <select class="form-control" id="edu3" disabled >
                              <option value="" >Select Degree</option>
                              @foreach($degree as $deg)
                              <option  value="{{$deg->name}}" {{$edu->degree == $deg->name  ? 'selected' : ''}}>{{$deg->name}}</option>
                              @endforeach
                            </select> -->
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-6 " style="margin-top: 10px;">
                            <label for="team-name">Year of Degree</label>

                            <input type="number" class="form-control" id="edu4" disabled min="1900" max="2099" step="1" value="{{$edu->yeardegree}}" />
                          </div>
                          <div class="col-lg-12 col-md-12 col-sm-12 col-12 " style="margin-top: 10px;">
                            <label for="team-name">Degree Transcript</label>
                            <div id="transcript_error"></div>

                            <img src="{{asset('assets/wiserlance/assets/img/education/'.$edu->degrecertificate)}}" style="width:150px;height: 150px;margin-top: 10px;margin-bottom: 10px;">
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">
                            <div class="form-group">
                              <input @if($edu->showdegree == '1') checked @else @endif type="checkbox" value="1" name="showdegree[]" >
                              &nbsp <span style="position: absolute;top: -2px;">click here if you want to hide your degree.</span>
                            </div>
                          </div>
                          <div class="col-lg-12 col-md-12 col-sm-12 col-12"  style="margin-top: 10px;">
                            <button onclick="deleteimage({{$edu->id}})" type="button" class="btn btn-danger text-white border-rounded btn-sm ">
                            <i class="fa fa-times"></i>
                            </button>
                          </div>
                        </div>
                        @endforeach
                        <div class="row addeduboxes">

                        </div>
                        <!--  <div class="wrapper1">
                          <div class="element1">
                            <div class="row" >

                              <div class="col-lg-6 col-md-6 col-sm-6 col-6 " style="margin-top: 10px;">
                                <label for="team-name">Country of College/Univeristy</label>
                                <input type="text" id="edu1" name="countrycollege[]" class="form-control">
                              </div>
                              <div class="col-lg-6 col-md-6 col-sm-6 col-6 " style="margin-top: 10px;">
                                <label for="team-name">Name of College/Univeristy</label>
                                <input type="text" id="edu2" name="namecollege[]" class="form-control">
                              </div>
                              <div class="col-lg-6 col-md-6 col-sm-6 col-6 " style="margin-top: 10px;">
                                <label for="team-name">Degree Name</label>
                                <input type="text" id="edu3" name="namedegree[]" class="form-control">
                                <select style="border-radius: 5px !important;" class="form-control" id="edu3" name="namedegree[]" >
                                  <option value="" >Select Degree</option>
                                  @foreach($degree as $deg)
                                  <option value="{{$deg->name}}">{{$deg->name}}</option>
                                  @endforeach
                                </select>
                              </div>
                              <div class="col-lg-6 col-md-6 col-sm-6 col-6 " style="margin-top: 10px;">
                                <label for="team-name">Year of Degree</label>

                                <input type="number" class="form-control" id="edu4" name="yeardegree[]" min="1900" max="2099" step="1" value="2022" />
                              </div>
                              <div class="col-lg-12 col-md-12 col-sm-12 col-12 " style="margin-top: 10px;">
                                <label for="team-name">Degree Transcript</label>
                                <div id="transcript_error"></div>
                                <input type="file" name="degrecertificate[]" id="degrecertificate" class="form-control">

                              </div>
                            </div>
                          </div>
                          <div class="results1"></div>
                          <div class="buttons">
                            <button type="button"  class="clone1 adddelbtn">+</button>
                            <button type="button"  class="remove1 adddelbtn" >-</button>
                          </div>
                        </div> -->
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane" id="5">
                    <h4 class="text-center mb-3">Certification</h4>
                    <div class="row">
                      <div class="col-lg-5 col-md-5 col-sm-4 col-12 mt-4">
                        <img src="{{asset('assets/wiserlance/assets/img/about-1.png')}}" class="img-fluid">
                      </div>
                      <div class="col-lg-7 col-md-7 col-sm-8 col-12">

                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                            <h6 class="text-left">Add Certification</h6>
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-6 float-right text-right" style="float:right !important;">
                            <button style="width:120px;font-size: 12px !important; " id="hideextra" type="button" class="btn theme-bg text-light addcer"><i class="las la-plus"></i> @lang('Add New')</button>
                          </div>
                        </div>
                        @foreach($certificates as $c)
                        <div class="row mt-3" id="cer-{{$c->id}}">
                          <!--  <div class="col-lg-12 col-md-12 col-sm-12 col-12 " >
                            <span style=" position: relative;color: #e40c0c;font-size: 28px;" ><i class="fa fa-times-circle-o" onclick="deletecer({{$c->id}})"></i> </span>
                          </div> -->
                          <div class="col-lg-6 col-md-6 col-sm-6 col-6 " style="margin-top: 5px;">
                            <label for="team-name">Name of Institue</label>
                            <input type="text" id="cer1" disabled value="{{$c->nameinstitue}}" class="form-control">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-6 " style="margin-top: 5px;">
                            <label for="team-name">Certificate Name</label>

                            <input type="text" id="cer2" disabled value="{{$c->namecertificate}}" class="form-control">
                          </div>
                          <div class="col-lg-12 col-md-12 col-sm-12 col-12 " style="margin-top: 10px;">
                            <label for="team-name">Year of Certification</label>
                            <input type="number" class="form-control" id="cer3" disabled name="yearcertificate[]" min="1900" max="2099" step="1" value="2022" />
                          </div>
                          <div class="col-lg-12 col-md-12 col-sm-12 col-12 " style="margin-top: 10px;">
                            <label for="team-name">Certificate Transcript</label>
                            <div id="cer_error"></div>

                            <img src="{{asset('assets/wiserlance/assets/img/certification/'.$c->certificate)}}" style="width:150px;height: 150px;margin-top: 10px;margin-bottom: 10px;">
                          </div>
                          <div class="col-lg-12" style="margin-top:10px;">
                            <div class="form-group">
                              <input @if($c->showcertificate == '1') checked @else @endif type="checkbox" value="1" name="showcertificate[]" >
                              &nbsp <span style="position: absolute;top: -2px;">click here if you want to hide your certificate.</span>
                            </div>
                          </div>
                          <div class="col-lg-12 col-md-12 col-sm-12 col-12"  style="margin-top: 10px;">
                            <button onclick="deletecer({{$c->id}})" type="button" class="btn btn-danger text-white border-rounded btn-sm ">
                            <i class="fa fa-times"></i>
                            </button>
                          </div>
                        </div>
                        @endforeach
                        <div class="row addcerboxes">

                        </div>
                        <!-- <div class="wrapper">
                          <div class="element">
                            <div class="row mt-3" >


                              <div class="col-lg-6 col-md-6 col-sm-6 col-6 " style="margin-top: 10px;">
                                <label for="team-name">Name of Institue</label>
                                <input type="text" id="cer1" name="nameinstitue[]" class="form-control">
                              </div>
                              <div class="col-lg-6 col-md-6 col-sm-6 col-6 " style="margin-top: 10px;">
                                <label for="team-name">Certificate Name</label>

                                <input type="text" id="cer2" name="namecertificate[]" class="form-control">
                              </div>
                              <div class="col-lg-12 col-md-12 col-sm-12 col-12 " style="margin-top: 10px;">
                                <label for="team-name">Year of Certificate</label>

                                <input type="number" class="form-control" id="cer3" name="yearcertificate[]" min="1900" max="2099" step="1" value="2022" />
                              </div>
                              <div class="col-lg-12 col-md-12 col-sm-12 col-12 " style="margin-top: 10px;">
                                <label for="team-name">Certificate Transcript</label>
                                <div id="cer_error"></div>
                                <input type="file" name="certificate[]" id="certificate" class="form-control">
                              </div>
                            </div>
                          </div>
                          <div class="results"></div>
                          <div class="buttons">
                            <button type="button"  class="clone adddelbtn">+</button>
                            <button type="button"  class="remove adddelbtn" >-</button>
                          </div>
                        </div> -->
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane" id="6">
                    <div class="row">
                      <div class="col-lg-5 col-md-5 col-sm-4 col-12 mt-4">
                        <img src="{{asset('assets/wiserlance/assets/img/about-1.png')}}" class="img-fluid">
                      </div>
                      <div class="col-lg-7 col-md-7 col-sm-8 col-12">

                        <div class="row">
                          <div class="col-lg-12">
                            Categories
                          </div>
                          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                              <label class="text-dark ft-medium">@lang('Category Level 1')*</label>
                              <select class="form-control rounded" name="category" id="category">
                                <option selected="" disabled="">@lang('Select Category Level 1')</option>
                                @foreach($categoryss as $category)
                                <option {{ $category->id == $user->cat_id ? 'selected':'' }} value="{{__($category->id)}}">{{__($category->name)}}</option>
                                @endforeach

                              </select>
                            </div>
                          </div>
                          @php
                                        $subs=DB::table('sub_categories')->where('category_id',$user->cat_id)->get();
                                        $childs=DB::table('child_categories')->where('subcat_id',$user->subcat_id)->get();
                                        $childscount=DB::table('child_categories')->where('subcat_id',$user->subcat_id)->count();
                                        @endphp
                          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                              <label class="text-dark ft-medium">@lang('Category Level 2')*</label>
                              <select name="subcategory" class="form-control rounded mySubCatgry getchild" id="subCategorys">
                                @foreach($subs as $category)
                                                    <option {{ $user->subcat_id == $category->id ? 'selected':'' }} value="{{__($category->id)}}">{{__($category->name)}}</option>
                                                    @endforeach

                              </select>
                            </div>
                          </div>
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group myChildCatgry">
                              @if($childscount > 0)
                                                <label class="text-dark ft-medium">@lang('Category Level 3')</label>
                                                <select name="childcategory" class="form-control rounded tierchildcat " id="childCategorys"  >

                                                    @foreach($childs as $category)
                                                    <option {{ $user->childcat_id == $category->id ? 'selected':'' }} value="{{__($category->id)}}">{{__($category->name)}}</option>
                                                    @endforeach

                                                </select>

                                        @endif


                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-12">
                          Skills
                          <div class="col-xl-12 col-lg-12 ">
                          <ul class="nav nav-tabs " role="tablist" id="result">

                          @foreach($skills as $skill)
                            <li class="nav-item" style="margin-top:10px">
                              <a type="button" class="nav-link addcl active show" id="nav-link" data-toggle="tab" href="#tab-947" role="tab" aria-selected="true">{{ $skill->name }}</a>
                              </li>
                          @endforeach
  </ul>
                        </div>

                        </div>
                        <div id="skills_result" class="col-xl-12 col-lg-12 form-group">
                        </div>
                        <div class="col-xl-12 col-lg-12 ">
                          <ul class="nav nav-tabs " role="tablist" id="result">
                          </ul>
                          <div class="tab-content" id="demos">
                          </div>
                        </div>
                      </div>
                      <div class="row mt-3">
                        <div class="col-lg-12">
                          Extra Skills
                          <div class="col-xl-12 col-lg-12 ">
                          <ul class="nav nav-tabs " role="tablist" id="result">

                          @foreach($newskills as $skill)
                            <li class="nav-item" style="margin-top:10px">
                              <a type="button" class="nav-link addcl active show" id="nav-link" data-toggle="tab" href="#tab-947" role="tab" aria-selected="true">{{ $skill->name }}</a>
                              </li>
                          @endforeach
  </ul>
                        </div>

                        </div>
                        <div id="skills_result_extra" class="col-xl-12 col-lg-12 form-group">
                        </div>
                        <div class="col-xl-12 col-lg-12">
                          <div class="form-group">
                            <!--  <label class="text-dark ft-medium">@lang('Find pre-defined skills or add yours')*</label> -->
                            {{-- <select onselect="onselect()" class="form-control select2" id="search_skill" name="fskill" multiple="multiple" >
                            </select> --}}
                            <input onkeyup="getSkillbuname(this.value)" id="search_skill"  class="form-control rounded" type="text" autocomplete="off" placeholder="Type to Search ..."  >
                          </div>
                        </div>
                        <div id="skil_result" class="col-xl-12 col-lg-12 form-group">
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane" id="7">
                  <h4 class="text-center mb-3">Additional Information</h4>
                  <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-4 col-12 mt-4">
                      <img src="{{asset('assets/wiserlance/assets/img/about-1.png')}}" class="img-fluid">
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-8 col-12">
                      <div class="col-lg-12">

                        <div class="form-group">
                          <label class="control-label">@lang('Per Hour Rate')*</label>
                          <input type="number" value="{{$user->per_hour_rate}}" name="rate" class="form-control">
                        </div>



                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 col-12 " style="margin-top: 10px;">
                        <label for="team-name">Level</label>
                        <select class="form-control" id="edu3" name="level" >
                          <option value="" >Select Level</option>
                          <option  value="Beginner" {{$user->level == 'Beginner'  ? 'selected' : ''}}>Beginner</option>
                          <option value="Intermedite" {{$user->level == 'Intermedite'  ? 'selected' : ''}}>Intermedite</option>
                          <option value="Expert" {{$user->level == 'Expert'  ? 'selected' : ''}}>Expert</option>

                        </select>
                      </div>
                      <div class="col-lg-12">

                        <div class="form-group">
                          <label class="control-label">@lang('Description')*</label>
                          <textarea class="form-control with-light nicEdit" style="height: 200px !important;width: 455% !important;" id="about_me" name="about_me" rows="5" cols="10" >{{__($user->about_me)}}</textarea>
                        </div>



                      </div>

                    </div>
                  </div>
                </div>
                <div class="tab-pane" id="8">
                  <h4 class="text-center mb-3">Portfolio Samples</h4>
                  <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-4 col-12 mt-4">
                      <img src="{{asset('assets/wiserlance/assets/img/about-1.png')}}" class="img-fluid">
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-8 col-12">
                      <div class="col-lg-12">
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                            <h6 class="text-left">Add Portfolio</h6>
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-6 float-right text-right" style="float:right !important;">
                            <button style="width:120px;font-size: 12px !important; " id="hideextra" type="button" class="btn theme-bg text-light addport"><i class="fa fa-plus"></i> @lang('Add New')</button>
                          </div>
                        </div>
                        <div class="form-group">

                          @foreach($user->portfolioprofile as $as)
                          <div class="row" id="port-{{$as->id}}">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 " style="margin-top: 10px;">
                              <label for="team-name">Portfolio Title</label>
                              <input type="text" value="{{$as->title}}"  name="ptitle[]" class="form-control">

                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 " style="margin-top: 10px;">
                              <label for="team-name">Portfolio Description</label>
                              <textarea  class="form-control with-light nicEdit" style="height: 200px !important;width: 500% !important;" id="about_me" name="pdesc[]" rows="5" cols="10" >{{$as->description}}</textarea>
                            </div>
                            <div class="col-lg-12">


                                <!-- <span style="margin-right: 10px; margin-top: -10px;color: #e40c0c;font-size: 18px;" ><i class="fa fa-times-circle-o" ></i> </span> -->
                                <img src="{{asset('assets/wiserlance/assets/img/portfolio/'.$as->image)}}" style="width:150px;height: 150px;margin-top: 10px;margin-bottom: 10px;">

                            </div>
                             <div class="col-lg-12 col-md-12 col-sm-12 col-12"  style="margin-top: 10px;">
                            <button onclick="deleteport({{$as->id}})" type="button" class="btn btn-danger text-white border-rounded btn-sm ">
                            <i class="fa fa-times"></i>
                            </button>
                          </div>
                          </div>



                          @endforeach
                        </div>
                      </div>


                      <div class="row addportboxes">

                    </div>
                    </div>

                  </div>
                </div>

              <div class="tab-pane" id="9">
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-4 text-center">
                    <img src="{{asset('assets/wiserlance/assets/img/about-1.png')}}" style="width:50%" class="img-fluid">
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-4 text-center">

                    <input  type="submit" value="Update Professional" class="btn theme-bg text-light ft-medium apply-btn fs-sm rounded">
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <script src="{{asset('assets/wiserlance/assets/js/slick.js')}}"></script>
    <script src="{{asset('assets/wiserlance/assets/js/slider-bg.js')}}"></script>
    <script src="{{asset('assets/wiserlance/assets/js/smoothproducts.js')}}"></script>
    <script src="{{asset('assets/wiserlance/assets/js/snackbar.min.js')}}"></script>
    <script src="{{asset('assets/wiserlance/assets/js/jQuery.style.switcher.js')}}"></script>
    <script src="{{asset('assets/wiserlance/assets/js/custom.js')}}"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="{{asset($activeTemplateTrue.'frontend/js/select2.min.js')}}"></script>
    <script src="{{asset($activeTemplateTrue.'frontend/js/nicEdit.js')}}"></script>
    <script type="text/javascript">
    function deleteimage(id)
    {
    $('#loading').show();
    var id=id;
    $.ajax({
    type: "POST",
    url: '{{url("deleteproductimage")}}',
    data:{'id':id, "_token": "{{ csrf_token() }}"},
    dataType: 'json',
    success: function(data){
    $('#loading').hide();
    if(data.success==1)
    {
    $("#img-"+id).remove();
    }
    }
    });
    }
    function deletecer(id)
    {
    $('#loading').show();
    var id=id;
    $.ajax({
    type: "POST",
    url: '{{url("deletecer")}}',
    data:{'id':id, "_token": "{{ csrf_token() }}"},
    dataType: 'json',
    success: function(data){
    $('#loading').hide();
    if(data.success==1)
    {
    $("#cer-"+id).remove();
    }
    }
    });
    }
    function deleteport(id)
    {
    $('#loading').show();
    var id=id;
    $.ajax({
    type: "POST",
    url: '{{url("deleteport")}}',
    data:{'id':id, "_token": "{{ csrf_token() }}"},
    dataType: 'json',
    success: function(data){
    $('#loading').hide();
    if(data.success==1)
    {
    $("#port-"+id).remove();
    }
    }
    });
    }
    </script>
    <script type="text/javascript">
    "use strict";
    function proPicURL(input) {
    if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
    var preview = $(input).parents('.preview-thumb').find('.profilePicPreview');
    $(preview).css('background-image', 'url(' + e.target.result + ')');
    $(preview).addClass('has-image');
    $(preview).hide();
    $(preview).fadeIn(650);
    }
    reader.readAsDataURL(input.files[0]);
    }
    }
    $(".profilePicUpload").on('change', function () {
    proPicURL(this);
    });
    $(".remove-image").on('click', function () {
    $(".profilePicPreview").css('background-image', 'none');
    $(".profilePicPreview").removeClass('has-image');
    })
    $(document).ready(function() {
    // $('.select2').select2({
    // });
    });
    $('.addExtra').on('click', function () {
    var html = `
    <div class="col-lg-12 extraServiceRemove">
      <div class="row">
        <div class="col-xl-9 col-lg-12 form-group">
          <input type="text" name="extra_title[]" class="form-control" placeholder="@lang("Enter title")" >
        </div>
        <div class="col-xl-2 col-lg-12 form-group">
          <div class="input-group mb-3">
            <input type="text" class="form-control" name="extra_price[]" placeholder="@lang('Enter Price')" >
            <span class="input-group-text" id="basic-addon2">{{__($general->cur_text)}}</span>
          </div>
        </div>
        <div class="col-xl-1">
          <button type="button" class="btn btn--danger text-white border--rounded btn-sm mt-1 removeBtn">
          <i class="fa fa-times"></i>
          </button>
        </div>
      </div>
    </div>`;
    $('.addExtraService').append(html);
    });
    $(document).on('click', '.removeBtn', function () {
    $(this).closest('.extraServiceRemove').remove();
    });
    $('.addExtraImage').on('click',function(){
    var html = `
    <div class="custom-file-wrapper removeImage">
      <div class="custom-file">
        <input type="file" class="custom-file-input" name="optional_image[]" id="customFile" >
        <label class="custom-file-label" for="customFile">@lang('Choose file')</label>
      </div>
      <button class="btn btn--danger text-white border--rounded removeExtraImage"><i class="fa fa-times"></i></button>
    </div>`;
    $('.addImage').append(html);
    });
    $(document).on('click', '.removeExtraImage', function (){
    $(this).closest('.removeImage').remove();
    });
    bkLib.onDomLoaded(function() {
    $( ".nicEdit" ).each(function( index ) {
    $(this).attr("id","nicEditor"+index);
    new nicEditor({fullPanel : true}).panelInstance('nicEditor'+index,{hasPanel : true});
    });
    });
    (function($){
    $( document ).on('mouseover ', '.nicEdit-main,.nicEdit-panelContain',function(){
    $('.nicEdit-main').focus();
    });
    })(jQuery);
    $(document).on("change",".custom-file-input",function(){
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
    $('#category').on('change', function(){
    $('.myChildCatgry').empty();
    $('#demos').empty();
    $('.filtered-tiers').empty();
    $('#result').empty();
    $('#skills_result').empty();
    var category = $(this).val();
    $.ajax({
    type:"GET",
    url:"{{route('user.category')}}",
    data: {category : category},
    success:function(data){
    var html = '';
    if(data.error){
    $("#subCategorys").empty();
    html += `<option value="" selected disabled>${data.error}</option>`;
    $(".mySubCatgry").html(html);
    }
    else{
    $("#subCategorys").empty();
    html += `<option value="" selected disabled>@lang('Select Category Level 2')</option>`;
    $.each(data, function(index, item) {
    html += `<option value="${item.id}">${item.name}</option>`;
    $(".mySubCatgry").html(html);
    });
    }
    }
    });
    });
    $('.mySubCatgry').on('change', function(){
    $('#demos').empty();
    $('.filtered-tiers').empty();
    $('#result').empty();
    $('#skills_result').empty();
    var subcategory = $(this).val();
    gettiers(subcategory);
    childcattier(subcategory);
    $.ajax({
    type:"GET",
    url:"{{route('user.subcategory')}}",
    data: {subcategory : subcategory},
    success:function(data){
    var html = '';
    if(data.error){
    $("#childCategorys").empty();
    // html += `<option value="" selected disabled>${data.error}</option>`;
    html += ``;
    $(".myChildCatgry").html(html);
    }
    else{
    $("#childCategorys").empty();
    html += `<label class="text-dark ft-medium">@lang('Category Level 3')</label>
    <select name="childcategory" class="form-control rounded tierchildcat " id="childCategorys"><option value="" selected disabled>@lang('Select Category Level 3')</option>`;
    $.each(data, function(index, item) {
    html += `<option value="${item.id}">${item.name}</option>`;
    $(".myChildCatgry").html(html);
    });
    }
    }
    });
    });
    const checkload = [];
    const skills_arr = [];
    const skills_name = [];
    const newskills_arr = [];
    const newskills_name = [];
    const exskills_arr = [];
    const exskills_name = [];
    const newexskills_arr = [];
    const newexskills_name = [];
    let newskillcount = 0;
    let newexskillcount = 0;
    //let button_html = '';
    $('#subCategorys').on('change', function(){

    var subcategory = $(this).val();

    $.ajax({
    type:"GET",
    url:"{{route('user.skillcategory')}}",
    data: {subcategory : subcategory},
    success:function(data){
    var html = '';
    if(data.error){

    html += 'No Skill Category Availble';

    }else{
    $.each(data, function(name, id) {
    console.log(id);
    html+=' <li class="nav-item"><a onclick="getskills('+id+')" type="button" class="nav-link addcl " id="nav-link"  data-toggle="tab" href="#tab-'+id+'" role="tab">'+name+'</a></li>';
    });
    }

    $('#result').html(html);
    }
    });
    });
    function getskills(c_id)
    {
    if(checkload.indexOf(c_id) !== -1){
    //alert("Value exists!")
    return
    }
    $.ajax({
    type:"GET",
    url:"{{route('user.getskills')}}",
    data: {c_id : c_id},
    success:function(data){
    var html = '';
    if(data.error){

    html += 'No Skill  Availble';
    }else{
    $.each(data, function(name, id, skill_category_id) {
    //alert(category_id);
    console.log(c_id);
    html+=`<div class="tab-pane nbv " style="display:inline;" id="tab-'+c_id+'" role="tabpanel"><button type="button" onclick="selectskills(${id},'${name}',${c_id},false)" value="${id}" class="btn gray ft-small apply-btn fs-sm rounded mt-1">${name}<i class="fa fa-plus ml-2"></i></button>&nbsp</div>`;
    });
    }
    $('#demos').html(html);
    //checkload.push(c_id);
    }
    });
    }
    $(".addcl").click(function(){
    $(".nbv").addClass("active");
    $(this).addClass("active");
    });
    function selectskills(s_id,name,c_id,check)
    {
    //alert(c_id);
    let button_html='';
    //check if skill already selected
    if(skills_arr.indexOf(s_id) !== -1)
    {
    //alert("Value exists!")
    return
    }
    //initialize note at first
    // button_html+='<p><b>Click to unselect the skill</b></p>';
    skills_arr.push(s_id);
    skills_name.push(name);
    //console.log(skills_arr);
    for(let i=0;i < skills_arr.length;i++){
    button_html+='<button type="button" onclick="unselectskill('+skills_arr[i]+')" id="skill_btn'+skills_arr[i]+'" value="'+skills_arr[i]+'" class="btn gray ft-small apply-btn fs-sm rounded mt-1">'+skills_name[i]+' <i class="fa fa-times ml-2"></i></button>&nbsp;<input type="hidden" name="skills[]" id="job_skills'+skills_arr[i]+'" value="'+skills_arr[i]+'"/>';
    }
    for(let i=0;i < newskills_arr.length;i++){
    button_html+='<button type="button" onclick="unselectnewskill('+newskills_arr[i]+')" id="newskill_btn'+newskills_arr[i]+'" value="'+skills_arr[i]+'" class="btn gray ft-small apply-btn fs-sm rounded mt-1">'+newskills_name[i]+' <i class="fa fa-times"></i></button>&nbsp;<input type="hidden" name="newskills[]" id="newjob_skills'+newskills_arr[i]+'" value="'+newskills_name[i]+'"/>';

    }
    $('#skills_result').html(button_html);
    if(check == true){
    $('#skil_result').html('');
    $('#search_skill').val('');
    }

    }
    //select new skill added
    function selectnewskill(name)
    {
    let button_html='';

    //check if skill already selected
    if(newexskills_name.indexOf(name) !== -1)
    {
    console.log(newexskills_arr);
    return
    }
    newexskillcount++;
    //add new skill in array
    newexskills_arr.push(newexskillcount);
    newexskills_name.push(name);
    for(let i=0;i < exskills_arr.length;i++){
    button_html+='<button type="button" onclick="unselectskill('+exskills_arr[i]+')" id="skill_btn'+exskills_arr[i]+'" value="'+exskills_arr[i]+'" class="btn gray ft-small apply-btn fs-sm rounded mt-1">'+exskills_name[i]+' <i class="fa fa-times-circle"></i></button>&nbsp;<input type="hidden" name="skills[]" id="job_skills'+exskills_arr[i]+'" value="'+exskills_arr[i]+'"/>';

    }
    for(let i=0;i < newexskills_arr.length;i++){
    button_html+='<button type="button" onclick="unselectnewskill('+newexskills_arr[i]+')" id="newskill_btn'+newexskills_arr[i]+'" value="'+exskills_arr[i]+'" class="btn gray ft-small apply-btn fs-sm rounded mt-1">'+newexskills_name[i]+' <i class="fa fa-times-circle"></i></button>&nbsp;<input type="hidden" name="newskills[]" id="newjob_skills'+newexskills_arr[i]+'" value="'+newexskills_name[i]+'"/>';

    }
    $('#skills_result_extra').html(button_html);
    $('#skil_result').html('');
    $('#search_skill').val('');
    }
    function unselectskill(s_id){
    //removing skill from skill array
    var index = skills_arr.indexOf(s_id);
    if (index !== -1) {
    skills_arr.splice(index, 1);
    skills_name.splice(index, 1);
    }
    $('#skill_btn'+s_id).remove();
    $('#job_skills'+s_id).remove();

    }
    function unselectnewskill(s_id){
    //removing skill from skill array
    var index = newskills_arr.indexOf(s_id);
    if (index !== -1) {
    newskills_arr.splice(index, 1);
    newskills_name.splice(index, 1);
    }
    $('#newskill_btn'+s_id).remove();
    $('#newjob_skills'+s_id).remove();
    console.log(newskills_arr);

    }
    //getting existing skill by search
    function getSkillbuname(search)
    {
    let html ='';
    //console.log(search);
    if(search == ""){
    $('#skil_result').html('');
    return
    }
    $.ajax({
    type:"GET",
    url:"{{route('user.getskillsbyname')}}",
    data: {search : search},
    success:function(data){
    var html = '';
    if(data.error){

    html+=`<b><a onclick="selectnewskill('${search}')" style="cursor:pointer;font-size:14px; line-height: 0.7;">${search}</a></b><hr>`;
    }else{
    html+="<div  style='height:100px;overflow:scroll; overflow-x: hidden;'>";
      $.each(data, function(name, id) {
      html+=`<p><b><a onclick="selectextraskills(${id},'${name}',true)" style="cursor:pointer;font-size:14px; line-height: 0.7;">${name}</a></b></p>`;

      });
    html+='</div>';
    //console.log(data);
    }

    $('#skil_result').html(html);
    // checkload.push(c_id);
    }
    });
    }
    // $(document).on('keyup keydown', '.select2-search__field', function(e) {
    //     let search = this.value;
    //     if(search != ""){
    //         getSkillbuname(search);
    //     }

    // });
    // $(document).on('select', '#search_skill', function(e) {

    //     onselectskill();
    // });
    // function onselectskill(){
    //     alert('working');
    // }
    </script>
    <script type="text/javascript">
    var checkBoxValue = $('#checkBoxwe').is(':checked')?$('#checkBoxwe').val():yes;
    </script>
    <script type="text/javascript">
    function childcattier(subcategory){
    $('.tierchildcat').on('change', function(){

    var childcategory = $('#childCategorys').val();

    gettiers(subcategory,childcategory);
    });
    }
    </script>
    <script type="text/javascript">
    function gettiers(subcategory , childcategory){

    $.ajax({
    url: "{{url('/Search_tiers')}}",
    method: "POST",
    //dataType: 'json',
    data: { "_token": "{{csrf_token()}}", 'subcategory':subcategory, 'childcategory':childcategory},
    success:function(response){
    if(response != 0){
    var result = jQuery.parseJSON(response);
    if(result.status == 'true'){
    // $('.strains-current-count').html('12');

    $('.filtered-tiers').html(result.content_data);

    // $("#loading").hide();
    }else{

    }
    }else{

    }


    },
    error:function(data){
    alert('failure');

    }
    });
    /* Ajax Call */
    }
    </script>
    <script type="text/javascript">
    $(function() {
    $('#colorselector').change(function(){
    $('.colors').hide();
    $('#' + $(this).val()).show();
    if($(this).val() == 'all') {
    $('.colors').show();
    }
    });
    });
    </script>
    <script>
    $(document).on('click',"#page-image",function() {
    $("#image-input").click();
    });
    // $(document).on('click',"#create_new",function() {
    // $(".field-form-group-dropdown").hide();
    // });
    function change_my_image(abc){
    var input = abc;
    if (input.files && input.files[0]){
    var reader = new FileReader();
    reader.onload = function (e){
    // $('#page-image').attr('src', e.target.result);
    $('.business_logo').css('background', "url('"+e.target.result+"') no-repeat center center");
    $('.business_logo').css('background-size', "cover");
    $('#business_logo_error').html('');
    }
    reader.readAsDataURL(input.files[0]);
    }else{
    alert('Something went wrong with image uploading!');
    }
    }
    </script>
    <script type="text/javascript">
    $('.wrapper').on('click', '.remove', function() {
    $('.remove').closest('.wrapper').find('.element').not(':first').last().remove();
    });
    $('.wrapper').on('click', '.clone', function() {
    $('.clone').closest('.wrapper').find('.element').first().clone().appendTo('.results');
    });
    </script>
    <script type="text/javascript">
    $('.wrapper1').on('click', '.remove1', function() {
    $('.remove1').closest('.wrapper1').find('.element1').not(':first').last().remove();
    });
    $('.wrapper1').on('click', '.clone1', function() {
    $('.clone1').closest('.wrapper1').find('.element1').first().clone().appendTo('.results1');
    });
    </script>
    <script type="text/javascript">
    $(document).ready(function () {
    var num = $('.property-fields__row').length;
    if (num - 1 == 0)
    $('#btnDel').attr('disabled', 'disabled');
    $('#btnAdd').click(function () {
    var num = $('.property-fields__row').length;
    var newNum = num + 1;
    var newElem = $('#property-fields__row-1').clone().attr('id', 'property-fields__row-' + newNum);
    newElem.find('.line-item-property__year label').attr('for', 'year_' + newNum).val('');
    newElem.find('.line-item-property__year input').attr('id', 'year_' + newNum).attr('name', 'properties[YEAR '  + newNum + ']').val('');
    newElem.find('.line-item-property__team label').attr('for', 'team-name_' + newNum).val('');
    newElem.find('.line-item-property__team input').attr('id', 'team-name_' + newNum).attr('name', 'properties[TEAM NAME '  + newNum + ']').val('');
    newElem.find('.line-item-property__name label').attr('for', 'winner-name_' + newNum).val('');
    newElem.find('.line-item-property__name input').attr('id', 'winner-name_' + newNum).attr('name', 'properties[WINNER NAME '  + newNum + ']').val('');
    $('#property-fields__row-' + num).after(newElem);
    $('#btnDel').attr('disabled', false);
    if (newNum == 19)
    $('#btnAdd').attr('disabled', 'disabled');
    });
    $('#btnDel').click(function () {
    var num = $('.property-fields__row').length;
    $('#property-fields__row-' + num).remove();
    $('#btnAdd').attr('disabled', false);
    if (num - 1 == 1)
    $('#btnDel').attr('disabled', 'disabled');
    });
    });
    </script>
    <script type="text/javascript">
    window.onload = function () {
    //Reference the DropDownList.
    var ddlYears = document.getElementById("edu4");
    //Determine the Current Year.
    var currentYear = (new Date()).getFullYear();
    //Loop and add the Year values to DropDownList.
    for (var i = 1950; i <= currentYear; i++) {
    var option = document.createElement("OPTION");
    option.innerHTML = i;
    option.value = i;
    ddlYears.appendChild(option);
    }
    };
    </script>
    <script type="text/javascript">
    $('.tags').ready(function(s_id){
    var t = document.getElementById('t');
    t.style.visibility = 'hidden';
    let button_html='';

    var tags=$('.tags').val();
    console.log(tags.length);
    if(tags.indexOf(s_id) !== -1)
    {
    //alert("Value exists!")
    return
    }
    for(let i=0;i < tags.length;i++){

    button_html+='<button type="button" onclick="unselectnewskill('+[i]+')" id="newskill_btn'+[i]+'" value="'+tags[i]+'" class="btn gray ft-small apply-btn fs-sm rounded mt-1">'+tags[i]+' <i class="fa fa-times"></i></button>&nbsp;<input type="hidden" name="skills[]" id="newjob_skills'+[i]+'" value="'+tags[i]+'"/>';

    }
    $('#skills_result1').html(button_html);
    });
    </script>
    <script type="text/javascript">
    $('.tagsnew').ready(function(s_id){
    var t = document.getElementById('tnew');
    t.style.visibility = 'hidden';
    let button_html1='';

    var tags=$('.tagsnew').val();
    console.log(tags.length);
    if(tags.indexOf(s_id) !== -1)
    {
    //alert("Value exists!")
    return
    }
    for(let i=0;i < tags.length;i++){

    button_html1+='<button type="button" onclick="unselectnewskill('+[i]+')" id="newskill_btn'+[i]+'" value="'+tags[i]+'" class="btn gray ft-small apply-btn fs-sm rounded mt-1">'+tags[i]+' <i class="fa fa-times"></i></button>&nbsp;<input type="hidden" name="newskills[]" id="newjob_skills'+[i]+'" value="'+tags[i]+'"/>';

    }
    $('#skills_result2').html(button_html1);
    });
    </script>
    <script type="text/javascript">
    window.onload = function () {
    //Reference the DropDownList.
    var ddlYears = document.getElementById("edu5");
    //Determine the Current Year.
    var currentYear = (new Date()).getFullYear();
    //Loop and add the Year values to DropDownList.
    for (var i = 1950; i <= currentYear; i++) {
    var option = document.createElement("OPTION");
    option.innerHTML = i;
    option.value = i;
    ddlYears.appendChild(option);
    }
    };
    </script>
    <script type="text/javascript">
    $('.date-own').datepicker({
    minViewMode: 2,
    format: 'yyyy'
    });
    </script>
    <script type="text/javascript">
    function selectextraskills(s_id,name,check)
    {
    let button_html='';
    //check if skill already selected
    if(exskills_arr.indexOf(s_id) !== -1)
    {
    //alert("Value exists!")
    return
    }
    //initialize note at first
    // button_html+='<p><b>Click to unselect the skill</b></p>';
    exskills_arr.push(s_id);
    exskills_name.push(name);
    //console.log(skills_arr);
    for(let i=0;i < exskills_arr.length;i++){
    button_html+='<button type="button" onclick="unselectskill('+exskills_arr[i]+')" id="skill_btn'+exskills_arr[i]+'" value="'+exskills_arr[i]+'" class="btn gray ft-small apply-btn fs-sm rounded mt-1">'+exskills_name[i]+' <i class="fa fa-times ml-2"></i></button>&nbsp;<input type="hidden" name="skills[]" id="job_skills'+exskills_arr[i]+'" value="'+exskills_arr[i]+'"/>';

    }
    for(let i=0;i < newexskills_arr.length;i++){
    button_html+='<button type="button" onclick="unselectnewskill('+newexskills_arr[i]+')" id="newskill_btn'+newexskills_arr[i]+'" value="'+exskills_arr[i]+'" class="btn gray ft-small apply-btn fs-sm rounded mt-1">'+newexskills_name[i]+' <i class="fa fa-times"></i></button>&nbsp;<input type="hidden" name="newskills[]" id="newjob_skills'+newexskills_arr[i]+'" value="'+newskills_name[i]+'"/>';

    }
    $('#skills_result_extra').html(button_html);
    if(check == true){
    $('#skil_result').html('');
    $('#search_skill').val('');
    }

    }
    </script>
    <script type="text/javascript">
    var c_no2 = 100001;
    $('.addedu').on('click', function () {
    var html = '<div class="col-lg-12 eduRemove" id="port1-'+c_no2+'">\
      <div class="row">\
        <div class="col-lg-6 col-md-6 col-sm-6 col-6 " style="margin-top: 10px;">\
          <label for="team-name">Name of College/Univeristy</label>\
          <input type="text" id="edu2" name="namecollege[]" class="form-control">\
        </div>\
        <div class="col-lg-6 col-md-6 col-sm-6 col-6 " style="margin-top: 10px;">\
          <label for="team-name">Country of College/Univeristy</label>\
          <input type="text" id="edu1" name="countrycollege[]" class="form-control">\
        </div>\
        <div class="col-lg-6 col-md-6 col-sm-6 col-6 " style="margin-top: 10px;">\
          <label for="team-name">Degree Name</label>\
          <input type="text" id="edu3" name="namedegree[]" class="form-control">\
        </div>\
        <div class="col-lg-6 col-md-6 col-sm-6 col-6 " style="margin-top: 10px;">\
          <label for="team-name">Year of Degree</label>\
          <input type="number" class="form-control" id="edu4" name="yeardegree[]" min="1900" max="2099" step="1" value="2022" />\
        </div>\
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 " style="margin-top: 10px;">\
          <label for="team-name">Degree Transcript</label>\
          <div id="transcript_error"></div>\
          <input type="file" name="degrecertificate[]" id="degrecertificate" class="form-control" multiple>\
        </div>\
        <div class="col-lg-12" style="margin-top:10px;">\
          <div class="form-group">\
            <input type="checkbox" value="1" name="showdegree[]" >\
            &nbsp <span style="position: absolute;top: -2px;">click here if you want to hide your degree.</span>\
          </div>\
        </div>\
        <div class="col-lg-2 col-md-2 col-sm-12 col-12">\
          <button data-id="'+c_no2+'" type="button" class="btn btn-danger text-white border-rounded btn-sm removeBtn">\
          <i class="fa fa-times"></i>\
          </button>\
        </div>\
      </div>\
    </div>';
    $('.addeduboxes').append(html);
    });
    $(document).on('click', '.removeBtn', function () {
    $(this).closest('.eduRemove').remove();
    });
    </script>
    <script type="text/javascript">
    var c_no3 = 100001;
    $('.addcer').on('click', function () {
    var html1 = '<div class="col-lg-12 cerRemove" id="port1-'+c_no3+'">\
      <div class="row">\
        <div class="col-lg-6 col-md-6 col-sm-6 col-6 " style="margin-top: 10px;">\
          <label for="team-name">Certificate Name</label>\
          <input type="text" id="cer2" name="namecertificate[]" class="form-control">\
        </div>\
        <div class="col-lg-6 col-md-6 col-sm-6 col-6 " style="margin-top: 10px;">\
          <label for="team-name">Name of Institue</label>\
          <input type="text" id="cer1" name="nameinstitue[]" class="form-control">\
        </div>\
        <div class="col-lg-6 col-md-6 col-sm-6 col-6 " style="margin-top: 10px;">\
          <label for="team-name">Year of Certification</label>\
          <input type="number" class="form-control" id="cer3" name="yearcertificate[]" min="1900" max="2099" step="1" value="2022" />\
        </div>\
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 " style="margin-top: 10px;">\
          <label for="team-name">Certificate Transcript</label>\
          <div id="cer_error"></div>\
          <input type="file" name="certificate[]" id="certificate" class="form-control">\
        </div>\
        <div class="col-lg-12" style="margin-top:10px;">\
          <div class="form-group">\
            <input type="checkbox" value="1"  name="showcerti[]" >\
            &nbsp <span style="position: absolute;top: -2px;">click here if you want to hide your certificate.</span>\
          </div>\
        </div>\
        <div class="col-lg-2 col-md-2 col-sm-12 col-12">\
          <button data-id="'+c_no3+'" type="button" class="btn btn-danger text-white border-rounded btn-sm removecerBtn">\
          <i class="fa fa-times"></i>\
          </button>\
        </div>\
      </div>\
    </div>';
    $('.addcerboxes').append(html1);
    });
    $(document).on('click', '.removecerBtn', function () {
    $(this).closest('.cerRemove').remove();
    });
    </script>
    <script type="text/javascript">
  var c_no4 = 100001;
$('.addport').on('click', function () {
var html2 = '<div class="col-lg-12 portRemove" id="port1-'+c_no4+'">\
<div class="row">\
                                                     <div class="col-lg-12 col-md-12 col-sm-12 col-12 " style="margin-top: 10px;">\
                          <label for="team-name">Portfolio Title</label>\
                          <input type="text"  name="ptitle[]" class="form-control">\
                        </div>\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 " style="margin-top: 10px;">\
                           <label for="team-name">Portfolio Description</label>\
                            <textarea  class="form-control with-light nicEdit" style="height: 200px !important;width: 100% !important;" id="about_me" name="pdesc[]" rows="5"  ></textarea>\
                        </div>\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 " style="margin-top: 10px;">\
                          <label for="team-name">Portfolio Image</label>\
                         <input type="file" class="form-control" name="images[]" >\
                        </div>\
    <div class="col-lg-2 col-md-2 col-sm-12 col-12"  style="margin-top: 10px;">\
        <button data-id="'+c_no4+'" type="button" class="btn btn-danger text-white border-rounded btn-sm removeportBtn">\
        <i class="fa fa-times"></i>\
        </button>\
    </div>\
</div>\
</div>';
$('.addportboxes').append(html2);
});
$(document).on('click', '.removeportBtn', function () {
    $(this).closest('.portRemove').remove();


});
</script>
  </body>
</html>
