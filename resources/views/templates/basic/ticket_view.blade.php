@extends($activeTemplate.'layouts.frontend')
@section('content')
@include($activeTemplate.'partials.breadcrumbs')
<section class="all-sections pt-60">
    <div class="container-fluid p-max-sm-0">
        <div class="sections-wrapper d-flex flex-wrap justify-content-center">
            <article class="main-section">
                <div class="section-inner">
                    <div class="item-section">
                        <div class="container">
                            <div class="card-area">
                                <div class="row justify-content-center">
                                    <div class="col-xl-10 col-md-10 col-sm-12">

                                        <div class="card custom--card">
                                            <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                                                <h4 class="card-title text-lowercase mb-0">
                                                    @if($my_ticket->status == 0)
                                                        <span class="badge badge--success text-capitalize text-white">@lang('Open')</span>
                                                    @elseif($my_ticket->status == 1)
                                                        <span class="badge badge--primary text-capitalize text-white">@lang('Answered')</span>
                                                    @elseif($my_ticket->status == 2)
                                                        <span class="badge badge--warning text-capitalize text-white">@lang('Replied')</span>
                                                    @elseif($my_ticket->status == 3)
                                                        <span class="badge badge--danger text-capitalize text-white">@lang('Closed')</span>
                                                    @endif [@lang('Ticket')#{{ $my_ticket->ticket }}] {{ $my_ticket->subject }}
                                                </h4>
                                                <div class="card-btn">
                                                    <button class="btn btn--danger text-white border--rounded close-button" type="button" title="Close Ticket" data-bs-toggle="modal"
                                                        data-bs-target="#DelModal"><i class="fa fa-lg fa-times-circle"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="card-form-wrapper">
                                                 @if($my_ticket->status != 4)
                                                    <form action="{{ route('ticket.reply', $my_ticket->id) }}" method="post" role="form" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="replayTicket" value="1">
                                                        <div class="row justify-content-center mb-20-none">
                                                            <div class="col-xl-12 col-lg-12 form-group">
                                                                <textarea class="form-control bg--gray" name="message" rows="8" placeholder="@lang('Your Reply') ..." required=""></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="row justify-content-between mt-30">
                                                            <div class="col-md-8 ">
                                                                <div class="row justify-content-between">
                                                                    <div class="col-md-11">
                                                                        <div class="form-group">
                                                                            <div class="input-group ticket-input-group">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text text-white" id="inputGroupFileAddon01">@lang('Upload')</span>
                                                                                </div>
                                                                                <div class="custom-file">
                                                                                    <input type="file" class="custom-file-input" name="attachments[]" id="inputAttachments"
                                                                                        aria-describedby="inputGroupFileAddon01">
                                                                                    <label class="custom-file-label" for="inputGroupFile01">@lang('Choose file')</label>
                                                                                </div>
                                                                            </div>
                                                        
                                                                            <div id="fileUploadsContainer"></div>
                                                                            <p class="my-2 ticket-attachments-message">@lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'),
                                                                                .@lang('doc'), .@lang('docx')</p>
                                                                        </div>
                                                        
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                        <div class="form-group">
                                                                            <a href="javascript:void(0)" class="btn--base addFile">
                                                                                <i class="fa fa-plus"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <button type="submit" class="btn--base">
                                                                    <i class="fa fa-reply"></i> @lang('Reply') </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                @endif

                                                @foreach($messages as $message)
                                                    @if($message->admin_id == 0)
                                                        <div class="row border--success border--rounded my-3 py-3 mx-2">
                                                            <div class="col-md-3 border-end text-end">
                                                                <h5 class="my-3">{{ $message->ticket->name }}</h5>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <p class="fw-bold my-2">
                                                                    @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                                                <p>{{$message->message}}</p>
                                                                @if($message->attachments()->count() > 0)
                                                                    <div class="mt-2">
                                                                        @foreach($message->attachments as $k=> $image)
                                                                            <a href="{{route('ticket.download',encrypt($image->id))}}" class="me-3"><i class="fa fa-file"></i>  @lang('Attachment') {{++$k}} </a>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="row border--success border--rounded my-3 py-3 mx-2">
                                                            <div class="col-md-3 border-end text-end">
                                                                <h5 class="my-3">{{ $message->admin->name }}</h5>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <p class="fw-bold my-2">
                                                                    @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                                                <p>{{$message->message}}</p>
                                                                @if($message->attachments()->count() > 0)
                                                                    <div class="mt-2">
                                                                        @foreach($message->attachments as $k=> $image)
                                                                            <a href="{{route('ticket.download',encrypt($image->id))}}" class="me-3"><i class="fa fa-file"></i>  @lang('Attachment') {{++$k}} </a>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
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
            </article>
        </div>
    </div>
</section>
@include($activeTemplate.'partials.end_ad')

<div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalLabel">@lang('Confirmation')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
                <form method="POST" action="{{ route('ticket.reply', $my_ticket->id) }}">
                    @csrf
                    <input type="hidden" name="replayTicket" value="2">
                    <div class="modal-body">
                        <p>@lang('Are you sure you want to close this support ticket')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--danger btn-rounded text-white" data-bs-dismiss="modal">@lang('Close')</button>
                         <button type="submit" class="btn btn--success btn-rounded text-white">@lang('Submit')</button>
                    </div>
                </form>
        </div>
    </div>
</div>
@endsection


@push('script')
<script>
    $(document).on("change",".custom-file-input",function(){
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

     $('.addFile').on('click',function(){
        $("#fileUploadsContainer").append(
            `<div class="input-group ticket-input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text text-white" id="inputGroupFileAddon01">@lang('Upload')</span>
                </div>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="attachments[]" id="inputAttachments"
                        aria-describedby="inputGroupFileAddon01">
                    <label class="custom-file-label" for="inputGroupFile01">@lang('Choose file')</label>
                </div>
            <span class="btn btn--danger text-white remove-btn ms-2"><i class="las la-times"></i></span>
        </div>`
        )
    });
    $(document).on('click','.remove-btn',function(){
        $(this).closest('.input-group').remove();
    });
</script>
@endpush

