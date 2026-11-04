@extends('templates.basic.layouts.wiserlance.dashboard-layout-job')
@section('content')
<div class="row">
    <div class="col-12">
        <h5 class="text-dgreen2 mt-2"> Inbox</h5>
        <div class="card shadow-sm border mt-md-3 p-4">
            @forelse($conversions as $conversion)
                <?php $color = ''; ?>

                @if ($conversion->sender_id != auth()->user()->id)
                    @if (auth()->user()->id == $conversion->r_id)
                        @if ($conversion->read == 0)
                            <?php $color = 'color:black !important;font-weight:bold !important;font-size:14px;'; ?>
                        @endif
                    @endif
                @endif

                <!-- Message Row -->
                <div class="row pb-3 border-bottom fs-14 mt-3"> <!-- Message -->
                    <div class="col-lg-4">
                        <p class="fs-6 mb-0">
                            <img class="rounded-circle img-fluid me-2"
                                src="{{ $conversion->sender_id != auth()->user()->id ? getImage(imagePath()['profile']['user']['path'] . '/' . $conversion->sender->image) : getImage(imagePath()['profile']['user']['path'] . '/' . $conversion->receiver->image) }}"
                                width="40">
                            {{ $conversion->sender_id != auth()->user()->id ? $conversion->sender->username : $conversion->receiver->username }}
                        </p>
                    </div>

                    <div class="col-6 col-sm-9 col-md-4 ps-4 ps-lg-0 pt-lg-2">
                        <p class="ms-5" style="{{ $color }}">Subject : {{ Str::words($conversion->messages->last()->message, 8) }}</p>
                    </div>

                    <div class="col-6 col-sm-3 col-md-4 pt-lg-2">
                        <button class="btn btn-sm btn-green p-1 float-end"
                            onclick="location.href='{{ route('user.conversation.chat', $conversion->id) }}';">
                            <i class="fa-regular fa-comments fs-6"></i> Chat
                        </button>
                    </div>
                </div> <!-- Message End -->
            @empty
                <!-- Empty State -->
                <div class="row">
                    <div class="col-12 text-center">
                        <h6 class="title">@lang('Empty Conversation')</h6>
                    </div>
                </div>
            @endforelse
            {{ $conversions->links() }}
        </div>
    </div>
</div>
@endsection
