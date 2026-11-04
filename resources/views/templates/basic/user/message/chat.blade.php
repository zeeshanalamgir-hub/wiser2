@extends($activeTemplate.'layouts.master')
@section('content')
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
<style>
    div.stars {
  width: 270px;
  display: inline-block;
}
 
input.star { display: none; }
 
label.star {
  float: right;
  margin-right:25px;
  padding: 10px;
  font-size: 36px;
  color: #444;
  transition: all .2s;
}
 
input.star:checked ~ label.star:before {
  content: '\f005';
  color: yellow;
  transition: all .25s;
}
 
input.star-5:checked ~ label.star:before {
  color: yellow;
  text-shadow: 0 0 20px #952;
}
 
input.star-1:checked ~ label.star:before { color: #F62; }
 
label.star:hover { transform: rotate(-15deg) scale(1.3); }
 
label.star:before {
  content: '\f006';
  font-family: FontAwesome;
}
</style>
<section class="all-sections ptb-60">
    <div class="container-fluid">
        <div class="section-wrapper">
            <div class="row justify-content-center mb-30-none">
                @if(auth()->user()->is_freelancer == 1)
                @include($activeTemplate . 'partials.seller_sidebar')    
                @else
                @include($activeTemplate . 'partials.buyer_sidebar')  
                @endif
                 <div class="col-xl-9 col-lg-12 mb-30">
                    <div class="dashboard-sidebar-open"><i class="las la-bars"></i> @lang('Menu')</div>
                    <div class="card-area">
                        <div class="row justify-content-center">
                            <div class="col-xl-12">
                                <div class="card custom--card chat-box">
                                    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                                        <h4 class="card-title mb-0">
                                        @foreach($messages as $message)
                                            @if($loop->first)
                                                @if($message->sender_id != auth()->user()->id)
                                                    {{$message->sender->username}}
                                                @else
                                                    {{$message->receiver->username}}
                                                @endif
                                            @endif
                                        @endforeach
                                        </h4>
                                        @if(auth()->user()->is_employer == 1)
                                        @if ($checkjob[0]->job_status ==  1)
                                        <a data-toggle="modal" data-target="#respondmodel_completed" id="res-btn2"> <button class="btn btn-success mt-3">Completed</button></a>
                                        @elseif($checkjob[0]->job_status == 0)
                                        <a data-toggle="modal" data-target="#respondmodel" id="res-btn"> <button class="btn btn-success mt-3">Assign</button></a>   
                                        @endif
                                        
                                        @endif
                                    </div>
                                <div class="card-body p-0">
                                    <div class="ps-container">
                                        @foreach($messages as $message)
                                            @if($message->sender_id != auth()->user()->id)
                                                <div class="media media-chat">
                                                    <img class="avatar" src="{{ getImage(imagePath()['profile']['user']['path'].'/'.$message->sender->image,imagePath()['profile']['user']['size']) }}" alt="client">
                                                    <div class="media-body">
                                                        @if(!empty($message->message))
                                                            <p>{{$message->message}}</p>
                                                        @endif
                                                        @if(!empty($message->file))
                                                            <div class="media-chat-thumb text-end">
                                                                <img src="{{getImage(imagePath()['message']['path'].'/'. $message->file)}}" alt="item-banner">
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <div class="media media-chat media-chat-reverse">
                                                    <img class="avatar" src="{{ getImage(imagePath()['profile']['user']['path'].'/'.$message->sender->image,imagePath()['profile']['user']['size']) }}" alt="client">
                                                    <div class="media-body">
                                                        @if(!empty($message->message))
                                                            <p>{{$message->message}}</p>
                                                        @endif
                                                        @if(!empty($message->file))
                                                            <div class="media-chat-thumb text-end">
                                                                <img src="{{getImage(imagePath()['message']['path'].'/'. $message->file)}}" alt="item-banner">
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    @foreach($messages as $message)
                                        @if($loop->last)
                                            <form class="chat-form" method="POST" action="{{route('user.message.store')}}" enctype="multipart/form-data">
                                                @csrf
                                                @if($message->sender_id != auth()->user()->id)
                                                    <input type="hidden" value="{{encrypt($message->sender_id)}}" name="receiver_id">
                                                @else
                                                    <input type="hidden" value="{{encrypt($message->receiver_id)}}" name="receiver_id">
                                                @endif
                                                <input type="hidden" value="{{encrypt($conversionId)}}" name="conversion_id">
                                                <div class="publisher">
                                                    <div class="chatbox-message-part">
                                                        <img class="avatar" src="{{ getImage(imagePath()['profile']['user']['path'].'/'.auth()->user()->image,imagePath()['profile']['user']['size']) }}" alt="client">
                                                        <input class="publisher-input" type="text" name="message" placeholder="@lang('Write something')">
                                                    </div>
                                                    <div class="chatbox-send-part d-flex flex-wrap align-items-center">
                                                        <span class="publisher-btn file-group me-3">
                                                            <input type="file" name="image" id="data">
                                                            <label for="data"><i class="fa fa-paperclip"></i></label>
                                                        </span>
                                                        <button type="submit" class="btn--base btn-md">@lang('Submit')</button>
                                                    </div>
                                                </div>
                                            </form>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<!-- model for assign job -->
<div class="modal fade" id="respondmodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalLabel">@lang('Assign Job to freelancer')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
                <form method="POST" action="{{url('/user/buyer/job/assignjob')}}">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                     
                        <div class="row">
                            
                            <input type="hidden" value="{{$job_id}}" name="job_id"/>
                            <input type="hidden" value="{{$freelancer_id}}" name="f_id"/>
                          
                            <div class="col-md-12 text-center">
                                <h4>Are you sure to assign this job?</h4>
                               <button class="btn btn-success mt-2">Yes</button>
                               <button type="button" class="btn btn--danger btn-rounded text-white mt-2" data-bs-dismiss="modal">@lang('No')</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--danger btn-rounded text-white" data-bs-dismiss="modal">@lang('Close')</button>
                        
                    </div>
                </form>
        </div>
    </div>
</div>
<!-- model for job completed-->
<div class="modal fade" id="respondmodel_completed" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalLabel">@lang('Marl Job as completed')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
                <form method="POST" action="{{url('/user/buyer/job/completedjob')}}">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                     
                        <div class="row">
                            
                            <input type="hidden" value="{{$job_id}}" name="job_id"/>
                            <input type="hidden" value="{{$freelancer_id}}" name="f_id"/>
                            <div class="col-md-12">
                                <label>Rate</label>
                                <div class="star">
                                <input value="5" class="star star-5" id="star-5" type="radio" name="rate"/>
                                <label class="star star-5" for="star-5"></label>
                                <input value="4" class="star star-4" id="star-4" type="radio" name="rate"/>
                                <label class="star star-4" for="star-4"></label>
                                <input value="3" class="star star-3" id="star-3" type="radio" name="rate"/>
                                <label class="star star-3" for="star-3"></label>
                                <input value="2" class="star star-2" id="star-2" type="radio" name="rate"/>
                                <label class="star star-2" for="star-2"></label>
                                <input checked value="1" class="star star-1" id="star-1" type="radio" name="rate"/>
                                <label class="star star-1" for="star-1"></label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Review</label>
                                <textarea required class="form-control" name="review"></textarea>
                            </div>
                            <hr>
                            <div class="col-md-12 text-center">
                                <h4>Are you sure to mark this job completed?</h4>
                               <button class="btn btn-success mt-2">Yes</button>
                               <button type="button" class="btn btn--danger btn-rounded text-white mt-2" data-bs-dismiss="modal">@lang('No')</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--danger btn-rounded text-white" data-bs-dismiss="modal">@lang('Close')</button>
                        
                    </div>
                </form>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    'use strict';
    $('#res-btn').on('click', function () {
        var modal = $('#respondmodel');
        modal.find('input[name=id]').val($(this).data('id'))
        modal.modal('show');
    });
    $('#res-btn2').on('click', function () {
        var modal = $('#respondmodel_completed');
        modal.find('input[name=id]').val($(this).data('id'))
        modal.modal('show');
    });
</script>
@endpush

