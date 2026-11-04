<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="author" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $general->sitename(__($pageTitle)) }}</title>


    <link href="{{ asset('assets/wiserlance/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('assets/wiserlance/assets/css/custom-styles.css') }}" rel="stylesheet">
    <!-- Fonts Awesom -->
    <link href="{{ asset('assets/wiserlance/assets/css/all.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/wiserlance/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/wiserlance/assets/js/jquery.min.js') }}"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@26.5.1/build/css/intlTelInput.min.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@26.5.1/build/js/intlTelInputWithUtils.min.js"></script>



    @stack('style-lib')
    @stack('style')
</head>

<body class="bg-light">
    {{-- Hidden Google Translate mount point --}}
    <div id="google_translate_element" style="display:none !important;"></div>

     {{-- Loader Overlay (must be direct child of body, outside main) --}}
        <div id="translateLoader"
            style="display:none; position:fixed; inset:0; z-index:99999; backdrop-filter:blur(6px); -webkit-backdrop-filter:blur(6px); background:rgba(0,0,0,0.35); align-items:center; justify-content:center; flex-direction:column; gap:16px;">
            <div
                style="width:52px; height:52px; border:4px solid rgba(255,255,255,0.2); border-top-color:#fff; border-radius:50%; animation:spin 0.8s linear infinite;">
            </div>
            <p style="color:#fff; font-size:15px; margin:0; letter-spacing:0.4px;">Translating page...</p>
        </div>

        <style>
            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }

            .goog-te-banner-frame,
            .VIpgJd-ZVi9od-ORHb,
            .skiptranslate {
                display: none !important;
            }

            body {
                top: 0 !important;
                position: static !important;
            }
        </style>

    @include('partials.notify')
    <div class="preloader"></div>
    @include('templates.basic.partials.new_header')
    {{-- @include('templates.basic.header_new') --}}
    {{--  @include('templates.basic.partials.wiserlance.header', ['hideSecondaryHeader' => true])  --}}
    <div class="container">
        @yield('content')
        {{-- <title>@yield('pageTitle', 'Default Page Title')</title>
            <div id="contents" class="col-lg-10 px-lg-2">

            </div> --}}
    </div>
    {{--  @include('templates.basic.partials.wiserlance.footer-dashboard')  --}}
    @include('templates.basic.partials.new_footer')

    @if (isset($scripts))
        @foreach ($scripts as $script)
            <script src="{{ asset($script) }}?v={{ file_exists(public_path($script)) ? filemtime(public_path($script)) : time() }}"></script>
        @endforeach
    @endif
    @stack('script-lib')
    @yield('script')
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
    <script src="{{ asset('assets/templates/basic/scripts/js/user/buyer/job_list.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Use event delegation to handle clicks on dynamically loaded content
            $(document).on('click', '.ajax-link', function(e) {
                e.preventDefault();
                var url = $(this).data('url');
                $('.ajax-link').parent().removeClass('active');
                $(this).parent().addClass('active');
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        $('#contents').html(response);
                        if (history.pushState) {
                            window.history.pushState({
                                path: url
                            }, '', url);
                        }
                        // Reinitialize any necessary scripts here
                        initializeTabs(); // Important to reapply tab functionality
                    },
                    error: function(xhr) {
                        console.log("An error occurred: " + xhr.status + " " + xhr.statusText);
                    }
                });
            });
        });
    </script>

    <script>
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'ar,ur,en,fr,de',
                autoDisplay: false
            }, 'google_translate_element');
        }

        function showLoader() {
            document.getElementById('translateLoader').style.display = 'flex';
        }

        function hideLoader() {
            document.getElementById('translateLoader').style.display = 'none';
        }

        function translatePage(lang) {
            showLoader();

            var select = document.querySelector('.goog-te-combo');
            if (select) {
                select.value = lang;
                select.dispatchEvent(new Event('change'));
            }

            var minTime = 3000;
            var start = Date.now();

            var observer = new MutationObserver(function() {
                var elapsed = Date.now() - start;
                var remaining = Math.max(0, minTime - elapsed);
                setTimeout(hideLoader, remaining);
                observer.disconnect();
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true,
                characterData: true
            });

            setTimeout(hideLoader, 5000);
        }

        $(document).on('change', '#languageSelect', function() {
            var selectedLang = $(this).val();
            translatePage(selectedLang);
        });
    </script>

    <script type="text/javascript" src="{{ asset('js/google_translater.js') }}"></script>


</body>

</html>
