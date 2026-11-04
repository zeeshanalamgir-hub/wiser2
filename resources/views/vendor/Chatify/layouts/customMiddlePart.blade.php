<div data-id="{{ $id }}" class="row" data-job_id="{{ $job_id }}" data-booking_id="{{ $booking_id ?? '' }}">
    <div class="messenger-messagingView" style="height: 436px;">
        {{-- header title [conversation name] amd buttons --}}

        <div class="m-header m-header-messaging">
            <div class="pt-3 border-bottom">
                <p class="float-end text-3gray mt-3 me-3 fs-6">
                    {{-- <i class="fas fa-mobile-alt me-2" style="color: #003f45;"></i> --}}
                    {{-- <i class="fas fa-info-circle" style="color: #003f45;"></i> --}}
                    @if(!empty($booking_id))
                        <a href="#" style="color: #003f45;" class="me-2 zoom-start-call-btn" data-booking_id="{{ $booking_id }}" title="Start Video Call"><i class="fas fa-video"></i></a>
                    @endif
                    <a href="#" style="color: #003f45;" class="add-to-favorite"><i class="fas fa-star"></i></a>
                    <a href="/" style="color: #003f45;"><i class="fas fa-home"></i></a>
                </p>
                <div class="d-flex p-2">
                    <div class="avatar av-s header-avatar">
                    </div>
                    <div style=" margin-left:10px;">
                        <a href="#" class="user-name mb-0 pt-1 text-green">{{ config('chatify.name') }}</a>
                        {{-- <p class="fs-12 text-secondary">7:05 PM </p> --}}
                    </div>
                </div>
            </div>
            {{-- <nav class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">

            <div class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                <a href="#" class="show-listView"><i class="fas fa-arrow-left"></i></a>
                <div class="avatar av-s header-avatar"
                    style="margin: 0px 10px; margin-top: -5px; margin-bottom: -5px;">
                </div>

                <div style=" margin-left:10px;">
                    <a href="#" class="user-name mb-0 pt-1 text-green">{{ config('chatify.name') }}</a>
                    <p class="fs-12 text-secondary">7:05 PM </p>
                </div>
            </div>

            <nav class="m-header-right">
                <a href="#" class="add-to-favorite"><i class="fas fa-star"></i></a>
                <a href="/"><i class="fas fa-home"></i></a>
                <a href="#" class="show-infoSide"><i class="fas fa-info-circle"></i></a>
            </nav>
        </nav> --}}
            {{-- Internet connection --}}
            <div class="internet-connection">
                {{--  <span class="ic-connected">Connected</span>
                <span class="ic-connecting">Connecting...</span>  --}}
                <span class="ic-noInternet">No internet access</span>
            </div>
        </div>

        {{-- Messaging area --}}
        <div class="m-body messages-container app-scroll">
            <div class="messages">
                <p class="message-hint center-el"><span>Please select a chat to start messaging</span></p>
            </div>
            {{-- Typing indicator --}}
            <div class="typing-indicator">
                <div class="message-card typing">
                    <div class="message">
                        <span class="typing-dots">
                            <span class="dot dot-1"></span>
                            <span class="dot dot-2"></span>
                            <span class="dot dot-3"></span>
                        </span>
                    </div>
                </div>
            </div>

        </div>
        {{-- Send Message Form --}}
        @include('Chatify::layouts.sendForm')

    </div>
</div>

@include('Chatify::layouts.footerLinks')
