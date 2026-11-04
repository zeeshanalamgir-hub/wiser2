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
            url('https://images.unsplash.com/photo-1552664730-d307ca884978?w=1800&auto=format&fit=crop&q=80') center/cover;
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

    .slide h2 {
        font-size: 1.55rem;
        font-weight: 800;
        color: var(--primary);
        letter-spacing: .5px;
        margin-bottom: 6px;
    }

    .slide p {
        font-size: .88rem;
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
        background: #0f5256;
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
        padding-left: 0px !important;
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
    .goog-te-banner-frame,
    .VIpgJd-ZVi9od-ORHb,
    .skiptranslate {
        display: none !important;
    }

    /* Fix body top shift caused by the toolbar */
    body {
        top: 0 !important;
        position: static !important;
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
<header>
    @php
        $user = Auth::user();
    @endphp
    <div class="container">
        {{-- Loader Overlay --}}

        <nav class="nav">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="Wiserlance"
                    style="height:34px;width:auto;object-fit:contain;">
            </a>

            <!-- Nav links -->
            <div class="nav-links">
                <a href="{{ route('professionals') }}" class="nav-link">Professionals</a>
                <a href="{{ route('job') }}" class="nav-link">Jobs</a>
                <a href="{{ route('service') }}" class="nav-link">Services</a>
                <div class="nav-drop-wrap">
                    <a href="#" class="nav-link">About <i class="fas fa-chevron-down chev"></i></a>
                    <div class="nav-drop-menu">
                        <a href="{{ route('about.us') }}"><i class="fas fa-info-circle"></i>About Wiserlance</a>
                        <a href="{{ route('how.our.platform.works') }}"><i class="fas fa-question-circle"></i>How
                            Wiserlance Works</a>
                        <hr>
                        <a href="{{ route('why.choose.us') }}"><i class="fas fa-star"></i>Why Wiserlance</a>
                        <a href="{{ route('memberships.pricing') }}"><i class="fas fa-tags"></i>Pricing</a>
                    </div>
                </div>
            </div>

            <!-- Right (search lives here → always right side, away from About) -->
            <div class="nav-right">
                <!-- Scroll-triggered search -->
                <div class="h-search-wrap visible" id="hSearch" >
                    <select class="hs-type" id="hType">
                        <option value="professionals" {{ request('type') == 'professionals' ? 'selected' : '' }}>Professional</option>
                        <option value="jobs" {{ request('type') == 'jobs' ? 'selected' : '' }}>Job</option>
                        <option value="services" {{ request('type') == 'services' ? 'selected' : '' }}>Service</option>
                    </select>
                    <div class="hs-input-row">
                        <i class="fas fa-search"></i>
                        <input type="text" id="hInput" placeholder="Search..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="lang notranslate" translate="no">
                    <i class="fas fa-globe" style="font-size:.78rem;"></i>
                    <select id="languageSelect">
                        <option value="en" {{ session('locale') == 'en' ? 'selected' : '' }}>EN</option>
                        <option value="ar" {{ session('locale') == 'ar' ? 'selected' : '' }}>AR</option>
                        <option value="fr" {{ session('locale') == 'fr' ? 'selected' : '' }}>FR</option>
                        <option value="de" {{ session('locale') == 'de' ? 'selected' : '' }}>DE</option>
                    </select>
                </div>
                <script>
                    // Google Translate persists the chosen language across page loads via its own
                    // "googtrans" cookie (e.g. "/en/fr"), independent of the server-side session('locale')
                    // used above for the initial "selected" option. Without this, the dropdown reverts to
                    // EN on every new page even though the content is still shown translated.
                    (function () {
                        var SUPPORTED_LANGS = { en: true, ar: true, fr: true, de: true };

                        function getGoogTransLang() {
                            var match = document.cookie.match(/(?:^|;\s*)googtrans=([^;]+)/);
                            if (!match) return null;

                            var parts = decodeURIComponent(match[1]).split('/').filter(Boolean);
                            return parts.length > 1 ? parts[1] : null;
                        }

                        function syncLanguageSelect() {
                            var select = document.getElementById('languageSelect');
                            if (!select) return;

                            var lang = getGoogTransLang();
                            if (!lang || !SUPPORTED_LANGS[lang]) return;

                            if (select.value !== lang) {
                                select.value = lang;
                            }
                        }

                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', syncLanguageSelect);
                        } else {
                            syncLanguageSelect();
                        }
                    })();
                </script>
                <button class="mob-search-butn" id="mobSearchbutn"><i class="fas fa-search"></i></button>

                @auth
                    @php
                        $user = Auth::user();
                    @endphp
                    @if ($user->ev == 1)
                        @if ($user->is_employer)
                            <a class="text-3gray" style="color:white !important;" href="/user/buyer/dashboard"><i
                                    class="fa-solid fa-house fs-14"></i></a>
                        @else
                            <a class="text-3gray" style="color:white !important;" href="/user/seller/dashboard"><i
                                    class="fa-solid fa-house fs-14"></i></a>
                        @endif
                        <a href="{{ route('user.logout') }}" class="butn butn-primary"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('user.logout') }}" method="get" style="display: none;">
                            @csrf
                        </form>
                    @else
                        <a class="text-3gray" style="color:white !important;" href="/user/authorization"><i
                                class="fa-solid fa-house fs-14"></i></a>
                    @endif
                @else
                    <a href="{{ url('/login') }}" class="nav-login">Log In</a>
                    <a href="{{ url('signup') }}" class="butn butn-primary">Sign Up</a>
                @endauth

                <button class="mob-butn" id="mobbutn"><i class="fas fa-bars"></i></button>
            </div>
        </nav>
    </div>
    <!-- Mobile search dropdown -->
    <div class="mob-search-bar" id="mobSearchBar">
        <div class="container">
            <div class="msb-inner">
                <select class="msb-type" id="msbType">
                    <option value="professionals">Professionals</option>
                    <option value="jobs">Jobs</option>
                    <option value="services">Services</option>
                </select>
                <div class="msb-input">
                    <i class="fas fa-search"></i>
                    <input type="text" id="msbInput" placeholder="Search...">
                </div>
            </div>
        </div>
    </div>
    <div class="mob-nav" id="mobNav">
        <a href="{{ url('professionals') }}">Professional</a>
        <a href="{{ url('job') }}">Job</a>
        <a href="{{ url('service') }}">Service</a>
        <a href="{{ route('about.us') }}">About Wiserlance</a>
        <a href="{{ route('how.our.platform.works') }}">How It Works</a>
        <a href="{{ route('why.choose.us') }}">Why Wiserlance</a>
        <a href="{{ route('memberships.pricing') }}">Pricing</a>
    </div>
