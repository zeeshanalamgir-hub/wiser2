<!DOCTYPE html>
<html lang="zxx">
	<head>
		<meta charset="utf-8" />
		<meta name="author" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Chat Data</title>
		<meta name="csrf-token" content="{{ csrf_token() }}">
		@include('partials.seo')
		<!-- Custom CSS -->
		<link href="{{asset('assets/wiserlance/assets/css/styles.css')}}" rel="stylesheet">
	</head>
	<body class="blog-page">
		
		<!-- ============================================================== -->
		<!-- Preloader - style you can find in spinners.css -->
		<!-- ============================================================== -->
		<div class="preloader"></div>
		
		<!-- ============================================================== -->
		<!-- Main wrapper - style you can find in pages.scss -->
		<!-- ============================================================== -->
		
		<div id="main-wrapper">
			
			<!-- ============================================================== -->
			<!-- Top header  -->
			<!-- ============================================================== -->
			<!-- Start Navigation -->
	
<!-- Top header  -->
<!-- ============================================================== -->
<!-- ======================= Home Banner ======================== -->
<!-- <div class="home-banner1 margin-bottom-0" style="background:#28b661 url(assets/img/banner-3.jpg) no-repeat; " data-overlay="5">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-xl-8 col-lg-10 col-md-12 col-sm-12 col-12">
<div class="banner_caption text-center mb-2">
<h1 class="ft-bold mb-4">Messaging </h1>
</div>
</div>
</div>
</div>
</div> -->
<!-- ======================= Home Banner ======================== -->
<!-- ============================ Service Details Start ================================== -->
<section class="bg-light py-5 position-relative">
<div class="container-fluid">
<div class="dashboard-widg-bar d-block">
<div class="row">
<div class="col-xl-12 col-lg-12 col-md-12">
<div class="_dashboard_content bg-white rounded mb-4">
	
	<div class="_dashboard_content_body">
		<!-- Convershion -->
		<div class="messages-container margin-top-0">
			<div class="messages-headline">
				<h4>Admin</h4>
				<a href="{{route('admin.dashboard')}}" class="message-action"><i class="fa fa-home"></i> Go To Dashboard</a>
			</div>
			<div class="messages-container-inner">
                           <!-- Messages -->
                            
                           <div class="dash-msg-inbox">
                              <ul>
                                 @foreach($chatsg as $user)
                                 <li>
                                    <a href="{{route('admin.frontend.chatdata-detail',['sid'=>$user->sendChat($user->id)->ch_s_id,'rid'=>$user->sendChat($user->id)->ch_r_id])}}">
                                       <div class="dash-msg-avatar"><img src="{{asset('assets/images/user/profile/'.$user->image)}}" alt=""><span class="_user_status {{$user->is_login=='yes'?'online':'offline'}}"></span></div>
                                       <div class="message-by">
                                          <div class="message-by-headline">
                                             <h5>{{$user->firstname}}-{{$user->lastname}}</h5>
                                             <span>{{$user->ChatStatus($user->id)['userMessageTime']}}</span>
                                          </div>
                                          <p>{{$user->ChatStatus($user->id)['userLastMessage']}} </p>
                                       </div>
                                    </a>
                                 </li>
                                 @endforeach

                              </ul>
                           </div>
                           <!-- Messages / End -->
                           <!-- Message Content -->
                           <div class="dash-msg-content">
                              <span id="message_box">
                                 @foreach($chats as $chat)
                                 @if($chat->ch_s_id==$sid)
                                 <div class="message-plunch me">
                                    {{--<div class="dash-msg-avatar"><img src="{{asset('assets/images/user/profile/'.$chat->suser->image)}}" alt="{{$chat->suser->id}}" ></div>--}}
                                    <div class="dash-msg-text">
                                       <p>{{$chat->message}}</p>
                                    </div>
                                 </div>
                                 @else
                                 <div class="message-plunch">
                                    <div class="dash-msg-avatar"><img src="{{asset('assets/images/user/profile/'.$chat->suser->image)}}" alt="{{$chat->suser->id}}" ></div>
                                    <div class="dash-msg-text">
                                       <p>{{$chat->message}}</p>
                                    </div>
                                 </div>
                                 @endif
                                 @endforeach
                              </span>
                              <!-- Reply Area -->
                              <div class="clearfix"></div>
                              <form>
                                 <div class="message-reply">
                                    <textarea cols="40" rows="3" name="message" id="message" class="form-control with-light" placeholder="Your Message"></textarea>
                                    <input type="hidden" name="ch_s_id" value="{{$sid}}">
                                    <input type="hidden" name="ch_r_id" value="{{$rid}}">
                                    <button type="submit" class="btn theme-bg text-white btn_sub">Send Message</button>
                                 </div>
                              </form>
                           </div>
                           <!-- Message Content -->
                        </div>
		</div>
	</div>
