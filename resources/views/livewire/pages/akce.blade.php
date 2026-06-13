<?php

use App\Models\Event;
use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;

new #[Layout('components.layouts.landing', [
    'metaDescription' => 'Plánované akce Judo Clubu Raion-ryu — soustředění, turnaje, semináře a pobyty japonských mistrů. Termíny, místa konání a archiv proběhlých akcí.',
])]
#[Title('Akce | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        return [
            'upcoming' => Event::upcoming()->get(),
            'past' => Event::past()->take(12)->get(),
            'pastTotal' => Event::past()->count(),
        ];
    }
}; ?>

<div class="akce-page"
     x-data="{
       inquiry: false,
       ask(msg) {
         this.inquiry = true;
         Livewire.dispatch('inquiry-prefill', { trainingType: 'Obecný dotaz', message: msg });
       },
     }"
     @keydown.escape.window="inquiry = false">

<style>
  /* ─── Stránka „Akce" ─────────────────────────────────────────────────────
     Styly scopované pod .akce-page; navbar a footer jsou sdílené komponenty.
     Flex sloupec přes celou výšku → footer drží dole i u krátké stránky. */
  .akce-page { min-height: 100vh; display: flex; flex-direction: column; }
  .akce-page svg { display: block; }

  /* ─── HEADER ─── */
  .akce-page .page-header {
    padding: 140px 80px 56px;
    background: var(--bg-dark); color: #fff;
    position: relative; overflow: hidden;
  }
  .akce-page .breadcrumb {
    font-size: 11px; letter-spacing: .15em; text-transform: uppercase;
    color: rgba(255,255,255,.4); margin-bottom: 22px;
    display: flex; gap: 8px; align-items: center; flex-wrap: wrap; position: relative; z-index: 1;
  }
  .akce-page .breadcrumb a { color: rgba(255,255,255,.4); text-decoration: none; transition: color .2s; }
  .akce-page .breadcrumb a:hover { color: var(--red); }
  .akce-page .header-eyebrow {
    font-size: 11px; letter-spacing: .2em; text-transform: uppercase;
    color: var(--red); font-weight: 600; margin-bottom: 18px;
    display: flex; align-items: center; gap: 12px; position: relative; z-index: 1;
  }
  .akce-page .header-eyebrow::before { content:''; display:block; width:32px; height:1px; background:var(--red); }
  .akce-page .page-title {
    font-family: var(--serif); font-size: clamp(36px, 4.4vw, 58px);
    font-weight: 300; line-height: 1.08; color: #fff; margin-bottom: 16px; position: relative; z-index: 1;
  }
  .akce-page .page-sub { font-size: 16px; color: rgba(255,255,255,.5); font-weight: 300; max-width: 560px; line-height: 1.7; position: relative; z-index: 1; }

  /* ─── OBSAH ─── */
  .akce-page main { flex: 1; padding: 72px 80px 100px; }
  .akce-page .ak-group { margin-bottom: 64px; }
  .akce-page .ak-group:last-child { margin-bottom: 0; }
  .akce-page .group-head {
    display: flex; align-items: baseline; gap: 16px; margin-bottom: 28px;
    border-bottom: 2px solid var(--ink); padding-bottom: 16px;
  }
  .akce-page .group-num { font-family: var(--serif); font-size: 14px; color: var(--red); font-weight: 700; }
  .akce-page .group-title { font-family: var(--serif); font-size: 26px; font-weight: 400; line-height: 1.1; }
  .akce-page .group-count { margin-left: auto; font-size: 11px; letter-spacing: .12em; text-transform: uppercase; color: var(--ink-light); font-weight: 600; }

  /* ─── Nadcházející akce ─── */
  .akce-page .ak-list { display: flex; flex-direction: column; gap: 2px; background: var(--rule); border: 1px solid var(--rule); }
  .akce-page .ak-item {
    display: flex; align-items: center; gap: 28px;
    padding: 26px 30px; background: var(--bg); transition: background .2s;
  }
  .akce-page .ak-item:hover { background: #F0EDE8; }
  .akce-page .ak-date { text-align: center; min-width: 68px; flex-shrink: 0; }
  .akce-page .ak-day { display: block; font-family: var(--serif); font-size: 34px; font-weight: 300; line-height: 1; }
  .akce-page .ak-month { display: block; font-size: 10px; letter-spacing: .18em; text-transform: uppercase; color: var(--red); margin-top: 5px; font-weight: 600; }
  .akce-page .ak-rule { width: 1px; align-self: stretch; background: var(--rule); flex-shrink: 0; }
  .akce-page .ak-body { flex: 1; min-width: 0; }
  .akce-page .ak-title { font-family: var(--serif); font-size: 21px; font-weight: 400; line-height: 1.25; }
  .akce-page .ak-meta { font-size: 12.5px; color: var(--ink-light); margin-top: 5px; display: flex; gap: 18px; flex-wrap: wrap; }
  .akce-page .ak-desc { margin-top: 10px; font-size: 13.5px; line-height: 1.7; color: var(--ink-mid); font-weight: 300; max-width: 640px; }
  .akce-page .ak-tag {
    flex-shrink: 0; align-self: flex-start; margin-top: 4px;
    font-size: 9px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase;
    padding: 5px 10px; white-space: nowrap;
  }
  .akce-page .ak-tag.red { background: var(--red); color: #fff; }
  .akce-page .ak-tag.dark { background: var(--ink); color: #fff; }
  .akce-page .ak-tag.line { border: 1px solid var(--rule); color: var(--ink-mid); }
  .akce-page .ak-tag.faint { background: rgba(28,25,20,.07); color: var(--ink-mid); }

  /* hlavní akce — tmavá zvýrazněná karta */
  .akce-page .ak-item.main { background: var(--bg-dark); color: #fff; position: relative; overflow: hidden; }
  .akce-page .ak-item.main:hover { background: #14110D; }
  .akce-page .ak-item.main .ak-title { color: #fff; }
  .akce-page .ak-item.main .ak-meta { color: rgba(255,255,255,.45); }
  .akce-page .ak-item.main .ak-desc { color: rgba(255,255,255,.55); }
  .akce-page .ak-item.main .ak-day { color: #fff; }

  /* ─── Proběhlé akce ─── */
  .akce-page .ak-past { display: grid; grid-template-columns: 1fr 1fr; gap: 2px; background: var(--rule); border: 1px solid var(--rule); }
  .akce-page .ak-past-row {
    display: flex; align-items: baseline; gap: 14px;
    padding: 18px 24px; background: var(--bg); transition: background .2s;
  }
  .akce-page .ak-past-row:hover { background: #F0EDE8; }
  .akce-page .ak-past-date { font-size: 11px; letter-spacing: .08em; color: var(--red); font-weight: 600; white-space: nowrap; min-width: 118px; }
  .akce-page .ak-past-title { font-size: 14.5px; font-weight: 500; }
  .akce-page .ak-past-place { font-size: 12px; color: var(--ink-light); margin-left: auto; text-align: right; }
  .akce-page .note {
    margin-top: 12px; font-size: 13px; color: var(--ink-light);
    display: flex; align-items: center; gap: 10px; font-weight: 300;
  }
  .akce-page .note::before { content:''; display:block; width:18px; height:1px; background:var(--red); }

  .akce-page .ak-empty {
    border: 1px dashed var(--rule); padding: 42px 28px; text-align: center;
    font-size: 13px; letter-spacing: .08em; text-transform: uppercase; color: var(--ink-light);
  }

  /* tlačítko „Zeptat se na akci" */
  .akce-page .ak-contact {
    margin-top: 12px; background: transparent; border: none; padding: 0;
    color: var(--red); font-family: var(--sans); font-size: 12px; font-weight: 600;
    letter-spacing: .08em; text-transform: uppercase; cursor: pointer; transition: color .2s;
  }
  .akce-page .ak-contact:hover { color: var(--red-muted); }
  .akce-page .ak-item.main .ak-contact { color: #fff; }
  .akce-page .ak-item.main .ak-contact:hover { color: rgba(255,255,255,.7); }
  .akce-page .ak-past-contact {
    background: transparent; border: none; padding: 0; margin-left: 14px;
    color: var(--red); font-size: 11px; font-weight: 600; letter-spacing: .06em;
    text-transform: uppercase; cursor: pointer; white-space: nowrap; transition: color .2s;
  }
  .akce-page .ak-past-contact:hover { color: var(--red-muted); }

  /* ─── Příloha ke stažení ─── */
  .akce-page .ak-file {
    display: inline-flex; align-items: center; gap: 9px; margin-top: 14px;
    font-size: 13px; font-weight: 600; color: var(--red); text-decoration: none;
    border: 1px solid var(--rule); padding: 9px 15px; transition: border-color .2s, background .2s;
  }
  .akce-page .ak-file:hover { border-color: var(--red); background: #fff; }
  .akce-page .ak-file svg { flex-shrink: 0; }
  .akce-page .ak-file em { font-style: normal; font-weight: 400; color: var(--ink-light); }
  .akce-page .ak-item.main .ak-file { color: #fff; border-color: rgba(255,255,255,.28); }
  .akce-page .ak-item.main .ak-file:hover { border-color: #fff; background: rgba(255,255,255,.08); }
  .akce-page .ak-item.main .ak-file em { color: rgba(255,255,255,.5); }
  /* kompaktní odkaz u proběhlých akcí */
  .akce-page .ak-past-file {
    margin-left: 14px; color: var(--red); font-size: 11px; font-weight: 600;
    letter-spacing: .06em; text-transform: uppercase; text-decoration: none;
    white-space: nowrap; transition: color .2s;
  }
  .akce-page .ak-past-file:hover { color: var(--red-muted); }

  /* ─── POPUP S FORMULÁŘEM (vzor ze stránky Tréninky dětí) ─── */
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

  @media (max-width: 900px) {
    .akce-page .page-header { padding: 120px 28px 44px; }
    .akce-page main { padding: 48px 28px 72px; }
    .akce-page .ak-item { flex-wrap: wrap; gap: 16px; padding: 22px 20px; }
    .akce-page .ak-rule { display: none; }
    .akce-page .ak-past { grid-template-columns: 1fr; }
    .akce-page .ak-past-row { flex-wrap: wrap; }
    .akce-page .ak-past-place { margin-left: 0; text-align: left; width: 100%; }
  }
</style>

{{-- NAV (sdílená komponenta) --}}
<x-ui.landing-nav />

{{-- HEADER --}}
<header class="page-header">
  <div class="breadcrumb">
    <a href="{{ route('home') }}" wire:navigate>Úvod</a> <span>/</span>
    <span style="color:rgba(255,255,255,.65);">Akce</span>
  </div>
  <div class="header-eyebrow">Klub</div>
  <h1 class="page-title">Akce klubu</h1>
  <p class="page-sub">Soustředění, turnaje, semináře a pobyty japonských mistrů. Co nás čeká — a co už máme za sebou.</p>
</header>

<main>

  {{-- SKUPINA 1: Nadcházející --}}
  <div class="ak-group">
    <div class="group-head">
      <span class="group-num">01</span>
      <h2 class="group-title">Nadcházející akce</h2>
      <span class="group-count">{{ $upcoming->count() }} {{ $upcoming->count() === 1 ? 'akce' : ($upcoming->count() >= 2 && $upcoming->count() <= 4 ? 'akce' : 'akcí') }}</span>
    </div>

    @if ($upcoming->isEmpty())
      <div class="ak-empty">Aktuálně nejsou naplánované žádné akce — sledujte tuto stránku.</div>
    @else
      <div class="ak-list">
        @foreach ($upcoming as $event)
          <article class="ak-item {{ $event->is_main ? 'main' : '' }}">
            <div class="ak-date">
              <span class="ak-day">{{ $event->day() }}</span>
              <span class="ak-month">{{ $event->monthAbbr() }}</span>
            </div>
            <div class="ak-rule"></div>
            <div class="ak-body">
              <h3 class="ak-title">{{ $event->title }}</h3>
              <div class="ak-meta">
                <span>{{ $event->dateRange() }}</span>
                @if ($event->place)<span>{{ $event->place }}</span>@endif
                @if ($event->note)<span>{{ $event->note }}</span>@endif
              </div>
              @if ($event->description)
                <p class="ak-desc">{{ $event->description }}</p>
              @endif
              @if ($event->hasAttachment())
                <div>
                  <a class="ak-file" href="{{ $event->attachmentHref() }}">
                    <svg width="15" height="15" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M10 3v10m0 0l-3.6-3.6M10 13l3.6-3.6M4 16.5h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span>Stáhnout: {{ $event->attachment_name }}</span>
                    @if ($event->attachmentSizeLabel())<em>· {{ $event->attachmentSizeLabel() }}</em>@endif
                  </a>
                </div>
              @endif
              <button type="button" class="ak-contact"
                      data-msg="Dotaz k akci „{{ $event->title }}&quot; ({{ $event->dateRange() }}): "
                      @click="ask($el.dataset.msg)">Zeptat se na akci &rarr;</button>
            </div>
            <span class="ak-tag {{ $event->tagClass() }}">{{ $event->tagLabel() }}</span>
          </article>
        @endforeach
      </div>
    @endif
  </div>

  {{-- SKUPINA 2: Proběhlé --}}
  <div class="ak-group">
    <div class="group-head">
      <span class="group-num">02</span>
      <h2 class="group-title">Proběhlé akce</h2>
      <span class="group-count">{{ $pastTotal }} celkem</span>
    </div>

    @if ($past->isEmpty())
      <div class="ak-empty">Archiv akcí se teprve plní.</div>
    @else
      <div class="ak-past">
        @foreach ($past as $event)
          <div class="ak-past-row">
            <span class="ak-past-date">{{ $event->dateRange() }}</span>
            <span class="ak-past-title">{{ $event->title }}</span>
            @if ($event->place)<span class="ak-past-place">{{ $event->place }}</span>@endif
            @if ($event->hasAttachment())<a class="ak-past-file" href="{{ $event->attachmentHref() }}">{{ $event->attachmentExt() }} &darr;</a>@endif
            <button type="button" class="ak-past-contact"
                    data-msg="Dotaz k akci „{{ $event->title }}&quot; ({{ $event->dateRange() }}): "
                    @click="ask($el.dataset.msg)">Dotaz</button>
          </div>
        @endforeach
      </div>
      @if ($pastTotal > $past->count())
        <p class="note">Zobrazujeme posledních {{ $past->count() }} akcí. Fotky z proběhlých akcí najdete v <a href="{{ route('gallery') }}" wire:navigate style="color: var(--red);">galerii</a>.</p>
      @else
        <p class="note">Fotky z proběhlých akcí najdete v <a href="{{ route('gallery') }}" wire:navigate style="color: var(--red);">galerii</a>.</p>
      @endif
    @endif
  </div>

</main>

{{-- POPUP S DOTAZEM K AKCI --}}
<div class="inq-modal" x-show="inquiry" x-cloak
     x-transition.opacity.duration.200ms
     x-effect="document.body.style.overflow = inquiry ? 'hidden' : ''"
     @click.self="inquiry = false"
     role="dialog" aria-modal="true" aria-label="Dotaz k akci">
  <div class="inq-modal-box">
    <button type="button" class="inq-modal-close" @click="inquiry = false" aria-label="Zavřít">✕</button>
    <livewire:inquiry-form />
  </div>
</div>

{{-- FOOTER (sdílená komponenta) --}}
<x-ui.landing-footer />

</div>