</header>

<!-- CATEGORIES BANNER-->
<div class="cat-banner" id="catBanner">
    <div class="container">
        <div class="banner-row">
            <button class="ban-arrow left" id="banL"><i class="fas fa-chevron-left"></i></button>
            <div class="cat-scroll" id="catScroll">

                @if (isset($categories) && count($categories) > 0)
                    @foreach ($categories as $category)
                        <div class="cdw">
                            <a href="{{ url('/job') }}?cat_id={{ $category->id }}" class="cat-item">
                                @if ($category->icon)
                                    <i class="{{ $category->icon }} ci"></i>
                                @else
                                    <i class="fas fa-folder ci"></i>
                                @endif
                                {{ $category->name }}<i class="fas fa-chevron-down ca"></i>
                            </a>
                            @if (isset($category->subCategory) && count($category->subCategory) > 0)
                                <div class="mega">
                                    <div class="mega-in">
                                        <div class="mega-left">
                                            <h3>{{ $category->name }}</h3>
                                            <p>{{ $category->description ?? 'Expert professionals for every need.' }}
                                            </p>
                                            <a href="{{ url('/job') }}?cat_id={{ $category->id }}"
                                                class="mega-butn">
                                                <i class="fas fa-arrow-right"></i> Browse All
                                            </a>
                                        </div>
                                        <div class="mega-grid">
                                            @foreach ($category->subCategory as $subcategory)
                                                <a href="{{ url('/job') }}?subcat_id={{ $subcategory->id }}"
                                                    class="mega-link">
                                                    @if ($subcategory->icon)
                                                        <i class="{{ $subcategory->icon }}"></i>
                                                    @else
                                                        <i class="fas fa-angle-right"></i>
                                                    @endif
                                                    {{ $subcategory->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif

            </div><!-- /cat-scroll -->
            <button class="ban-arrow right" id="banR"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</div>

<form method="GET" class="hero-search" style="display:none" id="heroSearch">
    <select class="hs-drop-butn" id="hsType" name="type" onchange="syncType(this)">
        <option value="professionals" {{ request('type') == 'professionals' ? 'selected' : '' }}>Professional</option>
        <option value="jobs" {{ request('type') == 'jobs' ? 'selected' : '' }}>Job</option>
        <option value="services" {{ request('type') == 'services' ? 'selected' : '' }}>Service</option>
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

<script>
    // Route the search to the page of the selected type (professionals / jobs / services)
    const searchRoutes = {
        professionals: "{{ route('professionals') }}",
        jobs: "{{ route('job') }}",
        services: "{{ route('service') }}"
    };

    function submitHeaderSearch() {
        const form = document.getElementById('heroSearch');
        const hType = document.getElementById('hType');
        const hsType = document.getElementById('hsType');
        const hInput = document.getElementById('hInput');
        const hsBig = document.getElementById('hsBig');

        // Header dropdown/input are the source of truth when submitting from the header
        const type = hType ? hType.value : hsType.value;
        hsType.value = type;
        if (hInput) hsBig.value = hInput.value;

        form.action = searchRoutes[type] || searchRoutes.professionals;
        form.submit();
    }

    document.getElementById('hInput').addEventListener('keydown', function(e) {
        if (e.key === "Enter") { // Enter key pressed
            e.preventDefault(); // Prevent default Enter behavior (like refreshing page)
            submitHeaderSearch();
        }
    });

    document.getElementById('heroSearch').addEventListener('submit', function() {
        const hsType = document.getElementById('hsType');
        this.action = searchRoutes[hsType.value] || searchRoutes.professionals;
    });


</script>
