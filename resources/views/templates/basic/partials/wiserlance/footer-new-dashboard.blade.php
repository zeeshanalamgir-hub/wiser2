
@php
    $user = Illuminate\Support\Facades\Auth::user();
@endphp
<style>
  /* PAGE RESET – only for preview */
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    background: #f4f4f2;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
  }

  .wl-footer {
    --wl-primary:       #0f5256;
    --wl-primary-dark:  #0a3a3d;
    --wl-secondary:     #4caf50;
    --wl-secondary-dark:#3a8f3c;
    --wl-white:         #ffffff;
    --wl-border:        rgba(255,255,255,0.09);
    --wl-text-muted:    rgba(255,255,255,0.45);
    --wl-text-mid:      rgba(255,255,255,0.65);
    --wl-text-bright:   rgba(255,255,255,0.88);
    --wl-radius:        12px;
    --wl-transition:    .25s ease;

    background: var(--wl-primary);
    color: var(--wl-white);
    padding: 44px 0 22px;
    width: 100%;
    font-family: inherit;
    line-height: 1.6;
  }

  /* Container */
  .wl-footer .wl-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 40px;
  }

  /* Grid */
  .wl-footer .wl-ft-grid {
    display: grid;
    grid-template-columns: 1.7fr repeat(4, 1fr);
    gap: 28px;
    padding-bottom: 28px;
    border-bottom: 1px solid var(--wl-border);
    margin-bottom: 22px;
  }

  /* Brand column */
  .wl-footer .wl-ft-brand

  .wl-footer .wl-ft-logo {
    display: flex;
    align-items: center;
    gap: 9px;
    font-size: 1.2rem;
    font-weight: 800;
    color: var(--wl-white);
    margin-bottom: 12px;
    text-decoration: none;
  }

  .wl-footer .wl-ft-logo img {
    height: 54px;
    width: auto;
    object-fit: contain;
    display: block;
  }

  .wl-footer .wl-ft-brand p {
    color: var(--wl-text-muted);
    font-size: .83rem;
    line-height: 1.7;
    margin-bottom: 18px;
  }

  /* Social icons */
  .wl-footer .wl-social {
    display: flex;
    gap: 8px;
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .wl-footer .wl-social li { display: inline-flex; }

  .wl-footer .wl-social a {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.1);
    color: var(--wl-text-mid);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .82rem;
    transition: var(--wl-transition);
    text-decoration: none;
  }

  .wl-footer .wl-social a:hover {
    background: var(--wl-secondary);
    border-color: var(--wl-secondary);
    color: var(--wl-white);
  }

  /* Nav columns */
  .wl-footer .wl-ft-col h4 {
    font-size: .78rem;
    font-weight: 700;
    color: var(--wl-secondary);
    margin-bottom: 14px;
    text-transform: uppercase;
    letter-spacing: .5px;
  }

  .wl-footer .wl-ft-col ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .wl-footer .wl-ft-col li {
    margin-bottom: 8px;
  }

  .wl-footer .wl-ft-col a {
    color: var(--wl-text-muted);
    font-size: .84rem;
    transition: var(--wl-transition);
    display: inline-block;
    text-decoration: none;
  }

  .wl-footer .wl-ft-col a:hover {
    color: var(--wl-secondary);
    transform: translateX(3px);
  }

  /* Bottom bar */
  .wl-footer .wl-ft-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
  }

  .wl-footer .wl-copy {
    color: rgba(255,255,255,.3);
    font-size: .8rem;
    margin: 0;
  }

  .wl-footer .wl-ft-links {
    display: flex;
    gap: 16px;
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .wl-footer .wl-ft-links a {
    color: rgba(255,255,255,.3);
    font-size: .79rem;
    text-decoration: none;
    transition: var(--wl-transition);
  }

  .wl-footer .wl-ft-links a:hover {
    color: var(--wl-secondary);
  }

  /* ── RESPONSIVE ── */

  /* 1060px – 2-col grid */
  @media (max-width: 1060px) {
    .wl-footer .wl-ft-grid {
      grid-template-columns: 1fr 1fr;
      gap: 24px;
    }
    .wl-footer .wl-ft-brand {
      grid-column: 1 / -1;
    }
  }

  /* 820px – tablet */
  @media (max-width: 820px) {
    .wl-footer .wl-ft-grid {
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }
    .wl-footer .wl-ft-brand {
      grid-column: 1 / -1;
    }
  }

  /* 600px – mobile */
  @media (max-width: 600px) {
    .wl-footer .wl-container {
      padding: 0 16px;
    }
    .wl-footer .wl-ft-grid {
      grid-template-columns: 1fr 1fr;
      gap: 18px;
    }
    .wl-footer .wl-ft-brand {
      grid-column: 1 / -1;
    }
    .wl-footer .wl-ft-brand p {
      font-size: .8rem;
    }
    .wl-footer .wl-ft-col h4 {
      font-size: .72rem;
    }
    .wl-footer .wl-ft-col a {
      font-size: .8rem;
    }
    .wl-footer .wl-ft-bottom {
      flex-direction: column;
      text-align: center;
      gap: 8px;
    }
    .wl-footer .wl-copy {
      font-size: .75rem;
    }
  }

  /* 380px – small phone */
  @media (max-width: 380px) {
    .wl-footer .wl-ft-grid {
      grid-template-columns: 1fr;
    }
  }
</style>
</head>
<body>

<!-- WISERLANCE FOOTER  -->
<footer class="wl-footer">
  <div class="wl-container">

    <!-- Main grid -->
    <div class="wl-ft-grid">

      <!-- Brand -->
      <div class="wl-ft-brand">
        <a href="/" class="wl-ft-logo">
            <img src="data:image/png;base64,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" alt="Wiserlance" style="height:34px;width:auto;object-fit:contain;">
        </a>
        <p>The smarter way to hire and get hired. Connecting businesses with top freelance talent worldwide.</p>
        <ul class="wl-social">
          <li><a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a></li>
          <li><a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a></li>
          <li><a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a></li>
          <li><a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a></li>
        </ul>
      </div>

      <!-- Company -->
      <div class="wl-ft-col">
        <h4>Company</h4>
        <ul>
          <li><a href="{{ route('about.us') }}">About Us</a></li>
          <li><a href="{{ route('why.choose.us') }}">Why Choose Us</a></li>
          <li><a href="{{ route('memberships.pricing') }}">Membership Pricing</a></li>
          <li><a href="{{ route('how.our.platform.works') }}">How Our Platform Works?</a></li>
        </ul>
      </div>

      <!-- For Freelancers -->
      <div class="wl-ft-col">
        <h4>For Freelancers</h4>
        <ul>
          <li><a href="{{ route('job') }}">Find Work</a></li>
          <li><a href="{{ url('/signup') }}">Create Profile</a></li>
          <li><a href="{{ route('tips.for.success.seller') }}">Tips for Success Seller</a></li>
        </ul>
      </div>

      <!-- For Clients -->
      <div class="wl-ft-col">
        <h4>For Clients</h4>
        <ul>
            <li><a href="{{ url('/professionals') }}">Hire Professional</a></li>
            @if($user?->is_employer == 1)
                <li><a href="{{ route('user.job.create') }}">Post a Job</a></li>
            @endif
            <li><a href="{{ route('tips.for.success.buyer') }}">Tips for Success Buyer</a></li>
            {{-- <li><a href="#">Payment Protection</a></li> --}}
        </ul>
      </div>

      <!-- Support -->
      <div class="wl-ft-col">
        <h4>Support</h4>
        <ul>
            <li><a href="{{ route('help.support.center') }}">Help Center</a></li>
            <li><a href="{{ route('contact.us') }}">Contact Us</a></li>
            <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
            <li><a href="{{ route('terms') }}">Terms of Service</a></li>
        </ul>
      </div>

    </div>

    <!-- Bottom bar -->
    <div class="wl-ft-bottom">
      <p class="wl-copy">&copy; {{ date('Y') }} Wiserlance. All rights reserved.</p>
      {{-- <ul class="wl-ft-links">
        <li><a href="#">Privacy</a></li>
        <li><a href="#">Terms</a></li>
        <li><a href="#">Cookies</a></li>
      </ul> --}}
    </div>

  </div>
</footer>
