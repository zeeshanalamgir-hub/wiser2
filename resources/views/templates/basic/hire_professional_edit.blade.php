@extends($activeTemplate.'layouts.wiserlance.layout')
@section('content')
@php
$pageTitle = "Hire Professional";
@endphp
@php
$helpers = new \App\Classes\Helpers();
$currency_rates = $helpers->get_currency_rates();
$currencies = $helpers->get_all_currencies();
// echo "<pre>";print_r($currency_rates);echo "</pre>";exit;
@endphp
<section class="bg-light py-5 position-relative">
	<div class="container">
		<div class="row">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				
				
				<div class="bg-white rounded mb-4">
					<div class="jbd-01 px-3 py-4">
						<div class="row no-gutters">
							
							<div class="col-lg-4 col-md-4 col-sm-12 col-12">
								<div class="jbd-details mb-4">
									<h5 class="ft-medium fs-md">@lang('Name')</h5>
									<p> <a href="{{route('profile', [slug($professional->username), encrypt($professional->id)])}}"> {{$professional->firstname}} {{$professional->lastname}} </a>  </p>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-12">
								<div class="jbd-details mb-4">
									<h5 class="ft-medium fs-md">@lang('Designation')</h5>
									<p>{{$professional->designation}}</p>
									
								</div>
							</div>
							
							@php
							$selectedjob=DB::table('jobs')->where('user_id',Auth::user()->id)->get();
							@endphp

							<div class="col-lg-4 col-md-4 col-sm-12 col-12">
								<div class="jbd-details mb-4">
									<h5 class="ft-medium fs-md">@lang('Select Job')</h5>
									<form enctype="multipart/form-data" method="post" action="{{url('/selectedjob',[ encrypt($professional->id)])}}">
										@csrf
										<select id="changejob" class="form-control" name="hirejob" disabled>
											<option value="">Selected Job</option>
											@foreach($selectedjob as $job)
											<option @if(@$singlejoboffers->job_id == $job->id) selected @else ''@endif value="{{$job->id}}">{{$job->title}}</option>
											@endforeach
										</select>
										<input id="submit" style="display:none;" type="submit" name="submit" value="Submit">
									</form>
								</div>
							</div>
						</div>
						
						
						
						<div class="jbd-details mb-1">
							<h5 class="ft-medium fs-md">@lang('Skills')</h5>
							<ul class="p-0 skills_tag text-left">
								@foreach(@$professional->skillsprofilenew as $as)
								<li><span class="px-2 py-1 medium skill-bg rounded text-dark">{{__($as->name)}}</span></li>
								@endforeach
								@foreach(@$professional->skillsprofile as $as)
								@php
								$name=DB::table('skills')->where('id',$as->skill_id)->first();
								@endphp
								<li><span class="px-2 py-1 medium skill-bg rounded text-dark">{{@$name->name}}</span></li>
								@endforeach
							</ul>
						</div>
					</div>
					
				</div>

				
				@php
				$job=DB::table('jobs')->where('id',@$singlejoboffers->job_id)->first();
				@endphp
				@if($job->budgettype == 'per_hour')

					<form enctype="multipart/form-data" method="post" action="{{url('/job/addofferhourlyupdate', [encrypt($singlejoboffers->id)])}}">
						@csrf
					<div class="bg-white rounded mb-4">
						<div class="jbd-01 px-3 py-4">
							<div class="jbd-details mb-4">
								
								<h6 class="ft-medium fs-md">@lang('Update Offer')</h6>
								
								<div class="row pl-2" >
									<div class="col-lg-4 col-md-4 col-sm-12 col-12 mt-2">
										<label>Hourly Rate</label>
										
										<div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text" style="    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    height: 40px !important;
    padding: 6px 15px;
    font-size: 15px;
    margin-bottom: 0;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    text-align: center;
    white-space: nowrap;
    background-color: #e9ecef;
    border: 1px solid #ced4da;">$</span>
  </div>
  <input type="number" name="bid_price" value="{{@$singlejoboffers->hourlyrate}}" class="form-control" aria-label="Amount (to the nearest dollar)">
  <div class="input-group-append">
    <span class="input-group-text " style="    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    height: 40px !important;
    padding: 6px 15px;
    font-size: 15px;
    margin-bottom: 0;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    text-align: center;
    white-space: nowrap;
    background-color: #e9ecef;
    border: 1px solid #ced4da;">hr</span>
  </div>
