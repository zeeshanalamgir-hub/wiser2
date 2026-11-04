<!doctype html>
<html lang="en-US">
<head></head>
<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
	<table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8" style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: \'Open Sans\', sans-serif;">
		<tr>
			<td>
				<table style="background-color: #f2f3f8; max-width:670px;  margin:0 auto;" width="100%" border="0"
					align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td style="height:80px;">&nbsp;</td>
					</tr>
					<tr>
						<td style="text-align:center;">
						  <a href="{{ config('app.APP_URL') }}" title="logo" target="_blank">
							<img width="60" src="{{asset('Frontend/images/logo.png')}}" title="logo" alt="logo">
						  </a>
						</td>
					</tr>
					<tr>
						<td style="height:20px;">&nbsp;</td>
					</tr>
					<tr>
						<td>
							<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
								style="max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
								<tr>
									<td style="height:40px;">&nbsp;</td>
								</tr>
								<tr>
									<td style="padding:0 35px;">
										<h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:\'Rubik\',sans-serif;">Welcome to Wiserlance</h1>
										<span
											style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span><br>

											<p><span class="font-weight-bold">Greetings,</span> </p>
											<p><a href="{{route('buyerprofile', [slug($booker->username), encrypt($booker->id)])}}">{{$booker->firstname}} {{$booker->lastname}}</a> has
											 @if($type=='yes')
											 approved your work on following service:
											 @elseif($type=='no')
											 rejected your work on following service:
											  @elseif($type=='pay')
											 payed your following service booking:
											  @elseif($type=='dispute')
											 created a dispute with you on following service booking:
											  @elseif($type=='dispute_offer_buyer')
											 sent you a new dispute offer on following service booking:
											  @elseif($type=='dispute_offer_buyer_accept')
											 accepted your dispute offer on following service booking:
											  @elseif($type=='dispute_offer_buyer_declined')
											 declined your dispute offer on following service booking:
											 @elseif($type=='dispute_admin')
											  submited the dispute to admin on following service booking:
											 @endif
 
											 </p> 
											<p><span class="font-weight-bold">Service Title:</span><a href="{{route('service.details', [slug($service_single->title), encrypt($service_single->id)])}}"> {{$service_single->title}} </a></p>
											@if($type=='pay' || $type=='dispute' || $type=='dispute_offer_buyer' || $type=='dispute_offer_buyer_accept' || $type=='dispute_offer_buyer_declined' || $type=='dispute_admin')

											@else
											<p><span class="font-weight-bold">Work Title:</span>{{$work_title}}</p>
											@endif
											@if($type=='dispute_offer_buyer_accept')
											<p><span class="font-weight-bold">Note:</span>Dispute has been closed.wait for your agreed payment.</p>
											@endif
											@if($type=='dispute_admin')
											<p><span class="font-weight-bold">Note:</span>Admin decision will be final.</p>
											@endif
											<p><span class="font-weight-bold">Order Number:</span>{{$order_number}}</p>
											<p>To access the complete details, please log to your profile and act accordingly. </p>
											
										</td> 
									</tr>
									<!-- <tr>
									<td style="height:40px">&nbsp;</td>
								</tr> -->
										<tr>
										<td style="height:40px">
									<p style="color:#455056;font-size:15px;line-height:24px;margin:0">Best,<br>The Wiserlance Team.</p>
									</td>
										</tr>

								
										

									</td>
								</tr>
								
							</table>
						</td>
					</tr>
					<tr>
						<td style="height:20px;">&nbsp;</td>
					</tr>
					<tr>
						<td style="text-align:center;">
							<p style="font-size:14px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;"><!-- &copy; --> <strong>Wiserlance © 2023 All rights Reserved</strong></p>
						</td>
					</tr>
					<tr>
						<td style="height:80px;">&nbsp;</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<!--/100% body table-->
</body>

</html>