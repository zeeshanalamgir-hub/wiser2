@extends('admin.layouts.app')
@section('panel')
<style>
body {
  margin: 0 auto;
}

.container {
  border: 2px solid #dedede;
  background-color: #f1f1f1;
  border-radius: 5px;
  padding: 10px;
  margin: 10px 0;
}

.darker {
  border-color: #ccc;
  background-color: #ddd;
}

.container::after {
  content: "";
  clear: both;
  display: table;
}

.container img {
  float: left;
  max-width: 60px;
  width: 100%;
  margin-right: 20px;
  border-radius: 50%;
}

.container img.right {
  float: right;
  margin-left: 20px;
  margin-right:0;
}

.time-right {
  float: right;
  color: #aaa;
}

.time-left {
  float: left;
  color: #999;
}
</style>

<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
<section class="all-sections ptb-60">
    <div class="container-fluid">
        <div class="section-wrapper">
            <div class="row justify-content-center mb-30-none">
              
                 <div class="col-xl-9 col-lg-12 mb-30">
                    <div class="card-area">
                        <div class="row justify-content-center">
                            <div class="col-xl-12">
                                <div class="card custom--card chat-box">
                                  @if(count($messages)>0)

                                  
                                    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                                        <h1 class="card-title mb-0">
                                            {{$messages[0]->sender->username}} chats with {{$messages[0]->reciever->username}}
                                        </h1>
                        
                                    </div>
                                <div class="card-body p-0">
                                    <div class="ps-container">
                                        @foreach($messages as $message)
                                        
                                                <div class="media media-chat media-chat-reverse">
                                                    <!-- <img class="avatar" src="{{ getImage(imagePath()['profile']['user']['path'].'/'.$message->sender->image,imagePath()['profile']['user']['size']) }}" alt="client"> -->
                                                   
                                                </div>

                                                <div class="container">
                                                <!-- <img src="/w3images/bandmember.jpg" alt="Avatar" style="width:100%;"> -->
                                                <h5>{{$message->sender->username}}</h5>
                                                @if(!empty($message->body))
                                                            <p>{{$message->body}}</p>
                                                        @endif
                                                        @if(!empty($message->attachment))
                                                            <div class="media-chat-thumb text-end">
                                                                <img src="{{getImage(imagePath()['message']['path'].'/'. $message->attachment)}}" alt="item-banner">
                                                            </div>
                                                         @endif
                                                <span class="time-right">{{$message->created_at}}</span>
                                                </div>

                                             
                                        @endforeach
                                    </div>

                                   </div>
                                   @else
                                   <div>
                                    There are no chats
                                  </div>
                                  @endif

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


