<?php
use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.landing')]
#[Title('Klub – ke stažení | JC Raion-Ryu')]
class extends Component {}; ?>

<div class="dl-page">

<style>
  /* ─── Stránka „Klub – ke stažení" ────────────────────────────────────────
     Styly scopované pod .dl-page; navbar a footer jsou sdílené komponenty.
     .dl-page je flex sloupec přes celou výšku → footer drží u spodního okraje
     i u krátké stránky. */
  .dl-page { min-height: 100vh; display: flex; flex-direction: column; }
  .dl-page svg { display: block; }

  /* ─── HEADER ─── */
  .dl-page .page-header {
    padding: 140px 80px 56px;
    background: var(--bg-dark); color: #fff;
    position: relative; overflow: hidden;
  }
  .dl-page .page-header-kanji {
    position: absolute; right: 40px; top: 50%; transform: translateY(-46%);
    font-family: var(--serif); font-size: 220px; line-height: 1;
    color: rgba(255,255,255,.035); font-weight: 700; pointer-events: none; user-select: none;
  }
  .dl-page .breadcrumb {
    font-size: 11px; letter-spacing: .15em; text-transform: uppercase;
    color: rgba(255,255,255,.4); margin-bottom: 22px;
    display: flex; gap: 8px; align-items: center; flex-wrap: wrap; position: relative; z-index: 1;
  }
  .dl-page .breadcrumb a { color: rgba(255,255,255,.4); text-decoration: none; transition: color .2s; }
  .dl-page .breadcrumb a:hover { color: var(--red); }
  .dl-page .header-eyebrow {
    font-size: 11px; letter-spacing: .2em; text-transform: uppercase;
    color: var(--red); font-weight: 600; margin-bottom: 18px;
    display: flex; align-items: center; gap: 12px; position: relative; z-index: 1;
  }
  .dl-page .header-eyebrow::before { content:''; display:block; width:32px; height:1px; background:var(--red); }
  .dl-page .page-title {
    font-family: var(--serif); font-size: clamp(36px, 4.4vw, 58px);
    font-weight: 300; line-height: 1.08; color: #fff; margin-bottom: 16px; position: relative; z-index: 1;
  }
  .dl-page .page-sub { font-size: 16px; color: rgba(255,255,255,.5); font-weight: 300; max-width: 540px; line-height: 1.7; position: relative; z-index: 1; }

  /* ─── DOWNLOADS ─── */
  .dl-page main { flex: 1; padding: 72px 80px 100px; }
  .dl-page .dl-group { margin-bottom: 64px; }
  .dl-page .dl-group:last-child { margin-bottom: 0; }
  .dl-page .group-head {
    display: flex; align-items: baseline; gap: 16px; margin-bottom: 28px;
    border-bottom: 2px solid var(--ink); padding-bottom: 16px;
  }
  .dl-page .group-num { font-family: var(--serif); font-size: 14px; color: var(--red); font-weight: 700; }
  .dl-page .group-title { font-family: var(--serif); font-size: 26px; font-weight: 400; line-height: 1.1; }
  .dl-page .group-count { margin-left: auto; font-size: 11px; letter-spacing: .12em; text-transform: uppercase; color: var(--ink-light); font-weight: 600; }

  .dl-page .dl-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2px; background: var(--rule); border: 1px solid var(--rule); }
  .dl-page .dl-grid.single { grid-template-columns: 1fr; }
  .dl-page .dl-item {
    display: flex; align-items: center; gap: 20px;
    padding: 22px 26px; background: var(--bg);
    text-decoration: none; color: var(--ink); transition: background .2s;
  }
  .dl-page .dl-item:hover { background: var(--bg-dark); }
  .dl-page .dl-item:hover .dl-name { color: #fff; }
  .dl-page .dl-item:hover .dl-meta { color: rgba(255,255,255,.45); }
  .dl-page .dl-item:hover .dl-icon { background: transparent; border-color: rgba(255,255,255,.45); }
  .dl-page .dl-item:hover .dl-icon svg { stroke: #fff; }
  .dl-page .dl-item:hover .dl-arrow { color: #fff; transform: translateY(2px); }

  .dl-page .dl-icon {
    flex-shrink: 0; width: 46px; height: 46px;
    border: 1.5px solid var(--rule); display: flex; align-items: center; justify-content: center;
    transition: all .2s; position: relative;
  }
  .dl-page .dl-ext {
    position: absolute; bottom: -1px; right: -1px;
    font-size: 7px; font-weight: 700; letter-spacing: .04em;
    background: var(--red); color: #fff; padding: 1px 3px; line-height: 1;
  }
  .dl-page .dl-text { flex: 1; min-width: 0; }
  .dl-page .dl-name { display: block; font-size: 15px; font-weight: 500; line-height: 1.35; transition: color .2s; }
  .dl-page .dl-meta { display: block; font-size: 12px; color: var(--ink-light); margin-top: 3px; transition: color .2s; }
  .dl-page .dl-arrow { flex-shrink: 0; font-size: 18px; color: var(--ink-light); transition: all .2s; }

  .dl-page .dl-item.external .dl-icon { border-style: dashed; }
  .dl-page .dl-item.external:hover .dl-icon { background: var(--ink); border-color: var(--ink); }

  .dl-page .note {
    margin-top: 8px; font-size: 13px; color: var(--ink-light);
    display: flex; align-items: center; gap: 10px; font-weight: 300;
  }
  .dl-page .note::before { content:''; display:block; width:18px; height:1px; background:var(--red); }

  @media (max-width: 900px) {
    .dl-page .page-header { padding: 120px 28px 44px; }
    .dl-page main { padding: 48px 28px 72px; }
    .dl-page .dl-grid, .dl-page .dl-grid.single { grid-template-columns: 1fr; }
  }
</style>

{{-- NAV (sdílená komponenta) --}}
<x-ui.landing-nav />

{{-- HEADER --}}
<header class="page-header">
  <div class="breadcrumb">
    <a href="{{ route('home') }}">Úvod</a> <span>/</span>
    <span>Klub</span> <span>/</span>
    <span style="color:rgba(255,255,255,.65);">Ke stažení</span>
  </div>
  <div class="header-eyebrow">Klub</div>
  <h1 class="page-title">Ke stažení</h1>
  <p class="page-sub">Přihlášky, klubové dokumenty a studijní materiály k technikám. Soubory ve formátu PDF — stačí kliknout a stáhnout.</p>
</header>

{{-- DOWNLOADS --}}
@php
  $pdfIcon = '<svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M5 2h7l3 3v13H5z" stroke="#4A4540" stroke-width="1.4" stroke-linejoin="round"/><path d="M12 2v3h3" stroke="#4A4540" stroke-width="1.4" stroke-linejoin="round"/></svg>';
  $extIcon = '<svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M8 4H4v12h12v-4" stroke="#4A4540" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M11 4h5v5M16 4l-7 7" stroke="#4A4540" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>';
@endphp

<main>

  {{-- SKUPINA 1: Přihlášky & dokumenty --}}
  <div class="dl-group">
    <div class="group-head">
      <span class="group-num">01</span>
      <h2 class="group-title">Přihlášky &amp; klubové dokumenty</h2>
      <span class="group-count">4 soubory</span>
    </div>
    <div class="dl-grid">
      <a class="dl-item" href="{{ asset('dokumenty/prihlaska_Judo_club.pdf') }}" target="_blank" rel="noopener">
        <span class="dl-icon">{!! $pdfIcon !!}<span class="dl-ext">PDF</span></span>
        <span class="dl-text"><span class="dl-name">Přihláška na judo — Praha</span><span class="dl-meta">Přihláška do oddílu · Praha 8</span></span>
        <span class="dl-arrow">↓</span>
      </a>
      <a class="dl-item" href="{{ asset('dokumenty/prihlaska_Judo_club.pdf') }}" target="_blank" rel="noopener">
        <span class="dl-icon">{!! $pdfIcon !!}<span class="dl-ext">PDF</span></span>
        <span class="dl-text"><span class="dl-name">Přihláška na judo — Vodochody</span><span class="dl-meta">Přihláška do oddílu · Vodochody</span></span>
        <span class="dl-arrow">↓</span>
      </a>
      <a class="dl-item" href="{{ asset('dokumenty/JCRR-GDPR.pdf') }}" target="_blank" rel="noopener">
        <span class="dl-icon">{!! $pdfIcon !!}<span class="dl-ext">PDF</span></span>
        <span class="dl-text"><span class="dl-name">GDPR JC Raion-Ryu</span><span class="dl-meta">Ochrana osobních údajů klubu</span></span>
        <span class="dl-arrow">↓</span>
      </a>
      <a class="dl-item" href="{{ asset('dokumenty/etika.pdf') }}" target="_blank" rel="noopener">
        <span class="dl-icon">{!! $pdfIcon !!}<span class="dl-ext">PDF</span></span>
        <span class="dl-text"><span class="dl-name">Zásady chování v dojo</span><span class="dl-meta">Etika a pravidla tréninku</span></span>
        <span class="dl-arrow">↓</span>
      </a>
    </div>
  </div>

  {{-- SKUPINA 2: Studijní materiály --}}
  <div class="dl-group">
    <div class="group-head">
      <span class="group-num">02</span>
      <h2 class="group-title">Studijní materiály — techniky</h2>
      <span class="group-count">6 souborů</span>
    </div>
    <div class="dl-grid">
      <a class="dl-item" href="{{ asset('dokumenty/GOkyo.pdf') }}" target="_blank" rel="noopener">
        <span class="dl-icon">{!! $pdfIcon !!}<span class="dl-ext">PDF</span></span>
        <span class="dl-text"><span class="dl-name">Go-Kyo</span><span class="dl-meta">Soubor základních technik v postoji</span></span>
        <span class="dl-arrow">↓</span>
      </a>
      <a class="dl-item" href="{{ asset('dokumenty/osaekomiwaza.pdf') }}" target="_blank" rel="noopener">
        <span class="dl-icon">{!! $pdfIcon !!}<span class="dl-ext">PDF</span></span>
        <span class="dl-text"><span class="dl-name">Techniky na zemi — rozdělení</span><span class="dl-meta">Základní rozdělení Ne-waza</span></span>
        <span class="dl-arrow">↓</span>
      </a>
      <a class="dl-item" href="{{ asset('dokumenty/katamewaza.pdf') }}" target="_blank" rel="noopener">
        <span class="dl-icon">{!! $pdfIcon !!}<span class="dl-ext">PDF</span></span>
        <span class="dl-text"><span class="dl-name">Techniky znehybnění</span><span class="dl-meta">Katame-waza · znehybnění soupeře</span></span>
        <span class="dl-arrow">↓</span>
      </a>
      <a class="dl-item" href="{{ asset('dokumenty/renkohowaza.pdf') }}" target="_blank" rel="noopener">
        <span class="dl-icon">{!! $pdfIcon !!}<span class="dl-ext">PDF</span></span>
        <span class="dl-text"><span class="dl-name">Odváděcí techniky (policejní)</span><span class="dl-meta">Renkoho-waza</span></span>
        <span class="dl-arrow">↓</span>
      </a>
      <a class="dl-item" href="{{ asset('dokumenty/nagewaza.pdf') }}" target="_blank" rel="noopener">
        <span class="dl-icon">{!! $pdfIcon !!}<span class="dl-ext">PDF</span></span>
        <span class="dl-text"><span class="dl-name">Kompletní přehled technik 1.–5. kyu</span><span class="dl-meta">Nage-waza · zkušební řád</span></span>
        <span class="dl-arrow">↓</span>
      </a>
      <a class="dl-item" href="{{ asset('dokumenty/slovnicek.pdf') }}" target="_blank" rel="noopener">
        <span class="dl-icon">{!! $pdfIcon !!}<span class="dl-ext">PDF</span></span>
        <span class="dl-text"><span class="dl-name">Slovníček pojmů</span><span class="dl-meta">Japonské výrazy používané v dojo</span></span>
        <span class="dl-arrow">↓</span>
      </a>
    </div>
  </div>

  {{-- SKUPINA 3: Externí odkazy ČSJu --}}
  <div class="dl-group">
    <div class="group-head">
      <span class="group-num">03</span>
      <h2 class="group-title">Externí odkazy — ČSJu</h2>
      <span class="group-count">2 odkazy</span>
    </div>
    <div class="dl-grid">
      <a class="dl-item external" href="http://www.czechjudo.org/gdpr-informacni-memorandum" target="_blank" rel="noopener">
        <span class="dl-icon">{!! $extIcon !!}</span>
        <span class="dl-text"><span class="dl-name">Informace GDPR ČSJu</span><span class="dl-meta">czechjudo.org · informační memorandum</span></span>
        <span class="dl-arrow">↗</span>
      </a>
      <a class="dl-item external" href="http://www.czechjudo.org/Files/1/Documents/lexikon/Sm%C4%9Brnice%20%C4%8CSJu%20o%20zdravotn%C3%AD%20zp%C5%AFsobilosti%20aktivn%C3%ADch%20%C4%8Dlen%C5%AF%20%C4%8CSJu.pdf" target="_blank" rel="noopener">
        <span class="dl-icon">{!! $extIcon !!}</span>
        <span class="dl-text"><span class="dl-name">Směrnice o zdravotní způsobilosti — důležité!</span><span class="dl-meta">ČSJu · zdravotní způsobilost aktivních členů</span></span>
        <span class="dl-arrow">↗</span>
      </a>
    </div>
    <p class="note">Externí odkazy vedou na stránky Českého svazu juda (czechjudo.org).</p>
  </div>

</main>

{{-- FOOTER (sdílená komponenta) --}}
<x-ui.landing-footer />

</div>
