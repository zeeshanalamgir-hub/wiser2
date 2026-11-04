@extends($activeTemplate.'layouts.wiserlance.layout')
@section('content')
<section class="bg-light py-5 position-relative">
	<div class="container">
		<div class="row">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
					<div class="bg-white rounded mb-4">
					<div class="jbd-01 px-3 pt-4 pb-1">
						<div class="jbd-details mb-4">
							<h5 class="ft-medium fs-md">@lang('Title'): {{$job->title}} &nbsp <a href="{{route('job.details', [slug($job->title), encrypt($job->id)])}}"><i style="color:#fa7610 !important;" class="fa fa-eye"></i></a></h5>
						</div>
					</div>
				</div>
				<div class="bg-white rounded mb-4">
					<div class="jbd-01 px-3 pt-4 pb-1">
						<div class="jbd-details mb-4">
							<h5 class="ft-medium fs-md">@lang('Description')</h5>
							<hr style="border:1px solid #fa7610 !important;">
							<p>{!!$job->description!!}</p>
						</div>


					</div>

				</div>

				<div class="bg-white rounded mb-4">
					<div class="jbd-01 px-3 pt-4 pb-1">
						<div class="jbd-details mb-4">
							 <h5 class="ft-medium fs-md">@lang('Requirements')</h5>
							 <hr style="border:1px solid #fa7610 !important;">
                        <p>{!!$job->requirements!!}</p>
						</div>


					</div>

				</div>
				<div class="bg-white rounded mb-4">
					<div class="jbd-01 px-3 pt-4 pb-1">


						<div class="jbd-details mb-1">
							<h5 class="ft-medium fs-md">@lang('Skills and Expertise')</h5>
							<hr style="border:1px solid #fa7610 !important;">
							<ul class="p-0 skills_tag text-left">
								@if($job->skill > 0)
								@foreach($job->skill as $skill)
								<li><span class="px-2 py-1 medium skill-bg rounded text-dark">{{__($skill)}}</span></li>
								@endforeach
								@else
								@endif
							</ul>
						</div>
					</div>

				</div>
				@php
				$ms=DB::table('user_milestone')->where('bid_id',$bid->id)->get();
				$sum=$ms->sum('amount');
				$per = ($sum*$usermembership[0]->fee)/100;
$total = $job->amount - $per;
				@endphp
				<form enctype="multipart/form-data" method="post" action="{{url('/job/revisebiding/milestone', [encrypt($bid->id)])}}">

					@csrf
					<div class="bg-white rounded mb-4">
						<div class="jbd-01 px-3 py-4">
							<div class="jbd-details mb-4">
								<!-- <h6 class="ft-medium fs-md">@lang('How do you want to be paid?')</h6>
								 -->
								<input type="hidden" id="female-rb" value="bymilestone" name="projectmethod"  />
								<input type="hidden" value="{{showAmount($job->amount)}}" name="job_price" />
										<input type="hidden" name="platformfeemulti" value="{{showAmount($usermembership[0]->fee)}}" name="fee" />
										<input name="milestone_job" value="{{$job->id}}" type="hidden" />
								<div id="bymilestone" class=" show-hide pl-2" >
									<div class="row">
										@foreach($ms as $oms)
										<div class="col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
											<label for="field" class="hidden-md">Milestone Title</label>
												<input type="text" id="field" placeholder="Milestone Title" class="form-control" value="{{$oms->title}}" name="milestone_title[]" />
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
													<label for="field" class="hidden-md">Bid Amount</label>
													<div class="input-group">
													<input type="number" class="form-control bidamountmulti" value="{{$oms->amount}}" placeholder="$0" name="milestone_amount[]" />
													<div class="input-group-append" style="border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;">
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
												border: 1px solid #ced4da;border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">$</span>
											</div>
										</div>
												</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
													<label>Days</label>
													<div class="input-group">
													<input type="number" value="{{$oms->due_date}}" class="form-control" name="milestone_due_date[]" style="border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;">
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
												border: 1px solid #ced4da;border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">Days</span>
											</div>
										</div>
												</div>
										</div>
										@endforeach
									</div>
									<div class="row">


									<div class="col-md-10 dynamic-field " id="dynamic-field-1">
										<div class="row" >
											<div class="col-md-4">
												<label for="field" class="hidden-md">Milestone Title</label>
												<input type="text" id="field" placeholder="Milestone Title" class="form-control" name="milestone_title[]" />

											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="field" class="hidden-md">Bid Amount</label>
													<div class="input-group">
													<input type="number"  class="form-control bidamountmulti" placeholder="$0" name="milestone_amount[]" style="border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;"/>
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
												border: 1px solid #ced4da;border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">$</span>
											</div>
										</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label>Days</label>
													<div class="input-group">
													<input type="number" class="form-control" name="milestone_due_date[]" style="border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;">
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
												border: 1px solid #ced4da;border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">$</span>
											</div>
										</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-2 append-buttons" style="margin-top:1.8rem !important">
										<div class="clearfix">
											<button type="button" id="add-button" class="btn rounded theme-bg text-light fs-sm ft-mediume float-left shadow-sm"><i class="fa fa-plus fa-fw"></i>
											</button>
											<button type="button" id="remove-button" class="btn rounded bg-danger text-light fs-sm ft-mediume float-left shadow-sm ml-1" disabled="disabled"><i class="fa fa-minus fa-fw"></i>
											</button>
										</div>
									</div>
								</div>
								<div class="row ">
									<div class="col-lg-4 col-md-4 col-sm-4 col-4 mt-2 mb-2">
										<label>Job Budget</label>
										<input type="hidden" value="{{showAmount($job->amount)}}" name="job_price" />
										<input type="hidden" id="" value="{{showAmount($usermembership[0]->fee)}}" name="fee" />
										<div class="input-group">
										<input id="job_price" readonly value="{{showAmount($job->amount)}}" class="form-control" style="border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;">
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
												border: 1px solid #ced4da;border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">$</span>
											</div>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-4 mt-2 mb-2">
										<label>Platform Fee(%)</label>
										<div class="input-group">
										<input id="feemulti"  readonly value="{{showAmount($usermembership[0]->fee)}}" class="form-control" style="border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;">
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
												border: 1px solid #ced4da;border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">$</span>
											</div>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-4 mt-2">
										<label>You Will Get Paid</label>
										<div class="input-group">
										<input id="totalmulti"  readonly value="{{$total}}" class="form-control" style="border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;">
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
												border: 1px solid #ced4da;border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">$</span>
											</div>
										</div>
									</div>
									</div>
									<div class="row">
									<div class="col-lg-12 mt-2">
										<label>Cover Letter</label>
										<textarea name="milestine_proposal"  class="form-control" cols="10" rows="10" style="border-radius:5px;">{{$bid->proposal}}</textarea>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
										<label>Attachment</label>
										<input value="{{$bid->file}}" type="file" name="milestone_attachment" class="form-control">
									</div>

									<div class="col-lg-4 col-md-4 col-dm-4 col-12 mt-2">
										<button type="submit" class="btn rounded theme-bg text-light  fs-sm ft-medium" style="width:150px;">@lang('Revise Bid')</button>
									</div>
									<div class="zohaib">

									</div>
								</div>
							</div>
						</div>

					</div>
				</form>
			</div>

		</div>
	</div>
