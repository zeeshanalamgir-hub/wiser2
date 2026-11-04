<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <meta name="author" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $general->sitename(__($pageTitle)) }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(isset($id))
    <meta name="id" content="{{ $id }}">
    @endif

    @if(isset($messengerColor))
        <meta name="messenger-color" content="{{ $messengerColor }}">
    @endif

    @if(isset($dark_mode))
        <meta name="messenger-theme" content="{{ $dark_mode }}">
    @endif

    @if(Auth::check())
         <meta name="user-id" content="{{ auth()->id() }}">
        <meta name="url" content="{{ url('').'/'.config('chatify.routes.prefix') }}" data-user="{{ Auth::user()->id }}">
    @endif
    @include('partials.seo')
    <link href="{{ asset('assets/wiserlance/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('assets/wiserlance/assets/css/custom-styles.css') }}" rel="stylesheet">
    <!-- Fonts Awesom -->
    <link href="{{ asset('assets/wiserlance/assets/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/wiserlance/assets/css/styles.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/wiserlance/assets/js/bootstrap.bundle.min.js') }}"></script>
</head>

<body>
    @include('partials.notify')

    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader"></div>
    @if ($pageTitle == 'Service details')
        @yield('chat')
    @endif

    @if ($pageTitle == 'User Profile')
        @yield('chat')
    @endif
    @if ($pageTitle == 'Job Details')
        @yield('chat')
    @endif
    @if ($pageTitle == 'Service Booking')
        @yield('chat')
    @endif
    <script src="{{ asset('assets/wiserlance/assets/js/jquery.min.js') }}"></script>
    <!-- Google Translate Script -->
    <script src="{{ asset('assets/wiserlance/assets/js/custom.js') }}"></script>
    {{-- <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,ru,ar', // Specify languages to include
                layout: google.translate.TranslateElement.FloatPosition.TOP_LEFT,
                autoDisplay: true
            }, 'google_translate_element');
    </script>
    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script> --}}

    @include('templates.basic.partials.wiserlance.header', ['hideSecondaryHeader' => true])
    <div class="container">
        @yield('content')
    </div>
    @include('templates.basic.partials.wiserlance.footer')

    <script src="{{ asset('assets/wiserlance/assets/js/slick.js') }}"></script>
    {{-- <script src="{{ asset('assets/wiserlance/assets/js/slider-bg.js') }}"></script> --}}
    <script src="{{ asset('assets/wiserlance/assets/js/smoothproducts.js') }}"></script>
    {{-- <script src="{{ asset('assets/wiserlance/assets/js/snackbar.min.js') }}"></script>
    <script src="{{ asset('assets/wiserlance/assets/js/jQuery.style.switcher.js') }}"></script> --}}

    @yield('script')

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".btn_subs").click(function(e) {
            e.preventDefault();
            document.getElementById("btn_subs").disabled = true;
            var ch_s_id = $("input[name=ch_s_id]").val();
            var ch_r_id = $("input[name=ch_r_id]").val();
            var messageg = $("input[name=message]").val();
            var chat_url = "{{ url('chat-secreen') }}" + "/" + ch_s_id + "/" + ch_r_id;

            $.ajax({
                type: 'POST',
                url: "{{ route('chat-store') }}",
                data: {
                    ch_s_id: ch_s_id,
                    ch_r_id: ch_r_id,
                    message: messageg
                },
                success: function(data) {
                    var message = '<div class="client-message"><a href=' + chat_url +
                        '>view Message</a></div>';

                    // var message = '<div class="message-plunch me"><div class="dash-msg-avatar"><img src="https://beta.bizlanceguru.com/wiselancer/assets/img/team-1.jpg" alt=""></div><div class="dash-msg-text"><p>' + messageg + '</p></div></div>';
                    $("#message_boxs").append(message);
                    $("#message").val('');
                }
            });

        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#show-reply-area').click(function() {
                $('.reply-form-area').slideToggle("slow");
            });
        });
    </script>
    <script type="text/javascript">
        $(".langSel").on("change", function() {
            window.location.href = "{{ route('home') }}/change/" + $(this).val();
        });
    </script>
    <script>
        var toggle = document.getElementById("toggle");
        var content = document.getElementById("content");
        var body = document.getElementById("body");
        var close = document.getElementById("close");
        if (toggle) {
            toggle.addEventListener("click", function() {
                content.classList.toggle("sidebar");
                body.classList.toggle("body");
            });
        }
    </script>
    <script type="text/javascript">
        $(document).on("click", ".loveHeartAction", function() {
            let id = $(this).data('serviceid');
            //alert(id)
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                url: "{{ route('user.favorite.service') }}",
                method: "POST",
                dataType: "json",
                data: {
                    id: id
                },
                success: function(response) {
                    if (response.success) {
                        notify('success', response.success);
                    } else {
                        $.each(response, function(i, val) {
                            notify('error', val);
                        });
                    }
                }
            });
        });
    </script>

    @if (isset($scripts))
        @foreach ($scripts as $script)
            <script src="{{ asset($script) }}?v={{ file_exists(public_path($script)) ? filemtime(public_path($script)) : time() }}"></script>
        @endforeach
    @endif
    @if (app()->environment('local'))
    <script src="{{ mix('js/app.js') }}"></script>
    @else
        <script src="/js/app.js"></script>
    @endif
</body>
</html>
