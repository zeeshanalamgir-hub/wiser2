<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">
    {{-- Meta tags --}}
    @if (isset($id))
        <meta name="id" content="{{ $id }}">
    @endif

    @if (isset($messengerColor))
        <meta name="messenger-color" content="{{ $messengerColor }}">
    @endif

    @if (isset($dark_mode))
        <meta name="messenger-theme" content="{{ $dark_mode }}">
    @endif

    @if (Auth::check())
        <meta name="url" content="{{ url('') . '/' . config('chatify.routes.prefix') }}"
            data-user="{{ Auth::user()->id }}">
    @endif
    <title>Wiserlance - Hiring </title>

    {{-- <link rel='stylesheet' href='https://unpkg.com/nprogress@0.2.0/nprogress.css'/> --}}
    <link href="{{ asset('assets/wiserlance/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('assets/wiserlance/assets/css/custom-styles.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/wiserlance/assets/css/jquery.datetimepicker.min.css') }}" rel="stylesheet">
    <!-- Fonts Awesom -->
    <link href="{{ asset('assets/wiserlance/assets/css/all.min.css') }}" rel="stylesheet">
    <link rel='stylesheet' href='https://unpkg.com/nprogress@0.2.0/nprogress.css' />
    <script src="{{ asset('assets/wiserlance/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/wiserlance/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/wiserlance/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/wiserlance/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/wiserlance/assets/js/slick.js') }}"></script>
    <script src="{{ asset('assets/wiserlance/assets/js/slider-bg.js') }}"></script>
    <script src="{{ asset('assets/wiserlance/assets/js/smoothproducts.js') }}"></script>
    <script src="{{ asset('assets/wiserlance/assets/js/snackbar.min.js') }}"></script>
    <script src="{{ asset('assets/wiserlance/assets/js/jQuery.style.switcher.js') }}"></script>
    <script src="{{ asset('assets/wiserlance/assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/templates/basic/scripts/js/data-ajax.js') }}"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@26.5.1/build/css/intlTelInput.min.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@26.5.1/build/js/intlTelInputWithUtils.min.js"></script>

    {{--  Personna  --}}
    <script src="https://cdn.withpersona.com/dist/persona-v5.1.2.js"
        integrity="sha384-nuMfOsYXMwp5L13VJicJkSs8tObai/UtHEOg3f7tQuFWU5j6LAewJbjbF5ZkfoDo" crossorigin="anonymous">
    </script>

    {{-- <script src="{{ asset('assets/wiserlance/assets/js/pusher.min.js') }}"></script>
    <script src="{{ asset('assets/wiserlance/assets/js/index.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/chatify/autosize.js') }}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js">
    </script>
    {{-- <script src='https://unpkg.com/nprogress@0.2.0/nprogress.js'></script> --}}
    <script>
        function loadAjaxContent(url, tabText = null) {
            if (tabText) {
                $('#current-breadcrumb').text(tabText);
            }
            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(html => {
                    const contentDiv = document.getElementById('ajax-content');
                    contentDiv.innerHTML = html;
                    // Extract the meta tag from the loaded HTML
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const id = doc.querySelector('[data-id]')?.getAttribute('data-id');
                    if (id) {
                        let existingMetaId = document.querySelector('meta[name="id"]');
                        if (existingMetaId) {
                            // Update the existing meta tag
                            existingMetaId.content = id;
                        } else {
                            // Create a new meta tag if it doesn't exist
                            const meta = document.createElement('meta');
                            meta.name = 'id';
                            meta.content = id;
                            document.head.appendChild(meta);
                        }
                    }
                    const scripts = contentDiv.querySelectorAll('script');
                    scripts.forEach(script => {
                        const newScript = document.createElement('script');
                        if (script.src) {
                            newScript.src = script.src;
                        } else {
                            newScript.text = script.innerHTML;
                        }
                        document.body.appendChild(newScript);
                    });
                    // if (typeof Chatify !== 'undefined' && typeof Chatify.initialize === 'function') {
                    //     Chatify.initialize();
                    //     console.log('Chatify re-initialized after AJAX load');
                    // } else {
                    //     console.error('Chatify is not defined or missing initialization function.');
                    // }
                })
                .catch(error => console.error('Error loading content:', error));
        }
        window.addEventListener('popstate', function(event) {
            const url = document.location.href;

            // Reload the content based on the current URL
            loadAjaxContent(url);
        });

        function updateBrowserUrl(url) {
            if (history.pushState) {
                window.history.pushState({
                    path: url
                }, '', url);
            } else {
                console.warn('Browser does not support history.pushState');
            }
        }

        function initializeAjaxLinks() {
            // Event delegation for dynamically generated elements
            document.body.addEventListener('click', function(e) {
                const target = e.target;

                if (target.classList.contains('custom-ajax-link')) {
                    e.preventDefault();

                    // Update active state for tabs
                    document.querySelectorAll('.custom-ajax-link').forEach(link => {
                        link.classList.remove('active', 'text-green');
                    });
                    target.classList.add('active', 'text-green');

                    // Load the content
                    const url = target.getAttribute('data-url');

                    //console.log(url);
                    const tabText = target.textContent.trim();
                    document.getElementById('breadcrumb-title').innerText = tabText;
                    updateBrowserUrl(url);
                    loadAjaxContent(url, tabText);
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            initializeAjaxLinks();

        });

        $('.contractBtn').on('click', function() {
            var modal = $('#contractModal');
            modal.find('input[name=buyer_id]').val($(this).data('buyer_id'))
            modal.find('input[name=seller_id]').val($(this).data('seller_id'))
            modal.modal('show');
        });
    </script>
    <style>
        .form-check-input:checked {
            background-color: #003f45 !important;
            border-color: #003f45 !important;
        }
    </style>
</head>

<body class="bg-light">
    @include('partials.notify')
    <div class="preloader"></div>
    @if (Auth::user()->is_freelancer == '1' || Auth::user()->is_freelancer == 1)
        {{-- @include('templates.basic.partials.wiserlance.seller-header') --}}
        @include('templates.basic.partials.wiserlance.seller-new-header')
    @else
        {{-- @include('templates.basic.partials.wiserlance.seller-header') --}}
        @include('templates.basic.partials.wiserlance.buyer-new-header')
    @endif
    <div class="container">
        <div class="row">
            @if (Auth::check())
                @if (Auth::user()->is_employer == '1' || Auth::user()->is_employer == 1)
                    @include('templates.basic.partials.wiserlance.dashboard-sidebar-buyer')
                    @include('templates.basic.partials.wiserlance.dashboard-mobile-sidebar-buyer')
                @elseif (Auth::user()->is_freelancer == '1')
                    @include('templates.basic.partials.wiserlance.dashboard-sidebar')
                    @include('templates.basic.partials.wiserlance.dashboard-mobile-sidebar-seller')
                @endif
            @endif
            {{-- <title>@yield('pageTitle', 'Default Page Title')</title> --}}

            <div id="contents" class="col-lg-10 col-lg-10 p-4">
                <div id="breadcrumb">
                    @include('components.breadcrumbs')
                </div>
                <div id="main-content">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    @include('templates.basic.partials.wiserlance.footer-new-dashboard')
    {{-- @include('templates.basic.partials.wiserlance.footer-dashboard') --}}


    @if (isset($scripts))
        @foreach ($scripts as $script)
            <script src="{{ asset($script) }}?v={{ file_exists(public_path($script)) ? filemtime(public_path($script)) : time() }}"></script>
        @endforeach
    @endif
    <script src="{{ asset($activeTemplateTrue . 'frontend/js/nicEdit.js') }}"></script>
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
    @if(Auth::check() && Auth::user()->is_employer == 1)
        <script src="{{ asset('assets/templates/basic/scripts/js/user/buyer/job_list.js') }}"></script>
    @else
        <script src="{{ asset('assets/templates/basic/scripts/js/user/buyer/seller_job_list.js') }}"></script>
    @endif
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
                        console.log(response.breadcrumbs);
                        if (response.breadcrumbs) {
                            $('#breadcrumb').html(response.breadcrumbs); // Update breadcrumbs
                        }

                        if (response.html) {
                            $('#main-content').html(response.html);
                        } else
                            $('#main-content').html(response);


                        if (history.pushState) {
                            window.history.pushState({
                                path: url
                            }, '', url);
                        }
                        reloadActiveTab();
                        if (typeof initializeTabs === 'function') {
                            initializeTabs();
                        } else {
                            console.log('initializeTabs function not found!');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 401 && xhr.responseJSON.redirect_url) {
                            // Redirect to login page for unauthenticated AJAX requests
                            alert(redirect_url);
                            window.location.href = xhr.responseJSON.redirect_url;
                        } else {
                            console.error("An error occurred: " + xhr.status + " " + xhr
                                .statusText);
                        }
                    }
                });
            });

            function reloadActiveTab() {
                let activeTab = $('.job-tab-buttons .btn-success'); // Find the currently active tab
                if (activeTab.length) {
                    let url = activeTab.data('url');
                    let target = activeTab.data('target');
                    let pageTitle = activeTab.data('page-title');
                    fetchAndUpdateContent(url, target, null, pageTitle);
                } else {
                    console.log("No active tab found!");
                }
            }
            //reloadActiveTab();
            // window.chatify = {
            //         name: "{{ config('chatify.name') }}",
            //         sounds: {!! json_encode(config('chatify.sounds')) !!},
            //         allowedImages: {!! json_encode(config('chatify.attachments.allowed_images')) !!},
            //         allowedFiles: {!! json_encode(config('chatify.attachments.allowed_files')) !!},
            //         maxUploadSize: {{ Chatify::getMaxUploadSize() }},
            //         reverb: {!! json_encode(config('chatify.pusher')) !!},
            //         pusherAuthEndpoint: '{{ route('pusher.auth') }}'
            //     };
            // // Combine allowed image and file extensions
            // window.chatify.allAllowedExtensions = chatify.allowedImages.concat(chatify.allowedFiles);
            // console.log("Chatify configuration initialized:", window.chatify);
        });
    </script>
    <script src="{{ asset('js/chatify/autosize.js') }}"></script>
    @if (app()->environment('local'))
        <script src="{{ mix('js/app.js') }}"></script>
    @else
        <script src="/js/app.js"></script>
    @endif
    <script src='https://unpkg.com/nprogress@0.2.0/nprogress.js'></script>
    {{-- @include('Chatify::layouts.modals') --}}
    {{-- @include('Chatify::layouts.footerLinks') --}}

    @auth
    <script src="https://js.pusher.com/7.2.0/pusher.min.js"></script>
    <script>
    (function () {
        const pusherConfig = {!! json_encode(config('chatify.pusher')) !!};
        const authId = {{ auth()->id() }};
        const csrfToken = "{{ csrf_token() }}";

        const pusher = new Pusher(pusherConfig.key, {
            cluster: pusherConfig.options.cluster,
            wsHost: pusherConfig.options.host,
            wsPort: pusherConfig.options.port,
            wssPort: pusherConfig.options.port,
            forceTLS: pusherConfig.options.useTLS,
            authEndpoint: '{{ route("pusher.auth") }}',
            httpHost: pusherConfig.options.httpHost,
            auth: { headers: { "X-CSRF-TOKEN": csrfToken } }
        });

        const channel = pusher.subscribe(`private-chatify.${authId}`);

        channel.bind('messaging', function (data) {
            if (data.to_id == authId) {
                const badge = document.getElementById('msg-unread-badge');
                if (badge) {
                    const current = parseInt(badge.textContent.trim()) || 0;
                    badge.textContent = current + 1;
                    badge.style.display = 'flex';
                }
            }
        });
    })();
    </script>
    @endauth
</body>

</html>
