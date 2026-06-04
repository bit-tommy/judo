<?php
use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.landing', [
    'metaDescription' => 'Tréninky dětí v JC Raion-ryu – Kódókan Judo pro děti od pěti let v Praze a Vodochodech. První dva tréninky zdarma, hravou formou a vždy bezpečně.',
])]
#[Title('Tréninky dětí | Judo Club Raion-ryu')]
class extends Component {}; ?>

@php
    // Fotky z tréninků dětí – uložené lokálně (public/images/deti).
    $detiPhotos = [
        'images/deti/deti1.jpeg',
        'images/deti/deti2.jpeg',
        'images/deti/deti3.jpeg',
        'images/deti/deti4.jpeg',
        'images/deti/deti5.jpeg',
        'images/deti/deti6.jpeg',
    ];
@endphp

<div class="deti-page" x-data="{ inquiry: false }" @keydown.escape.window="inquiry = false">

<style>
  /* ─── Stránka „Tréninky dětí" ──────────────────────────────────────────────
     Styly scopované pod .deti-page; navbar, footer a základní typografie
     (section-eyebrow / section-title / btn-*) jsou sdílené z layoutu.
     Japonské vodoznaky (kanji) jsou záměrně vynechané. */

  .deti-page svg, .deti-page img { display: block; }

  /* ─── HEADER ─── */
  .deti-page .page-header {
    padding: 132px 80px 56px;
    background: var(--bg-dark); color: #fff;
    position: relative; overflow: hidden;
  }
  .deti-page .breadcrumb {
    font-size: 11px; letter-spacing: .15em; text-transform: uppercase;
    color: rgba(255,255,255,.4); margin-bottom: 22px;
    display: flex; gap: 8px; align-items: center; flex-wrap: wrap; position: relative; z-index: 1;
  }
  .deti-page .breadcrumb a { color: rgba(255,255,255,.4); text-decoration: none; transition: color .2s; }
  .deti-page .breadcrumb a:hover { color: var(--red); }
  .deti-page .header-eyebrow {
    font-size: 11px; letter-spacing: .2em; text-transform: uppercase;
    color: var(--red); font-weight: 600; margin-bottom: 18px;
    display: flex; align-items: center; gap: 12px; position: relative; z-index: 1;
  }
  .deti-page .header-eyebrow::before { content:''; display:block; width:32px; height:1px; background:var(--red); }
  .deti-page .page-title {
    font-family: var(--serif); font-size: clamp(36px, 4.4vw, 58px);
    font-weight: 300; line-height: 1.08; color: #fff; margin-bottom: 16px; position: relative; z-index: 1;
  }
  .deti-page .page-sub { font-size: 16px; color: rgba(255,255,255,.5); font-weight: 300; max-width: 600px; line-height: 1.7; position: relative; z-index: 1; }
  .deti-page .header-stats { display: flex; gap: 48px; margin-top: 40px; position: relative; z-index: 2; flex-wrap: wrap; }
  .deti-page .hstat-num { font-family: var(--serif); font-size: 34px; font-weight: 300; color: #fff; line-height: 1; }
  .deti-page .hstat-label { font-size: 11px; letter-spacing: .12em; text-transform: uppercase; color: rgba(255,255,255,.4); margin-top: 6px; }

  /* ─── SECTION RHYTHM ─── */
  .deti-page section { padding: 110px 80px; }
  .deti-page .lead { font-size: 16px; line-height: 1.8; color: var(--ink-mid); font-weight: 300; }
  .deti-page .lead strong { font-weight: 600; color: var(--ink); }
  .deti-page .lead em { font-style: italic; color: var(--red); }
  .deti-page .lead + .lead { margin-top: 18px; }

  /* ─── INTRO (2-col s carouselem) ─── */
  .deti-page .intro { display: grid; grid-template-columns: 1.05fr .95fr; gap: 80px; align-items: center; }

  /* ─── CAROUSEL ─── */
  .deti-page .carousel { position: relative; }
  .deti-page .carousel-frame {
    aspect-ratio: 4/3; border: 1px solid var(--rule); position: relative; overflow: hidden;
    background: #ECE8E1;
  }
  .deti-page .carousel-slide { position: absolute; inset: 0; opacity: 0; transition: opacity .7s ease; }
  .deti-page .carousel-slide.active { opacity: 1; }
  .deti-page .carousel-slide img { width: 100%; height: 100%; object-fit: cover; }
  .deti-page .carousel-cap {
    position: absolute; bottom: 0; left: 0; z-index: 3;
    background: var(--red); color: #fff;
    font-size: 10px; letter-spacing: .12em; text-transform: uppercase; font-weight: 700;
    padding: 7px 13px;
  }
  .deti-page .carousel-arrow {
    position: absolute; top: 50%; transform: translateY(-50%); z-index: 4;
    width: 42px; height: 42px; background: rgba(20,18,14,.55); backdrop-filter: blur(4px);
    border: none; color: #fff; cursor: pointer;
    display: flex; align-items: center; justify-content: center; transition: background .2s;
  }
  .deti-page .carousel-arrow:hover { background: var(--red); }
  .deti-page .carousel-prev { left: 0; } .deti-page .carousel-next { right: 0; }
  .deti-page .carousel-dots { display: flex; gap: 8px; margin-top: 18px; }
  .deti-page .carousel-dot {
    width: 28px; height: 3px; background: var(--rule); border: none; cursor: pointer; padding: 0;
    transition: background .2s;
  }
  .deti-page .carousel-dot.active { background: var(--red); }
  .deti-page .carousel-note {
    font-family: monospace; font-size: 11px; color: var(--ink-light);
    letter-spacing: .06em; margin-top: 14px;
  }

  /* ─── PICKUP HIGHLIGHT ─── */
  .deti-page .pickup {
    margin-top: 36px; border-left: 3px solid var(--red);
    padding: 22px 26px; background: #F0EDE8;
    display: flex; gap: 18px; align-items: flex-start;
  }
  .deti-page .pickup svg { flex-shrink: 0; margin-top: 2px; }
  .deti-page .pickup-title { font-family: var(--serif); font-size: 18px; font-weight: 400; margin-bottom: 6px; }
  .deti-page .pickup-body { font-size: 14px; color: var(--ink-mid); line-height: 1.65; }

  /* ─── EQUIPMENT / GI ─── */
  .deti-page .equip { background: #F0EDE8; }
  .deti-page .equip-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: start; }
  .deti-page .equip-list { list-style: none; }
  .deti-page .equip-list li {
    display: flex; gap: 16px; align-items: baseline;
    padding: 18px 0; border-bottom: 1px solid var(--rule);
    font-size: 15px; color: var(--ink-mid); line-height: 1.6;
  }
  .deti-page .equip-list li:last-child { border-bottom: none; }
  .deti-page .equip-list .equip-num { font-family: monospace; font-size: 12px; color: var(--red); font-weight: 600; flex-shrink: 0; min-width: 24px; }
  .deti-page .equip-list strong { color: var(--ink); font-weight: 600; }
  .deti-page .gi-card { background: var(--bg-dark); color: #fff; padding: 44px 40px; position: relative; overflow: hidden; }
  .deti-page .gi-eyebrow { font-size: 10px; letter-spacing: .18em; text-transform: uppercase; color: var(--red); font-weight: 700; margin-bottom: 16px; }
  .deti-page .gi-title { font-family: var(--serif); font-size: 26px; font-weight: 300; margin-bottom: 18px; line-height: 1.2; }
  .deti-page .gi-body { font-size: 14px; color: rgba(255,255,255,.55); line-height: 1.75; font-weight: 300; position: relative; z-index: 2; }
  .deti-page .gi-body + .gi-body { margin-top: 14px; }
  .deti-page .gi-body strong { color: #fff; font-weight: 600; }

  /* ─── DVĚ SKUPINY ─── */
  .deti-page .groups-intro { max-width: 640px; }
  .deti-page .groups-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2px; margin-top: 56px; }
  .deti-page .group-card {
    background: #F0EDE8; padding: 48px 44px; position: relative; overflow: hidden;
    transition: background .25s;
  }
  .deti-page .group-card:hover { background: var(--bg-dark); }
  .deti-page .group-card:hover .group-name,
  .deti-page .group-card:hover .group-list li { color: #fff; }
  .deti-page .group-card:hover .group-body { color: rgba(255,255,255,.55); }
  .deti-page .group-tag { display: inline-block; background: var(--red); color: #fff; font-size: 10px; letter-spacing: .15em; text-transform: uppercase; font-weight: 700; padding: 5px 12px; margin-bottom: 20px; }
  .deti-page .group-name { font-family: var(--serif); font-size: 26px; font-weight: 400; margin-bottom: 14px; line-height: 1.2; transition: color .25s; position: relative; z-index: 2; }
  .deti-page .group-body { font-size: 14px; color: var(--ink-mid); line-height: 1.75; margin-bottom: 24px; transition: color .25s; position: relative; z-index: 2; }
  .deti-page .group-list { list-style: none; position: relative; z-index: 2; }
  .deti-page .group-list li { font-size: 13px; color: var(--ink-mid); padding: 7px 0; padding-left: 20px; position: relative; transition: color .25s; line-height: 1.5; }
  .deti-page .group-list li::before { content: '—'; position: absolute; left: 0; color: var(--red); }

  /* ─── ZÁVODNÍ PŘÍPRAVA (tmavá) ─── */
  .deti-page .comp { background: var(--bg-dark); color: #fff; position: relative; overflow: hidden; }
  .deti-page .comp .section-title { color: #fff; }
  .deti-page .comp .section-eyebrow::before { background: var(--red); }
  .deti-page .comp-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: start; position: relative; z-index: 2; }
  .deti-page .comp-body { font-size: 16px; color: rgba(255,255,255,.55); line-height: 1.8; font-weight: 300; }
  .deti-page .comp-body + .comp-body { margin-top: 18px; }
  .deti-page .comp-body strong { color: #fff; font-weight: 600; }
  .deti-page .belt-card { border: 1px solid rgba(255,255,255,.12); padding: 36px 34px; }
  .deti-page .belt-label { font-size: 10px; letter-spacing: .18em; text-transform: uppercase; color: rgba(255,255,255,.4); font-weight: 600; margin-bottom: 18px; }
  .deti-page .belt-visual { height: 26px; border-radius: 2px; margin-bottom: 16px; background: linear-gradient(90deg, #F4EFE6 0% 50%, #E0B93B 50% 100%); }
  .deti-page .belt-grade { font-family: var(--serif); font-size: 30px; font-weight: 300; color: #fff; margin-bottom: 6px; }
  .deti-page .belt-sub { font-size: 13px; color: rgba(255,255,255,.45); line-height: 1.6; }
  .deti-page .belt-note { margin-top: 24px; font-size: 13px; color: rgba(255,255,255,.4); font-style: italic; line-height: 1.6; }

  /* ─── RANDORI CITÁT ─── */
  .deti-page .randori { background: var(--red); color: #fff; text-align: center; padding: 96px 80px; }
  .deti-page .randori-eyebrow { font-size: 11px; letter-spacing: .22em; text-transform: uppercase; font-weight: 700; opacity: .8; margin-bottom: 24px; }
  .deti-page .randori-quote { font-family: var(--serif); font-size: clamp(28px, 3.4vw, 44px); font-weight: 300; font-style: italic; line-height: 1.25; max-width: 820px; margin: 0 auto 24px; }
  .deti-page .randori-body { font-size: 15px; font-weight: 300; opacity: .85; max-width: 560px; margin: 0 auto; line-height: 1.7; }

  /* ─── CTA ─── */
  .deti-page .cta { background: var(--bg); text-align: center; }
  .deti-page .cta .section-eyebrow { justify-content: center; }
  .deti-page .cta .section-eyebrow::before { display: none; }
  .deti-page .cta-title { font-family: var(--serif); font-size: clamp(30px, 3.6vw, 46px); font-weight: 300; line-height: 1.12; margin-bottom: 20px; }
  .deti-page .cta-body { font-size: 16px; color: var(--ink-mid); font-weight: 300; max-width: 560px; margin: 0 auto 36px; line-height: 1.75; }
  .deti-page .cta-actions { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }

  /* ─── POPUP S FORMULÁŘEM ─── */
  .inq-modal {
    position: fixed; inset: 0; z-index: 1000;
    background: rgba(20,18,14,.62); backdrop-filter: blur(4px);
    display: flex; align-items: flex-start; justify-content: center;
    padding: 64px 20px; overflow-y: auto;
  }
  .inq-modal-box {
    position: relative; width: 100%; max-width: 720px;
    background: var(--bg); border-top: 3px solid var(--red);
    box-shadow: 0 30px 90px rgba(0,0,0,.45);
  }
  .inq-modal-close {
    position: absolute; top: 12px; right: 12px; z-index: 5;
    width: 40px; height: 40px; background: none; border: none;
    font-size: 22px; line-height: 1; color: var(--ink-light); cursor: pointer;
    font-family: var(--sans); transition: color .2s;
  }
  .inq-modal-close:hover { color: var(--red); }
  /* Formulář v popupu: pod sebou, bez horního oddělovače a okraje. */
  .inq-modal .inquiry {
    margin-top: 0; border-top: none;
    grid-template-columns: 1fr; gap: 28px; padding: 44px;
  }

  @media (max-width: 1000px) {
    .deti-page .intro { grid-template-columns: 1fr; gap: 48px; }
    .deti-page .equip-grid, .deti-page .comp-grid { grid-template-columns: 1fr; gap: 40px; }
    .deti-page .groups-grid { grid-template-columns: 1fr; }
  }
  @media (max-width: 760px) {
    .deti-page .page-header { padding: 116px 26px 44px; }
    .deti-page .header-stats { gap: 28px; }
    .deti-page section { padding: 64px 26px; }
    .deti-page .randori { padding: 64px 26px; }
    .inq-modal { padding: 0; }
    .inq-modal-box { max-width: none; min-height: 100%; border-top: none; }
    .inq-modal .inquiry { padding: 56px 24px 36px; }
  }
</style>

{{-- NAV (sdílená komponenta) --}}
<x-ui.landing-nav />

{{-- HEADER --}}
<header class="page-header">
  <div class="breadcrumb">
    <a href="{{ route('home') }}">Úvod</a> <span>/</span>
    <span>Judo pro děti</span> <span>/</span>
    <span style="color:rgba(255,255,255,.65);">Tréninky dětí</span>
  </div>
  <div class="header-eyebrow">Judo pro děti</div>
  <h1 class="page-title">Tréninky dětí</h1>
  <p class="page-sub">Přijímáme děti od pěti let. První dva tréninky jsou zdarma — stačí přijít, podívat se a vyzkoušet si tatami. Bez závazků, hravou formou a vždy bezpečně.</p>
  <div class="header-stats">
    <div><div class="hstat-num">5+</div><div class="hstat-label">Věk od</div></div>
    <div><div class="hstat-num">2</div><div class="hstat-label">Tréninky zdarma</div></div>
    <div><div class="hstat-num">2</div><div class="hstat-label">Skupiny</div></div>
  </div>
</header>

{{-- INTRO + CAROUSEL --}}
<section class="intro">
  <div>
    <div class="section-eyebrow">Jak u nás děti trénují</div>
    <h2 class="section-title">Cesta začíná<br>na tatami</h2>
    <p class="lead">V našich oddílech přijímáme děti od <strong>pěti let věku</strong> a dále. První dva tréninky jsou <strong>zdarma</strong> — přijďte se nezávazně podívat. Cvičí se na boso na žíněnce (tatami), kterou společně postavíme a po tréninku zase uklidíme.</p>
    <p class="lead">Tréninky vždy přizpůsobujeme věku a úrovni dětí. I když jsme všichni na jednom prostoru, rozdělujeme je do dvou skupin — <em>přípravky</em> a <em>pokročilých</em> — a spojujeme se při společných činnostech jako rozcvička, hry nebo zápasy.</p>

    <div class="pickup">
      <svg width="26" height="26" viewBox="0 0 26 26" fill="none"><path d="M5 22v-8a8 8 0 0116 0v8" stroke="var(--red)" stroke-width="1.6" stroke-linecap="round"/><circle cx="13" cy="6" r="3.2" stroke="var(--red)" stroke-width="1.6"/></svg>
      <div>
        <div class="pickup-title">Vyzvedáváme z družiny</div>
        <div class="pickup-body">Děti vyzvedáváme přímo z družiny v Praze na ZŠ&nbsp;P.&nbsp;Strozziho a odvádíme je rovnou na trénink.</div>
      </div>
    </div>
  </div>

  <div class="carousel" data-carousel>
    <div class="carousel-frame">
      @foreach ($detiPhotos as $i => $photo)
        <div class="carousel-slide{{ $i === 0 ? ' active' : '' }}">
          <img src="{{ asset($photo) }}" alt="Děti na tréninku juda" loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
        </div>
      @endforeach
      <span class="carousel-cap">Z&nbsp;tréninků dětí</span>
      <button type="button" class="carousel-arrow carousel-prev" aria-label="Předchozí fotka">
        <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M12.5 4L6.5 10l6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>
      <button type="button" class="carousel-arrow carousel-next" aria-label="Další fotka">
        <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M7.5 4l6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>
    </div>
    <div class="carousel-dots">
      @foreach ($detiPhotos as $i => $photo)
        <button type="button" class="carousel-dot{{ $i === 0 ? ' active' : '' }}" aria-label="Fotka {{ $i + 1 }}"></button>
      @endforeach
    </div>
    <div class="carousel-note">Foto: z tréninků dětí JC Raion-Ryu</div>
  </div>
</section>

{{-- CO S SEBOU / GI --}}
<section class="equip">
  <div class="equip-grid">
    <div>
      <div class="section-eyebrow">Co si vzít s sebou</div>
      <h2 class="section-title">Na první trénink<br>stačí málo</h2>
      <ul class="equip-list">
        <li><span class="equip-num">01</span><span><strong>Přezůvky</strong> na cestu k tatami.</span></li>
        <li><span class="equip-num">02</span><span><strong>Triko „na zničení“</strong> a volné tepláky bez zipu.</span></li>
        <li><span class="equip-num">03</span><span><strong>Mikina</strong> na zimní období.</span></li>
        <li><span class="equip-num">04</span><span><strong>Pití</strong> — nejlépe voda nebo neslazený čaj.</span></li>
        <li><span class="equip-num">05</span><span>Cvičí se <strong>na boso</strong> přímo na tatami.</span></li>
      </ul>
    </div>
    <div class="gi-card">
      <div class="gi-eyebrow">Judo-Gi · kimono</div>
      <div class="gi-title">Kimono řešíme<br>až po přihlášce</div>
      <p class="gi-body">Úbor k tréninku judistů se nazývá <strong>Judo-Gi</strong>, známější jako kimono. Žák jej získá po vyplnění přihlášky a zaplacení tréninků.</p>
      <p class="gi-body">Kimona mezi sebou <strong>zdarma točíme</strong>. Pokud nebude k dispozici požadovaná velikost, zakoupíme nové přes vedoucího klubu p.&nbsp;Rubínka — to si zájemce hradí sám.</p>
      <p class="gi-body">Po vyplnění přihlášky vám zašleme variabilní symbol a informace k platbě na příslušné období.</p>
    </div>
  </div>
</section>

{{-- DVĚ SKUPINY --}}
<section>
  <div class="groups-intro">
    <div class="section-eyebrow">Dvě skupiny</div>
    <h2 class="section-title">Přípravka a pokročilí</h2>
    <p class="lead">Obě skupiny mají trénink přizpůsobený svým potřebám a věku. Spojené jsou při hromadných záležitostech — rozcvičce, hrách a zápasech.</p>
  </div>
  <div class="groups-grid">
    <div class="group-card">
      <span class="group-tag">Přípravka</span>
      <div class="group-name">Pro nejmenší</div>
      <p class="group-body">Zde se věnujeme těm nejmenším. Hodiny stavíme hravě a postupně — děti si zvykají na tatami a získávají základy.</p>
      <ul class="group-list">
        <li>Základní etika a pohyb po tatami</li>
        <li>Motorika, gymnastické a pohybové prvky</li>
        <li>Pády a průpravy na zemi i v postoji</li>
        <li>Základní techniky Judo na zemi i v postoji</li>
      </ul>
    </div>
    <div class="group-card">
      <span class="group-tag">Pokročilí</span>
      <div class="group-name">Vyšší principy juda</div>
      <p class="group-body">Zde je již vyžadována etika a zdvořilostní formy. Větší důraz klademe na úchop a pohyb po tatami.</p>
      <ul class="group-list">
        <li>Vyšší principy juda, kata a úniky</li>
        <li>Škrcení a páky</li>
        <li>Sebeobrana</li>
        <li>Randori — cvičný zápas s pravidly</li>
      </ul>
    </div>
  </div>
</section>

{{-- ZÁVODNÍ PŘÍPRAVA --}}
<section class="comp">
  <div class="comp-grid">
    <div>
      <div class="section-eyebrow">Závodní příprava</div>
      <h2 class="section-title">Závody nejsou<br>cílem — jsou cestou</h2>
      <p class="comp-body">V našem oddíle na závody jezdíme, ale ne v takové míře jako ostatní sportovní kluby. Pořádáme <strong>tréninkové srazy a campy</strong>, kde tato možnost je.</p>
      <p class="comp-body">Existují i klubové a oddílové závody mimo svaz, kterých se mohou zúčastnit i děti s&nbsp;bílým pásem.</p>
    </div>
    <div class="belt-card">
      <div class="belt-label">Minimální stupeň na závody svazu</div>
      <div class="belt-visual"></div>
      <div class="belt-grade">6/5. kyu</div>
      <div class="belt-sub">Bíložlutý pás. Pro závody svazu musí být uhrazena licence svazu na příslušný rok.</div>
      <div class="belt-note">Klubové a oddílové závody mimo svaz jsou otevřené i začínajícím judistům s bílým pásem.</div>
    </div>
  </div>
</section>

{{-- RANDORI --}}
<section class="randori">
  <div class="randori-eyebrow">Randori · cvičný zápas</div>
  <blockquote class="randori-quote">„Být lepší, než včera.“</blockquote>
  <p class="randori-body">Randori pořádáme většinou každý trénink. Jeho smyslem je zdokonalit se v daných technikách — a den za dnem růst.</p>
</section>

{{-- CTA --}}
<section class="cta">
  <div class="section-eyebrow">Přijďte na tatami</div>
  <h2 class="cta-title">První dva tréninky<br>jsou zdarma</h2>
  <p class="cta-body">Přihlaste dítě nezávazně. Po vyplnění přihlášky vám zašleme variabilní symbol a všechny informace k platbě na příslušné období.</p>
  <div class="cta-actions">
    <button type="button" class="btn-primary" @click="inquiry = true">Přihlásit dítě</button>
    <a href="{{ route('gallery') }}" class="btn-ghost">Galerie z tréninků</a>
  </div>
</section>

{{-- POPUP: poptávkový / přihlašovací formulář (stejný jako na úvodu) --}}
<div class="inq-modal" x-show="inquiry" x-cloak
     x-transition.opacity
     x-effect="document.body.style.overflow = inquiry ? 'hidden' : ''"
     @click.self="inquiry = false"
     role="dialog" aria-modal="true" aria-label="Přihlášení na trénink">
  <div class="inq-modal-box">
    <button type="button" class="inq-modal-close" @click="inquiry = false" aria-label="Zavřít">✕</button>
    <livewire:inquiry-form />
  </div>
</div>

{{-- FOOTER (sdílená komponenta) --}}
<x-ui.landing-footer />

<script>
  (function () {
    // Tělo skriptu Livewire přehraje při každé SPA navigaci – spustíme ho jen
    // jednou a globální listenery registrujeme jednou.
    if (window.__detiCarousels) return;
    window.__detiCarousels = true;

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
    // Po odchodu ze stránky zastavíme intervaly (DOM ještě existuje), ať neběží
    // nad odpojenými uzly.
    document.addEventListener('livewire:navigating', function () {
      document.querySelectorAll('[data-carousel]').forEach(function (root) {
        if (root.__carouselTimer) clearInterval(root.__carouselTimer);
      });
    });
  })();
</script>

</div>
