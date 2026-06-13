<?php

use App\Models\Price;
use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;

new #[Layout('components.layouts.landing', [
    'metaDescription' => 'Ceník členských příspěvků Judo Clubu Raion-ryu — judo Praha 8, judo Vodochody a Hiko-ryu Taijutsu. První dva tréninky zdarma.',
])]
#[Title('Ceník | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        return [
            'prices' => Price::visible()->ordered()->get(),
        ];
    }
}; ?>

<div class="cenik-page">

<style>
  /* ─── Stránka „Ceník" ────────────────────────────────────────────────────
     Styly scopované pod .cenik-page; navbar a footer jsou sdílené komponenty.
     Flex sloupec přes celou výšku → footer drží dole i u krátké stránky. */
  .cenik-page { min-height: 100vh; display: flex; flex-direction: column; }
  .cenik-page svg { display: block; }

  /* ─── HEADER ─── */
  .cenik-page .page-header {
    padding: 140px 80px 56px;
    background: var(--bg-dark); color: #fff;
    position: relative; overflow: hidden;
  }
  .cenik-page .breadcrumb {
    font-size: 11px; letter-spacing: .15em; text-transform: uppercase;
    color: rgba(255,255,255,.4); margin-bottom: 22px;
    display: flex; gap: 8px; align-items: center; flex-wrap: wrap; position: relative; z-index: 1;
  }
  .cenik-page .breadcrumb a { color: rgba(255,255,255,.4); text-decoration: none; transition: color .2s; }
  .cenik-page .breadcrumb a:hover { color: var(--red); }
  .cenik-page .header-eyebrow {
    font-size: 11px; letter-spacing: .2em; text-transform: uppercase;
    color: var(--red); font-weight: 600; margin-bottom: 18px;
    display: flex; align-items: center; gap: 12px; position: relative; z-index: 1;
  }
  .cenik-page .header-eyebrow::before { content:''; display:block; width:32px; height:1px; background:var(--red); }
  .cenik-page .page-title {
    font-family: var(--serif); font-size: clamp(36px, 4.4vw, 58px);
    font-weight: 300; line-height: 1.08; color: #fff; margin-bottom: 16px; position: relative; z-index: 1;
  }
  .cenik-page .page-sub { font-size: 16px; color: rgba(255,255,255,.5); font-weight: 300; max-width: 560px; line-height: 1.7; position: relative; z-index: 1; }

  /* ─── OBSAH ─── */
  .cenik-page main { flex: 1; padding: 72px 80px 100px; }

  .cenik-page .price-grid {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 2px; background: var(--rule); border: 1px solid var(--rule);
  }
  .cenik-page .price-card {
    background: var(--bg); padding: 44px 38px 40px;
    display: flex; flex-direction: column; gap: 6px;
    transition: background .2s; position: relative;
  }
  .cenik-page .price-card:hover { background: #F0EDE8; }
  .cenik-page .price-name { font-family: var(--serif); font-size: 22px; font-weight: 400; line-height: 1.2; }
  .cenik-page .price-rule { width: 34px; height: 2px; background: var(--red); margin: 14px 0 18px; }
  .cenik-page .price-amount { font-family: var(--serif); font-size: clamp(38px, 4vw, 52px); font-weight: 300; line-height: 1; }
  .cenik-page .price-period {
    margin-top: 10px; font-size: 11px; letter-spacing: .14em;
    text-transform: uppercase; color: var(--ink-light); font-weight: 600;
  }
  .cenik-page .price-note { margin-top: 14px; font-size: 13px; color: var(--ink-mid); font-weight: 300; line-height: 1.6; }

  .cenik-page .cenik-empty {
    border: 1px dashed var(--rule); padding: 42px 28px; text-align: center;
    font-size: 13px; letter-spacing: .08em; text-transform: uppercase; color: var(--ink-light);
  }

  /* ─── POZNÁMKY + CTA ─── */
  .cenik-page .cenik-notes {
    margin-top: 56px; display: grid; grid-template-columns: 1.4fr 1fr;
    gap: 48px; align-items: start;
  }
  .cenik-page .note-line {
    font-size: 14px; color: var(--ink-mid); font-weight: 300; line-height: 1.8;
    display: flex; gap: 12px; align-items: baseline; padding: 10px 0;
    border-bottom: 1px solid var(--rule);
  }
  .cenik-page .note-line:last-child { border-bottom: none; }
  .cenik-page .note-line::before { content: '—'; color: var(--red); flex-shrink: 0; }

  .cenik-page .cenik-cta {
    background: var(--bg-dark); color: #fff; padding: 36px 34px;
  }
  .cenik-page .cenik-cta h3 { font-family: var(--serif); font-size: 22px; font-weight: 300; line-height: 1.3; margin-bottom: 10px; }
  .cenik-page .cenik-cta p { font-size: 13px; color: rgba(255,255,255,.5); font-weight: 300; line-height: 1.7; margin-bottom: 22px; }
  .cenik-page .cenik-cta a {
    display: inline-block; background: var(--red); color: #fff; text-decoration: none;
    padding: 13px 24px; font-size: 11px; font-weight: 600;
    letter-spacing: .12em; text-transform: uppercase; transition: background .25s;
  }
  .cenik-page .cenik-cta a:hover { background: var(--red-muted); }

  @media (max-width: 1000px) {
    .cenik-page .price-grid { grid-template-columns: 1fr; }
    .cenik-page .cenik-notes { grid-template-columns: 1fr; }
  }
  @media (max-width: 900px) {
    .cenik-page .page-header { padding: 120px 28px 44px; }
    .cenik-page main { padding: 48px 28px 72px; }
  }
</style>

{{-- NAV (sdílená komponenta) --}}
<x-ui.landing-nav />

{{-- HEADER --}}
<header class="page-header">
  <div class="breadcrumb">
    <a href="{{ route('home') }}" wire:navigate>Úvod</a> <span>/</span>
    <span style="color:rgba(255,255,255,.65);">Ceník</span>
  </div>
  <div class="header-eyebrow">Klub</div>
  <h1 class="page-title">Ceník</h1>
  <p class="page-sub">Členské příspěvky za trénování v oddíle. První dva tréninky jsou zdarma — přijďte si to nezávazně vyzkoušet.</p>
</header>

<main>

  @if ($prices->isEmpty())
    <div class="cenik-empty">Ceník právě aktualizujeme — napište nám a rádi poradíme.</div>
  @else
    <div class="price-grid">
      @foreach ($prices as $price)
        <article class="price-card" wire:key="price-{{ $price->id }}">
          <h2 class="price-name">{{ $price->title }}</h2>
          <div class="price-rule"></div>
          <div class="price-amount">{{ $price->amountLabel() }}</div>
          <div class="price-period">/ {{ $price->period }}</div>
          @if ($price->note)
            <p class="price-note">{{ $price->note }}</p>
          @endif
        </article>
      @endforeach
    </div>
  @endif

  <div class="cenik-notes">
    <div>
      <div class="note-line">Příspěvky se hradí na začátku období na číslo účtu <strong>2703495387/2010</strong>. Variabilní symbol vám sdělíme po přihlášení.</div>
      <div class="note-line">Přihlášku a další dokumenty najdete v sekci <a href="{{ route('downloads') }}" wire:navigate style="color: var(--red);">Ke stažení</a>.</div>
      <div class="note-line">Rozvrh tréninků a objednání zkušebního tréninku najdete na <a href="{{ route('home') }}#rozvrh" style="color: var(--red);">úvodní stránce</a>.</div>
    </div>
    <div class="cenik-cta">
      <h3>Nejste si jistí?</h3>
      <p>Napište nám — poradíme s výběrem skupiny i termínu. A první dva tréninky máte zdarma.</p>
      <a href="{{ route('home') }}#kontakt">Napište nám</a>
    </div>
  </div>

</main>

{{-- FOOTER (sdílená komponenta) --}}
<x-ui.landing-footer />

</div>
