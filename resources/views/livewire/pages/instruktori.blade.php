<?php
use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.landing', [
    'metaDescription' => 'Trenéři Judo Clubu Raion-ryu – zkušení trenéři Kódókan Judo a Hiko-ryu Taijutsu, kteří vedou tréninky v Praze 8 a ve Vodochodech.',
])]
#[Title('Trenéři | Judo Club Raion-ryu')]
class extends Component {}; ?>

@php
    // Fotky vedoucího školy Filipa Rubínka pro carousel (public/images/instruktori).
    $filipPhotos = ['filip', 'filipp', 'filip1', 'filip2', 'filip3', 'filip4'];
@endphp

<div class="inst-page">

<style>
  /* ─── Stránka „Instruktoři" ───────────────────────────────────────────────
     Styly scopované pod .inst-page; navbar a footer jsou sdílené komponenty. */

  .inst-page svg, .inst-page img { display: block; }

  /* ─── HEADER ─── */
  .inst-page .page-header {
    padding: 140px 80px 60px;
    background: var(--bg-dark); color: #fff;
    position: relative; overflow: hidden;
  }
  .inst-page .page-header-kanji {
    position: absolute; right: 40px; top: 50%; transform: translateY(-46%);
    font-family: var(--serif); font-size: 220px; line-height: 1;
    color: rgba(255,255,255,.035); font-weight: 700; pointer-events: none; user-select: none;
  }
  .inst-page .breadcrumb {
    font-size: 11px; letter-spacing: .15em; text-transform: uppercase;
    color: rgba(255,255,255,.4); margin-bottom: 22px;
    display: flex; gap: 8px; align-items: center; flex-wrap: wrap; position: relative; z-index: 1;
  }
  .inst-page .breadcrumb a { color: rgba(255,255,255,.4); text-decoration: none; transition: color .2s; }
  .inst-page .breadcrumb a:hover { color: var(--red); }
  .inst-page .header-eyebrow {
    font-size: 11px; letter-spacing: .2em; text-transform: uppercase;
    color: var(--red); font-weight: 600; margin-bottom: 18px;
    display: flex; align-items: center; gap: 12px; position: relative; z-index: 1;
  }
  .inst-page .header-eyebrow::before { content:''; display:block; width:32px; height:1px; background:var(--red); }
  .inst-page .page-title {
    font-family: var(--serif); font-size: clamp(36px, 4.4vw, 58px);
    font-weight: 300; line-height: 1.08; color: #fff; margin-bottom: 16px; position: relative; z-index: 1;
  }
  .inst-page .page-sub { font-size: 16px; color: rgba(255,255,255,.5); font-weight: 300; max-width: 560px; line-height: 1.7; position: relative; z-index: 1; }

  /* ─── SECTIONS ─── */
  .inst-page section { padding: 90px 80px; }
  .inst-page .section-eyebrow {
    font-size: 11px; letter-spacing: .2em; text-transform: uppercase;
    color: var(--red); font-weight: 600; margin-bottom: 36px;
    display: flex; align-items: center; gap: 12px;
  }
  .inst-page .section-eyebrow::before { content:''; display:block; width:32px; height:1px; background:var(--red); }

  /* ─── VEDOUCÍ ŠKOLY (FILIP) ─── */
  .inst-page .lead-instructor {
    display: grid; grid-template-columns: 420px 1fr; gap: 64px; align-items: start;
  }
  .inst-page .li-photo-wrap { position: sticky; top: 96px; }
  /* Carousel vedoucího školy (Filip) – stejný vzor jako carousel na stránce dětí. */
  .inst-page .li-carousel { position: relative; }
  .inst-page .carousel-frame {
    width: 100%; aspect-ratio: 3/4; position: relative; overflow: hidden;
    border: 1px solid var(--rule); background: #ece8e1;
  }
  .inst-page .carousel-slide { position: absolute; inset: 0; opacity: 0; transition: opacity .7s ease; }
  .inst-page .carousel-slide.active { opacity: 1; }
  .inst-page .carousel-slide img { width: 100%; height: 100%; object-fit: cover; filter: grayscale(.1) contrast(1.02); }
  .inst-page .carousel-arrow {
    position: absolute; top: 50%; transform: translateY(-50%); z-index: 4;
    width: 40px; height: 40px; background: rgba(20,18,14,.55); backdrop-filter: blur(4px);
    border: none; color: #fff; cursor: pointer;
    display: flex; align-items: center; justify-content: center; transition: background .2s;
  }
  .inst-page .carousel-arrow:hover { background: var(--red); }
  .inst-page .carousel-prev { left: 0; } .inst-page .carousel-next { right: 0; }
  .inst-page .carousel-dots { display: flex; gap: 8px; margin-top: 16px; }
  .inst-page .carousel-dot {
    width: 26px; height: 3px; background: var(--rule); border: none; cursor: pointer; padding: 0;
    transition: background .2s;
  }
  .inst-page .carousel-dot.active { background: var(--red); }
  .inst-page .li-photo-accent { position: absolute; top: -1px; left: -1px; width: 4px; height: 72px; background: var(--red); z-index: 5; }
  .inst-page .li-name { font-family: var(--serif); font-size: 40px; font-weight: 300; line-height: 1.1; margin-bottom: 8px; }
  .inst-page .li-role {
    font-size: 12px; letter-spacing: .15em; text-transform: uppercase;
    color: var(--red); font-weight: 700; margin-bottom: 24px;
  }
  .inst-page .grade-badges { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 32px; }
  .inst-page .grade-badge {
    font-size: 11px; letter-spacing: .06em; font-weight: 600;
    background: var(--bg-dark); color: #fff; padding: 8px 14px;
  }
  .inst-page .grade-badge.alt { background: transparent; color: var(--ink); border: 1.5px solid var(--rule); }
  .inst-page .li-certs { margin-bottom: 32px; }
  .inst-page .inst-certs { margin-top: 18px; }
  .inst-page .li-intro {
    font-size: 16px; line-height: 1.8; color: var(--ink-mid); font-weight: 300;
    margin-bottom: 32px; max-width: 640px;
  }
  .inst-page .li-intro strong { font-weight: 600; color: var(--ink); }
  .inst-page .bio-list { list-style: none; display: grid; gap: 0; border-top: 1px solid var(--rule); max-width: 720px; }
  .inst-page .bio-list li {
    font-size: 14.5px; line-height: 1.6; color: var(--ink-mid); font-weight: 300;
    padding: 14px 0 14px 28px; border-bottom: 1px solid var(--rule); position: relative;
  }
  .inst-page .bio-list li::before {
    content: ''; position: absolute; left: 0; top: 21px;
    width: 8px; height: 8px; background: var(--red); transform: rotate(45deg);
  }
  .inst-page .bio-list li strong { font-weight: 600; color: var(--ink); }

  /* ─── TRENÉRSKÝ TÝM ─── */
  .inst-page .team { background: #F0EDE8; }
  .inst-page .team-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2px; }
  .inst-page .instructor { background: var(--bg); overflow: hidden; display: flex; flex-direction: column; }
  .inst-page .inst-photo {
    width: 100%; aspect-ratio: 4/5; object-fit: cover;
    background: #ece8e1; filter: grayscale(.1) contrast(1.02);
  }
  /* Mini-slider fotky v kartě týmu — využívá carousel engine [data-carousel];
     fade přebírá generické .carousel-slide, tečky překryjeme přes fotku. */
  .inst-page .inst-slider { position: relative; aspect-ratio: 4/5; overflow: hidden; background: #ece8e1; }
  .inst-page .inst-slider-dots { position: absolute; bottom: 12px; left: 50%; transform: translateX(-50%); margin-top: 0; z-index: 3; }
  .inst-page .inst-slider-dots .carousel-dot { background: rgba(255,255,255,.55); }
  .inst-page .inst-slider-dots .carousel-dot.active { background: var(--red); }
  .inst-page .inst-photo-placeholder {
    width: 100%; aspect-ratio: 4/5; background: var(--bg-dark);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    position: relative; overflow: hidden;
  }
  .inst-page .inst-photo-placeholder .ph-initials { font-family: var(--serif); font-size: 64px; font-weight: 300; color: rgba(255,255,255,.85); }
  .inst-page .inst-photo-placeholder .ph-label {
    font-family: monospace; font-size: 10px; letter-spacing: .12em; text-transform: uppercase;
    color: rgba(255,255,255,.3); margin-top: 12px;
  }
  .inst-page .inst-photo-placeholder .ph-kanji {
    position: absolute; bottom: -30px; right: -10px;
    font-family: var(--serif); font-size: 140px; color: rgba(255,255,255,.04); line-height: 1;
  }
  .inst-page .inst-body { padding: 28px 28px 32px; display: flex; flex-direction: column; flex: 1; }
  .inst-page .inst-name { font-family: var(--serif); font-size: 22px; font-weight: 400; line-height: 1.2; margin-bottom: 6px; }
  .inst-page .inst-grade { font-size: 11px; letter-spacing: .1em; text-transform: uppercase; color: var(--red); font-weight: 700; margin-bottom: 20px; }
  .inst-page .inst-list { list-style: none; display: grid; gap: 10px; }
  .inst-page .inst-list li {
    font-size: 13.5px; line-height: 1.55; color: var(--ink-mid); font-weight: 300;
    padding-left: 20px; position: relative;
  }
  .inst-page .inst-list li::before {
    content: ''; position: absolute; left: 0; top: 8px;
    width: 6px; height: 6px; background: var(--red); transform: rotate(45deg);
  }
  .inst-page .inst-todo {
    margin-top: auto; padding-top: 20px;
    font-size: 12px; color: var(--ink-light); font-style: italic; font-weight: 300;
    display: flex; align-items: center; gap: 8px;
  }
  .inst-page .inst-todo::before { content:''; display:block; width:16px; height:1px; background:var(--red); }

  @media (max-width: 900px) {
    .inst-page .page-header { padding: 120px 28px 48px; }
    .inst-page section { padding: 56px 28px; }
    .inst-page .lead-instructor { grid-template-columns: 1fr; gap: 32px; }
    .inst-page .li-photo-wrap { position: static; }
    .inst-page .li-name { font-size: 32px; }
    .inst-page .team-grid { grid-template-columns: 1fr; }
  }
</style>

{{-- NAV (sdílená komponenta) --}}
<x-ui.landing-nav />

{{-- HEADER --}}
<header class="page-header">
  <div class="breadcrumb">
    <a href="{{ route('home') }}">Úvod</a> <span>/</span>
    <span>Klub</span> <span>/</span>
    <span style="color:rgba(255,255,255,.65);">Trenéři</span>
  </div>
  <div class="header-eyebrow">Klub</div>
  <h1 class="page-title">Trenéři</h1>
  <p class="page-sub">Lidé, kteří předávají tradiční Judo dál. Mistři kata, sebeobrany i práce s dětmi — profesionálové na svém místě s osobním přístupem ke každému cvičenci.</p>
</header>

{{-- VEDOUCÍ ŠKOLY: FILIP --}}
<section>
  <div class="section-eyebrow">Vedoucí školy</div>
  <div class="lead-instructor">
    <div class="li-photo-wrap">
      <div class="li-photo-accent"></div>
      <div class="li-carousel" data-carousel>
        <div class="carousel-frame">
          @foreach ($filipPhotos as $i => $photo)
            <div class="carousel-slide{{ $i === 0 ? ' active' : '' }}">
              <img src="{{ asset('images/instruktori/' . $photo . '.jpeg') }}" alt="Filip Rubínek" loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
            </div>
          @endforeach
          <button type="button" class="carousel-arrow carousel-prev" aria-label="Předchozí fotka">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M12.5 4L6.5 10l6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <button type="button" class="carousel-arrow carousel-next" aria-label="Další fotka">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M7.5 4l6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
        </div>
        <div class="carousel-dots">
          @foreach ($filipPhotos as $i => $photo)
            <button type="button" class="carousel-dot{{ $i === 0 ? ' active' : '' }}" aria-label="Fotka {{ $i + 1 }}"></button>
          @endforeach
        </div>
      </div>
    </div>
    <div>
      <div class="li-name">Filip Rubínek</div>
      <div class="li-role">Renshi · Vedoucí školy</div>
      <div class="grade-badges">
        <span class="grade-badge">6. dan · Hiko-ryu Taijutsu</span>
        <span class="grade-badge alt">3. dan · Kódókan Judo (ČSJu)</span>
        <span class="grade-badge alt">2. level · Balintawak B3</span>
      </div>
      <div class="li-certs">
        <x-ui.certificates :images="['cer-filip.jpeg', 'cer-filip2.jpeg']" name="Filip Rubínek" />
      </div>
      <p class="li-intro">
        Titul <strong>Renshi</strong> a 6. dan v japonském samurajském systému boje Hiko-ryu Taijutsu mu udělil osobně velmistr <strong>Koshiro Tanaka</strong>, jehož je od roku 2017 osobním žákem. Judu se věnuje od roku 2000.
      </p>
      <ul class="bio-list">
        <li>Člen <strong>Kodokan Judo Institute</strong>, Tokio, Japonsko</li>
        <li>Člen Kolégia Danů ČSJu, trenér II. třídy, zkušební komisař II. třídy</li>
        <li>Lektor pro rozvoj kat, sebeobrany a průpravných metodických cviků pro ČR v rámci Kolégia ČSJu</li>
        <li>Osobní žák velmistra Koshiro Tanaky (Hiko-ryu Taijutsu, Japonsko) od roku 2017</li>
        <li>Reiki mistr III. stupně Ueshieho systému</li>
        <li>Zaměstnání: <strong>Městská Policie hl. m. Prahy</strong> — výcvikový instruktor policejní sebeobrany a taktiky; 2007–2013 Pohotovostní útvar — SOP (speciální jednotka MP Praha)</li>
        <li>Bývalý student Fakulty tělesné výchovy a sportu UK Praha — trenérská škola, specializace Judo</li>
        <li>Bronzový medailista MČR 2015 — Goshin jutsu no kata</li>
        <li><strong>Březen/duben 2016</strong> — 19denní stáž v Japonsku, Tokio, institut Kodokan Judo a Kenkukai ne-waza</li>
        <li><strong>Březen/duben 2019</strong> — 15denní stáž v Japonsku, Tokio, u velmistra Tanaky (Hiko-ryu Taijutsu) a Kodokan Judo institut</li>
        <li>Stříbrný medailista MČR Jiu-jitsu 2019 · bronz kime-no-kata MČR Jiu-jitsu 2019</li>
      </ul>
    </div>
  </div>
</section>

{{-- TRENÉRSKÝ TÝM --}}
<section class="team">
  <div class="section-eyebrow">Trenérský tým</div>
  <div class="team-grid">

    {{-- Zuzka Skálová --}}
    <div class="instructor">
      <div class="inst-slider" data-carousel>
        <div class="carousel-slide active"><img src="{{ asset('images/instruktori/zuzka1.jpeg') }}" alt="Ing. Zuzka Skálová" loading="lazy"></div>
        <div class="carousel-slide"><img src="{{ asset('images/instruktori/zuzka-skalova.jpeg') }}" alt="Ing. Zuzka Skálová" loading="lazy"></div>
        <div class="carousel-dots inst-slider-dots">
          <button type="button" class="carousel-dot active" aria-label="Fotka 1"></button>
          <button type="button" class="carousel-dot" aria-label="Fotka 2"></button>
        </div>
      </div>
      <div class="inst-body">
        <div class="inst-name">Ing. Zuzka Skálová</div>
        <div class="inst-grade">1. dan jiu-jitsu · 2. kyu judo</div>
        <ul class="inst-list">
          <li>Trenérka III. třídy judo i jiu-jitsu</li>
          <li>Členka JC Raion-Ryu</li>
        </ul>
        <div class="inst-certs">
          <x-ui.certificates :images="['skalova-certifikat.jpeg']" name="Zuzka Skálová" />
        </div>
      </div>
    </div>

    {{-- Lukáš Fiala --}}
    <div class="instructor">
      <img class="inst-photo" src="{{ asset('images/instruktori/luky.jpg') }}" alt="Ing. Lukáš Fiala" loading="lazy">
      <div class="inst-body">
        <div class="inst-name">Ing. Lukáš Fiala</div>
        <div class="inst-grade">1. dan Judo</div>
        <ul class="inst-list">
          <li>Člen ČSJu (Český svaz juda)</li>
          <li>Instruktor pro dospělé</li>
          <li>Člen asociace WIBK, kde získal 2. dan Judo</li>
          <li>Trenér a zkušební komisař III. třídy ČSJu</li>
        </ul>
      </div>
    </div>

    {{-- Zuzka Ratajová --}}
    <div class="instructor">
      <div class="inst-slider" data-carousel>
        <div class="carousel-slide active"><img src="{{ asset('images/instruktori/ratajova.jpeg') }}" alt="Zuzka Ratajová" loading="lazy"></div>
        <div class="carousel-slide"><img src="{{ asset('images/instruktori/rata.jpeg') }}" alt="Zuzka Ratajová" loading="lazy"></div>
        <div class="carousel-dots inst-slider-dots">
          <button type="button" class="carousel-dot active" aria-label="Fotka 1"></button>
          <button type="button" class="carousel-dot" aria-label="Fotka 2"></button>
        </div>
      </div>
      <div class="inst-body">
        <div class="inst-name">Zuzka Ratajová</div>
        <div class="inst-grade">1. kyu Judo</div>
        <ul class="inst-list">
          <li>Členka ČSJu (Český svaz juda)</li>
          <li>Instruktorka / trenérka pro děti i dospělé</li>
          <li>III. třída Judo ČSJu</li>
        </ul>
      </div>
    </div>

  </div>
</section>

{{-- FOOTER (sdílená komponenta) --}}
<x-ui.landing-footer />

<script>
  (function () {
    // Carousel vedoucího školy – přežije SPA navigaci Livewire (init jen jednou,
    // globální listenery registrované jednou). Stejný vzor jako na stránce dětí.
    if (window.__instCarousels) return;
    window.__instCarousels = true;

    function initCarousels() {
      document.querySelectorAll('[data-carousel]').forEach(function (root) {
        if (root.dataset.carouselReady) return;
        var slides = [].slice.call(root.querySelectorAll('.carousel-slide'));
        var dots = [].slice.call(root.querySelectorAll('.carousel-dot'));
        if (slides.length < 2) return;
        root.dataset.carouselReady = '1';

        var cur = 0, timer = null;
        function go(i) {
          cur = (i + slides.length) % slides.length;
          slides.forEach(function (s, n) { s.classList.toggle('active', n === cur); });
          dots.forEach(function (d, n) { d.classList.toggle('active', n === cur); });
        }
        function reset() { clearInterval(timer); timer = setInterval(function () { go(cur + 1); }, 4500); root.__carouselTimer = timer; }

        var prev = root.querySelector('.carousel-prev');
        var next = root.querySelector('.carousel-next');
        if (prev) prev.addEventListener('click', function () { go(cur - 1); reset(); });
        if (next) next.addEventListener('click', function () { go(cur + 1); reset(); });
        dots.forEach(function (d, n) { d.addEventListener('click', function () { go(n); reset(); }); });
        reset();
      });
    }

    // `livewire:navigated` Livewire vyvolá i při prvním načtení stránky.
    document.addEventListener('livewire:navigated', initCarousels);
    document.addEventListener('livewire:navigating', function () {
      document.querySelectorAll('[data-carousel]').forEach(function (root) {
        if (root.__carouselTimer) clearInterval(root.__carouselTimer);
      });
    });
  })();
</script>

</div>