</div>
										<input type="hidden" name="job_type" value="{{$job->budgettype}}">
										<input type="hidden" name="job_id" value="{{$job->id}}">
										<input type="hidden" name="pro_id" value="{{$professional->id}}">
									</div>
									<div class="col-lg-4 col-md-4 col-sm-12 col-12 mt-2">
										<label>Minimum Hour Per Week</label>
										<div class="input-group">
										<input class="form-control" value="{{@$singlejoboffers->hours}}"  type="number" name="hour_per_week" >
										<div class="input-group-append">
												<span class="input-group-text " style="    display: -webkit-box;
													display: -ms-flexbox;
													display: flex;
													-webkit-box-align: center;
													-ms-flex-align: center;
													align-items: center;
													height: 40px !important;
													padding: 6px 15px;
													font-size: 15px;
													margin-bottom: 0;
													font-weight: 400;
													line-height: 1.5;
													color: #495057;
													text-align: center;
													white-space: nowrap;
													background-color: #e9ecef;
												border: 1px solid #ced4da;">Week</span>
											</div>
										</div>
										
									</div>
									<div class="col-lg-4 col-md-4 col-sm-12 col-12 mt-2">
										<label>Deposit Fund</label>
										<div class="input-group">
										<input class="form-control" value="{{@$singlejoboffers->deposit}}"  type="number" name="depositfund" >
										<div class="input-group-append">
												<span class="input-group-text " style="    display: -webkit-box;
													display: -ms-flexbox;
													display: flex;
													-webkit-box-align: center;
													-ms-flex-align: center;
													align-items: center;
													height: 40px !important;
													padding: 6px 15px;
													font-size: 15px;
													margin-bottom: 0;
													font-weight: 400;
													line-height: 1.5;
													color: #495057;
													text-align: center;
													white-space: nowrap;
													background-color: #e9ecef;
												border: 1px solid #ced4da;">$</span>
											</div>
										</div>

									</div>
									<!-- <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
										<label>Title</label>
										<input type="text" value="{{@$singlejoboffers->title}}" name="title" class="form-control">
									</div> -->
									<div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
										<label>Attachment</label> 
										<input type="file" name="file" multiple class="form-control">
									</div>
									<div class="col-lg-12 mt-2">
										<label>Cover Letter</label>
										<textarea name="proposal"  class="form-control nicEdit" cols="10" rows="10">{{@$singlejoboffers->proposal}}</textarea>
									</div>
									<div class="col-lg-4 col-md-4 col-dm-4 col-12 mt-2">
										<button type="submit" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium" style="width:150px;">@lang('Update Offer')</button>
									</div>
								</div>
								
							</div>
						</div>
						
					</div>
				</form>
				@elseif($job->budgettype == 'fixed') 
				@php
				$ms=DB::table('user_offer_milestone')->where('offer_id',@$singlejoboffers->id)->get();
				@endphp
				<form enctype="multipart/form-data" method="post" action="{{url('/job/addofferfixedupdate/milestone', [encrypt($singlejoboffers->id)])}}">
					@csrf
					<div class="bg-white rounded mb-4">
						<div class="jbd-01 px-3 py-4">
							<div class="jbd-details mb-4"> 
								<h6 class="ft-medium fs-md">@lang('Update Offer')</h6>
								<input type="hidden" name="job_type" value="{{$job->budgettype}}">
								<input type="hidden" name="job_id" value="{{$job->id}}">
								<input type="hidden" name="pro_id" value="{{$professional->id}}">
								<div class="row">
										@foreach($ms as $oms)
										<div class="col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
											<label for="field" class="hidden-md">Title</label>
												<input type="text" id="field" placeholder="Milestone Title" class="form-control" value="{{$oms->title}}" name="milestone_title[]" />
											</div> 
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
													<label for="field" class="hidden-md">Amount</label>
													<div class="input-group">
													<input type="number" class="form-control bidamountmulti" value="{{$oms->amount}}" placeholder="$0" name="milestone_amount[]" />
													<div class="input-group-append">
												<span class="input-group-text " style="    display: -webkit-box;
													display: -ms-flexbox;
													display: flex;
													-webkit-box-align: center;
													-ms-flex-align: center;
													align-items: center;
													height: 40px !important;
													padding: 6px 15px;
													font-size: 15px;
													margin-bottom: 0;
													font-weight: 400;
													line-height: 1.5;
													color: #495057;
													text-align: center;
													white-space: nowrap;
													background-color: #e9ecef;
												border: 1px solid #ced4da;">$</span>
											</div>
										</div>
												</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
													<label>Days</label>
													<div class="input-group">
													<input type="number" value="{{$oms->due_date}}" class="form-control" name="milestone_due_date[]">
													<div class="input-group-append">
												<span class="input-group-text " style="    display: -webkit-box;
													display: -ms-flexbox;
													display: flex;
													-webkit-box-align: center;
													-ms-flex-align: center;
													align-items: center;
													height: 40px !important;
													padding: 6px 15px;
													font-size: 15px;
													margin-bottom: 0;
													font-weight: 400;
													line-height: 1.5;
													color: #495057;
													text-align: center;
													white-space: nowrap;
													background-color: #e9ecef;
												border: 1px solid #ced4da;">Days</span>
											</div>
										</div>
												</div>
										</div>
										@endforeach
									</div>
								<div class="row show-hide pl-2" >
									
									
									<div class="col-md-10 dynamic-field " id="dynamic-field-1">
										<div class="row" >
											<div class="col-md-4">
												<label for="field" class="hidden-md">Title</label>
												<input type="text" id="field" placeholder="Milestone Title" class="form-control" name="milestone_title[]" />
												
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="field" class="hidden-md">Amount</label>
													<div class="input-group">
													<input type="text" id="field" onkeyup="calculatetotal1(this.value)" class="form-control" placeholder="$0" name="milestone_amount[]" />
													<div class="input-group-append">
												<span class="input-group-text " style="    display: -webkit-box;
													display: -ms-flexbox;
													display: flex;
													-webkit-box-align: center;
													-ms-flex-align: center;
													align-items: center;
													height: 40px !important;
													padding: 6px 15px;
													font-size: 15px;
													margin-bottom: 0;
													font-weight: 400;
													line-height: 1.5;
													color: #495057;
													text-align: center;
													white-space: nowrap;
													background-color: #e9ecef;
												border: 1px solid #ced4da;">$</span>
											</div>
										</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label>Days</label>
													<div class="input-group">
													<input type="number" class="form-control" name="milestone_due_date[]">
													<div class="input-group-append">
												<span class="input-group-text " style="    display: -webkit-box;
													display: -ms-flexbox;
													display: flex;
													-webkit-box-align: center;
													-ms-flex-align: center;
													align-items: center;
													height: 40px !important;
													padding: 6px 15px;
													font-size: 15px;
													margin-bottom: 0;
													font-weight: 400;
													line-height: 1.5;
													color: #495057;
													text-align: center;
													white-space: nowrap;
													background-color: #e9ecef;
												border: 1px solid #ced4da;">Days</span>
											</div>
										</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-2 append-buttons" style="margin-top:2rem !important">
										<div class="clearfix">
											<button type="button" id="add-button" class="btn rounded theme-bg text-light fs-sm ft-mediume float-left shadow-sm"><i class="fa fa-plus fa-fw"></i>
											</button>
											<button type="button" id="remove-button" class="btn rounded bg-danger text-light fs-sm ft-mediume float-left shadow-sm ml-1" disabled="disabled"><i class="fa fa-minus fa-fw"></i>
											</button>
										</div>
									</div>
									
									<div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
										<label>Attachment</label>
										<input  type="file" name="milestone_attachment" class="form-control">
									</div>
									<div class="col-lg-12 mt-2">
										<label>Cover Letter</label>
										<textarea name="milestine_proposal"  class="form-control nicEdit" cols="10" rows="10">{{@$singlejoboffers->proposal}}</textarea>
									</div>
									<div class="col-lg-4 col-md-4 col-dm-4 col-12 mt-2">
										<button type="submit" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium" style="width:150px;">@lang('Update Offer')</button>
									</div> 
								</div>
							</div>
						</div>
						
					</div>
				</form>
				@endif
				

				
			</div>
			
			
			
		</div>
		
	</div>
