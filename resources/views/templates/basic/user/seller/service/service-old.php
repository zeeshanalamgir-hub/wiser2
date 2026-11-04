 @php
        $ser_det=DB::table('services_details')->where('service_id',$service->id)->first();
        @endphp
        <!-- three services tiers -->
        
        <div  class="filtered-tiers">
            <div >
                <input  type="checkbox" class="checkbox" id="flexSwitchCheckDefault2" {{ $service->service_type == '1' ? 'checked':'' }}  />
                <label for="flexSwitchCheckDefault2" class="switch">
                    <span class="switch__circle">
                        <span class="switch__circle-inner"></span>
                    </span>
                    <span class="switch__left">Single</span>
                    <span class="switch__right">Multi</span>
                </label>
            </div>
            <input type="hidden" id="in" name="msval">
            <div class="row mt-3" id="a" style="display: none">
                <div class="col-lg-12 col-md-12">
                    <div class="_dashboard_content bg-white rounded mb-4">
                        
                        
                        <div class="_dashboard_content_body py-3 px-3 ">
                            <div class="_dashboard__header_flex">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                        <h4 class="mb-0 ft-medium fs-md">First Service</h4>
                                    </div>
                                    <div class=" col-lg-3 col-md-3 col-sm-4 col-4">
                                        <h4 class="mb-0 ft-medium fs-md">Second Service</h4>
                                    </div>
                                    <div class=" col-lg-3 col-md-3 col-sm-4 col-4">
                                        <h4 class="mb-0 ft-medium fs-md">Third Service</h4>
                                    </div>
                                </div>
                            </div>
                            <!-- title -->
                            <div class="row mt-2">
                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                    <h4 class="mb-0 ft-medium fs-md">Title</h4>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control rounded" placeholder="Title" value="{{$ser_det->title1}}" name="title1">
                                    </div>
                                </div>
                                <div class=" col-lg-3 col-md-3 col-sm-4 col-4">
                                    <input type="text" class="form-control rounded" placeholder="Title" value="{{$ser_det->title2}}" name="title2">
                                </div>
                                <div class=" col-lg-3 col-md-3 col-sm-4 col-4">
                                    <input type="text" class="form-control rounded" placeholder="Title" value="{{$ser_det->title3}}" name="title3">
                                </div>
                            </div>
                            <!-- title -->
                            <!-- description -->
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                    <h4 class="mb-0 ft-medium fs-md">Description</h4>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                    <div class="form-group">
                                        <textarea  name="desc1" class="form-control with-light" placeholder="Description">{{$ser_det->desc1}}</textarea>
                                    </div>
                                </div>
                                <div class=" col-lg-3 col-md-3 col-sm-4 col-4">
                                    <textarea  name="desc2" class="form-control with-light" placeholder="Description">{{$ser_det->desc2}}</textarea>
                                </div>
                                <div class=" col-lg-3 col-md-3 col-sm-4 col-4">
                                    <textarea  name="desc3" class="form-control with-light" placeholder="Description">{{$ser_det->desc3}}</textarea>
                                </div>
                            </div>
                            <!-- description -->
                            @if($service->service_type == '1')
                            @if(isset($service->category_id) )
                            @php
                            $tiers_select =DB::table('service_tiers')->where('type','select')->where('cat_id',$service->category_id)->get();
                            @endphp
                            @else
                            @php
                            $tiers_select =DB::table('service_tiers')->where('type','select')->where('sub_category_id',$service->sub_category_id)->get();
                            @endphp
                            @endif
                            <!-- @php
                                                        echo "<pre>";print_r($tiers_select);
                                @endphp -->
                                                                @foreach($tiers_select as $a) 
                                                                @php
                                                                $td1=DB::table('service_detail_tiers_basics')->where('service_id',$service->id)->where('service_tier_id',$a->id)->first();
                                                                $td2=DB::table('service_detail_tiers_premia')->where('service_id',$service->id)->where('service_tier_id',$a->id)->first();
                                                                $td3=DB::table('service_detail_tiers_standards')->where('service_id',$service->id)->where('service_tier_id',$a->id)->first();
                                                                @endphp
                                                                <div class="row">  
                                                                            @if(isset($td1->service_value_tier))
                                                                                                                    <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                                <h4 class="mb-0 ft-medium fs-md">{{$a->name}}</h4>
                                                                                                                                <input type="hidden" name="tier_id[]" value="{{$a->id}}">
                                                                                                                    </div>
                                                                                                                    <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                     <div class="form-group">
                                                                                                                                            <select class="form-control" name="tier_service_value1[]">
                                            {{ $td1->service_value_tier == '2' ? 'selected':'' }} value="2">2</option>
                                                                                                                                                    <option {{ $td1->service_value_tier == '3' ? 'selected':'' }} value="3">3</option>
                                                                                                                                                    <option {{ $td1->service_value_tier == '4' ? 'selected':'' }} value="4">4</option>
                                                                                                                                                    <option {{ $td1->service_value_tier == '5' ? 'selected':'' }} value="5">5</option>
                                                                                                                                                    <option {{ $td1->service_value_tier == '6' ? 'selected':'' }} value="6">6</option>
                                                                                                                                                    <option {{ $td1->service_value_tier == '7' ? 'selected':'' }} value="7">7</option>
                                                                                                                                                    <option {{ $td1->service_value_tier == '8' ? 'selected':'' }} value8</option>
                                                                                                                                                    <option {{ $td1->service_value_tier == '9' ? 'selected':'' }} value="9">9</option>
                                                                                                                                                    <option {{ $td1->service_value_tier == 'unlimited' ? 'selected':'' }} value="unlimited">Unlimited</option>
                                                                                                                                        </select>
                                                                                                                            </div>
                                                                                                                </div>
                                                                                                                @endif
                                                                                                                @if(isset($td2->service_value_tier))
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <div class="form-group">
                                                                                                                                        <select class="form-control" name="tier_service_value2[]">
                                                                                                                                                    <option>select {{$a->name}}</option>
                                                                                                                                                   <option {{ $td2->service_value_tier == '1' ? 'selected':'' }} value="1">1</option>
                                                                                                                                                    <option {{ $td2->service_value_tier == '2' ? 'selected':'' }} value="2">2</option>
                                                                                                                                                    <option {{ $td2->service_value_tier == '3' ? 'selected':'' }} value="3">3</option>
                                                                                                                                                    <option {{ $td2->service_value_tier == '4' ? 'selected':'' }} value="4">4</option>
                                                                                                                                                    <option {{ $td2->service_value_tier == '5' ? 'selected':'' }} value="5">5</option>
                                                                                                                                                    <option {{ $td2->service_value_tier == '6' ? 'selected':'' }} value="6">6</option>
                                                                                                                                                    <option {{ $td2->service_value_tier == '7' ? 'selected':'' }} value="7">7</option>
                                                                                                                                                    <option {{ $td2->service_value_tier == '9' ? 'selected':'' }} value="9">9</option>
                                                                                                                                                    <option {{ $td2->service_value_tier == 'unlimited' ? 'selected':'' }} value="unlimited">Unlimited</option>
                                                                                                                                        </select> 
                                                                                                                            </div>
                                                                                                                </div>
                                                                                                                @endif
                                                                                                                @if(isset($td3->service_value_tier))
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <div class="form-group">
                                                                                                                                        <select class="form-control" name="tier_service_value3[]">
                                                                                                                                                    <option>select {{$a->name}}</option>
                                                                                                                                                   <option {{ $td3->service_value_tier == '1' ? 'selected':'' }} value="1">1</option>
                                                                                                                                                    <option {{ $td3->service_value_tier == '2' ? 'selected':'' }} value="2">2</option>
                                                                                                                                                    <option {{ $td3->service_value_tier == '3' ? 'selected':'' }} value="3">3</option>
                                                                                                                                                    <option {{ $td3->service_value_tier == '4' ? 'selected':'' }} value="4">4</option>
                                                                                                                                                    <option {{ $td3->service_value_tier == '5' ? 'selected':'' }} value="5">5</option>
                                                                                                                                                    <option {{ $td3->service_value_tier == '6' ? 'selected':'' }} value="6">6</option>
                                                                                                                                                    <option {{ $td3->service_value_tier == '7' ? 'selected':'' }} value="7">7</option>
                                                                                                                                                    <option {{ $td3->service_value_tier == '8' ? 'selected':'' }} value="8">8</option>
                                                                                                                                                    <option {{ $td3->service_value_tier == '9' ? 'selected':'' }} value="9">9</option>
                                                                                                                                                    <option {{ $td3->service_value_tier == 'unlimited' ? 'selected':'' }} value="unlimited">Unlimited</option>
                                                                                                                                        </select>
                                                                                                                            </div>
                                                                                                                </div>
                                                                                                                @endif
                                                                                                    </div>
                                                                                                    @endforeach
                                                                                                    
                                                             
                                                             @if(isset($service->category_id) )
                                                             @php
                                                            $tiers_input =DB::table('service_tiers')->where('type','input')->where('cat_id',$service->category_id)->get();
                                                            @endphp
                                                            @else
                                                            @php
                                                            $tiers_input =DB::table('service_tiers')->where('type','input')->where('sub_category_id',$service->sub_category_id)->get();
                                                            @endphp
                                                            @endif
                                                            @foreach($tiers_input as $a)
                                                            @php
                                                            $td1=DB::table('service_detail_tiers_basics')->where('service_id',$service->id)->where('service_tier_id',$a->id)->first();
                                                            $td2=DB::table('service_detail_tiers_premia')->where('service_id',$service->id)->where('service_tier_id',$a->id)->first();
                                                            $td3=DB::table('service_detail_tiers_standards')->where('service_id',$service->id)->where('service_tier_id',$a->id)->first();
                                                            @endphp
                                                            <div class="row">
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <h4 class="mb-0 ft-medium fs-md">{{$a->name}}</h4>
                                                                                                                            <input type="hidden" name="tier_id[]"  value="{{$a->id}}">
                                                                                                                </div>
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <div class="form-group">
                                                                                                                                        <div class="form-group">
                                                                                                                                                    @if(isset($td1->service_value_tier))
                                                                                                                                                    <input type="text" class="form-control rounded" placeholder="{{$a->name}}" value="{{$td1->service_value_tier}}" name="tier_service_value1[]">
                                                                                                                                                    @endif
                                                                                                                                        </div>
                                                                                                                            </div>
                                                                                                                </div>
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <div class="form-group">
                                                                                                                                        <div class="form-group">
                                                                                                                                                    @if(isset($td2->service_value_tier))
                                                                                                                                                    <input type="text" class="form-control rounded" placeholder="{{$a->name}}" value="{{$td2->service_value_tier}}" name="tier_service_value2[]">
                                                                                                                                                    @endif
                                                                                                                                        </div>
                                                                                                                            </div>
                                                                                                                </div>
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <div class="form-group">
                                                                                                                                        <div class="form-group">
                                                                                                                                                    @if(isset($td3->service_value_tier))
                                                                                                                                                    <input type="text" class="form-control rounded" placeholder="{{$a->name}}" value="{{$td3->service_value_tier}}" name="tier_service_value3[]">
                                                                                                                                                    @endif
                                                                                                                                        </div>
                                                                                                                            </div>
                                                                                                                </div>
                                                                                                    </div>
                                                                                                    @endforeach
                                                                                                     
                                                             @if(isset($service->category_id) )
                                                             @php
                                                            $tiers_checkbox =DB::table('service_tiers')->where('type','checkbox')->where('cat_id',$service->category_id)->get();
                                                            @endphp
                                                            @else
                                                            @php
                                                            $tiers_checkbox =DB::table('service_tiers')->where('type','checkbox')->where('sub_category_id',$service->sub_category_id)->get();
                                                            @endphp
                                                            @endif
                                                            @foreach($tiers_checkbox as $a)
                                                            @php
                                                            $td1=DB::table('service_detail_tiers_basics')->where('service_id',$service->id)->where('service_tier_id',$a->id)->first();
                                                            $td2=DB::table('service_detail_tiers_premia')->where('service_id',$service->id)->where('service_tier_id',$a->id)->first();
                                                            $td3=DB::table('service_detail_tiers_standards')->where('service_id',$service->id)->where('service_tier_id',$a->id)->first();
                                                            @endphp
                                                            <div class="row">
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <h4 class="mb-0 ft-medium fs-md">{{$a->name}}</h4>
                                                                                                                            <input type="hidden" name="tier_id[]"   value="{{$a->id}}">
                                                                                                                </div>
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <div class="form-group">
                                                                                                                                        @if(isset($td1->service_value_tier))
                                                                                                                                        <input type="checkbox" {{ $td1->service_value_tier == 'yes' ? 'checked':'' }} name="tier_service_value1[]" value="yes" id="checkBoxwe">
                                                                                                                                        @endif
                                                                                                                            </div>
                                                                                                                </div>
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <div class="form-group">
                                                                                                                                        @if(isset($td2->service_value_tier))
                                                                                                                                        <input type="checkbox" {{ $td2->service_value_tier == 'yes' ? 'checked':'' }} name="tier_service_value2[]" value="yes" id="checkBoxwe">
                                                                                                                                        @endif
                                                                                                                            </div>
                                                                                                                </div>
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <div class="form-group">
                                                                                                                                        @if(isset($td3->service_value_tier))
                                                                                                                                        <input type="checkbox" {{ $td3->service_value_tier == 'yes' ? 'checked':'' }} name="tier_service_value3[]" value="yes" id="checkBoxwe">
                                                                                                                                        @endif
                                                                                                                            </div>
                                                                                                                </div>
                                                                                                    </div>
                                                                                                    @endforeach
                            <!-- Price -->
                                                                                                    <div class="row">
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <h4 class="mb-0 ft-medium fs-md">Price</h4>
                                                                                                                </div>
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <div class="form-group">
                                                                                                                                        <input type="text" class="form-control rounded" placeholder="Price" value="{{$ser_det->price1}}"  name="price1">
                                                                                                                            </div>
                                                                                                                </div>
                                                                                                                <div class=" col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <input type="text" class="form-control rounded" placeholder="Price" value="{{$ser_det->price2}}" name="price2">
                                                                                                                </div>
                                                                                                                <div class=" col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <input type="text" class="form-control rounded" placeholder="Price" value="{{$ser_det->price3}}" name="price3">
                                                                                                                </div>
                                                                                                    </div>
                            <!-- Price -->
                            <!-- revisions -->
                                                                                                    <div class="row">
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <h4 class="mb-0 ft-medium fs-md">Revisions</h4>
                                                                                                                </div>
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <div class="form-group">
                                                                                                                                        <select class="form-control" name="revision1">
                                                                                                                                                    <option>Select Revisions</option>
                                                                                                                                                    <option {{ $ser_det->revision1 == '1' ? 'selected':'' }} value="1">1</option>
                                                                                                                                                    <option {{ $ser_det->revision1 == '2' ? 'selected':'' }} value="2">2</option>
                                                                                                                                                    <option {{ $ser_det->revision1 == '3' ? 'selected':'' }} value="3">3</option>
                                                                                                                                                    <option {{ $ser_det->revision1 == '4' ? 'selected':'' }} value="4">4</option>
                                                                                                                                                    <option {{ $ser_det->revision1 == '5' ? 'selected':'' }} value="5">5</option>
                                                                                                                                                    <option {{ $ser_det->revision1 == '6' ? 'selected':'' }} value="6">6</option>
                                                                                                                                                    <option {{ $ser_det->revision1 == '7' ? 'selected':'' }} value="7">7</option>
                                                                                                                                                    <option {{ $ser_det->revision1 == '8' ? 'selected':'' }} value="8">8</option>
                                                                                                                                                    <option {{ $ser_det->revision1 == '9' ? 'selected':'' }} value="9">9</option>
                                                                                                                                                    <option {{ $ser_det->revision1 == 'unlimited' ? 'selected':'' }} value="unlimited">Unlimited</option>
                                                                                                                                        </select>
                                                                                                                            </div>
                                                                                                                </div>
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <div class="form-group">
                                                                                                                                        <select class="form-control" name="revision2">
                                                                                                                                                    <option>Select Revisions</option>
                                                                                                                                                      <option {{ $ser_det->revision2 == '1' ? 'selected':'' }} value="1">1</option>
                                                                                                                                                    <option {{ $ser_det->revision2 == '2' ? 'selected':'' }} value="2">2</option>
                                                                                                                                                    <option {{ $ser_det->revision2 == '3' ? 'selected':'' }} value="3">3</option>
                                                                                                                                                    <option {{ $ser_det->revision2 == '4' ? 'selected':'' }} value="4">4</option>
                                                                                                                                                    <option {{ $ser_det->revision2 == '5' ? 'selected':'' }} value="5">5</option>
                                                                                                                                                    <option {{ $ser_det->revision2 == '6' ? 'selected':'' }} value="6">6</option>
                                                                                                                                                    <option {{ $ser_det->revision2 == '7' ? 'selected':'' }} value="7">7</option>
                                                                                                                                                    <option {{ $ser_det->revision2 == '8' ? 'selected':'' }} value="8">8</option>
                                                                                                                                                    <option {{ $ser_det->revision2 == '9' ? 'selected':'' }} value="9">9</option>
                                                                                                                                                    <option {{ $ser_det->revision2 == 'unlimited' ? 'selected':'' }} value="unlimited">Unlimited</option>
                                                                                                                                        </select>
                                                                                                                            </div>
                                                                                                                </div>
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <div class="form-group">
                                                                                                                                        <select class="form-control" name="revision3">
                                                                                                                                                    <option>Select Revisions</option>
                                                                                                                                                      <option {{ $ser_det->revision3 == '1' ? 'selected':'' }} value="1">1</option>
                                                                                                                                                    <option {{ $ser_det->revision3 == '2' ? 'selected':'' }} value="2">2</option>
                                                                                                                                                    <option {{ $ser_det->revision3 == '3' ? 'selected':'' }} value="3">3</option>
                                                                                                                                                    <option {{ $ser_det->revision3 == '4' ? 'selected':'' }} value="4">4</option>
                                                                                                                                                    <option {{ $ser_det->revision3 == '5' ? 'selected':'' }} value="5">5</option>
                                                                                                                                                    <option {{ $ser_det->revision3 == '6' ? 'selected':'' }} value="6">6</option>
                                                                                                                                                    <option {{ $ser_det->revision3 == '7' ? 'selected':'' }} value="7">7</option>
                                                                                                                                                    <option {{ $ser_det->revision3 == '8' ? 'selected':'' }} value="8">8</option>
                                                                                                                                                    <option {{ $ser_det->revision3 == '9' ? 'selected':'' }} value="9">9</option>
                                                                                                                                                    <option {{ $ser_det->revision3 == 'unlimited' ? 'selected':'' }} value="unlimited">Unlimited</option>
                                                                                                                                        </select>
                                                                                                                            </div>
                                                                                                                </div>
                                                                                                    </div>
                            <!-- revisions -->
                            <!-- Delivery Time -->
                                                                                                    <div class="row">
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <h4 class="mb-0 ft-medium fs-md">Delivery Time</h4>
                                                                                                                </div>  
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <div class="form-group">
                                                                                                                                        <input type="text" class="form-control rounded" placeholder="Delivery Time" value="{{$ser_det->time1}}" name="time1">
                                                                                                                            </div>
                                                                                                                </div>
                                                                                                                <div class=" col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <input type="text" value="{{$ser_det->time2}}" class="form-control rounded" placeholder="Delivery Time" name="time2">
                                                                                                                </div>
                                                                                                                <div class=" col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <input type="text" class="form-control rounded" placeholder="Delivery Time" value="{{$ser_det->time3}}" name="time3">
                                                                                                                </div>
                                                                                                    </div>
                            <!-- Delivery Time -->
                                                                                                    
                                                                                        </div>
                                                                            </div>
                                                                </div>
                                                    </div>
                                                    @endif
            <!-- single -->
                            @if($service->service_type == '0')
                                                    <div class="row mt-3" id="b" style="display: block;">
                                                                                <div class="col-lg-12 col-md-12">
                                                                            <div class="_dashboard_content bg-white rounded mb-4">
                                                                                        
                                                                                        
                                                                                        <div class="_dashboard_content_body py-3 px-3 ">
                                                            <div class="_dashboard__header_flex">
                                                                                                                <div class="row">
                                                                                                                            <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            </div>
                                                                                                                            <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                                        <h4 class="mb-0 ft-medium fs-md">First Service</h4>
                                                                                                                            </div>
                                                                                                                            
                                                                                                                </div>
                                                                                                    </div>
                            <!-- title -->
                                                                                                    <div class="row mt-2">
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <h4 class="mb-0 ft-medium fs-md">Title</h4>
                                                                                                                </div>
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <div class="form-group">
                                                                                                                                        <input type="text" class="form-control rounded" placeholder="Title" name="titlesin">
                                                                                                                            </div>
                                                                                                                </div>
                                                                                                                
                                                                                                    </div>
                            <!-- title -->
                            <!-- description -->
                                                                                                    <div class="row">
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <h4 class="mb-0 ft-medium fs-md">Description</h4>
                                                                                                                </div>
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <div class="form-group">
                                                                                                                                        <textarea  name="descsin" class="form-control with-light" placeholder="Description"></textarea>
                                                                                                                            </div>
                                                                                                                </div>
                                                                                                                
                                                                                                    </div>
                            <!-- description -->
                                                            @if(isset($service->childcat_id) )
                                                            @php
                                                            $tiers_select =DB::table('service_tiers')->where('type','select')->where('childcat_id',$service->childcat_id)->get();
                                                            @endphp
                                                            @else
                                                            @php
                                                            $tiers_select =DB::table('service_tiers')->where('type','select')->where('sub_category_id',$service->sub_category_id)->get();
                                                            @endphp
                                                            @endif 
                                                            @foreach($tiers_select as $a) 
                                                            @php
                                                            $td1=DB::table('service_detail_tiers_singles')->where('service_id',$service->id)->where('service_tier_id',$a->id)->first();
                                                            @endphp
                                                            <div class="row">
                                                                        @if(isset($td1->service_value_tier))
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <h4 class="mb-0 ft-medium fs-md">{{$a->name}}</h4>
                                                                                                                            <input type="hidden" name="tier_id[]" value="{{$a->id}}">
                                                                                                                </div>
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <div class="form-group">
                                                                                                                                        <select class="form-control" name="tier_service_value1[]">
                                                                                                                                                    <option {{ $td1->service_value_tier == '
                                                                                                                                                    ' ? 'selected':'' }} value="">select {{$a->name}}</option>
                                                                                                                                                    <option {{ $td1->service_value_tier == '1' ? 'selected':'' }} value="1">1</option>
                                                                                                                                                    <option {{ $td1->service_value_tier == '2' ? 'selected':'' }} value="2">2</option>
                                                                                                                                                    <option {{ $td1->service_value_tier == '3' ? 'selected':'' }} value="3">3</option>
                                                                                                                                                    <option {{ $td1->service_value_tier == '4' ? 'selected':'' }} value="4">4</option>
                                                                                                                                                    <option {{ $td1->service_value_tier == '5' ? 'selected':'' }} value="5">5</option>
                                                                                                                                                    <option {{ $td1->service_value_tier == '6' ? 'selected':'' }} value="6">6</option>
                                                                                                                                                    <option {{ $td1->service_value_tier == '7' ? 'selected':'' }} value="7">7</option>
                                                                                                                                                    <option {{ $td1->service_value_tier == '8' ? 'selected':'' }} value="8">8</option>
                                                                                                                                                    <option {{ $td1->service_value_tier == '9' ? 'selected':'' }} value="9">9</option>
                                                                                                                                                    <option {{ $td1->service_value_tier == 'unlimited' ? 'selected':'' }} value="unlimited">Unlimited</option>
                                                                                                                                        </select>
                                                                                                                            </div>
                                                                                                                </div>
                                                                                                                @endif
                                                                                                    </div>
                                                                                                    @endforeach
                                                                                                    
                                                             
                                                             @if(isset($service->childcat_id) )
                                                             @php
                                                            $tiers_input =DB::table('service_tiers')->where('type','input')->where('childcat_id',$service->childcat_id)->get();
                                                            @endphp
                                                            @else
                                                            @php
                                                            $tiers_input =DB::table('service_tiers')->where('type','input')->where('sub_category_id',$service->sub_category_id)->get();
                                                            @endphp
                                                            @endif
                                                            @foreach($tiers_input as $a)
                                                            @php
                                                            $td1=DB::table('service_detail_tiers_singles')->where('service_id',$service->id)->where('service_tier_id',$a->id)->first();
                                                            @endphp
                                                            <div class="row">
                                                                        @if(isset($td1->service_value_tier))
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <h4 class="mb-0 ft-medium fs-md">{{$a->name}}</h4>
                                                                                                                            <input type="hidden" name="tier_id[]"  value="{{$a->id}}">
                                                                                                                </div>
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <div class="form-group">
                                                                                                                                        <div class="form-group">
                                                                                                                                                    
                                                                                                                                                    <input type="text" class="form-control rounded" placeholder="{{$a->name}}" value="{{$td1->service_value_tier}}" name="tier_service_value1[]">
                                                                                                                                        </div>
                                                                                                                            </div>
                                                                                                                </div>
                                                                                                                @endif
                                                                                                    </div>
                                                                                                    @endforeach
                                                                                                     
                                                             
                                                             @if(isset($service->childcat_id) )
                                                             @php
                                                            $tiers_checkbox =DB::table('service_tiers')->where('type','checkbox')->where('childcat_id',$service->childcat_id)->get();
                                                            @endphp
                                                            @else
                                                            @php
                                                            $tiers_checkbox =DB::table('service_tiers')->where('type','checkbox')->where('sub_category_id',$service->sub_category_id)->get();
                                                            @endphp
                                                            @endif
                                                            @foreach($tiers_checkbox as $a)
                                                            @php
                                                            $td1=DB::table('service_detail_tiers_singles')->where('service_id',$service->id)->where('service_tier_id',$a->id)->first();
                                                            @endphp
                                                            <div class="row">
                                                                        @if(isset($td1->service_value_tier))
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <h4 class="mb-0 ft-medium fs-md">{{$a->name}}</h4>
                                                                                                                            <input type="hidden" name="tier_id[]"   value="{{$a->id}}">
                                                                                                                </div>
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <div class="form-group">
                                                                                                                                        <input type="checkbox" {{ $td1->service_value_tier == 'yes' ? 'checked':'' }} name="tier_service_value1[]" value="yes" id="checkBoxwe">
                                                                                                                            </div>
                                                                                                                </div>
                                                                                                                @endif
                                                                                                    </div>
                                                                                                    @endforeach
                            <!-- Price -->
                                                                                                    <div class="row">
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <h4 class="mb-0 ft-medium fs-md">Price</h4>
                                                                                                                </div>
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <div class="form-group">
                                                                                                                                        <input type="text" class="form-control rounded" placeholder="Price"  name="pricesin">
                                                                                                                            </div>
                                                                                                                </div>
                                                                                                                
                                                                                                    </div>
                            <!-- Price -->
                            <!-- revisions -->
                                                                                                    <div class="row">
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <h4 class="mb-0 ft-medium fs-md">Revisions</h4>
                                                                                                                </div>
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <div class="form-group">
                                                                                                                                        <select class="form-control" name="revision1">
                                                                                                                                                    <option>Select Revisions</option>
                                                                                                                                                    <option {{ $ser_det->revision1 == '1' ? 'selected':'' }} value="1">1</option>
                                                                                                                                                    <option {{ $ser_det->revision1 == '2' ? 'selected':'' }} value="2">2</option>
                                                                                                                                                    <option {{ $ser_det->revision1 == '3' ? 'selected':'' }} value="3">3</option>
                                                                                                                                                    <option {{ $ser_det->revision1 == '4' ? 'selected':'' }} value="4">4</option>
                                                                                                                                                    <option {{ $ser_det->revision1 == '5' ? 'selected':'' }} value="5">5</option>
                                                                                                                                                    <option {{ $ser_det->revision1 == '6' ? 'selected':'' }} value="6">6</option>
                                                                                                                                                    <option {{ $ser_det->revision1 == '7' ? 'selected':'' }} value="7">7</option>
                                                                                                                                                    <option {{ $ser_det->revision1 == '8' ? 'selected':'' }} value="8">8</option>
                                                                                                                                                    <option {{ $ser_det->revision1 == '9' ? 'selected':'' }} value="9">9</option>
                                                                                                                                                    <option {{ $ser_det->revision1 == 'unlimited' ? 'selected':'' }} value="unlimited">Unlimited</option>
                                                                                                                                        </select>
                                                                                                                            </div>
                                                                                                                </div>
                                                                                                    </div>
                            <!-- revisions -->
                            <!-- Delivery Time -->
                                                                                                    <div class="row">
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <h4 class="mb-0 ft-medium fs-md">Delivery Time</h4>
                                                                                                                </div>  
                                                                                                                <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                                                                                                                            <div class="form-group">
                                                                                                                                        <input type="text" class="form-control rounded" placeholder="Delivery Time" name="timesin">
                                                                                                                            </div>
                                                                                                                </div>
                                                                                                                
                                                                                                    </div>
                            <!-- Delivery Time -->
                                                                                                    
                                                                                        </div>
                                                                            </div>
                                                                </div>      
                                                    </div>
                                                            </div>
                     
        <!-- single services tiers -->
                                                                
                                            @endif
                                            
                                            
        <!-- Add Skills -->
                                            <div class="row mb-5">
                                                        <div class="col-lg-12 col-md-12">
                                                                    <div class="form-group">
                                                                            <button type="submit" class="btn btn-md ft-medium text-light rounded theme-bg">Update Service</button>
                                                                </div>
                                                                
                                                    </div>
                                                    
                                        </div>
                                </form>
            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
                        aria-hidden="true">
                        <div class="modal-dialog " role="document">
                                    <div class="modal-content">
                                                <div class="modal-header">
                                                            <h4 class="modal-title" id="ModalLabel">@lang('Delete Confirmation')</h4>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                            </button>         
                                                </div>
                                                <form method="POST" action="{{ route('user.optional.image') }}">
                                                            @csrf
                                                            <input type="hidden" name="id">
                                                            <div class="modal-body">
                                                                        <p>@lang('Are you sure to remove this optional image')</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                        <button type="button" class="btn btn--danger btn-rounded text-white" data-bs-dismiss="modal">@lang('Close')</button>
                                                                         <button type="submit" class="btn btn--success btn-rounded text-white">@lang('Submit')</button>
                                                            </div>
                                                </form>
                                    </div>
                        </div>
            </div>