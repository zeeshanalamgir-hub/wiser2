<style>
    footer {
        background: var(--primary);
        color: #fff;
        padding: 44px 0 22px;
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
</style>
<footer>
    <div class="container">
        <div class="ft-grid">
            <div class="ft-brand">
                <div class="ft-logo">
                    <a href="/">
                        <img src="{{ asset('assets/images/logo.svg') }}" alt="Wiserlance"
                            style="height:34px;width:auto;object-fit:contain;">
                    </a>
                </div>
                <p>The smarter way to hire and get hired. Connecting businesses with top freelance talent worldwide.</p>
                <div class="social">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="ft-col">
                <h4>Company</h4>
                <ul>
                    <li><a href="{{ route('about.us') }}">About Us</a></li>
                    <li><a href="{{ route('why.choose.us') }}">Why Choose Us</a></li>
                    <li><a href="{{ route('memberships.pricing') }}">Membership Pricing</a></li>
                    <li><a href="{{ route('how.our.platform.works') }}">How Our Platform Works?</a></li>
                </ul>
            </div>
            <div class="ft-col">
                <h4>For Freelancers</h4>
                <ul>
                    <li><a href="{{ url('/job') }}">Find Work</a></li>
                    <li><a href="{{ url('/signup') }}">Create Profile</a></li>
                    <li><a href="{{ route('tips.for.success.seller') }}">Tips for Success Seller</a></li>
                </ul>
            </div>
            <div class="ft-col">
                <h4>For Clients</h4>
                <ul>
                    <li><a href="{{ url('/professionals') }}">Hire Professional</a></li>
                    <li><a href="{{ url('/login') }}">Post a Job</a></li>
                    <li><a href="{{ route('tips.for.success.buyer') }}">Tips for Success Buyer</a></li>
                    {{-- <li><a href="#">Payment Protection</a></li> --}}
                </ul>
            </div>
            <div class="ft-col">
                <h4>Support</h4>
                <ul>
                    <li><a href="{{ route('help.support.center') }}">Help Center</a></li>
                    <li><a href="{{ route('contact.us') }}">Contact Us</a></li>
                    <li><a href="{{ route('privacy') }}">Project Rules</a></li>
                    <li><a href="{{ route('terms') }}">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        <div class="ft-bottom">
            <p class="copy">&copy; {{ date('Y') }} Wiserlance. All rights reserved.</p>
            {{-- <div class="ft-links"><a href="#">Privacy</a><a href="#">Terms</a><a href="#">Cookies</a> --}}
            </div>
        </div>
    </div>
</footer>

@unless (request()->is('/'))
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

            /* ── Mobile menu ── */
            /* MOBILE MENU */
            const mobbutn = document.getElementById('mobbutn');
            const mobNav = document.getElementById('mobNav');
            const mobSearchbutn = document.getElementById('mobSearchbutn');
            const mobSearchBar = document.getElementById('mobSearchBar');

            function closeAllMobMenus() {
                mobNav.classList.remove('open');
                mobbutn.innerHTML = '<i class="fas fa-bars"></i>';
                if (mobSearchBar) mobSearchBar.classList.remove('open');
                if (mobSearchbutn) mobSearchbutn.classList.remove('active');

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



        });
    </script>
@endunless
