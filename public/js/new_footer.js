document.addEventListener('DOMContentLoaded', function(){

  /* ── Mega dropdown positioning ── */
  function posMega(){
    const b = document.getElementById('catBanner');
    if(!b) return;
    const bot = b.getBoundingClientRect().bottom;
    document.querySelectorAll('.mega').forEach(m => m.style.top = bot+'px');
  }
  posMega();
  window.addEventListener('scroll', posMega, {passive:true});
  window.addEventListener('resize', posMega);

  /* ── Mobile menu ── */
  /* MOBILE MENU */
  const mobBtn = document.getElementById('mobBtn');
  const mobNav = document.getElementById('mobNav');
  const mobSearchBtn = document.getElementById('mobSearchBtn');
  const mobSearchBar = document.getElementById('mobSearchBar');

  function closeAllMobMenus(){
    mobNav.classList.remove('open');
    mobBtn.innerHTML='<i class="fas fa-bars"></i>';
    if(mobSearchBar) mobSearchBar.classList.remove('open');
    if(mobSearchBtn) mobSearchBtn.classList.remove('active');

  }

  mobBtn.addEventListener('click', () => {
    const opening = !mobNav.classList.contains('open');
    closeAllMobMenus();
    if(opening){
      mobNav.classList.add('open');
      mobBtn.innerHTML='<i class="fas fa-times"></i>';
    }
  });

  /* ── Mobile search icon toggle ── */
  if(mobSearchBtn && mobSearchBar){
    mobSearchBtn.addEventListener('click', () => {
      const wasOpen = mobSearchBar.classList.contains('open');
      closeAllMobMenus();
      if(!wasOpen){
        mobSearchBar.classList.add('open');
        mobSearchBtn.classList.add('active');
        setTimeout(()=>{ const inp=document.getElementById('msbInput'); if(inp) inp.focus(); },350);
      }
    });

    // Sync mobile search bar with all other search inputs
    const msbType  = document.getElementById('msbType');
    const msbInput = document.getElementById('msbInput');
    if(msbType) msbType.addEventListener('change', function(){
      ['hsType','hType'].forEach(id=>{ const el=document.getElementById(id); if(el) el.selectedIndex=this.selectedIndex; });
    });
    if(msbInput) msbInput.addEventListener('input', function(){
      ['hsBig','hInput'].forEach(id=>{ const el=document.getElementById(id); if(el) el.value=this.value; });
    });
    if(msbInput) msbInput.addEventListener('keydown', e=>{
      if(e.key==='Enter'){ closeAllMobMenus(); }
    });

    // Mobile search icon always available on mobile
  }

  /* CATEGORY MEGA MENU - TOUCH/CLICK SUPPORT */

  /* ── Header search: scroll-triggered (shows when hero scrolls out) ── */
  const hSearch   = document.getElementById('hSearch');
  const heroSearch= document.getElementById('heroSearch');

  window.syncType = function(sel){
    const hType = document.getElementById('hType');
    if(hType) hType.selectedIndex = sel.selectedIndex;
  };

  // Sync inputs both ways
  const hsType = document.getElementById('hsType');
  const hType  = document.getElementById('hType');
  const hsBig  = document.getElementById('hsBig');
  const hInput = document.getElementById('hInput');

  if(hsType) hsType.addEventListener('change', function(){
    if(hType) hType.selectedIndex = this.selectedIndex;
  });
  if(hType) hType.addEventListener('change', function(){
    if(hsType) hsType.selectedIndex = this.selectedIndex;
  });
  if(hsBig) hsBig.addEventListener('input', function(){
    if(hInput) hInput.value = this.value;
  });

  // IntersectionObserver
  if(heroSearch && hSearch){
    const scrollObs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if(!e.isIntersecting){
          hSearch.classList.add('visible');
          if(hType && hsType) hType.selectedIndex = hsType.selectedIndex;
          if(hInput && hsBig) hInput.value = hsBig.value;
        } else {
          hSearch.classList.remove('visible');
        }
      });
    }, { threshold: 0.1 });
    scrollObs.observe(heroSearch);
  }
  if(hInput) hInput.addEventListener('input', function(){
    if(hsBig) hsBig.value = this.value;
  });

  /* ── Banner scroll arrows ── */
  const catScroll = document.getElementById('catScroll');
  document.getElementById('banL').addEventListener('click', () => catScroll.scrollBy({left:-240, behavior:'smooth'}));
  document.getElementById('banR').addEventListener('click', () => catScroll.scrollBy({left:240, behavior:'smooth'}));

  /*  HERO SLIDER */
  const slides  = document.querySelectorAll('.slide');
  const hdots   = document.querySelectorAll('.hd');
  const htabs   = document.querySelectorAll('.htab');
  let cur = 0, hTimer;

  function goSlide(i){
    slides[cur].classList.remove('active');
    hdots[cur].classList.remove('active');
    htabs[cur].classList.remove('active');
    cur = (i + slides.length) % slides.length;
    slides[cur].classList.add('active');
    hdots[cur].classList.add('active');
    htabs[cur].classList.add('active');
  }
  function startH(){ hTimer = setInterval(() => goSlide(cur+1), 4200); }
  function stopH(){ clearInterval(hTimer); }

  document.getElementById('hPrev').addEventListener('click', () => { stopH(); goSlide(cur-1); startH(); });
  document.getElementById('hNext').addEventListener('click', () => { stopH(); goSlide(cur+1); startH(); });
  hdots.forEach(d => d.addEventListener('click', () => { stopH(); goSlide(+d.dataset.i); startH(); }));
  htabs.forEach(b => b.addEventListener('click', () => { stopH(); goSlide(+b.dataset.i); startH(); }));

  document.getElementById('heroCard').addEventListener('mouseenter', stopH);
  document.getElementById('heroCard').addEventListener('mouseleave', startH);
  startH();

  /* ── Hero search ── */
  window.doSearch = function(){
    const v = (document.getElementById('hsBig')||{}).value?.trim()
           || (document.getElementById('hInput')||{}).value?.trim();
    if(v) console.log('Searching:', v);
    else { const el=document.getElementById('hsBig'); if(el) el.focus(); }
  };
  ['hsBig','hInput'].forEach(id => {
    const el = document.getElementById(id);
    if(el) el.addEventListener('keydown', e => { if(e.key==='Enter') doSearch(); });
  });

  /* SERVICES CARD SLIDER */
  const sTrack = document.getElementById('sTrack');
  const sDots  = document.getElementById('sDots');
  const sCards = sTrack.querySelectorAll('.scard');
  let sIdx=0, sTimer;

  function getSpv(){
    if(window.innerWidth>=1024) return 4;
    if(window.innerWidth>=768) return 3;
    if(window.innerWidth>=480) return 2;
    return 1;
  }

  function buildSDots(){
    sDots.innerHTML='';
    const pages = Math.ceil(sCards.length/getSpv());
    for(let i=0;i<pages;i++){
      const d=document.createElement('button');
      d.className='sdot'+(i===0?' active':'');
      d.addEventListener('click',()=>{ stopS(); setSIdx(i*getSpv()); startS(); });
      sDots.appendChild(d);
    }
  }

  function updateSDots(){
    const pg=Math.floor(sIdx/getSpv());
    document.querySelectorAll('.sdot').forEach((d,i)=>d.classList.toggle('active',i===pg));
  }

  function setSIdx(i, anim=true){
    const spv=getSpv();
    sIdx=Math.max(0,Math.min(i,sCards.length-spv));
    const vp=sTrack.parentElement;
    const gap=20;
    const cw=(vp.offsetWidth-(spv-1)*gap)/spv;
    sCards.forEach(c=>c.style.flex=`0 0 ${cw}px`);
    sTrack.style.transition=anim?'transform .42s cubic-bezier(.4,0,.2,1)':'none';
    sTrack.style.transform=`translateX(${-sIdx*(cw+gap)}px)`;
    updateSDots();
    document.getElementById('sPrev').disabled=sIdx===0;
    document.getElementById('sNext').disabled=sIdx>=sCards.length-spv;
  }

  function startS(){ sTimer=setInterval(()=>setSIdx(sIdx+1>=sCards.length-getSpv()+1?0:sIdx+1),3200); }
  function stopS(){ clearInterval(sTimer); }

  document.getElementById('sPrev').addEventListener('click',()=>{ stopS(); setSIdx(sIdx-1); startS(); });
  document.getElementById('sNext').addEventListener('click',()=>{ stopS(); setSIdx(sIdx+1); startS(); });

  const sSec=document.querySelector('.sec');
  sSec.addEventListener('mouseenter',stopS);
  sSec.addEventListener('mouseleave',startS);

  buildSDots(); setSIdx(0,false); startS();

  let resTO;
  window.addEventListener('resize',()=>{
    clearTimeout(resTO);
    resTO=setTimeout(()=>{ buildSDots(); setSIdx(sIdx,false); posMega(); },120);
  });

  /* FADE-IN on scroll*/
  const fiObs=new IntersectionObserver((entries)=>{
    entries.forEach((e,i)=>{
      if(e.isIntersecting){
        e.target.style.transitionDelay=(i%4)*.08+'s';
        e.target.classList.add('vis');
        fiObs.unobserve(e.target);
      }
    });
  },{threshold:.1});
  document.querySelectorAll('.fi').forEach(el=>fiObs.observe(el));

});

