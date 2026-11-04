
@extends($activeTemplate.'layouts.wiserlance.dashboard-layout')
@section('content')


<div class="dashboard-content">
					<div class="dashboard-tlbar d-block mb-5">
						<div class="row">
							<div class="colxl-12 col-lg-12 col-md-12">
								<h1 class="ft-medium">{{__($pageTitle)}}</h1>
								<nav aria-label="breadcrumb">
									<ol class="breadcrumb">
										<li class="breadcrumb-item text-muted"><a href="landing2.html">Home</a></li>
										<li class="breadcrumb-item text-muted"><a href="dashboard.html">Dashboard</a></li>
										<li class="breadcrumb-item"><a href="dashboard-add-service.html" class="theme-cl">Create Service</a></li>
									</ol>
								</nav>
							</div>
						</div> 
					</div>
					<form action="{{route('user.service.store')}}" method="POST" enctype="multipart/form-data">
					@csrf
                        				 
					<div class="dashboard-widg-bar d-block">
						<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="_dashboard_content bg-white rounded mb-4">
									<div class="_dashboard_content_header br-bottom py-3 px-3">
										<div class="_dashboard__header_flex">
											<h4 class="mb-0 ft-medium fs-md"><i class="fa fa-file mr-1 theme-cl fs-sm"></i>Create Service</h4>
										</div>
									</div>
									
									<div class="_dashboard_content_body py-3 px-3">
										<div class="row" >
											<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
												<div class="custom-file avater_uploads">
													<input type="file" class="custom-file-input profilePicUpload"  name="image" id="profilePicUpload2" accept=".png, .jpg, .jpeg">
													<label class="custom-file-label" for="customFile"><i class="fa fa-user"></i></label>
												</div>
												<p class="mt-2" style="font-size:10px;color:red;">@lang('Supported files'): @lang('jpeg'), @lang('jpg'), @lang('png'). @lang('Image will be resized into 590x300 px')</p>
											</div>
											
											<div class="col-xl-9


											 col-lg-9 col-md-9 col-sm-12">
												<div class="row">
													<div class="col-xl-12 col-lg-12">
														<div class="form-group">
															<label class="text-dark ft-medium">@lang('Optional Image')</label>
															<input type="file" class="form-control rounded" placeholder="Additional Image" multiple>
														</div>
													</div>
													<div class="col-xl-6 col-lg-6">
														<div class="form-group">
															<label class="text-dark ft-medium">@lang('Title')*</label>
															<input  class="form-control rounded" type="text" name="title" maxlength="255" value="{{old('title')}}"  placeholder="@lang("Titles for displaying on your services (e.g., Expert IOS mobile application developer)")" required="">
														</div>
													</div>
													<div class="col-xl-6 col-lg-6">
														<div class="form-group">
															<label class="text-dark ft-medium">@lang('Post as a')*</label>
															<select name="priority" id="priority" class="form-control rounded">
																 @if($userservices[0]->normal_services > 0)
                                                <option value="0">Normal</option>
                                                @endif
                                                @if ($userservices[0]->priority_services > 0)
                                                <option value="1">Priority</option>
                                                @endif
                                                @if($userservices[0]->featured_services > 0)
                                                <option value="2">Featured</option>
                                                @endif
															</select>
														</div>
													</div>
													<div class="col-xl-12 col-lg-12 ">
                                            <div class="form-group">
                                            <label class="text-dark ft-medium">@lang('Description')*</label>
                                            <textarea class="form-control with-light" name="description" placeholder="Description">{{old('description')}}</textarea>
                                        </div>
                                    </div>
													<div class="col-xl-6 col-lg-6">
														<div class="form-group">
															<label class="text-dark ft-medium">@lang('Category')*</label>
															<select class="form-control rounded" name="category" id="category">
																<option selected="" disabled="">@lang('Select Category')</option>
																@foreach($categorys as $category)
                                                    <option value="{{__($category->id)}}">{{__($category->name)}}</option>
                                                @endforeach
																
															</select>
														</div>
													</div>
													<div class="col-xl-6 col-lg-6">
														<div class="form-group">
															<label class="text-dark ft-medium">@lang('Sub Category')</label>
															<select name="subcategory" class="form-control rounded mySubCatgry" id="subCategorys">
																
																
															</select>
														</div>
													</div>
													<div class="col-xl-12 col-lg-12">
														<div class="form-group">
															<label class="text-dark ft-medium">@lang('Find skills or add yours')*</label>
															{{-- <select onselect="onselect()" class="form-control select2" id="search_skill" name="fskill" multiple="multiple" required="">
                                            </select> --}}
															<input onkeyup="getSkillbuname(this.value)" id="search_skill"  class="form-control rounded" type="text" autocomplete="off" placeholder="Search ..."  >
														</div>
													</div>
													<div id="skil_result" class="col-xl-12 col-lg-12 form-group">

                                        </div>
                                     
                                        <div id="skills_result" class="col-xl-12 col-lg-12 form-group">

                                        </div>
                                        <div class="col-xl-12 col-lg-12 form-group">
                                            <div id="result">
                                     
                                            </div>
                                        </div>
                                         
                                        </div>
												<input type="hidden" name="price" value="123">	
													<!--  <div class="col-xl-12 form-group">
                                            <button type="submit" class="submit-btn mt-20 w-100">@lang('CREATE SERVICE')</button>
                                        </div> -->
												</div>
											</div>
											
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<!-- Add Service -->
						<!-- <div class="row">
							<div class="col-lg-12 col-md-12">
								<div class="_dashboard_content bg-white rounded mb-4">
									<div class="_dashboard_content_header br-bottom py-3 px-3">
										<div class="_dashboard__header_flex">
											<h4 class="mb-0 ft-medium fs-md"><i class="	fa fa-bullhorn mr-1 theme-cl fs-sm"></i>Single Service Options</h4>
										</div>
									</div>
									
									<div class="_dashboard_content_body py-3 px-3">
										
										<div class="row" >
											<div class="col-xl-6 col-lg-6">
												<div class="form-group">
													<label class="text-dark ft-medium">Price*</label>
													<input type="text" class="form-control rounded" placeholder="$">
												</div>
											</div>
											<div class="col-xl-6 col-lg-6">
												<div class="form-group">
													<label class="text-dark ft-medium">Delivery Time</label>
													<input type="text" class="form-control rounded" placeholder="Days">
												</div>
											</div>
											<div class="col-xl-12 col-lg-12">
												<div class="form-group">
													<label class="text-dark ft-medium">Description*</label>
													<textarea class="form-control with-light" placeholder="Description"></textarea>
												</div>
											</div>
											<div class="col-xl-12 col-lg-12">
												<div class="form-group">
													<label class="text-dark ft-medium">Service Options</label>
													
												</div>
											</div>
											
										</div>
										
											<div class="row">
												<div class="col-xl-4 col-lg-4 col-sm-6 col-6">
													<div class="form-group">
														<label class="text-dark ft-medium">Option 1</label>
														<input type="text" class="form-control rounded" placeholder="Option 1">
													</div>
												</div>
												<div class="col-xl-4 col-lg-4 col-sm-6 col-6">
													<div class="form-group">
														
														<label class="text-dark ft-medium">Option 2</label>
														<input type="text" class="form-control rounded" placeholder="Option 2">
													</div>
												</div>
												<div class="col-xl-4 col-lg-4 col-sm-6 col-6">
													<div class="form-group">
														<label class="text-dark ft-medium">Option 3</label>
														<input type="text" class="form-control rounded" placeholder="Option 3">
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-xl-4 col-lg-4 col-sm-6 col-6">
													<div class="form-group">
														<label class="text-dark ft-medium">Option 4</label><br>
														<input type="checkbox"  >
													</div>
												</div>
												<div class="col-xl-4 col-lg-4 col-sm-6 col-6">
													<div class="form-group">
														
														<label class="text-dark ft-medium">Option 5</label><br>
														<input type="checkbox"  >
													</div>
												</div>
												<div class="col-xl-4 col-lg-4 col-sm-6 col-6">
													<div class="form-group">
														<label class="text-dark ft-medium">Option 5</label><br>
														<input type="checkbox"  >
													</div>
												</div>
											</div>
										
										
									</div>
								</div>
							</div>
						</div> -->
						
						<!-- three services -->
						<div class="row">
							<div class="col-lg-12 col-md-12">
								<div class="_dashboard_content bg-white rounded mb-4">
									<div class="_dashboard_content_header br-bottom py-3 px-3">
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
									</div>
									
									<div class="_dashboard_content_body py-3 px-3">
										<!-- title -->
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<h4 class="mb-0 ft-medium fs-md">Title</h4>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<div class="form-group">
													<input type="text" class="form-control rounded" placeholder="Title" name="title1">
												</div>
											</div>
											<div class=" col-lg-3 col-md-3 col-sm-4 col-4">
												<input type="text" class="form-control rounded" placeholder="Title"  name="title2">
											</div>
											<div class=" col-lg-3 col-md-3 col-sm-4 col-4">
												<input type="text" class="form-control rounded" placeholder="Title"  name="title3">
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
													<textarea  name="desc1" class="form-control with-light" placeholder="Description"></textarea>
												</div>
											</div>
											<div class=" col-lg-3 col-md-3 col-sm-4 col-4">
												<textarea  name="desc2" class="form-control with-light" placeholder="Description"></textarea>
											</div>
											<div class=" col-lg-3 col-md-3 col-sm-4 col-4">
												<textarea  name="desc3" class="form-control with-light" placeholder="Description"></textarea>
											</div>
										</div>
										<!-- description -->

									</div>
								</div>
							</div>
						</div>
						<!-- three services -->

						<!-- three services-part2 -->
						<div class="row">
							<div class="col-lg-12 col-md-12">
								<div class="_dashboard_content bg-white rounded mb-4">
									
									
									<div class="_dashboard_content_body py-3 px-3 filtered-tiers">
										No Tiers Selected
										<!-- option -->
										<!-- @php
										$a=DB::table('service_tiers')->where('type','input')->where('service_tiers.cat_id',5)->where('service_tiers.subcat_id',3)->get();
										@endphp
										@foreach($a as $as)
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<h4 class="mb-0 ft-medium fs-md">{{$as->name}}</h4>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<div class="form-group">
													<input type="text" class="form-control rounded" placeholder="option here">
												</div>
											</div>
											<div class=" col-lg-3 col-md-3 col-sm-4 col-4">
												<input type="text" class="form-control rounded" placeholder="option here">
											</div>
											<div class=" col-lg-3 col-md-3 col-sm-4 col-4">
												<input type="text" class="form-control rounded" placeholder="option here">
											</div>
										</div>
										@endforeach -->
										<!-- option -->

										<!-- option -->
										<!-- <div class="row">
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<h4 class="mb-0 ft-medium fs-md">option 2</h4>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<div class="form-group">
													<input type="text" class="form-control rounded" placeholder="option here">
												</div>
											</div>
											<div class=" col-lg-3 col-md-3 col-sm-4 col-4">
												<input type="text" class="form-control rounded" placeholder="option here">
											</div>
											<div class=" col-lg-3 col-md-3 col-sm-4 col-4">
												<input type="text" class="form-control rounded" placeholder="option here">
											</div>
										</div> -->
										<!-- option -->

										<!-- option -->
										<!-- <div class="row">
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<h4 class="mb-0 ft-medium fs-md">option 3</h4>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<div class="form-group">
													<input type="text" class="form-control rounded" placeholder="option here">
												</div>
											</div>
											<div class=" col-lg-3 col-md-3 col-sm-4 col-4">
												<input type="text" class="form-control rounded" placeholder="option here">
											</div>
											<div class=" col-lg-3 col-md-3 col-sm-4 col-4">
												<input type="text" class="form-control rounded" placeholder="option here">
											</div>
										</div> -->
										<!-- option -->


										<!-- option -->
										<!-- @php
										$a=DB::table('service_tiers')->where('type','checkbox')->where('service_tiers.cat_id',5)->where('service_tiers.subcat_id',3)->get();
										@endphp
										@foreach($a as $as)
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<h4 class="mb-0 ft-medium fs-md">{{$as->name}}</h4>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<div class="form-group">
													<input type="checkbox" >
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<div class="form-group">
													<input type="checkbox" >
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<div class="form-group">
													<input type="checkbox" >
												</div>
											</div>

										</div>
										@endforeach -->
										<!-- option -->
										<!-- option -->
										<!-- <div class="row">
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<h4 class="mb-0 ft-medium fs-md">option 5</h4>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<div class="form-group">
													<input type="checkbox" >
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<div class="form-group">
													<input type="checkbox" >
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<div class="form-group">
													<input type="checkbox" >
												</div>
											</div>

										</div> -->
										<!-- option -->
										<!-- option -->
										<!-- <div class="row">
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<h4 class="mb-0 ft-medium fs-md">option 6</h4>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<div class="form-group">
													<input type="checkbox" >
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<div class="form-group">
													<input type="checkbox" >
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<div class="form-group">
													<input type="checkbox" >
												</div>
											</div>

										</div> -->
										<!-- option -->
										

									</div>
								</div>
							</div>
						</div>
						<!-- three services-part2 -->
						<!-- three services-part3 -->
						<div class="row">
							<div class="col-lg-12 col-md-12">
								<div class="_dashboard_content bg-white rounded mb-4">
									
									
									<div class="_dashboard_content_body py-3 px-3">
										<!-- Price -->
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<h4 class="mb-0 ft-medium fs-md">Price</h4>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<div class="form-group">
													<input type="text" class="form-control rounded" placeholder="Price"  name="price1">
												</div>
											</div>
											<div class=" col-lg-3 col-md-3 col-sm-4 col-4">
												<input type="text" class="form-control rounded" placeholder="Price" name="price2">
											</div>
											<div class=" col-lg-3 col-md-3 col-sm-4 col-4">
												<input type="text" class="form-control rounded" placeholder="Price" name="price3">
											</div>
										</div>
										<!-- Price -->
										<!-- Delivery Time -->
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<h4 class="mb-0 ft-medium fs-md">Delivery Time</h4>
											</div>  
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<div class="form-group">
													<input type="text" class="form-control rounded" placeholder="Delivery Time" name="time1">
												</div>
											</div>
											<div class=" col-lg-3 col-md-3 col-sm-4 col-4">
												<input type="text" class="form-control rounded" placeholder="Delivery Time" name="time2">
											</div>
											<div class=" col-lg-3 col-md-3 col-sm-4 col-4">
												<input type="text" class="form-control rounded" placeholder="Delivery Time" name="time3">
											</div>
										</div>
										<!-- Delivery Time -->
										<!-- description -->
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<h4 class="mb-0 ft-medium fs-md">Additional Information</h4>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-4 col-4">
												<div class="form-group">
													<textarea class="form-control with-light" placeholder="Additional Information"></textarea>
												</div>
											</div>
											<div class=" col-lg-3 col-md-3 col-sm-4 col-4">
												<textarea class="form-control with-light" placeholder="Additional Information"></textarea>
											</div>
											<div class=" col-lg-3 col-md-3 col-sm-4 col-4">
												<textarea class="form-control with-light" placeholder="Additional Information"></textarea>
											</div>
										</div>
										<!-- description -->

									</div>
								</div>
							</div>
						</div>
						<!-- three services-part3 -->
						<!-- Add Service -->
						<!-- <div class="row">
							<div class="col-lg-12 col-md-12">
								<div class="_dashboard_content bg-white rounded mb-4">
									<div class="_dashboard_content_header br-bottom py-3 px-3">
										<div class="_dashboard__header_flex">
											<h4 class="mb-0 ft-medium fs-md"><i class="	fa fa-bullhorn mr-1 theme-cl fs-sm"></i>Second Service</h4>
										</div>
									</div>
									
									<div class="_dashboard_content_body py-3 px-3">
										
										<div class="row" >
											<div class="col-xl-6 col-lg-6">
												<div class="form-group">
													<label class="text-dark ft-medium">Price*</label>
													<input type="text" class="form-control rounded" placeholder="$">
												</div>
											</div>
											<div class="col-xl-6 col-lg-6">
												<div class="form-group">
													<label class="text-dark ft-medium">Delivery Time</label>
													<input type="text" class="form-control rounded" placeholder="Days">
												</div>
											</div>
											<div class="col-xl-12 col-lg-12">
												<div class="form-group">
													<label class="text-dark ft-medium">Description*</label>
													<textarea class="form-control with-light" placeholder="Description"></textarea>
												</div>
											</div>
											<div class="col-xl-12 col-lg-12">
												<div class="form-group">
													<label class="text-dark ft-medium">Service Options</label>
													
												</div>
											</div>
											
										</div>
										<div id="content">
											<div class="row">
												<div class="col-xl-4 col-lg-4 col-sm-6 col-6">
													<div class="form-group">
														<label class="text-dark ft-medium">Name</label>
														<input type="text" class="form-control rounded" placeholder="e.g.,Service option name">
													</div>
												</div>
												<div class="col-xl-4 col-lg-4 col-sm-6 col-6">
													<div class="form-group">
														<label class="text-dark ft-medium">Show</label>
														<select name="" class="form-control" id="">
															<option value="">Yes</option>
															<option value="">No</option>
															<option value="">quantity</option>
														</select>
													</div>
												</div>
												<div class="col-xl-4 col-lg-4 col-sm-6 col-6">
													<div class="form-group">
														<label class="text-dark ft-medium">Quantity(if quantity is selected)</label>
														<input type="text" class="form-control rounded" placeholder="e.g.,5">
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-xl-12 col-lg-12">
												<div class="form-group">
													<button type="button" id="more_fields" onclick="add_fields();" class="btn gray ft-medium apply-btn fs-sm rounded"><i class="fas fa-plus mr-1"></i>Add New Option</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						 -->
						<!-- Add Service -->
						<!-- <div class="row">
							<div class="col-lg-12 col-md-12">
								<div class="_dashboard_content bg-white rounded mb-4">
									<div class="_dashboard_content_header br-bottom py-3 px-3">
										<div class="_dashboard__header_flex">
											<h4 class="mb-0 ft-medium fs-md"><i class="	fa fa-bullhorn mr-1 theme-cl fs-sm"></i>Third Service</h4>
										</div>
									</div>
									
									<div class="_dashboard_content_body py-3 px-3">
										
										<div class="row" >
											<div class="col-xl-6 col-lg-6">
												<div class="form-group">
													<label class="text-dark ft-medium">Price*</label>
													<input type="text" class="form-control rounded" placeholder="$">
												</div>
											</div>
											<div class="col-xl-6 col-lg-6">
												<div class="form-group">
													<label class="text-dark ft-medium">Delivery Time</label>
													<input type="text" class="form-control rounded" placeholder="Days">
												</div>
											</div>
											<div class="col-xl-12 col-lg-12">
												<div class="form-group">
													<label class="text-dark ft-medium">Description*</label>
													<textarea class="form-control with-light" placeholder="Description"></textarea>
												</div>
											</div>
											<div class="col-xl-12 col-lg-12">
												<div class="form-group">
													<label class="text-dark ft-medium">Service Options</label>
													
												</div>
											</div>
											
										</div>
										<div id="content">
											<div class="row">
												<div class="col-xl-4 col-lg-4 col-sm-6 col-6">
													<div class="form-group">
														<label class="text-dark ft-medium">Name</label>
														<input type="text" class="form-control rounded" placeholder="e.g.,Service option name">
													</div>
												</div>
												<div class="col-xl-4 col-lg-4 col-sm-6 col-6">
													<div class="form-group">
														<label class="text-dark ft-medium">Show</label>
														<select name="" class="form-control" id="">
															<option value="">Yes</option>
															<option value="">No</option>
															<option value="">quantity</option>
														</select>
													</div>
												</div>
												<div class="col-xl-4 col-lg-4 col-sm-6 col-6">
													<div class="form-group">
														<label class="text-dark ft-medium">Quantity(if quantity is selected)</label>
														<input type="text" class="form-control rounded" placeholder="e.g.,5">
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-xl-12 col-lg-12">
												<div class="form-group">
													<button type="button" id="more_fields" onclick="add_fields();" class="btn gray ft-medium apply-btn fs-sm rounded"><i class="fas fa-plus mr-1"></i>Add New Option</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						 -->
						
						<!-- Add Skills -->
						<div class="row">
							<div class="col-lg-12 col-md-12">
								<div class="form-group">
								<button type="submit" class="btn btn-md ft-medium text-light rounded theme-bg">Create Service</button>
							</div>
							
						</div>
						
					</div>
					</form>



    @endsection


    @section('script')
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
                    <input type="text" name="extra_title[]" class="form-control" placeholder="@lang("Enter title")" required>
                </div>

                <div class="col-xl-2 col-lg-12 form-group">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="extra_price[]" placeholder="@lang('Enter Price')" required="">
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
                        <input type="file" class="custom-file-input" name="optional_image[]" id="customFile" required>
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
                        html += `<option value="" selected disabled>@lang('Select Sub Category')</option>`;
                        $.each(data, function(index, item) {
                            html += `<option value="${item.id}">${item.name}</option>`;
                            $(".mySubCatgry").html(html);
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
    let newskillcount = 0;
    //let button_html = '';
    $('#subCategorys').on('change', function(){
    	
        var subcategory = $(this).val();
        gettiers(subcategory,category);
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
                           html+='<div class="card"><div  class="card-header text-white"><button style="color:#28b661;background-color: rgba(40,182,97,0.1);" onclick="getskills('+id+')" type="button" class="btn" data-toggle="collapse" data-target="#demo'+id+'">'+name+' <i class="fa fa-arrow-down"></i></button></div><div id="demo'+id+'" class="card-body collapse"> </div></div>';  
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
                        $.each(data, function(name, id) {
                           html+=`<button type="button" onclick="selectskills(${id},'${name}',false)" value="${id}" class="btn gray ft-small apply-btn fs-sm rounded mt-1">${name}<i class="fa fa-plus ml-2"></i></button>&nbsp;`;  
                        });
                    }
                        $('#demo'+c_id).html(html);
                        checkload.push(c_id);
                }
        });
    }
    function selectskills(s_id,name,check)
    {
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
        if(newskills_name.indexOf(name) !== -1)
        {
            console.log(newskills_arr);
        return
        }
        newskillcount++;
        //add new skill in array
        newskills_arr.push(newskillcount);
        newskills_name.push(name);

        for(let i=0;i < skills_arr.length;i++){
        button_html+='<button type="button" onclick="unselectskill('+skills_arr[i]+')" id="skill_btn'+skills_arr[i]+'" value="'+skills_arr[i]+'" class="btn gray ft-small apply-btn fs-sm rounded mt-1">'+skills_name[i]+' <i class="fa fa-times-circle"></i></button>&nbsp;<input type="hidden" name="skills[]" id="job_skills'+skills_arr[i]+'" value="'+skills_arr[i]+'"/>';
        
       }
        for(let i=0;i < newskills_arr.length;i++){
        button_html+='<button type="button" onclick="unselectnewskill('+newskills_arr[i]+')" id="newskill_btn'+newskills_arr[i]+'" value="'+skills_arr[i]+'" class="btn gray ft-small apply-btn fs-sm rounded mt-1">'+newskills_name[i]+' <i class="fa fa-times-circle"></i></button>&nbsp;<input type="hidden" name="newskills[]" id="newjob_skills'+newskills_arr[i]+'" value="'+newskills_name[i]+'"/>';
        
       }
       $('#skills_result').html(button_html);
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
                           html+=`<p><b><a onclick="selectskills(${id},'${name}',true)" style="cursor:pointer;font-size:14px; line-height: 0.7;">${name}</a></b></p>`;  
                       
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
    	function gettiers(subcategory , category){
    		$.ajax({
    url: "{{url('/Search_tiers')}}",
    method: "POST",
    //dataType: 'json',
    data: { "_token": "{{csrf_token()}}", 'subcategory':subcategory},
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
    	
    @endsection
