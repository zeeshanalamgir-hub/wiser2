<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wiserlance - @yield('title')</title>
    <!-- Font Awesome for icons (required) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>
        /* ========== ROOT VARIABLES (copy from original) ========== */
        :root {
            --primary: #0f5256;
            --primary-dark: #0a3a3d;
            --secondary: #4caf50;
            --secondary-dark: #3a8f3c;
            --dark: #0d0d0d;
            --body: #f4f4f2;
            --white: #ffffff;
            --gray: #64748b;
            --gray-light: #94a3b8;
            --border: #e2e8f0;
            --shadow-sm: 0 1px 4px rgba(0, 0, 0, .08);
            --shadow: 0 4px 16px rgba(0, 0, 0, .10);
            --shadow-lg: 0 12px 40px rgba(0, 0, 0, .14);
            --radius: 12px;
            --transition: .25s ease;
            --header-h: 68px;
            --banner-h: 48px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: var(--body);
            color: var(--dark);
            line-height: 1.6;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
        }

        /* ========== HEADER STYLES (condensed) ========== */
        header {
            background: linear-gradient(135deg, #0f5256, #0a3d40);
            position: sticky;
            top: 0;
            z-index: 1000;
            height: var(--header-h);
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.18);
        }

        .nav {
            display: flex;
            align-items: center;
            height: var(--header-h);
            width: 100%;
        }

        /* Logo */
        .logo {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 1.25rem;
            font-weight: 800;
            color: #fff;
            flex-shrink: 0;
            margin-right: 16px;
        }

        .logo-icon {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: .95rem;
        }

        /* Navigation links */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 0;
            flex: 0 1 auto;
        }

        .nav-links>a,
        .nav-drop-wrap>.nav-link {
            color: rgba(255, 255, 255, 0.88);
            font-size: .83rem;
            padding: 8px 10px;
            border-radius: 8px;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .nav-links>a:hover,
        .nav-drop-wrap:hover>.nav-link {
            color: #fff;
            background: rgba(255, 255, 255, .1);
        }

        /* Dropdown */
        .nav-drop-wrap {
            position: relative;
        }

        .nav-link .chev {
            font-size: .6rem;
            opacity: .6;
            color: rgba(255, 255, 255, .7);
        }

        .nav-drop-wrap:hover .chev {
            transform: rotate(180deg);
            opacity: 1;
        }

        .nav-drop-menu {
            display: none;
            position: absolute;
            top: calc(100%+6px);
            left: 0;
            background: var(--white);
            border-radius: 14px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
            min-width: 240px;
            z-index: 9999;
            padding: 8px;
        }

        .nav-drop-wrap:hover .nav-drop-menu {
            display: block;
        }

        .nav-drop-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 9px;
            color: var(--dark);
            font-size: .86rem;
            font-weight: 500;
            white-space: nowrap;
        }

        .nav-drop-menu a i {
            width: 16px;
            color: var(--primary);
            font-size: .8rem;
        }

        .nav-drop-menu a:hover {
            background: rgba(15, 82, 86, .06);
            color: var(--primary);
        }

        /* Header search (scroll-triggered) */
        .h-search-wrap {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            border: 1.5px solid rgba(255, 255, 255, 0.22);
            border-radius: 7px;
            overflow: hidden;
            opacity: 0;
            width: 0;
            pointer-events: none;
            transition: opacity .35s ease, width .35s ease;
            height: 32px;
        }

        .h-search-wrap.visible {
            opacity: 1;
            width: 380px;
            pointer-events: all;
        }

        .h-search-wrap .hs-type {
            border: none;
            background: transparent;
            color: #fff;
            font-size: .78rem;
            font-weight: 600;
            cursor: pointer;
            outline: none;
            height: 100%;
            padding: 0 22px 0 10px;
            border-right: 1px solid rgba(255, 255, 255, 0.18);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='5'%3E%3Cpath d='M0 0l4 5 4-5z' fill='rgba(255,255,255,0.7)'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
        }

        .h-search-wrap .hs-input-row {
            display: flex;
            align-items: center;
            gap: 6px;
            flex: 1;
            padding: 0 12px;
        }

        .h-search-wrap .hs-input-row i {
            color: rgba(255, 255, 255, 0.5);
            font-size: .82rem;
        }

        .h-search-wrap input {
            border: none;
            background: transparent;
            color: #fff;
            font-size: .82rem;
            outline: none;
            flex: 1;
            height: 100%;
        }

        .h-search-wrap input::placeholder {
            color: rgba(255, 255, 255, 0.45);
        }

        /* Right side */
        .nav-right {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-left: auto;
            flex-shrink: 0;
        }

        .lang {
            display: flex;
            align-items: center;
            gap: 4px;
            color: rgba(255, 255, 255, .8);
            font-size: .84rem;
        }

        .lang select {
            border: none;
            background: transparent;
            color: rgba(255, 255, 255, .85);
            cursor: pointer;
            outline: none;
        }

        .lang select option {
            background: #0f5256;
            color: #fff;
        }

        .btn {
            padding: 8px 18px;
            border-radius: 9px;
            font-weight: 700;
            cursor: pointer;
            font-size: .86rem;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-ghost {
            border: 1.5px solid rgba(255, 255, 255, .35) !important;
            color: #fff;
            background: transparent;
        }

        .btn-ghost:hover {
            border-color: rgba(255, 255, 255, .7) !important;
            background: rgba(255, 255, 255, .08);
        }

        .btn-primary {
            background: var(--secondary);
            color: #fff;
        }

        .nav-login {
            color: rgba(255, 255, 255, .88);
            font-size: .86rem;
            font-weight: 500;
            padding: 6px 10px;
            border-radius: 7px;
        }

        .nav-login:hover {
            color: #fff;
            background: rgba(255, 255, 255, .1);
        }

        .btn-primary:hover {
            background: var(--secondary-dark);
        }

        .mob-btn {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 1.3rem;
            cursor: pointer;
            padding: 6px;
            min-width: 44px;
        }

        .mob-search-btn {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 1.1rem;
            cursor: pointer;
            padding: 6px;
            min-width: 40px;
            border-radius: 8px;
        }

        /* Mobile elements */
        .mob-search-bar {
            background: linear-gradient(135deg, #0f5256, #0a3d40);
            border-bottom: 1px solid rgba(255, 255, 255, .1);
            max-height: 0;
            overflow: hidden;
            transition: max-height .35s ease;
        }

        .mob-search-bar.open {
            max-height: 80px;
        }

        .mob-search-bar .container {
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .msb-inner {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, .1);
            border: 1.5px solid rgba(255, 255, 255, .22);
            border-radius: 8px;
            height: 38px;
        }

        .msb-type {
            border: none;
            background: transparent;
            color: #fff;
            font-size: .78rem;
            font-weight: 600;
            cursor: pointer;
            outline: none;
            height: 100%;
            padding: 0 22px 0 10px;
            border-right: 1px solid rgba(255, 255, 255, .2);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='5'%3E%3Cpath d='M0 0l4 5 4-5z' fill='rgba(255,255,255,0.7)'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
        }

        .msb-type option {
            background: #0f5256;
        }

        .msb-input {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            padding: 0 12px;
        }

        .msb-input i {
            color: rgba(255, 255, 255, .5);
            font-size: .82rem;
        }

        .msb-input input {
            border: none;
            background: transparent;
            color: #fff;
            font-size: .85rem;
            outline: none;
            flex: 1;
            height: 100%;
        }

        .msb-input input::placeholder {
            color: rgba(255, 255, 255, .45);
        }

        .mob-nav {
            display: none;
            background: linear-gradient(135deg, #0f5256, #0a3d40);
            border-top: 1px solid rgba(255, 255, 255, .08);
            padding: 10px 16px 14px;
            flex-direction: column;
            gap: 2px;
        }

        .mob-nav.open {
            display: flex;
        }

        .mob-nav a {
            padding: 11px 14px;
            border-radius: 9px;
            color: rgba(255, 255, 255, .85);
            font-size: .9rem;
            font-weight: 500;
            min-height: 44px;
            display: flex;
            align-items: center;
        }

        .mob-nav a:hover {
            background: rgba(255, 255, 255, .1);
            color: #fff;
        }

        /* ========== CATEGORIES BANNER ========== */
        .cat-banner {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: var(--header-h);
            z-index: 998;
        }

        .banner-row {
            display: flex;
            align-items: center;
        }

        .ban-arrow {
            flex-shrink: 0;
            width: 40px;
            height: var(--banner-h);
            background: var(--white);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray);
            font-size: .85rem;
        }

        .ban-arrow:hover {
            color: var(--primary);
            background: rgba(15, 82, 86, .05);
        }

        .ban-arrow.left {
            border-right: 1px solid var(--border);
        }

        .ban-arrow.right {
            border-left: 1px solid var(--border);
        }

        .cat-scroll {
            display: flex;
            overflow-x: auto;
            flex: 1;
            scrollbar-width: none;
        }

        .cat-scroll::-webkit-scrollbar {
            display: none;
        }

        .cdw {
            position: static;
            flex: 0 0 auto;
        }

        .cat-item {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 0 15px;
            height: var(--banner-h);
            color: var(--gray);
            font-size: .84rem;
            font-weight: 600;
            white-space: nowrap;
            cursor: pointer;
            border-bottom: 2px solid transparent;
        }

        .cat-item .ci {
            font-size: .74rem;
            color: var(--secondary);
        }

        .cat-item .ca {
            font-size: .58rem;
        }

        .cdw:hover .cat-item {
            color: var(--primary);
            border-bottom-color: var(--primary);
            background: rgba(15, 82, 86, .04);
        }

        /* Mega dropdown */
        .mega {
            display: none;
            position: fixed;
            left: 0;
            right: 0;
            background: var(--white);
            border-top: 2px solid var(--primary);
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-lg);
            z-index: 9999;
            padding: 20px 0 22px;
        }

        .cdw:hover .mega {
            display: block;
        }

        .mega-in {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
            display: flex;
            gap: 36px;
        }

        .mega-left {
            flex: 0 0 200px;
            padding-right: 28px;
            border-right: 1px solid var(--border);
        }

        .mega-left h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 6px;
        }

        .mega-left p {
            font-size: .82rem;
            color: var(--gray);
            margin-bottom: 14px;
        }

        .mega-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: var(--primary);
            font-size: .82rem;
            font-weight: 600;
        }

        .mega-btn i {
            font-size: .75rem;
        }

        .mega-grid {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 9px 16px;
        }

        .mega-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #444;
            font-size: .81rem;
            font-weight: 400;
            white-space: nowrap;
        }

        .mega-link i {
            width: 14px;
            font-size: .72rem;
            color: #94a3b8;
        }

        .mega-link:hover {
            color: var(--primary);
        }

        .mega-link:hover i {
            color: var(--primary);
        }

        /* ========== FOOTER STYLES ========== */
        footer {
            background: var(--primary);
            color: #fff;
            padding: 44px 0 22px;
        }

        .ft-grid {
            display: grid;
            grid-template-columns: 1.7fr repeat(4, 1fr);
            gap: 28px;
            padding-bottom: 28px;
            border-bottom: 1px solid rgba(255, 255, 255, .09);
            margin-bottom: 22px;
        }

        .ft-logo {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 1.2rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 12px;
        }

        .ft-logo .logo-icon {
            width: 30px;
            height: 30px;
            font-size: .82rem;
        }

        .ft-brand p {
            color: rgba(255, 255, 255, .45);
            font-size: .83rem;
            line-height: 1.7;
            margin-bottom: 18px;
        }

        .social {
            display: flex;
            gap: 8px;
        }

        .social a {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .1);
            color: rgba(255, 255, 255, .6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .82rem;
        }

        .social a:hover {
            background: var(--secondary);
            border-color: var(--secondary);
            color: #fff;
        }

        .ft-col h4 {
            font-size: .78rem;
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 14px;
            text-transform: uppercase;
        }

        .ft-col ul {
            list-style: none;
        }

        .ft-col li {
            margin-bottom: 8px;
        }

        .ft-col a {
            color: rgba(255, 255, 255, .45);
            font-size: .84rem;
            display: inline-block;
        }

        .ft-col a:hover {
            color: var(--secondary);
            transform: translateX(3px);
        }

        .ft-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .copy {
            color: rgba(255, 255, 255, .3);
            font-size: .8rem;
        }

        .ft-links {
            display: flex;
            gap: 16px;
        }

        .ft-links a {
            color: rgba(255, 255, 255, .3);
            font-size: .79rem;
        }

        .ft-links a:hover {
            color: var(--secondary);
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ========== MOBILE RESPONSIVE (minified) ========== */
        @media(max-width:820px) {
            :root {
                --header-h: 60px;
            }

            .nav-links,
            .lang {
                display: none;
            }

            .mob-btn {
                display: block;
            }

            .h-search-wrap {
                display: none !important;
            }

            .mob-search-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .nav-right .btn-ghost {
                display: none;
            }

            .nav-right {
                gap: 8px;
            }
        }

        @media(max-width:600px) {
            .container {
                padding: 0 16px;
            }

            .ft-grid {
                grid-template-columns: 1fr 1fr;
            }

            .ft-brand {
                grid-column: 1/-1;
            }
        }

        @media(max-width:380px) {
            .ft-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @yield('custom-css')
</head>

<body>

    {{-- Hidden Google Translate mount point --}}
    <div id="google_translate_element" style="display:none !important;"></div>

    @include('templates.basic.partials.new_header')

    <main>
        @include('partials.notify')
        @yield('content')
    </main>

    @include('templates.basic.partials.new_footer')
    <script src="{{ asset('js/new_footer.js') }}"></script>

    @unless (request()->is('/'))
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
    @endunless

    @yield('custom-js')
        @unless (request()->is('/'))
        <script>
            function getCurrentLanguage() {
                let match = document.cookie.match(/googtrans=\/[^\/]+\/([^;]+)/);
                return match ? match[1] : 'en';
            }

            function isEnglish() {
                return getCurrentLanguage() === 'en-US';
            }

            function googleTranslateElementInit() {
                new google.translate.TranslateElement({
                    pageLanguage: 'en-US',
                    includedLanguages: 'ar,ur,en,fr,de',
                    autoDisplay: false
                }, 'google_translate_element');

                setTimeout(handlePageLoadLanguage, 500);
            }

            function showLoader() {
                document.getElementById('translateLoader').style.display = 'flex';
            }

            function hideLoader() {
                document.getElementById('translateLoader').style.display = 'none';
            }

            function handlePageLoadLanguage() {
                let lang = getCurrentLanguage();

                if (lang === 'en' || lang === 'en-US') {
                    hideLoader();
                    return;
                }

                let select = document.querySelector('.goog-te-combo');

                if (select && select.value !== lang) {
                    select.value = lang;
                    select.dispatchEvent(new Event('change'));
                }
            }

            function translatePage(lang) {

                if (getCurrentLanguage() === lang) {
                    return;
                }

                let select = document.querySelector('.goog-te-combo');

                if (select) {
                    select.value = lang;
                    select.dispatchEvent(new Event('change'));
                }

                if (lang === 'en') { 
                    lang = 'en-US'; 
                }

                if (lang === 'en') {
                hideLoader();
                return;
            }

            showLoader();

            let minTime = 2000;
            let start = Date.now();

            let observer = new MutationObserver(function () {
                let elapsed = Date.now() - start;
                let remaining = Math.max(0, minTime - elapsed);

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

        $(document).on('change', '#languageSelect', function () {
            let selectedLang = $(this).val();
            translatePage(selectedLang);
        });

        $(document).ready(function () {
            if (isEnglish()) {
                hideLoader();
            } else {
                handlePageLoadLanguage();
            }
        });

        </script>

        <script type="text/javascript" src="{{ asset('js/google_translater.js') }}"></script>
    @endunless

</body>

</html>
