
@extends('templates.basic.layouts.wiserlance.dashboard-layout-job-withoutsidebar')
@section('content')
    @if (Auth::user()->is_employer)
        <p class=" mb-0 mt-3 text-bc"><a class="text-3gray" href="/user/buyer/dashboard">
    @else
        <p class=" mb-0 mt-3 text-bc"><a class="text-3gray" href="/user/seller/dashboard">
    @endif
    <i class="fa-solid fa-house fs-14"></i></a>
    <i class="fa-solid fa-chevron-right fs-12 px-1"></i>
    <a href="#" style="text-decoration:none; color:#9c9c9c">Chat </a>
    </p>
    <div class="row messenger">
        <!--Title-->
        <h3 class="text-dgreen my-2">Messages</h3>
        <!--LeftBar xxxxxxxxxxxxxxxxxxxxxxxx-->
        <div class="bg-white shadow-sm border rounded-2 px-0 st">
            <div class="row">
                <div class="col-xl-3 col-lg-4">
                    <div class="bg-light p-3 rounded-start border-end">
                        <form class="chatify-search-form">
                            <div class="rounded-1 boreder bg-white ps-1 border d-flex">
                                <input class="search messenger-search w-100" name="search" autocomplete="off" style="height:30px; font-size: 14px; border:0; padding:5px;"
                                    type="search" placeholder="Search" value="{{ request()->search }}" aria-label="Search">
                                <button class="btn btn-sm p-0 float-end" type="submit" style=""><i
                                        class="fa-solid fa-magnifying-glass p-2 fs-6 text-3gray"></i></button>
                            </div>
                        </form>
                        <div class="d-flex pt-2 chat-filters align-items-center">
                            <button
                                type="button"
                                class="btn btn-sm chat-filter-btn active"
                                data-filter="all">
                                All
                            </button>

                            <button
                                type="button"
                                class="btn btn-sm chat-filter-btn"
                                data-filter="favorite">
                                Favorite
                            </button>

                            {{-- <button
                                type="button"
                                class="btn btn-sm chat-filter-btn"
                                data-filter="archived">
                                Archived
                            </button> --}}

                        </div>
                        {{-- <div class="d-flex pt-2">
                            <div class="dropdown">
                                <button class="btn btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    All
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                </ul>
                            </div>
                            <p class="fs-14 mt-1 ms-2">
                                Favorite
                            <div class="messenger-favorites app-scroll-hidden"></div>
                            <span class="mx-3">Archived</span>
                            </p>
                            <i class="fas fa-sliders-h ms-4 mt-2 text-green"></i>
                        </div> --}}
                        <div class="listOfContacts">
                        </div>
                    </div>
                </div>
                <!--Middle Contents --xxxxxxxxxxxxxxx-->
                <div class="messenger-messagingView col-lg-8 col-xl-6">
                    {{-- header title [conversation name] amd buttons --}}

                    <div class="m-header m-header-messaging">
                        <div class="pt-3 border-bottom">
                            <p class="float-end text-3gray mt-3 me-3 fs-6">
                                {{-- <i class="fas fa-mobile-alt me-2" style="color: #003f45;"></i> --}}
                                {{-- <i class="fas fa-info-circle" style="color: #003f45;"></i> --}}
                                <a href="#" class="add-to-favorite" style="color: #003f45;">
                                    <i class="fas fa-star"></i>
                                </a>
                                <a href="/">
                                    <i class="fas fa-home" style="color: #003f45;"></i>
                                </a>
                            </p>
                            <div class="d-flex p-2">
                                <div class="avatar av-s header-avatar">
                                </div>
                                <div style=" margin-left:10px;">
                                    <a href="#"
                                        class="user-name mb-0 pt-1 text-green">{{ config('chatify.name') }}</a>
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
                            {{--  <span class="ic-connected">Connected</span>  --}}
                            {{--  <span class="ic-connecting">Connecting...</span>  --}}
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
                {{-- ---------------------- Info side ---------------------- --}}
                <div class="messenger-infoView app-scroll col-xl-3 d-lg-none d-xl-block">

                    {!! view('Chatify::layouts.info')->render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            window.chatFilterType = 'all';
            function debounce(fn, delay) {
                let timer;
                return function (...args) {
                    clearTimeout(timer);
                    timer = setTimeout(() => fn.apply(this, args), delay);
                };
            }

            $(document).on('click', '.chat-filter-btn', function () {
                const filter = $(this).data('filter');
                window.chatFilterType = filter;
                $('.chat-filter-btn').removeClass('active');
                $(this).addClass('active');
                if (filter === 'all') {
                    $('.search').val('');
                    showAllChats();
                }
                if (filter === 'favorite') {
                    loadFavoriteChats($('.search').val());
                }
                if (filter === 'archived') {
                    loadArchivedChats();
                }
            });

            $('.chatify-search-form').on('submit', function (e) {
                e.preventDefault();
                const search = $('.search').val();
                if (window.chatFilterType === 'favorite') {
                    loadFavoriteChats(search);
                } else {
                    showAllChats(search);
                }
            });

            window.showAllChats = function (search = '') {
                $.ajax({
                    url: "{{ route('contacts.get') }}",
                    type: "GET",
                    data: {
                        _token: "{{ csrf_token() }}",
                        search: search,
                    },
                    success: function (response) {
                        $('.listOfContacts').html(response.contacts);
                    },
                    error: function (xhr) {
                        console.log('All chats error:', xhr.responseText);
                    }
                });
            }

            function loadFavoriteChats(search = '') {
                $.ajax({
                    url: "{{ route('favorites') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        search: search,
                    },
                    success: function (response) {
                        if (response.favorites && response.favorites !== 0) {
                            $('.listOfContacts').html(response.favorites);
                        } else {
                            $('.listOfContacts').html(`
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-star mb-2"></i>
                                    <br>
                                    No favorite conversations
                                </div>
                            `);
                        }
                    },

                    error: function (xhr) {
                        $('.listOfContacts').html(`
                            <div class="text-center text-danger py-4">
                                Unable to load favorites
                            </div>
                        `);
                    }
                });
            }

            function loadArchivedChats() {
                $('.listOfContacts').html(`
                    <div class="text-center text-muted py-4">
                        No archived conversations
                    </div>
                `);
            }

        });
    </script>
@endsection
