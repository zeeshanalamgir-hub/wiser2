<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wiserlance – Find Freelancers &amp; Jobs</title>

    <!-- System font stack - no external font needed -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>
        /* ROOT / RESET - (keep all your existing CSS exactly as is) */
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

        html {
            scroll-behavior: smooth;
        }

        body {
            --bs-font-sans-serif: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", "Noto Sans", "Liberation Sans", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            --bs-font-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-family: var(--bs-font-sans-serif);
            background: var(--body);
            color: var(--dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        img {
            display: block;
            max-width: 100%;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
        }

        /*  HEADER  – Upwork-style: white bg, clean, airy*/
        header {
            background: linear-gradient(135deg, #0f5256, #0a3d40);
            border-bottom: none;
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
            gap: 0;
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
            letter-spacing: -.4px;
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

        /* Nav links – Upwork style: plain text, hover underline */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 0;
            flex: 0 1 auto;
            min-width: 0;
        }

        .nav-links>a,
        .nav-drop-wrap>.nav-link {
            color: rgba(255, 255, 255, 0.88);
            font-size: .83rem;
            font-weight: 400;
            padding: 8px 10px;
            border-radius: 8px;
            transition: var(--transition);
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

        /* About dropdown */
        .nav-drop-wrap {
            position: relative;
        }

        .nav-link .chev {
            font-size: .6rem;
            opacity: .6;
            transition: transform .2s;
            color: rgba(255, 255, 255, .7);
        }

        .nav-drop-wrap:hover .chev {
            transform: rotate(180deg);
            opacity: 1;
            color: #fff;
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
            animation: fadeDown .18s ease;
        }

        @keyframes fadeDown {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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
            transition: var(--transition);
            white-space: nowrap;
        }

        .nav-drop-menu a i {
            width: 16px;
            text-align: center;
            color: var(--primary);
            font-size: .8rem;
        }

        .nav-drop-menu a:hover {
            background: rgba(15, 82, 86, .06);
            color: var(--primary);
            padding-left: 16px;
        }

        .nav-drop-menu hr {
            border: none;
            border-top: 1px solid var(--border);
            margin: 4px 0;
        }

        /* ── Header search: compact Upwork style on DARK header ── */
        .h-search-wrap {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            border: 1.5px solid rgba(255, 255, 255, 0.22);
            border-radius: 7px;
            overflow: hidden;
            margin: 0;
            opacity: 0;
            width: 0;
            max-width: 0;
            pointer-events: none;
            transition: opacity .35s ease, width .35s ease, max-width .35s ease;
            height: 32px;
            flex-shrink: 1;
            flex-grow: 0;
        }

        .h-search-wrap.visible {
            opacity: 1;
            width: 380px;
            max-width: 380px;
            pointer-events: all;
        }

        .h-search-wrap:focus-within {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(76, 175, 80, 0.7);
        }

        /* Left: dropdown type */
        .h-search-wrap .hs-type {
            border: none;
            background: transparent;
            color: #fff;
            font-family: inherit;
            font-size: .78rem;
            font-weight: 600;
            cursor: pointer;
            outline: none;
            height: 100%;
            padding: 0 22px 0 10px;
            flex-shrink: 0;
            white-space: nowrap;
            border-right: 1px solid rgba(255, 255, 255, 0.18);
            -webkit-appearance: none;
            appearance: none;
            width: auto;
            min-width: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='5'%3E%3Cpath d='M0 0l4 5 4-5z' fill='rgba(255,255,255,0.7)'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
        }

        .h-search-wrap .hs-type option {
            background: #0f5256;
        }

        /* Right: text input + search icon */
        .h-search-wrap .hs-input-row {
            display: flex;
            align-items: center;
            gap: 6px;
            flex: 1;
            padding: 0 12px;
            min-width: 0;
        }

        .h-search-wrap .hs-input-row i {
            color: rgba(255, 255, 255, 0.5);
            font-size: .82rem;
            flex-shrink: 0;
        }

        .h-search-wrap input {
            border: none;
            background: transparent;
            color: #fff;
            font-family: inherit;
            font-size: .82rem;
            outline: none;
            flex: 1;
            min-width: 0;
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
            font-weight: 500;
            cursor: pointer;
        }

        .lang select {
            border: none;
            background: transparent;
            font-family: inherit;
            font-size: .83rem;
            font-weight: 600;
            color: rgba(255, 255, 255, .85);
            cursor: pointer;
            outline: none;
        }

        .lang select option {
            background: #0f5256;
            color: #fff;
        }

        .butn {
            padding: 8px 18px;
            border-radius: 9px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: var(--transition);
            font-size: .86rem;
            white-space: nowrap;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .butn-ghost {
            border: 1.5px solid rgba(255, 255, 255, .35) !important;
            color: #fff;
            background: transparent;
        }

        .butn-ghost:hover {
            border-color: rgba(255, 255, 255, .7) !important;
            color: #fff;
            background: rgba(255, 255, 255, .08);
        }

        .butn-primary {
            background: var(--secondary);
            color: #fff;
            box-shadow: 0 2px 8px rgba(76, 175, 80, .3);
        }

        .nav-login {
            color: rgba(255, 255, 255, .88);
            font-size: .86rem;
            font-weight: 500;
            padding: 6px 10px;
            border-radius: 7px;
            transition: var(--transition);
            white-space: nowrap;
            text-decoration: none;
        }

        .nav-login:hover {
            color: #fff;
            background: rgba(255, 255, 255, .1);
        }

        .butn-primary:hover {
            background: var(--secondary-dark);
            transform: translateY(-1px);
        }

        .mob-butn {
            display: none;
            background: none;
            border: none;
            color: #ffffff;
            font-size: 1.3rem;
            cursor: pointer;
            padding: 6px;
            min-width: 44px;
            min-height: 44px;
        }

        .mob-search-butn {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 1.1rem;
            cursor: pointer;
            padding: 6px;
            min-width: 40px;
            min-height: 40px;
            border-radius: 8px;
            transition: var(--transition);
        }

        .mob-search-butn:hover,
        .mob-search-butn.active {
            background: rgba(255, 255, 255, .12);
        }

        /* Mobile search bar - slides down from header */
        .mob-search-bar {
            background: linear-gradient(135deg, #0f5256, #0a3d40);
            border-bottom: 1px solid rgba(255, 255, 255, .1);
            max-height: 0;
            overflow: hidden;
            transition: max-height .35s ease;
            display: block;
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
            overflow: hidden;
            height: 38px;
        }

        .msb-type {
            border: none;
            background: transparent;
            color: #fff;
            font-family: inherit;
            font-size: .78rem;
            font-weight: 600;
            cursor: pointer;
            outline: none;
            height: 100%;
            padding: 0 22px 0 10px;
            flex-shrink: 0;
            white-space: nowrap;
            border-right: 1px solid rgba(255, 255, 255, .2);
            -webkit-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='5'%3E%3Cpath d='M0 0l4 5 4-5z' fill='rgba(255,255,255,0.7)'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
            width: auto;
            min-width: 0;
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
            flex-shrink: 0;
        }

        .msb-input input {
            border: none;
            background: transparent;
            color: #fff;
            font-family: inherit;
            font-size: .85rem;
            outline: none;
            flex: 1;
            height: 100%;
        }

        .msb-input input::placeholder {
            color: rgba(255, 255, 255, .45);
        }

        /* Mobile nav */
        .mob-nav {
            display: none;
            background: linear-gradient(135deg, #0f5256, #0a3d40);
            border-top: 1px solid rgba(255, 255, 255, .08);
            padding: 10px 16px 14px;
            flex-direction: column;
            gap: 2px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .2);
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
            transition: var(--transition);
            min-height: 44px;
            display: flex;
            align-items: center;
        }

        .mob-nav a:active,
        .mob-nav a:hover {
            background: rgba(255, 255, 255, .1);
            color: #fff;
        }

        .mob-nav hr {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, .08);
            margin: 4px 0;
        }

        /* CATEGORIES BANNER= */
        .cat-banner {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: var(--header-h);
            z-index: 998;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
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
            transition: var(--transition);
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
            -ms-overflow-style: none;
        }

        .cat-scroll::-webkit-scrollbar {
            display: none;
        }

        /* Category item */
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
            transition: color .2s, border-color .2s, background .2s;
        }

        .cat-item .ci {
            font-size: .74rem;
            color: var(--secondary);
        }

        .cat-item .ca {
            font-size: .58rem;
            opacity: .4;
            transition: transform .2s;
        }

        .cdw:hover .cat-item {
            color: var(--primary);
            border-bottom-color: var(--primary);
            background: rgba(15, 82, 86, .04);
        }

        .cdw:hover .ca {
            transform: rotate(180deg);
            opacity: 1;
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
            animation: fadeDown .18s ease;
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
            align-items: flex-start;
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
            line-height: 1.7;
            margin-bottom: 14px;
            font-weight: 400;
        }

        .mega-butn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: none;
            color: var(--primary);
            text-decoration: none;
            font-size: .82rem;
            font-weight: 600;
            padding: 0;
            border-radius: 0;
            transition: color .15s;
        }

        .mega-butn:hover {
            color: var(--secondary);
            background: none;
        }

        .mega-butn i {
            font-size: .75rem;
        }

        .mega-grid {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 9px 16px;
            align-items: start;
            align-content: start;
        }

        .mega-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0;
            margin: 0;
            color: #444;
            font-size: .81rem;
            font-weight: 400;
            line-height: 1;
            transition: color .15s;
            white-space: nowrap;
            text-decoration: none;
            background: none;
            border-radius: 0;
        }

        .mega-link i {
            width: 14px;
            text-align: center;
            font-size: .72rem;
            color: #94a3b8;
            flex-shrink: 0;
        }

        .mega-link:hover {
            color: var(--primary);
            background: none;
        }

        .mega-link:hover i {
            color: var(--primary);
        }

        /* HERO= */
        .hero {
            min-height: 420px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            text-align: center;
            padding: 52px 0 44px;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            background: linear-gradient(160deg, rgba(11, 57, 61, 0.96) 0%, rgba(50, 46, 46, 0.82) 100%),
                url({{ asset('assets/wiserlance/assets/img/home-page/photo-1552664730-d307ca884978.jpeg') }}) center/cover;
            z-index: -1;
        }

        .hero .container {
            width: 100%;
            position: relative;
        }

        /* Hero slider card */
        .hero-card {
            max-width: 760px;
            margin: 0 auto 16px;
            background: rgba(255, 255, 255, .95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 24px 64px rgba(0, 0, 0, .3);
            position: relative;
            overflow: hidden;
            min-height: 120px;
        }

        .slide {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 18px 36px;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity .4s, transform .4s;
            pointer-events: none;
        }

        .slide.active {
            opacity: 1;
            transform: translateY(0);
            position: relative;
            pointer-events: all;
        }

        .slide h1 {
            font-size: 2.5rem !important;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: .5px;
            margin-bottom: 6px;
        }

        .slide p {
            font-size: 18px !important;
            color: #666;
            max-width: 520px;
        }

        /* Hero nav arrows */
        .ha {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .15);
            border: 1.5px solid rgba(255, 255, 255, .3);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: .95rem;
            transition: var(--transition);
            z-index: 4;
        }

        .ha:hover {
            background: var(--secondary);
            border-color: var(--secondary);
        }

        .ha.l {
            left: 20px;
        }

        .ha.r {
            right: 20px;
        }

        /* Dots */
        .hero-dots {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-bottom: 14px;
        }

        .hd {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .3);
            border: none;
            cursor: pointer;
            transition: all .22s;
        }

        .hd.active {
            background: var(--secondary);
            width: 26px;
            border-radius: 4px;
        }

        /* Tab buttons */
        .hero-tabs {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .htab {
            padding: 7px 18px;
            border-radius: 8px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: var(--transition);
            background: rgba(255, 255, 255, .12);
            color: rgba(255, 255, 255, .82);
            border: 1.5px solid rgba(255, 255, 255, .25);
            font-size: .86rem;
            backdrop-filter: blur(8px);
        }

        .htab.active,
        .htab:hover {
            background: var(--secondary);
            color: #fff;
            border-color: var(--secondary);
            box-shadow: 0 4px 14px rgba(76, 175, 80, .4);
        }

        /* ── Hero search */
        .hero-search {
            max-width: 720px;
            margin: 12px auto 0;
            display: flex;
            align-items: stretch;
            background: #0f5256;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 40px rgba(0, 0, 0, .30);
            height: 50px;
            isolation: isolate;
        }

        /* LEFT: dropdown */
        .hero-search .hs-drop-butn {
            background: #0f5256;
            color: #fff;
            border: none;
            outline: none;
            height: 100%;
            padding: 0 26px 0 16px;
            font-family: inherit;
            font-size: .88rem;
            font-weight: 700;
            cursor: pointer;
            flex-shrink: 0;
            white-space: nowrap;
            -webkit-appearance: none;
            appearance: none;
            width: auto;
            min-width: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='9' height='5'%3E%3Cpath d='M0 0l4.5 5 4.5-5z' fill='rgba(255,255,255,0.8)'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            transition: background .2s;
            box-shadow: none;
        }

        .hero-search .hs-drop-butn:hover {
            background: #0a3d40;
            outline: none;
            box-shadow: none;
        }

        .hero-search .hs-drop-butn:focus {
            outline: none;
            box-shadow: none;
            background: #0a3d40;
        }

        .hero-search .hs-drop-butn option {
            background: #0f5256 !important;
        }

        #hsType option:checked {
            background-color: #0a3d40;
            color: #fff;
        }

        /* DIVIDER */
        .hero-search .hs-divider {
            width: 0;
            height: 0;
            flex-shrink: 0;
        }

        /* MIDDLE: input */
        .hero-search .hs-main {
            display: flex;
            align-items: center;
            flex: 1;
            min-width: 0;
            height: 100%;
            background: #fff;
            padding: 0 16px;
            margin: 0;
            border: none;
            outline: none;
        }

        .hero-search input {
            border: none;
            background: transparent;
            color: #1e293b;
            font-family: inherit;
            font-size: .95rem;
            outline: none;
            flex: 1;
            min-width: 0;
            height: 100%;
        }

        .hero-search input::placeholder {
            color: #94a3b8;
        }

        /* RIGHT: search icon button */
        .hero-search .hs-row {
            display: flex;
            flex: 1;
            min-width: 0;
            background: #0f5256;
            align-items: stretch;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .hero-search .hs-search-ico {
            background: #0f5256;
            border: none;
            outline: none;
            height: 100%;
            padding: 0 18px;
            color: #fff;
            font-size: 1rem;
            cursor: pointer;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .2s;
            border-left: none;
        }

        .hero-search .hs-search-ico:hover {
            background: #0a3d40;
        }

        /* SECTIONS COMMON*/
        .sec {
            padding: 64px 0;
        }

        .sec-alt {
            background: var(--white);
        }

        .sec-title {
            text-align: center;
            font-size: 1.9rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 10px;
            letter-spacing: -.4px;
        }

        .sec-title::after {
            content: '';
            display: block;
            width: 48px;
            height: 4px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            margin: 12px auto 0;
            border-radius: 2px;
        }

        .sec-sub {
            text-align: center;
            color: var(--gray);
            font-size: .96rem;
            max-width: 600px;
            margin: 0 auto 40px;
            line-height: 1.75;
        }

        /* SERVICES CARD SLIDER*/
        .slide-wrap {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-top: 8px;
        }

        .sarrow {
            flex-shrink: 0;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: var(--white);
            border: 1.5px solid var(--border);
            color: var(--primary);
            font-size: .95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .sarrow:hover {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
            transform: scale(1.06);
        }

        .sarrow:disabled {
            opacity: .3;
            cursor: not-allowed;
            transform: none;
        }

        .svp {
            overflow: hidden;
            flex: 1;
        }

        .strack {
            display: flex;
            gap: 20px;
            transition: transform .42s cubic-bezier(.4, 0, .2, 1);
        }

        .scard {
            flex: 0 0 calc(25% - 15px);
            background: var(--white);
            border-radius: 16px;
            padding: 28px 18px 22px;
            text-align: center;
            box-shadow: var(--shadow);
            border: 1.5px solid var(--border);
            transition: var(--transition);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .scard::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
        }

        .scard:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(76, 175, 80, .3);
        }

        .scard-icon {
            width: 54px;
            height: 54px;
            background: linear-gradient(135deg, rgba(15, 82, 86, .1), rgba(105, 186, 107, .15));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            font-size: 1.5rem;
            color: var(--primary);
            transition: var(--transition);
        }

        .scard:hover .scard-icon {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
        }

        .scard h3 {
            font-size: .93rem;
            font-weight: 700;
            color: var(--dark);
        }

        .sdots {
            display: flex;
            justify-content: center;
            gap: 7px;
            margin-top: 24px;
        }

        .sdot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(15, 82, 86, .2);
            border: none;
            cursor: pointer;
            transition: all .2s;
        }

        .sdot.active {
            background: var(--primary);
            width: 24px;
            border-radius: 4px;
        }

        /* FOR BUYERS / SELLERS  (split layout)*/
        .split {
            padding: 64px 0;
        }

        .split-grid {
            display: grid;
            grid-template-columns: 1fr 1.6fr;
            gap: 52px;
            align-items: center;
        }

        .split-grid.rev {
            grid-template-columns: 1.6fr 1fr;
        }

        .split-img {
            width: 100%;
            height: 320px;
            object-fit: cover;
            border-radius: 18px;
            box-shadow: var(--shadow-lg);
        }

        .split-text h3 {
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .split-text p {
            color: var(--gray);
            font-size: .95rem;
            line-height: 1.8;
            margin-bottom: 6px;
        }

        .action-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-top: 28px;
        }

        .acard {
            background: var(--body);
            border-radius: 14px;
            padding: 20px 16px;
            text-align: center;
            box-shadow: var(--shadow-sm);
            border: 1.5px solid var(--border);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .acard::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
        }

        .acard:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow);
            background: var(--white);
        }

        .acard-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            margin: 0 auto 10px;
            box-shadow: 0 4px 12px rgba(15, 82, 86, .25);
        }

        .acard h4 {
            font-size: .9rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 14px;
        }

        /* HOW IT WORKS */
        .steps {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .step {
            background: var(--white);
            border-radius: 16px;
            padding: 28px 18px 22px;
            text-align: center;
            box-shadow: var(--shadow);
            border: 1.5px solid var(--border);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .step::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
        }

        .step:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
        }

        .step-n {
            width: 30px;
            height: 30px;
            background: var(--secondary);
            border-radius: 50%;
            color: #fff;
            font-weight: 800;
            font-size: .82rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
        }

        .step-ico {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin: 0 auto 16px;
            box-shadow: 0 4px 14px rgba(15, 82, 86, .25);
        }

        .step h3 {
            font-size: .97rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .step p {
            color: var(--gray);
            font-size: .85rem;
            line-height: 1.65;
        }

        /*  CTA= */
        .cta {
            padding: 72px 0;
            text-align: center;
            background: linear-gradient(135deg, #e6f4ea, #d0edd2);
        }

        .cta h2 {
            font-size: 2.1rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 14px;
        }

        .cta p {
            color: var(--gray);
            font-size: .97rem;
            max-width: 500px;
            margin: 0 auto 30px;
            line-height: 1.8;
        }

        .cta-row {
            display: flex;
            gap: 14px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /*  FOOTER – compact */
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
            transition: var(--transition);
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
            letter-spacing: .5px;
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
            transition: var(--transition);
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

        /* FADE IN */
        .fi {
            opacity: 0;
            transform: translateY(22px);
            transition: opacity .55s ease, transform .55s ease;
        }

        .fi.vis {
            opacity: 1;
            transform: translateY(0);
        }

        /* RESPONSIVE – Full coverage: 1400px → 320px */

        /* ── 1600px+: Very large screens ── */
        @media(min-width:1600px) {
            .container {
                max-width: 1520px;
                padding: 0 48px;
            }

            .mega-in {
                max-width: 1520px;
                padding: 0 48px;
            }
        }

        /* ── 1280px: Large laptop ── */
        @media(max-width:1280px) {
            .container {
                padding: 0 32px;
            }

            .mega-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* ── 1060px: Small laptop / large tablet landscape ── */
        @media(max-width:1060px) {
            .ft-grid {
                grid-template-columns: 1fr 1fr;
                gap: 24px;
            }

            .ft-brand {
                grid-column: 1/-1;
            }

            .steps {
                grid-template-columns: repeat(2, 1fr);
            }

            .scard {
                flex: 0 0 calc(33.33% - 14px);
            }

            .split-grid {
                grid-template-columns: 1fr 1.3fr;
                gap: 36px;
            }

            .split-grid.rev {
                grid-template-columns: 1.3fr 1fr;
            }

            .mega-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .sec-title {
                font-size: 1.65rem;
            }

            .cta h2 {
                font-size: 1.8rem;
            }
        }

        /* ── 900px: Tablet landscape ── */
        @media(max-width:900px) {

            .split-grid,
            .split-grid.rev {
                grid-template-columns: 1fr;
            }

            .split-img {
                height: 260px;
                order: -1;
            }

            .mega-in {
                flex-direction: column;
                gap: 20px;
            }

            .mega-left {
                flex: unset;
                padding-right: 0;
                border-right: none;
                border-bottom: 1px solid var(--border);
                padding-bottom: 16px;
            }

            .mega-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .steps {
                grid-template-columns: repeat(2, 1fr);
            }

            .action-cards {
                grid-template-columns: 1fr 1fr;
            }
        }

        /* ── 820px: Tablet portrait – hamburger menu ── */
        @media(max-width:820px) {
            :root {
                --header-h: 60px;
            }

            .nav-links,
            .lang {
                display: none;
            }

            .mob-butn {
                display: block;
            }

            .h-search-wrap {
                display: none !important;
            }

            .mob-search-butn {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .nav-right .butn-ghost {
                display: none;
            }

            .nav-right {
                gap: 8px;
            }

            .butn-primary {
                padding: 7px 14px;
                font-size: .82rem;
            }

            .logo img {
                height: 30px !important;
            }

            /* Categories banner scroll */
            .cat-item {
                padding: 0 12px;
                font-size: .8rem;
            }

            .ban-arrow {
                width: 34px;
            }

            /* Hero */
            .hero {
                padding: 28px 0 24px;
                min-height: auto;
            }

            .hero-card {
                margin-bottom: 12px;
            }

            .slide {
                padding: 16px 24px;
            }

            .slide h2 {
                font-size: 1.35rem;
            }

            .slide p {
                font-size: .83rem;
            }

            .ha {
                width: 32px;
                height: 32px;
                font-size: .8rem;
            }

            .ha.l {
                left: 10px;
            }

            .ha.r {
                right: 10px;
            }

            .hero-dots {
                margin-bottom: 10px;
            }

            .hero-tabs {
                margin-bottom: 12px;
                gap: 6px;
            }

            .htab {
                padding: 6px 14px;
                font-size: .8rem;
            }

            /* Hero search */
            .hero-search {
                max-width: 100%;
                height: 44px;
            }

            .hero-search .hs-main {
                padding: 0 14px;
            }

            .hero-search .hs-drop-butn {
                font-size: .82rem;
                padding: 0 14px 0 10px;
            }

            /* Sections */
            .sec {
                padding: 44px 0;
            }

            .sec-title {
                font-size: 1.5rem;
            }

            .sec-sub {
                font-size: .88rem;
                margin-bottom: 28px;
            }

            .split-img {
                height: 220px;
            }

            .split-text h3 {
                font-size: 1.3rem;
            }

            .scard {
                flex: 0 0 calc(33.33% - 14px);
            }

            /* Footer */
            .ft-grid {
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }

            .ft-brand {
                grid-column: 1/-1;
            }

            .cta {
                padding: 48px 0;
            }

            .cta h2 {
                font-size: 1.6rem;
            }
        }

        /* ── 600px: Large phone ── */
        @media(max-width:600px) {
            :root {
                --header-h: 56px;
                --banner-h: 42px;
            }

            .container {
                padding: 0 16px;
            }

            /* Header */
            .logo img {
                height: 26px !important;
            }

            .nav-right {
                gap: 6px;
            }

            .butn-primary {
                padding: 6px 12px;
                font-size: .8rem;
            }

            .nav-login {
                font-size: .8rem;
                padding: 5px 7px;
            }

            /* Banner */
            .cat-item {
                padding: 0 10px;
                font-size: .77rem;
            }

            .ban-arrow {
                width: 30px;
                font-size: .75rem;
            }

            /* Hero */
            .hero {
                padding: 24px 0 20px;
            }

            .hero-card {
                border-radius: 14px;
                margin-bottom: 10px;
                min-height: 80px;
            }

            .slide {
                padding: 14px 18px;
            }

            .slide h2 {
                font-size: 1.2rem;
                margin-bottom: 4px;
            }

            .slide p {
                font-size: .8rem;
                display: block !important;
                color: #555;
            }

            .ha {
                display: none;
            }

            .hero-dots {
                gap: 6px;
                margin-bottom: 8px;
            }

            .hd {
                width: 6px;
                height: 6px;
                min-height: unset !important;
                min-width: unset !important;
            }

            .hd.active {
                width: 20px;
            }

            .hero-tabs {
                gap: 5px;
                margin-bottom: 10px;
            }

            .htab {
                padding: 5px 12px;
                font-size: .77rem;
                border-radius: 7px;
            }

            /* Hero search - mobile: dropdown top, then input+icon in one row */
            .hero-search {
                flex-direction: column;
                border-radius: 12px;
                height: auto;
                max-width: 100%;
            }

            .hero-search .hs-drop-butn {
                width: 100%;
                text-align: center;
                padding: 10px 14px;
                background-image: none;
                font-size: .86rem;
                justify-content: center;
                border-radius: 12px 12px 0 0;
                border-bottom: 1px solid rgba(255, 255, 255, .15);
            }

            .hero-search .hs-row {
                width: 100%;
                border-top: 1px solid rgba(255, 255, 255, .15);
            }

            .hero-search .hs-main {
                padding: 10px 14px;
                flex: 1;
                min-width: 0;
                height: 44px;
            }

            .hero-search .hs-search-ico {
                padding: 0 16px;
                height: 44px;
                border-left: 1px solid #e2e8f0;
            }

            /* Services section */
            .sec {
                padding: 36px 0;
            }

            .sec-title {
                font-size: 1.35rem;
            }

            .sec-sub {
                font-size: .85rem;
                margin-bottom: 22px;
            }

            .scard {
                flex: 0 0 calc(50% - 10px);
            }

            .scard-icon {
                width: 44px;
                height: 44px;
                font-size: 1.2rem;
            }

            .scard h3 {
                font-size: .85rem;
            }

            .sarrow {
                width: 34px;
                height: 34px;
                font-size: .82rem;
            }

            /* Split sections */
            .split {
                padding: 36px 0;
            }

            .split-img {
                height: 200px;
                border-radius: 12px;
            }

            .split-text h3 {
                font-size: 1.15rem;
            }

            .split-text p {
                font-size: .88rem;
            }

            .action-cards {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .acard {
                padding: 16px 14px;
            }

            .acard-icon {
                width: 40px;
                height: 40px;
                font-size: .95rem;
            }

            /* Steps */
            .steps {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }

            .step {
                padding: 20px 12px 16px;
            }

            .step-ico {
                width: 42px;
                height: 42px;
                font-size: 1rem;
                margin-bottom: 10px;
            }

            .step h3 {
                font-size: .88rem;
            }

            .step p {
                font-size: .78rem;
            }

            /* CTA */
            .cta {
                padding: 40px 0;
            }

            .cta h2 {
                font-size: 1.4rem;
            }

            .cta p {
                font-size: .88rem;
            }

            .cta-row {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }

            .cta-row .butn {
                width: 100%;
                max-width: 280px;
                justify-content: center;
                padding: 12px 24px;
            }

            /* Footer */
            .ft-grid {
                grid-template-columns: 1fr 1fr;
                gap: 18px;
            }

            .ft-brand {
                grid-column: 1/-1;
            }

            .ft-brand p {
                font-size: .8rem;
            }

            .ft-col h4 {
                font-size: .72rem;
            }

            .ft-col a {
                font-size: .8rem;
            }

            .ft-bottom {
                flex-direction: column;
                text-align: center;
                gap: 8px;
            }

            .copy {
                font-size: .75rem;
            }
        }

        /* ── 480px: Medium phone ── */
        @media(max-width:480px) {
            .steps {
                grid-template-columns: 1fr;
            }

            .scard {
                flex: 0 0 calc(50% - 10px);
            }

            .mega-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .ft-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        /* ── 380px: Small phone ── */
        @media(max-width:380px) {
            :root {
                --header-h: 52px;
            }

            .scard {
                flex: 0 0 100%;
            }

            .ft-grid {
                grid-template-columns: 1fr;
            }

            .hero-search .hs-main input {
                font-size: .82rem;
            }

            .htab {
                padding: 5px 9px;
                font-size: .73rem;
            }

            .sec-title {
                font-size: 1.2rem;
            }

            .slide h2 {
                font-size: 1.1rem;
            }

            .split-text h3 {
                font-size: 1rem;
            }

            .cta h2 {
                font-size: 1.25rem;
            }

            .butn-primary {
                padding: 6px 10px;
                font-size: .78rem;
            }
        }

        /* ── 320px: Very small phone (iPhone SE) ── */
        @media(max-width:320px) {
            .container {
                padding: 0 12px;
            }

            .logo img {
                height: 22px !important;
            }

            .hero-tabs {
                gap: 4px;
            }

            .htab {
                padding: 4px 8px;
                font-size: .7rem;
            }

            .sec-title {
                font-size: 1.1rem;
            }

            .step {
                padding: 16px 10px 12px;
            }

            .ft-col a {
                font-size: .75rem;
            }
        }

        /* ── Landscape phone fix ── */
        @media(max-width:820px) and (orientation:landscape) and (max-height:500px) {
            .hero {
                padding: 16px 0 14px;
                min-height: auto;
            }

            .hero-card {
                min-height: 70px;
            }

            .slide {
                padding: 10px 20px;
            }

            .slide h2 {
                font-size: 1.1rem;
                margin-bottom: 3px;
            }

            .slide p {
                display: none;
            }

            .hero-dots,
            .hero-tabs {
                margin-bottom: 8px;
            }

            .htab {
                padding: 5px 12px;
                font-size: .75rem;
            }
        }

        /* ── Touch device improvements ── */
        @media(hover:none) and (pointer:coarse) {
            .scard:hover {
                transform: none;
            }

            .step:hover {
                transform: none;
            }

            .acard:hover {
                transform: none;
            }

            .sarrow,
            .ban-arrow {
                min-width: 44px;
                min-height: 44px;
            }

            .htab {
                min-height: 36px;
            }
        }
    </style>

</head>

<body>

    <!-- ═══════════════════════════════════════════════════════
     HEADER
═══════════════════════════════════════════════════════ -->
@if(request()->is('/'))
    <div id="google_translate_element" style="display:none !important;"></div>
    <div id="translateLoader" class="wl-lang-loader">
        <div class="wl-spinner"></div>
        <p>Translating page...</p>
    </div>

    <style>
        /* ── Language switch loader (shared design with seller/buyer portal headers) ── */
        .wl-lang-loader {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 2147483647;
            background: rgba(0, 0, 0, .35);
            -webkit-backdrop-filter: blur(6px);
            backdrop-filter: blur(6px);
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 16px;
        }

        .wl-lang-loader.show {
            display: flex;
        }

        .wl-lang-loader .wl-spinner {
            width: 52px;
            height: 52px;
            border: 4px solid rgba(255, 255, 255, .2);
            border-top-color: #fff;
            border-radius: 50%;
            animation: wl-lang-spin .8s linear infinite;
        }

        .wl-lang-loader p {
            color: #fff;
            font-size: 15px;
            margin: 0;
            letter-spacing: .4px;
        }

        @keyframes wl-lang-spin { to { transform: rotate(360deg); } }
        .goog-te-banner-frame, .VIpgJd-ZVi9od-ORHb, .skiptranslate { display: none !important; }
        body { top: 0 !important; position: static !important; }
    </style>
@endif

    @include('templates.basic.partials.new_header')



    <!-- HERO -->
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="container">
            <button class="ha l" id="hPrev"><i class="fas fa-chevron-left"></i></button>
            <button class="ha r" id="hNext"><i class="fas fa-chevron-right"></i></button>

            <!-- Slider card -->
            <div class="hero-card" id="heroCard">
                <div class="slide active" id="slide-professionals">
                    <h1>PROFESSIONALS</h1>
                    <p>Find skilled experts ready to bring your projects to life with unmatched expertise.</p>
                </div>
                <div class="slide" id="slide-jobs">
                    <h1>JOBS</h1>
                    <p>Discover rewarding job openings from top employers seeking your talent.</p>
                </div>
                <div class="slide" id="slide-services">
                    <h1>SERVICES</h1>
                    <p>Get high-quality solutions from freelancers offering specialised services.</p>
                </div>
            </div>

            <!-- Dots -->
            <div class="hero-dots">
                <button class="hd active" data-i="0"></button>
                <button class="hd" data-i="1"></button>
                <button class="hd" data-i="2"></button>
            </div>

            <!-- Tab buttons -->
            <div class="hero-tabs">
                <button class="htab active" data-i="0"
                    onclick="setActiveTab('professionals')">Professionals</button>
                <button class="htab" data-i="1" onclick="setActiveTab('jobs')">Jobs</button>
                <button class="htab" data-i="2" onclick="setActiveTab('services')">Services</button>
            </div>

            <!-- BIG hero search: dropdown LEFT + input MIDDLE + search icon RIGHT -->
            <form method="GET" class="hero-search" id="heroSearch">
                <select class="hs-drop-butn" id="hsType" name="type" onchange="syncType(this)">
                    <option value="professionals">Professional</option>
                    <option value="jobs">Job</option>
                    <option value="services">Service</option>
                </select>
                <div class="hs-row">
                    <div class="hs-main">
                        <input type="text" name="search" placeholder="Search for Professionals, Jobs or Services"
                            id="hsBig" value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="hs-search-ico">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- COMPREHENSIVE SERVICES SLIDER  -->
    <section class="sec">
        <div class="container">
            <h2 class="sec-title fi">Comprehensive Services, Expertly Delivered</h2>
            <p class="sec-sub fi">Wiserlance offers a versatile, end-to-end portfolio of services crafted to solve your
                business challenges and support your strategic vision.</p>
            <div class="slide-wrap">
                <button class="sarrow" id="sPrev"><i class="fas fa-chevron-left"></i></button>
                <div class="svp">
                    <div class="strack" id="sTrack">
                        @if (isset($service_categories) && count($service_categories) > 0)
                            @foreach ($service_categories as $serviceCat)
                                <a href="{{ route('home', ['type' => 'services', 'category' => $serviceCat->id]) }}"
                                    class="scard fi">
                                    <div class="scard-icon">
                                        @if ($serviceCat->image)
                                            <i class="{{ $serviceCat->image }}"></i>
                                        @else
                                            <i class="fas fa-cog"></i>
                                        @endif
                                    </div>
                                    <h3>{{ $serviceCat->name }}</h3>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
                <button class="sarrow" id="sNext"><i class="fas fa-chevron-right"></i></button>
            </div>
            <div class="sdots" id="sDots"></div>
        </div>
    </section>

    <!--  FOR BUYERS-->
    <section class="split sec-alt">
        <div class="container">
            <h2 class="sec-title fi">For Buyers</h2>
            <p class="sec-sub fi">Connect with the ideal professional on Wiserlance — seamlessly, and with certainty.
            </p>
            <div class="split-grid">
                <img src="{{ asset('assets/img/home/for-buyers.avif') }}" alt="For Buyers" class="split-img fi"
                    onerror="this.src='https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=700&auto=format&fit=crop&q=80'">
                <div>
                    <div class="split-text fi">
                        <h3>Expertise, On Demand</h3>
                        <p>Find the verified professional for your project. Choose a ready-to-go service or post a
                            custom job and receive competitive proposals from our global network of experts — all within
                            your budget.</p>
                        <p style="margin-top:6px;">Every professional is vetted and verified so you can hire with
                            confidence.</p>
                    </div>
                    <div class="action-cards">
                        <div class="acard fi">
                            <div class="acard-icon"><i class="fas fa-search"></i></div>
                            <h4>Search &amp; Buy a Service</h4>
                            <a href="{{ route('service') }}" class="butn butn-primary"
                                style="width:100%;justify-content:center;">Browse Services</a>
                        </div>
                        <div class="acard fi">
                            <div class="acard-icon"><i class="fas fa-user-check"></i></div>
                            <h4>Post a Job &amp; Hire</h4>
                            @auth
                                @if (Auth::user()->is_employer == 1 || Auth::user()->is_employer == '1')
                                    <a href="{{ route('user.job.create') }}" class="butn butn-primary"
                                        style="width:100%;justify-content:center;">Post a Job</a>
                                @else
                                    <a href="{{ url('/login') }}" class="butn butn-primary"
                                        style="width:100%;justify-content:center;">Post a Job</a>
                                @endif
                            @else
                                <a href="{{ url('/login') }}" class="butn butn-primary"
                                    style="width:100%;justify-content:center;">Post a Job</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--  HOW IT WORKS -->
    <section class="sec">
        <div class="container">
            <h2 class="sec-title fi">How Wiserlance Works</h2>
            <p class="sec-sub fi">Our simple four-step process makes it easy to find the perfect talent or the ideal
                project.</p>
            <div class="steps">
                <div class="step fi">
                    <div class="step-n">1</div>
                    <div class="step-ico"><i class="fas fa-edit"></i></div>
                    <h3>Post a Job or Service</h3>
                    <p>Create a detailed listing outlining your requirements and budget. Quick, easy and intuitive.</p>
                </div>
                <div class="step fi">
                    <div class="step-n">2</div>
                    <div class="step-ico"><i class="fas fa-search"></i></div>
                    <h3>Find the Perfect Match</h3>
                    <p>Review profiles and offerings to find your ideal match. Our algorithm surfaces the best talent.
                    </p>
                </div>
                <div class="step fi">
                    <div class="step-n">3</div>
                    <div class="step-ico"><i class="fas fa-sliders-h"></i></div>
                    <h3>Get It Done, Your Way</h3>
                    <p>Set the pace, decide on payments, and collaborate in WorkRooms where progress happens.</p>
                </div>
                <div class="step fi">
                    <div class="step-n">4</div>
                    <div class="step-ico"><i class="fas fa-handshake"></i></div>
                    <h3>Pay Securely</h3>
                    <p>Release payment when milestones are completed. Safe and transparent for everyone involved.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOR SELLERS -->
    <section class="split sec-alt">
        <div class="container">
            <h2 class="sec-title fi">For Sellers</h2>
            <p class="sec-sub fi">Command your career as a freelancer — work on your terms, from anywhere.</p>
            <div class="split-grid rev">
                <div>
                    <div class="split-text fi">
                        <h3>Discover Outstanding Opportunities</h3>
                        <p>Enjoy the independence to choose your projects and the flexibility to work from anywhere.
                            Reach a global clientele and work on your terms.</p>
                        <p style="margin-top:6px;">Whether you prefer fixed-price or hourly work, Wiserlance provides
                            the tools to make it happen.</p>
                    </div>
                    <div class="action-cards">
                        <div class="acard fi">
                            <div class="acard-icon"><i class="fas fa-briefcase"></i></div>
                            <h4>Find Projects</h4>
                            <a href="{{ route('job') }}" class="butn butn-primary"
                                style="width:100%;justify-content:center;">Browse Jobs</a>
                        </div>
                        <div class="acard fi">
                            <div class="acard-icon"><i class="fas fa-tools"></i></div>
                            <h4>Offer Your Services</h4>
                            @auth
                                @if (Auth::user()->is_freelancer == 1 || Auth::user()->is_freelancer == '1')
                                    <a href="{{ route('user.service.create') }}" class="butn butn-primary"
                                        style="width:100%;justify-content:center;">Create a Service</a>
                                @else
                                    <a href="{{ url('/login') }}" class="butn butn-primary"
                                        style="width:100%;justify-content:center;">Create a Service</a>
                                @endif
                            @else
                                <a href="{{ url('/login') }}" class="butn butn-primary"
                                    style="width:100%;justify-content:center;">Create a Service</a>
                            @endauth
                        </div>
                    </div>
                </div>
                <img src="{{ asset('assets/img/home/for-sellers.avif') }}" alt="For Sellers" class="split-img fi"
                    onerror="this.src='https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=700&auto=format&fit=crop&q=80'">
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta">
        <div class="container">
            <h2 class="fi">Ready to get started?</h2>
            <p class="fi">Join thousands of employers and freelancers already thriving on Wiserlance.</p>
            <div class="cta-row fi">
                <a href="{{ route('service') }}" class="butn butn-primary"
                    style="padding:13px 32px;font-size:.97rem;border-radius:10px;">Find Services</a>
                @auth
                    @if (Auth::user()->is_employer == 1 || Auth::user()->is_employer == '1')
                        <a href="{{ route('user.job.create') }}" class="butn butn-ghost"
                            style="padding:13px 32px;font-size:.97rem;border-radius:10px;border:2px solid var(--primary)!important;color:var(--primary);">Post
                            a Job</a>
                    @endif
                @else
                    <a href="{{ url('/login') }}" class="butn butn-ghost"
                        style="padding:13px 32px;font-size:.97rem;border-radius:10px;border:2px solid var(--primary)!important;color:var(--primary);">Post
                        a Job</a>
                @endauth
            </div>
        </div>
    </section>


    @include('templates.basic.partials.new_footer')


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /* ── Mega dropdown positioning ── */
            function posMega() {
                const b = document.getElementById('catBanner');
                if (!b) return;
                const bot = b.getBoundingClientRect().bottom;
                document.querySelectorAll('.mega').forEach(m => m.style.top = bot + 'px');
            }
            posMega();
            window.addEventListener('scroll', posMega, {
                passive: true
            });
            window.addEventListener('resize', posMega);


            /* ── Mobile menu fix ── */
            const mobbutn = document.getElementById('mobbutn');
            const mobNav = document.getElementById('mobNav');
            const mobSearchbutn = document.getElementById('mobSearchbutn');
            const mobSearchBar = document.getElementById('mobSearchBar');

            // Function to close all mobile menus
            function closeAllMobMenus() {
                if (mobNav) {
                    mobNav.classList.remove('open');
                    // Remove inline style to let CSS handle display
                    setTimeout(() => {
                        if (!mobNav.classList.contains('open')) {
                            mobNav.style.display = '';
                        }
                    }, 300);
                }
                if (mobbutn) {
                    mobbutn.innerHTML = '<i class="fas fa-bars"></i>';
                }
                if (mobSearchBar) {
                    mobSearchBar.classList.remove('open');
                }
                if (mobSearchbutn) {
                    mobSearchbutn.classList.remove('active');
                }
            }

            // Toggle mobile menu when burger icon is clicked
            if (mobbutn && mobNav) {
                mobbutn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const isOpening = !mobNav.classList.contains('open');

                    // Close search bar if it's open
                    if (mobSearchBar && mobSearchBar.classList.contains('open')) {
                        mobSearchBar.classList.remove('open');
                        if (mobSearchbutn) mobSearchbutn.classList.remove('active');
                    }

                    if (isOpening) {
                        // Open the mobile nav
                        mobNav.classList.add('open');
                        mobbutn.innerHTML = '<i class="fas fa-times"></i>';

                        // Ensure the mobile nav is visible and properly positioned
                        mobNav.style.display = 'flex';
                    } else {
                        // Close the mobile nav
                        mobNav.classList.remove('open');
                        mobbutn.innerHTML = '<i class="fas fa-bars"></i>';

                        // Let CSS handle the display after transition
                        setTimeout(() => {
                            if (!mobNav.classList.contains('open')) {
                                mobNav.style.display = '';
                            }
                        }, 300);
                    }
                });
            }

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(e) {
                if (mobNav && mobNav.classList.contains('open')) {
                    // Check if click is outside the mobile nav and burger button
                    if (!mobNav.contains(e.target) && !mobbutn.contains(e.target)) {
                        mobNav.classList.remove('open');
                        mobbutn.innerHTML = '<i class="fas fa-bars"></i>';

                        setTimeout(() => {
                            if (!mobNav.classList.contains('open')) {
                                mobNav.style.display = '';
                            }
                        }, 300);
                    }
                }
            });

            // Handle window resize - hide mobile menu on desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth > 820) {
                    if (mobNav) {
                        mobNav.classList.remove('open');
                        mobNav.style.display = '';
                    }
                    if (mobbutn) {
                        mobbutn.innerHTML = '<i class="fas fa-bars"></i>';
                    }
                    if (mobSearchBar) {
                        mobSearchBar.classList.remove('open');
                    }
                    if (mobSearchbutn) {
                        mobSearchbutn.classList.remove('active');
                    }
                }
            });

            /* ── Mobile search icon toggle ── */
            if (mobSearchbutn && mobSearchBar) {
                mobSearchbutn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const wasOpen = mobSearchBar.classList.contains('open');

                    // Close mobile nav if open
                    if (mobNav && mobNav.classList.contains('open')) {
                        mobNav.classList.remove('open');
                        if (mobbutn) {
                            mobbutn.innerHTML = '<i class="fas fa-bars"></i>';
                        }
                        setTimeout(() => {
                            if (!mobNav.classList.contains('open')) {
                                mobNav.style.display = '';
                            }
                        }, 300);
                    }

                    if (!wasOpen) {
                        mobSearchBar.classList.add('open');
                        mobSearchbutn.classList.add('active');

                        // Focus on search input after animation
                        setTimeout(() => {
                            const inp = document.getElementById('msbInput');
                            if (inp) inp.focus();
                        }, 350);
                    } else {
                        mobSearchBar.classList.remove('open');
                        mobSearchbutn.classList.remove('active');
                    }
                });
            }

            mobbutn.addEventListener('click', () => {
                const opening = !mobNav.classList.contains('open');
                closeAllMobMenus();
                if (opening) {
                    mobNav.classList.add('open');
                    mobbutn.innerHTML = '<i class="fas fa-times"></i>';
                }
            });

            /* ── Mobile search icon toggle ── */
            if (mobSearchbutn && mobSearchBar) {
                mobSearchbutn.addEventListener('click', () => {
                    const wasOpen = mobSearchBar.classList.contains('open');
                    closeAllMobMenus();
                    if (!wasOpen) {
                        mobSearchBar.classList.add('open');
                        mobSearchbutn.classList.add('active');
                        setTimeout(() => {
                            const inp = document.getElementById('msbInput');
                            if (inp) inp.focus();
                        }, 350);
                    }
                });

                // Sync mobile search bar with all other search inputs
                const msbType = document.getElementById('msbType');
                const msbInput = document.getElementById('msbInput');
                if (msbType) msbType.addEventListener('change', function() {
                    ['hsType', 'hType'].forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.selectedIndex = this.selectedIndex;
                    });
                });
                if (msbInput) msbInput.addEventListener('input', function() {
                    ['hsBig', 'hInput'].forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.value = this.value;
                    });
                });
                if (msbInput) msbInput.addEventListener('keydown', e => {
                    if (e.key === 'Enter') {
                        closeAllMobMenus();
                    }
                });

                // Mobile search icon always available on mobile
            }

            /* CATEGORY MEGA MENU - TOUCH/CLICK SUPPORT */

            /* ── Header search: scroll-triggered (shows when hero scrolls out) ── */
            const hSearch = document.getElementById('hSearch');
            const heroSearch = document.getElementById('heroSearch');

            window.syncType = function(sel) {
                const hType = document.getElementById('hType');
                if (hType) hType.selectedIndex = sel.selectedIndex;
            };

            // Sync inputs both ways
            const hsType = document.getElementById('hsType');
            const hType = document.getElementById('hType');
            const hsBig = document.getElementById('hsBig');
            const hInput = document.getElementById('hInput');

            if (hsType) hsType.addEventListener('change', function() {
                if (hType) hType.selectedIndex = this.selectedIndex;
            });
            if (hType) hType.addEventListener('change', function() {
                if (hsType) hsType.selectedIndex = this.selectedIndex;
            });
            if (hsBig) hsBig.addEventListener('input', function() {
                if (hInput) hInput.value = this.value;
            });

            // IntersectionObserver
            if (heroSearch && hSearch) {
                const scrollObs = new IntersectionObserver((entries) => {
                    entries.forEach(e => {
                        if (!e.isIntersecting) {
                            if (hType && hsType) hType.selectedIndex = hsType.selectedIndex;
                            if (hInput && hsBig) hInput.value = hsBig.value;
                        } else {}
                    });
                }, {
                    threshold: 0.1
                });
                scrollObs.observe(heroSearch);
            }
            if (hInput) hInput.addEventListener('input', function() {
                if (hsBig) hsBig.value = this.value;
            });

            /* ── Banner scroll arrows ── */
            const catScroll = document.getElementById('catScroll');
            document.getElementById('banL').addEventListener('click', () => catScroll.scrollBy({
                left: -240,
                behavior: 'smooth'
            }));
            document.getElementById('banR').addEventListener('click', () => catScroll.scrollBy({
                left: 240,
                behavior: 'smooth'
            }));

            /*  HERO SLIDER */
            const slides = document.querySelectorAll('.slide');
            const hdots = document.querySelectorAll('.hd');
            const htabs = document.querySelectorAll('.htab');
            let cur = 0,
                hTimer;

            function goSlide(i) {
                slides[cur].classList.remove('active');
                hdots[cur].classList.remove('active');
                htabs[cur].classList.remove('active');
                cur = (i + slides.length) % slides.length;
                slides[cur].classList.add('active');
                hdots[cur].classList.add('active');
                htabs[cur].classList.add('active');
            }

            function startH() {
                hTimer = setInterval(() => goSlide(cur + 1), 4200);
            }

            function stopH() {
                clearInterval(hTimer);
            }

            document.getElementById('hPrev').addEventListener('click', () => {
                stopH();
                goSlide(cur - 1);
                startH();
            });
            document.getElementById('hNext').addEventListener('click', () => {
                stopH();
                goSlide(cur + 1);
                startH();
            });
            hdots.forEach(d => d.addEventListener('click', () => {
                stopH();
                goSlide(+d.dataset.i);
                startH();
            }));
            htabs.forEach(b => b.addEventListener('click', () => {
                stopH();
                goSlide(+b.dataset.i);
                startH();
            }));

            document.getElementById('heroCard').addEventListener('mouseenter', stopH);
            document.getElementById('heroCard').addEventListener('mouseleave', startH);
            startH();

            /* ── Hero search ── */
            window.doSearch = function() {
                const v = (document.getElementById('hsBig') || {}).value?.trim() ||
                    (document.getElementById('hInput') || {}).value?.trim();
                if (v) console.log('Searching:', v);
                else {
                    const el = document.getElementById('hsBig');
                    if (el) el.focus();
                }
            };
            ['hsBig', 'hInput'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.addEventListener('keydown', e => {
                    if (e.key === 'Enter') doSearch();
                });
            });

            /* SERVICES CARD SLIDER */
            const sTrack = document.getElementById('sTrack');
            const sDots = document.getElementById('sDots');
            const sCards = sTrack.querySelectorAll('.scard');
            let sIdx = 0,
                sTimer;

            function getSpv() {
                if (window.innerWidth >= 1024) return 4;
                if (window.innerWidth >= 768) return 3;
                if (window.innerWidth >= 480) return 2;
                return 1;
            }

            function buildSDots() {
                sDots.innerHTML = '';
                const pages = Math.ceil(sCards.length / getSpv());
                for (let i = 0; i < pages; i++) {
                    const d = document.createElement('button');
                    d.className = 'sdot' + (i === 0 ? ' active' : '');
                    d.addEventListener('click', () => {
                        stopS();
                        setSIdx(i * getSpv());
                        startS();
                    });
                    sDots.appendChild(d);
                }
            }

            function updateSDots() {
                const pg = Math.floor(sIdx / getSpv());
                document.querySelectorAll('.sdot').forEach((d, i) => d.classList.toggle('active', i === pg));
            }

            function setSIdx(i, anim = true) {
                const spv = getSpv();
                sIdx = Math.max(0, Math.min(i, sCards.length - spv));
                const vp = sTrack.parentElement;
                const gap = 20;
                const cw = (vp.offsetWidth - (spv - 1) * gap) / spv;
                sCards.forEach(c => c.style.flex = `0 0 ${cw}px`);
                sTrack.style.transition = anim ? 'transform .42s cubic-bezier(.4,0,.2,1)' : 'none';
                sTrack.style.transform = `translateX(${-sIdx*(cw+gap)}px)`;
                updateSDots();
                document.getElementById('sPrev').disabled = sIdx === 0;
                document.getElementById('sNext').disabled = sIdx >= sCards.length - spv;
            }

            function startS() {
                sTimer = setInterval(() => setSIdx(sIdx + 1 >= sCards.length - getSpv() + 1 ? 0 : sIdx + 1), 3200);
            }

            function stopS() {
                clearInterval(sTimer);
            }

            document.getElementById('sPrev').addEventListener('click', () => {
                stopS();
                setSIdx(sIdx - 1);
                startS();
            });
            document.getElementById('sNext').addEventListener('click', () => {
                stopS();
                setSIdx(sIdx + 1);
                startS();
            });

            const sSec = document.querySelector('.sec');
            sSec.addEventListener('mouseenter', stopS);
            sSec.addEventListener('mouseleave', startS);

            buildSDots();
            setSIdx(0, false);
            startS();

            let resTO;
            window.addEventListener('resize', () => {
                clearTimeout(resTO);
                resTO = setTimeout(() => {
                    buildSDots();
                    setSIdx(sIdx, false);
                    posMega();
                }, 120);
            });

            /* FADE-IN on scroll*/
            const fiObs = new IntersectionObserver((entries) => {
                entries.forEach((e, i) => {
                    if (e.isIntersecting) {
                        e.target.style.transitionDelay = (i % 4) * .08 + 's';
                        e.target.classList.add('vis');
                        fiObs.unobserve(e.target);
                    }
                });
            }, {
                threshold: .1
            });
            document.querySelectorAll('.fi').forEach(el => fiObs.observe(el));

        });

        document.getElementById('hInput').addEventListener('keydown', function(e) {
            if (e.key === "Enter") { // Enter key pressed
                e.preventDefault(); // Prevent default Enter behavior (like refreshing page)
                document.getElementById('heroSearch').submit(); // Submit the form
            }
        });

        document.addEventListener('DOMContentLoaded', function() {

            /* ── Mega dropdown positioning ── */
            function posMega() {
                const b = document.getElementById('catBanner');
                if (!b) return;
                const bot = b.getBoundingClientRect().bottom;
                document.querySelectorAll('.mega').forEach(m => m.style.top = bot + 'px');
            }
            posMega();
            window.addEventListener('scroll', posMega, {
                passive: true
            });
            window.addEventListener('resize', posMega);

            /* ── Mobile menu fix ── */
            const mobbutn = document.getElementById('mobbutn');
            const mobNav = document.getElementById('mobNav');
            const mobSearchbutn = document.getElementById('mobSearchbutn');
            const mobSearchBar = document.getElementById('mobSearchBar');

            // Function to close all mobile menus
            function closeAllMobMenus() {
                if (mobNav) {
                    mobNav.classList.remove('open');
                }
                if (mobbutn) {
                    mobbutn.innerHTML = '<i class="fas fa-bars"></i>';
                }
                if (mobSearchBar) {
                    mobSearchBar.classList.remove('open');
                }
                if (mobSearchbutn) {
                    mobSearchbutn.classList.remove('active');
                }
            }

            // Toggle mobile menu when burger icon is clicked
            if (mobbutn && mobNav) {
                mobbutn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const isOpening = !mobNav.classList.contains('open');

                    // Close all other menus first
                    closeAllMobMenus();

                    if (isOpening) {
                        // Open the mobile nav
                        mobNav.classList.add('open');
                        mobbutn.innerHTML = '<i class="fas fa-times"></i>';

                        // Ensure the mobile nav is visible and properly positioned
                        mobNav.style.display = 'flex';
                    }
                });
            }

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(e) {
                if (mobNav && mobNav.classList.contains('open')) {
                    // Check if click is outside the mobile nav and burger button
                    if (!mobNav.contains(e.target) && !mobbutn.contains(e.target)) {
                        closeAllMobMenus();
                        if (mobNav) {
                            setTimeout(() => {
                                if (!mobNav.classList.contains('open')) {
                                    mobNav.style.display = '';
                                }
                            }, 300);
                        }
                    }
                }
            });

            // Handle window resize - hide mobile menu on desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth > 820) {
                    if (mobNav) {
                        mobNav.classList.remove('open');
                        mobNav.style.display = '';
                    }
                    if (mobbutn) {
                        mobbutn.innerHTML = '<i class="fas fa-bars"></i>';
                    }
                    closeAllMobMenus();
                }
            });

            /* ── Mobile search icon toggle ── */
            if (mobSearchbutn && mobSearchBar) {
                mobSearchbutn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const wasOpen = mobSearchBar.classList.contains('open');

                    // Close mobile nav if open
                    if (mobNav) {
                        mobNav.classList.remove('open');
                        if (mobbutn) {
                            mobbutn.innerHTML = '<i class="fas fa-bars"></i>';
                        }
                    }

                    if (!wasOpen) {
                        mobSearchBar.classList.add('open');
                        mobSearchbutn.classList.add('active');

                        // Focus on search input after animation
                        setTimeout(() => {
                            const inp = document.getElementById('msbInput');
                            if (inp) inp.focus();
                        }, 350);
                    } else {
                        mobSearchBar.classList.remove('open');
                        mobSearchbutn.classList.remove('active');
                    }
                });

                // Sync mobile search bar with all other search inputs
                const msbType = document.getElementById('msbType');
                const msbInput = document.getElementById('msbInput');

                if (msbType) {
                    msbType.addEventListener('change', function() {
                        ['hsType', 'hType'].forEach(id => {
                            const el = document.getElementById(id);
                            if (el) el.selectedIndex = this.selectedIndex;
                        });
                    });
                }

                if (msbInput) {
                    msbInput.addEventListener('input', function() {
                        ['hsBig', 'hInput'].forEach(id => {
                            const el = document.getElementById(id);
                            if (el) el.value = this.value;
                        });
                    });

                    msbInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            // Submit the search
                            const searchForm = document.getElementById('heroSearch');
                            if (searchForm) searchForm.submit();
                        }
                    });
                }
            }

            /* ── CATEGORY MEGA MENU - TOUCH/CLICK SUPPORT ── */
            // Add touch support for mega menus on mobile
            const catItems = document.querySelectorAll('.cdw');
            catItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    if (window.innerWidth <= 820) {
                        e.preventDefault();
                        e.stopPropagation();

                        // Close other open mega menus
                        catItems.forEach(otherItem => {
                            if (otherItem !== item) {
                                otherItem.classList.remove('active');
                            }
                        });

                        // Toggle current mega menu
                        this.classList.toggle('active');
                    }
                });
            });

            // Close mega menus when clicking outside
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 820) {
                    if (!e.target.closest('.cdw')) {
                        catItems.forEach(item => item.classList.remove('active'));
                    }
                }
            });

            /* ── Header search: scroll-triggered (shows when hero scrolls out) ── */
            const hSearch = document.getElementById('hSearch');
            const heroSearch = document.getElementById('heroSearch');

            window.syncType = function(sel) {
                const hType = document.getElementById('hType');
                if (hType) hType.selectedIndex = sel.selectedIndex;
            };

            // Sync inputs both ways
            const hsType = document.getElementById('hsType');
            const hType = document.getElementById('hType');
            const hsBig = document.getElementById('hsBig');
            const hInput = document.getElementById('hInput');

            if (hsType) {
                hsType.addEventListener('change', function() {
                    if (hType) hType.selectedIndex = this.selectedIndex;
                });
            }

            if (hType) {
                hType.addEventListener('change', function() {
                    if (hsType) hsType.selectedIndex = this.selectedIndex;
                });
            }

            if (hsBig) {
                hsBig.addEventListener('input', function() {
                    if (hInput) hInput.value = this.value;
                });
            }

            // IntersectionObserver for header search visibility
            if (heroSearch && hSearch) {
                const scrollObs = new IntersectionObserver((entries) => {
                    entries.forEach(e => {
                        if (!e.isIntersecting) {
                            if (hType && hsType) hType.selectedIndex = hsType.selectedIndex;
                            if (hInput && hsBig) hInput.value = hsBig.value;
                        } else {}
                    });
                }, {
                    threshold: 0.1
                });
                scrollObs.observe(heroSearch);
            }

            if (hInput) {
                hInput.addEventListener('input', function() {
                    if (hsBig) hsBig.value = this.value;
                });
            }

            /* ── Banner scroll arrows ── */
            const catScroll = document.getElementById('catScroll');
            const banL = document.getElementById('banL');
            const banR = document.getElementById('banR');

            if (banL && catScroll) {
                banL.addEventListener('click', () => catScroll.scrollBy({
                    left: -240,
                    behavior: 'smooth'
                }));
            }

            if (banR && catScroll) {
                banR.addEventListener('click', () => catScroll.scrollBy({
                    left: 240,
                    behavior: 'smooth'
                }));
            }

            /* ── HERO SLIDER ── */
            const slides = document.querySelectorAll('.slide');
            const hdots = document.querySelectorAll('.hd');
            const htabs = document.querySelectorAll('.htab');
            let cur = 0,
                hTimer;

            function goSlide(i) {
                if (!slides.length) return;

                slides[cur].classList.remove('active');
                hdots[cur].classList.remove('active');
                htabs[cur].classList.remove('active');
                cur = (i + slides.length) % slides.length;
                slides[cur].classList.add('active');
                hdots[cur].classList.add('active');
                htabs[cur].classList.add('active');
            }

            function startH() {
                if (hTimer) clearInterval(hTimer);
                hTimer = setInterval(() => goSlide(cur + 1), 4200);
            }

            function stopH() {
                clearInterval(hTimer);
            }

            const hPrev = document.getElementById('hPrev');
            const hNext = document.getElementById('hNext');
            const heroCard = document.getElementById('heroCard');

            if (hPrev) {
                hPrev.addEventListener('click', () => {
                    stopH();
                    goSlide(cur - 1);
                    startH();
                });
            }

            if (hNext) {
                hNext.addEventListener('click', () => {
                    stopH();
                    goSlide(cur + 1);
                    startH();
                });
            }

            hdots.forEach(d => {
                d.addEventListener('click', () => {
                    stopH();
                    goSlide(+d.dataset.i);
                    startH();
                });
            });

            htabs.forEach(b => {
                b.addEventListener('click', () => {
                    stopH();
                    goSlide(+b.dataset.i);
                    startH();
                });
            });

            if (heroCard) {
                heroCard.addEventListener('mouseenter', stopH);
                heroCard.addEventListener('mouseleave', startH);
            }

            startH();

            /* ── Hero search enter key ── */
            window.doSearch = function() {
                const v = (document.getElementById('hsBig') || {}).value?.trim() ||
                    (document.getElementById('hInput') || {}).value?.trim();
                if (v) {
                    document.getElementById('heroSearch').submit();
                } else {
                    const el = document.getElementById('hsBig');
                    if (el) el.focus();
                }
            };

            ['hsBig', 'hInput'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('keydown', e => {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            doSearch();
                        }
                    });
                }
            });

            if (document.getElementById('hInput')) {
                document.getElementById('hInput').addEventListener('keydown', function(e) {
                    if (e.key === "Enter") {
                        e.preventDefault();
                        document.getElementById('heroSearch').submit();
                    }
                });
            }

            /* ── SERVICES CARD SLIDER ── */
            const sTrack = document.getElementById('sTrack');
            const sDots = document.getElementById('sDots');

            if (sTrack) {
                const sCards = sTrack.querySelectorAll('.scard');
                let sIdx = 0,
                    sTimer;

                function getSpv() {
                    if (window.innerWidth >= 1024) return 4;
                    if (window.innerWidth >= 768) return 3;
                    if (window.innerWidth >= 480) return 2;
                    return 1;
                }

                function buildSDots() {
                    if (!sDots) return;

                    sDots.innerHTML = '';
                    const pages = Math.ceil(sCards.length / getSpv());
                    for (let i = 0; i < pages; i++) {
                        const d = document.createElement('button');
                        d.className = 'sdot' + (i === 0 ? ' active' : '');
                        d.addEventListener('click', () => {
                            stopS();
                            setSIdx(i * getSpv());
                            startS();
                        });
                        sDots.appendChild(d);
                    }
                }

                function updateSDots() {
                    const pg = Math.floor(sIdx / getSpv());
                    document.querySelectorAll('.sdot').forEach((d, i) => d.classList.toggle('active', i === pg));
                }

                function setSIdx(i, anim = true) {
                    const spv = getSpv();
                    sIdx = Math.max(0, Math.min(i, sCards.length - spv));
                    const vp = sTrack.parentElement;
                    const gap = 20;
                    const cw = (vp.offsetWidth - (spv - 1) * gap) / spv;
                    sCards.forEach(c => c.style.flex = `0 0 ${cw}px`);
                    sTrack.style.transition = anim ? 'transform .42s cubic-bezier(.4,0,.2,1)' : 'none';
                    sTrack.style.transform = `translateX(${-sIdx * (cw + gap)}px)`;
                    updateSDots();

                    const sPrev = document.getElementById('sPrev');
                    const sNext = document.getElementById('sNext');

                    if (sPrev) sPrev.disabled = sIdx === 0;
                    if (sNext) sNext.disabled = sIdx >= sCards.length - spv;
                }

                function startS() {
                    if (sTimer) clearInterval(sTimer);
                    sTimer = setInterval(() => setSIdx(sIdx + 1 >= sCards.length - getSpv() + 1 ? 0 : sIdx + 1),
                        3200);
                }

                function stopS() {
                    clearInterval(sTimer);
                }

                const sPrev = document.getElementById('sPrev');
                const sNext = document.getElementById('sNext');

                if (sPrev) {
                    sPrev.addEventListener('click', () => {
                        stopS();
                        setSIdx(sIdx - 1);
                        startS();
                    });
                }

                if (sNext) {
                    sNext.addEventListener('click', () => {
                        stopS();
                        setSIdx(sIdx + 1);
                        startS();
                    });
                }

                const sSec = document.querySelector('.sec');
                if (sSec) {
                    sSec.addEventListener('mouseenter', stopS);
                    sSec.addEventListener('mouseleave', startS);
                }

                buildSDots();
                setSIdx(0, false);
                startS();

                let resTO;
                window.addEventListener('resize', () => {
                    clearTimeout(resTO);
                    resTO = setTimeout(() => {
                        buildSDots();
                        setSIdx(sIdx, false);
                        posMega();
                    }, 120);
                });
            }

            /* ── FADE-IN on scroll ── */
            const fiObs = new IntersectionObserver((entries) => {
                entries.forEach((e, i) => {
                    if (e.isIntersecting) {
                        e.target.style.transitionDelay = (i % 4) * 0.08 + 's';
                        e.target.classList.add('vis');
                        fiObs.unobserve(e.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            document.querySelectorAll('.fi').forEach(el => fiObs.observe(el));

        });
    </script>

@if(request()->is('/'))

<script>
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            includedLanguages: 'ar,ur,en,fr,de',
            autoDisplay: false
        }, 'google_translate_element');
    }

    function showLoader() {
        document.getElementById('translateLoader').classList.add('show');
    }

    function hideLoader() {
        document.getElementById('translateLoader').classList.remove('show');
    }

    // Google translates the text INSIDE our own language <option>s too (a known
    // Google Translate quirk that "notranslate" doesn't reliably stop for
    // select/option elements), which is why switching back to EN can render as
    // something else (e.g. "IN"). We keep the dropdown's own labels immune to
    // that by always re-asserting the canonical EN/AR/FR/DE text.
    var LANG_LABELS = { en: 'EN', ar: 'AR', fr: 'FR', de: 'DE' };

    function enforceLanguageLabels(selectedLang) {
        var langSelect = document.getElementById('languageSelect');
        if (!langSelect) return;

        Array.prototype.forEach.call(langSelect.options, function (opt) {
            var canonical = LANG_LABELS[opt.value];
            if (canonical && opt.textContent !== canonical) {
                opt.textContent = canonical;
            }
        });

        if (selectedLang && langSelect.value !== selectedLang) {
            langSelect.value = selectedLang;
        }
    }

    (function watchLanguageSelect() {
        var langSelect = document.getElementById('languageSelect');
        if (!langSelect) return;

        var currentLang = langSelect.value || 'en';

        var labelGuard = new MutationObserver(function () {
            enforceLanguageLabels(currentLang);
        });

        labelGuard.observe(langSelect, {
            childList: true,
            subtree: true,
            characterData: true
        });

        window.__setCurrentLang = function (lang) {
            currentLang = lang;
            enforceLanguageLabels(lang);
        };
    })();

    function translatePage(lang) {
        showLoader();

        var select = document.querySelector('.goog-te-combo');
        if (select) {
            select.value = lang;
            select.dispatchEvent(new Event('change'));
        }

        var minTime = 3000;
        var start = Date.now();

        var observer = new MutationObserver(function () {
            var elapsed = Date.now() - start;
            var remaining = Math.max(0, minTime - elapsed);
            setTimeout(function () {
                enforceLanguageLabels(lang);
                hideLoader();
            }, remaining);
            observer.disconnect();
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
            characterData: true
        });

        setTimeout(function () {
            enforceLanguageLabels(lang);
            hideLoader();
        }, 5000);
    }

    $(document).on('change', '#languageSelect', function () {
        var selectedLang = $(this).val();
        if (window.__setCurrentLang) {
            window.__setCurrentLang(selectedLang);
        }
        translatePage(selectedLang);
    });
</script>

<script type="text/javascript" src="{{ asset('js/google_translater.js') }}"></script>

@endif

</body>

</html>