</div>
</div>
</div>

</div>
</div>
</section>

<!-- Log In Modal -->
<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
<div class="modal-dialog modal-xl login-pop-form" role="document">
<div class="modal-content" id="loginmodal">
<div class="modal-headers">
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span class="ti-close"></span>
</button>
</div>
<div class="modal-body p-5">
<div class="text-center mb-4">
<h2 class="m-0 ft-regular">Login</h2>
</div>
<form>
<div class="form-group">
<label>User Name</label>
<input type="text" class="form-control" placeholder="Username*">
</div>
<div class="form-group">
<label>Password</label>
<input type="password" class="form-control" placeholder="Password*">
</div>
<div class="form-group">
<div class="d-flex align-items-center justify-content-between">
	<div class="flex-1">
		<input id="dd" class="checkbox-custom" name="dd" type="checkbox">
		<label for="dd" class="checkbox-custom-label">Remember Me</label>
	</div>
	<div class="eltio_k2">
		<a href="#" class="theme-cl">Lost Your Password?</a>
	</div>
</div>
</div>
<div class="form-group">
<button type="submit" class="btn btn-md full-width theme-bg text-light fs-md ft-medium">Login</button>
</div>
<div class="form-group text-center mb-0">
<p class="extra">Not a member?<a href="#et-register-wrap" class="text-dark"> Register</a></p>
</div>
</form>
</div>
</div>
</div>
</div>

<a id="back2Top" class="top-scroll" title="Back to top" href="#"><i class="ti-arrow-up"></i></a>
</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="{{asset('assets/wiserlance/assets/js/jquery.min.js')}}"></script>
<script src="{{asset('assets/wiserlance/assets/js/popper.min.js')}}"></script>
<script src="{{asset('assets/wiserlance/assets/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/wiserlance/assets/js/slick.js')}}"></script>
<script src="{{asset('assets/wiserlance/assets/js/slider-bg.js')}}"></script>
<script src="{{asset('assets/wiserlance/assets/js/smoothproducts.js')}}"></script>
<script src="{{asset('assets/wiserlance/assets/js/snackbar.min.js')}}"></script>
<script src="{{asset('assets/wiserlance/assets/js/jQuery.style.switcher.js')}}"></script>
<script src="{{asset('assets/wiserlance/assets/js/custom.js')}}"></script>
<script type="text/javascript">
	function showmore(){
		document.getElementById("extra").style.display="block";
		document.getElementById("more").style.display="none";
	}
	function showless(){
		document.getElementById("extra").style.display="none";
		document.getElementById("more").style.display="block";
	}
</script>
<script type="text/javascript">
	function showmore2(){
		document.getElementById("extra2").style.display="block";
		document.getElementById("more2").style.display="none";
	}
	function showless2(){
		document.getElementById("extra2").style.display="none";
		document.getElementById("more2").style.display="block";
	}
</script>
<script type="text/javascript">
	function showmore3(){
		document.getElementById("extra3").style.display="block";
		document.getElementById("more").style.display="none";
	}
	function showless3(){
		document.getElementById("extra3").style.display="none";
		document.getElementById("more3").style.display="block";
	}
</script>
<script type="text/javascript">
	function showmore4(){
		document.getElementById("extra4").style.display="block";
		document.getElementById("more4").style.display="none";
	}
	function showless4(){
		document.getElementById("extra4").style.display="none";
		document.getElementById("more4").style.display="block";
	}
</script>
<script type="text/javascript">
	function showmore5(){
		document.getElementById("extra5").style.display="block";
		document.getElementById("more5").style.display="none";
	}
	function showless5(){
		document.getElementById("extra5").style.display="none";
		document.getElementById("more5").style.display="block";
	}
</script>
<script type="text/javascript">
	function showmore6(){
		document.getElementById("extra6").style.display="block";
		document.getElementById("more6").style.display="none";
	}
	function showless6(){
		document.getElementById("extra6").style.display="none";
		document.getElementById("more6").style.display="block";
	}
</script>
<script>
$(document).ready(function(){
$("#carttoogle").click(function() {
	$("#chat-box-main-wrap").toggle();
});
});
</script>
<script>
$(document).ready(function(){
$("#carttoogle1").click(function() {
$("#chat-box-main-wrap").toggle();
});
});
</script>
<script>
$(document).ready(function(){
$("#close-btn").click(function() {
$("#chat-box-main-wrap").hide();
});
});
</script>
<!-- ============================================================== -->
<!-- This page plugins -->
<!-- ============================================================== -->
</body>
</html>