</div>
</div>
</section>
@endsection
@section('script')

<script type="text/javascript">
document.getElementById("changejob").onchange = function() {
$('#submit').trigger('click');
};
</script>
<script type="text/javascript">
	
$(document).ready(function() {
var buttonAdd = $("#add-button");
var buttonRemove = $("#remove-button");
var className = ".dynamic-field";
var count = 0;
var field = "";
var maxFields =50;
function totalFields() {
return $(className).length;
}
function addNewField() {
count = totalFields() + 1;
field = $("#dynamic-field-1").clone();
field.attr("id", "dynamic-field-" + count);
field.children("label").text("Field " + count);
field.find("input").val("");
$(className + ":last").after($(field));
}
function removeLastField() {
if (totalFields() > 1) {
$(className + ":last").remove();
}
}
function enableButtonRemove() {
if (totalFields() === 2) {
buttonRemove.removeAttr("disabled");
buttonRemove.addClass("shadow-sm");
}
}
function disableButtonRemove() {
if (totalFields() === 1) {
buttonRemove.attr("disabled", "disabled");
buttonRemove.removeClass("shadow-sm");
}
}
function disableButtonAdd() {
if (totalFields() === maxFields) {
buttonAdd.attr("disabled", "disabled");
buttonAdd.removeClass("shadow-sm");
}
}
function enableButtonAdd() {
if (totalFields() === (maxFields - 1)) {
buttonAdd.removeAttr("disabled");
buttonAdd.addClass("shadow-sm");
}
}
buttonAdd.click(function() {
addNewField();
enableButtonRemove();
disableButtonAdd();
});
buttonRemove.click(function() {
removeLastField();
disableButtonRemove();
enableButtonAdd();
});
});
</script>
@endsection