</div>
</section>
@if(!empty($user))
@if($user->is_freelancer == 1)
<!-- The Bid Modal -->
@endif
@endif
@endsection
@section('script')
<script>
"use strict";
function calculatetotal(amount){
let job_price = parseFloat($('#job_price').val());
let fee = parseInt($('#fee').val());
let total = 0
let price = parseFloat(amount);
let per = 0;
if(price > job_price){
$('#err-msg').text('You biding price is greater then job price!');
}else{
$('#err-msg').text('');
per = (job_price*fee)/100;
total = price - per;
}
if(amount == ""){
$('#total').val(0);
}else{
$('#total').val(total);
}
}
function closemodel(){
$('#Bidmodel').hide();
}
$('.jobBidingMore').on('click',function(){
var link = $(this).data('link');
var page = $(this).data('page');
var href = link + page;
var jobBidingCount = {{$jobBidings->total()}};
$.get(href, function(response){
var html = $(response).find("#jobBidingShow").html();
$("#jobBidingShow").append(html);
var loadMoreCount = 5 * page;
if(loadMoreCount > jobBidingCount){
$('.jobBidingMore').hide()
}
});
$(this).data('page', (parseInt(page) +1));
});
$(document).ready(function(){
$('#Bidbtn').click(function(){
$('#Bidmodel').show();
})
});
</script>
<script type="text/javascript">
$(document).ready(function(){
$("input[name=projectmethod]").change(function() {
var test = $(this).val();
$(".show-hide").hide();
$("#"+test).show();
});
});
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
<script type="text/javascript">
	function calculatetotal1(amount){
let job_price = parseFloat($('#job_price').val());
let fee = parseInt($('#fee').val());
let total = 0
let price = parseFloat(amount);
let per = 0;
if(price > job_price){
$('#err-msg').text('You biding price is greater then job price!');
}else{
$('#err-msg').text('');
per = (job_price*fee)/100;
total = price - per;
}
if(amount == ""){
$('#total').val(0);
}else{
$('#total').val(total);
}
}
</script>
 <script type="text/javascript">
 	 $(document).on('keyup','.bidamountmulti',function(){
 	var abcd = 0;
 	var jobprice = $('#job_price').val();
    var percent = $('#feemulti').val();
    console.log(percent);
    	$('.bidamountmulti').each(function(key, value){
    		// console.log(key);
    		// console.log(value);
    		var $this = $(this);
    		var thisval = $this.val();
    		console.log(thisval);
    		abcd += parseFloat(thisval);
    	});
	var points = (parseFloat(percent)/100);
	console.log(points);
    var totalpercent = ((points)*parseFloat(abcd));
    var leftprice=jobprice-totalpercent
    // console.log(totalpercent);
    $('#totalmulti').val(leftprice);
    });
     /*
        $(document).on('focusout','#bidamountmulti',function(){
 var arrbidamount = [];
    $("#bidamountmulti").each(function(index, tr){
        arrbidamount.push($(this).val());
    });


        var bidamount = $('#bidamountmulti').val();
        var jobprice = $('#job_price').val();
        var fee = $('#feemulti').val();
        if(bidamount.length >= 0){
        $.ajax({
        url: "{{url('/countfee_multi_bids')}}",
        method: "POST",
        data: { "_token": "{{csrf_token()}}", 'arrbidamount' : arrbidamount, 'jobprice' : jobprice, 'fee' : fee},
        success: function(response){
        if(response != 0){
        var result = jQuery.parseJSON(response);

          $('#totalmulti').val(result.content_data);
        }
        }
        });
        }
        }); */
        </script>
<!--         <script type="text/javascript">
        	$("#bidamountmulti").keyup( function(index, value) {
   $("#bidamountmulti").each(function(){
        alert($(this).val())
   });
});
        </script> -->
        <!-- <script type="text/javascript">
        	$("#bidamountmulti").keyup( function(index, value) {
        	var arr = $('input[name="milestone_amount[]"]').map(function () {
    alert (this.value); // $(this).val()
});
        	});
        </script> -->
@endsection
