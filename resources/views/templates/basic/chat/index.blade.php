@extends($activeTemplate.'layouts.wiserlance.layout')
@section('content')
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
                           <h4>Messages</h4>
                           <a href="#" class="message-action"></a>
                        </div>
                        <div class="messages-container-inner">
                           <!-- Messages -->
                           <?php $ssid  = Auth::user()->id; ?>
                           <div class="dash-msg-inbox">
                              <ul>
                                 @foreach($users as $user)
                                 <li>
                                    <a href="{{route('chat-secreen',['sid'=>$ssid,'rid'=>$user->id])}}">
                                       <div class="dash-msg-avatar"><img src="{{asset('assets/images/user/profile/'.$user->image)}}" alt=""><span class="_user_status {{$user->isOnline() ?'online':'offline'}}"></span></div>
                                       <div class="message-by">
                                          <div class="message-by-headline">
                                             <h5>{{$user->firstname}} {{$user->lastname}}</h5>
                                             <span>{{$user->ChatStatus(94)['userMessageTime']}}</span>
                                          </div>
                                          <p>{{$user->ChatStatus(94)['userLastMessage']}} </p>
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
                                 @if($chat->ch_s_id==$ssid)
                                 <div class="message-plunch me">
                                    <div class="dash-msg-avatar"><img src="{{asset('assets/images/user/profile/'.$user->image)}}" alt=""></div>
                                    <div class="dash-msg-text">
                                       <p>{{$chat->message}}</p>
                                    </div>
                                 </div>
                                 @else
                                 <div class="message-plunch">
                                    <div class="dash-msg-avatar"><img src="{{asset('assets/images/user/profile/'.$user->image)}}" alt=""></div>
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
                                    <input type="file" name="tasver" id="tasver">
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
@endsection
@section('script')
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
   // Enable pusher logging - don't include this in production
   Pusher.logToConsole = true;
   var pusher = new Pusher('11e0e7461257ff7b2fcf', {
      cluster: 'ap2'
   });


   var ch_s_id = $("input[name=ch_s_id]").val();
   var ch_r_id = $("input[name=ch_r_id]").val();
   var login_u_id = <?php echo $ssid; ?>

   var channel = pusher.subscribe('my-channel' + ch_s_id);
   channel.bind('my-event' + ch_r_id, function(data) {

      const obj = JSON.parse(JSON.stringify(data));
      var message_for = '';
      if (obj.message.ch_s_id == login_u_id) {
         message_for = 'me';
      }

      var message = '<div class="message-plunch' + ' ' + message_for + '"><div class="dash-msg-avatar"><img src="{{asset('assets/images/user/profile/'.$user->image)}}" alt=""></div><div class="dash-msg-text"><p>' + obj.message.message + '</p></div></div>';
      $("#message_box").append(message);
   });
</script>
<script type="text/javascript">
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });

   $(".btn_sub").click(function(e) {
      e.preventDefault();
      var ch_s_id = $("input[name=ch_s_id]").val();
      var ch_r_id = $("input[name=ch_r_id]").val();
      var messageg = $('textarea#message').val();
      var img = $("input[name=tasver]").val();

      $.ajax({
         type: 'POST',
         url: "{{ route('chat-store') }}",
         data: {
            ch_s_id: ch_s_id,
            ch_r_id: ch_r_id,
            message: messageg,
            img:img
         },
         success: function(data) {
            var message = '<div class="message-plunch me"><div class="dash-msg-avatar"><img src="{{asset('assets/images/user/profile/'.$user->image)}}" alt=""></div><div class="dash-msg-text"><p>' + messageg + '</p></div></div>';
            $("#message_box").append(message);
            $("#message").val('');
         }
      });

   });
</script>
@endsection
