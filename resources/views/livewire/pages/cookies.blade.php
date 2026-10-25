<?php

use App\Support\CookieConsent;
use Livewire\Attributes\{Layout, On, Title};
use Livewire\Volt\Component;

new #[Layout('components.layouts.landing', [
    'metaDescription' => 'Jaké cookies web Judo Clubu Raion-ryu používá, k čemu slouží a jak změnit svou volbu. Žádné reklamy ani sledování napříč weby.',
])]
#[Title('Cookies | Judo Club Raion-ryu')]
class extends Component {
    /** Aktuální volba návštěvníka: all / necessary / null (zatím žádná). */
    public ?string $choice = null;

    public function mount(): void
    {
        $this->choice = CookieConsent::fromRequest(request());
    }

    /** Lišta právě uložila novou volbu – promítneme ji do stránky bez reloadu. */
    #[On('cookie-consent-changed')]
    public function updateChoice(string $choice): void
    {
        $this->choice = in_array($choice, [CookieConsent::ALL, CookieConsent::NECESSARY], true) ? $choice : null;
    }

    public function with(): array
    {
        return [
            'sessionCookie' => config('session.cookie'),
            'sessionMinutes' => (int) config('session.lifetime'),
            'decided' => $this->choice !== null,
            'allowsAnalytics' => $this->choice === CookieConsent::ALL,
        ];
    }
}; ?>

<div class="cookies-page">

<style>
  /* ─── Stránka „Cookies" ──────────────────────────────────────────────────
     Styly scopované pod .cookies-page; navbar a footer jsou sdílené komponenty.
     Flex sloupec přes celou výšku → footer drží dole i u krátké stránky. */
  .cookies-page { min-height: 100vh; display: flex; flex-direction: column; }
  .cookies-page svg { display: block; }

  /* ─── HEADER ─── */
  .cookies-page .page-header {
    padding: 140px 80px 56px;
    background: var(--bg-dark); color: #fff;
    position: relative; overflow: hidden;
  }
  .cookies-page .breadcrumb {
    font-size: 11px; letter-spacing: .15em; text-transform: uppercase;
    color: rgba(255,255,255,.4); margin-bottom: 22px;
    display: flex; gap: 8px; align-items: center; flex-wrap: wrap; position: relative; z-index: 1;
  }
  .cookies-page .breadcrumb a { color: rgba(255,255,255,.4); text-decoration: none; transition: color .2s; }
  .cookies-page .breadcrumb a:hover { color: var(--red); }
  .cookies-page .header-eyebrow {
    font-size: 11px; letter-spacing: .2em; text-transform: uppercase;
    color: var(--red); font-weight: 600; margin-bottom: 18px;
    display: flex; align-items: center; gap: 12px; position: relative; z-index: 1;
  }
  .cookies-page .header-eyebrow::before { content:''; display:block; width:32px; height:1px; background:var(--red); }
  .cookies-page .page-title {
    font-family: var(--serif); font-size: clamp(36px, 4.4vw, 58px);
    font-weight: 300; line-height: 1.08; color: #fff; margin-bottom: 16px; position: relative; z-index: 1;
  }
  .cookies-page .page-sub { font-size: 16px; color: rgba(255,255,255,.5); font-weight: 300; max-width: 600px; line-height: 1.7; position: relative; z-index: 1; }

  /* ─── OBSAH ─── */
  .cookies-page main { flex: 1; padding: 72px 80px 100px; }
  .cookies-page .cookies-layout {
    display: grid; grid-template-columns: minmax(0, 1.6fr) minmax(280px, 1fr);
    gap: 64px; align-items: start;
  }
  .cookies-page .cookies-body h2 { font-family: var(--serif); font-size: 26px; font-weight: 400; line-height: 1.2; margin: 52px 0 14px; }
  .cookies-page .cookies-body h2:first-child { margin-top: 0; }
  .cookies-page .cookies-body p { font-size: 15px; line-height: 1.8; color: var(--ink-mid); font-weight: 300; margin-bottom: 14px; }
  .cookies-page .cookies-body a { color: var(--red); }
  .cookies-page .cookies-body strong { color: var(--ink); font-weight: 600; }

  .cookies-page .cookie-table { width: 100%; border-collapse: collapse; margin: 22px 0 6px; font-size: 14px; }
  .cookies-page .cookie-table th {
    text-align: left; font-size: 11px; letter-spacing: .12em; text-transform: uppercase;
    color: var(--ink-light); font-weight: 600; padding: 0 16px 12px 0; border-bottom: 2px solid var(--ink);
  }
  .cookies-page .cookie-table td {
    padding: 14px 16px 14px 0; border-bottom: 1px solid var(--rule); vertical-align: top;
    color: var(--ink-mid); line-height: 1.6; font-weight: 300;
  }
  .cookies-page .cookie-table th:last-child, .cookies-page .cookie-table td:last-child { padding-right: 0; white-space: nowrap; }
  .cookies-page .cookie-table code { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: 13px; color: var(--ink); white-space: nowrap; }
  .cookies-page .cookie-tag {
    display: inline-block; font-size: 9px; font-weight: 700;
    letter-spacing: .12em; text-transform: uppercase; padding: 3px 8px; white-space: nowrap;
  }
  .cookies-page .cookie-tag.necessary { background: var(--ink); color: #fff; }
  .cookies-page .cookie-tag.analytics { background: var(--red); color: #fff; }

  /* ─── PANEL S AKTUÁLNÍ VOLBOU ─── */
  .cookies-page .cookies-panel { background: var(--bg-dark); color: #fff; padding: 36px 34px; position: sticky; top: 92px; }
  .cookies-page .cookies-panel h3 { font-family: var(--serif); font-size: 22px; font-weight: 300; line-height: 1.3; margin-bottom: 18px; }
  .cookies-page .panel-choice { display: flex; gap: 12px; align-items: center; font-size: 15px; color: #fff; margin-bottom: 8px; }
  .cookies-page .panel-choice::before { content: ''; width: 8px; height: 8px; border-radius: 50%; background: var(--red); flex: none; }
  .cookies-page .panel-choice.none::before { background: transparent; box-shadow: inset 0 0 0 1.5px rgba(255,255,255,.5); }
  .cookies-page .cookies-panel p { font-size: 13px; color: rgba(255,255,255,.5); font-weight: 300; line-height: 1.7; margin-bottom: 22px; }
  .cookies-page .cookies-panel button {
    display: inline-block; background: var(--red); color: #fff; border: none; cursor: pointer;
    font-family: var(--sans); padding: 13px 24px; font-size: 11px; font-weight: 600;
    letter-spacing: .12em; text-transform: uppercase; transition: background .25s;
  }
  .cookies-page .cookies-panel button:hover { background: var(--red-muted); }

  @media (max-width: 1000px) {
    .cookies-page .cookies-layout { grid-template-columns: 1fr; gap: 40px; }
    .cookies-page .cookies-panel { position: static; }
  }
  @media (max-width: 900px) {
    .cookies-page .page-header { padding: 120px 28px 44px; }
    .cookies-page main { padding: 48px 28px 72px; }
  }
  /* Tabulka na úzkém displeji jako seznam karet (popisky z data-label). */
  @media (max-width: 760px) {
    .cookies-page .cookie-table thead { display: none; }
    .cookies-page .cookie-table, .cookies-page .cookie-table tbody,
    .cookies-page .cookie-table tr, .cookies-page .cookie-table td { display: block; }
    .cookies-page .cookie-table tr { padding: 16px 0; border-bottom: 1px solid var(--rule); }
    .cookies-page .cookie-table td { padding: 4px 0; border: none; white-space: normal; }
    .cookies-page .cookie-table td::before {
      content: attr(data-label); display: block; font-size: 10px; letter-spacing: .12em;
      text-transform: uppercase; color: var(--ink-light); font-weight: 600; margin-bottom: 2px;
    }
    .cookies-page .cookie-table td:first-child::before { display: none; }
  }
</style>

{{-- NAV (sdílená komponenta) --}}
<x-ui.landing-nav />

{{-- HEADER --}}
<header class="page-header">
  <div class="breadcrumb">
    <a href="{{ route('home') }}" wire:navigate>Úvod</a> <span>/</span>
    <span style="color:rgba(255,255,255,.65);">Cookies</span>
  </div>
  <div class="header-eyebrow">Informace</div>
  <h1 class="page-title">Cookies</h1>
  <p class="page-sub">Co si web ukládá do vašeho prohlížeče, proč, a jak to změnit. Krátce: nezbytné minimum a – jen s vaším souhlasem – anonymní počítadlo návštěv.</p>
</header>

<main>
  <div class="cookies-layout">
    <div class="cookies-body">
      <h2>Co jsou cookies</h2>
      <p>Cookies jsou malé textové soubory, které si web ukládá ve vašem prohlížeči. Některé jsou nutné k tomu, aby stránky vůbec fungovaly (třeba zabezpečení formulářů), jiné slouží k měření návštěvnosti. Bez vašeho souhlasu používáme jen ty nezbytné.</p>

      <h2>Jaké cookies používáme</h2>
      <table class="cookie-table">
        <thead>
          <tr><th>Cookie</th><th>K čemu slouží</th><th>Typ</th><th>Platnost</th></tr>
        </thead>
        <tbody>
          <tr>
            <td data-label="Cookie"><code>{{ $sessionCookie }}</code></td>
            <td data-label="K čemu slouží">Relace návštěvníka – zabezpečení formulářů a fungování interaktivních částí webu (kalendář tréninků, poptávkový formulář, galerie).</td>
            <td data-label="Typ"><span class="cookie-tag necessary">Nezbytná</span></td>
            <td data-label="Platnost">{{ $sessionMinutes }} minut od poslední aktivity</td>
          </tr>
          <tr>
            <td data-label="Cookie"><code>XSRF-TOKEN</code></td>
            <td data-label="K čemu slouží">Ochrana formulářů proti podvrženým požadavkům (CSRF).</td>
            <td data-label="Typ"><span class="cookie-tag necessary">Nezbytná</span></td>
            <td data-label="Platnost">{{ $sessionMinutes }} minut od poslední aktivity</td>
          </tr>
          <tr>
            <td data-label="Cookie"><code>rr_cookie_consent</code></td>
            <td data-label="K čemu slouží">Pamatuje si vaši volbu z cookie lišty, aby se web neptal pořád dokola.</td>
            <td data-label="Typ"><span class="cookie-tag necessary">Nezbytná</span></td>
            <td data-label="Platnost">1 rok</td>
          </tr>
          <tr>
            <td data-label="Cookie"><code>rr_visitor</code></td>
            <td data-label="K čemu slouží">Náhodný anonymní identifikátor pro počítání unikátních návštěvníků (jeden záznam na den). Do databáze se ukládá jen jeho otisk – žádná IP adresa ani údaje o prohlížeči.</td>
            <td data-label="Typ"><span class="cookie-tag analytics">Analytická</span></td>
            <td data-label="Platnost">1 rok</td>
          </tr>
        </tbody>
      </table>
      <p>Analytickou cookie nastavíme jen po volbě <strong>Přijmout vše</strong>. Při volbě <strong>Pouze nezbytné</strong> ji neukládáme a případnou dřívější smažeme.</p>

      <h2>Služby třetích stran</h2>
      <p>Písma webu (Noto Serif a Noto Sans) načítáme ze serverů Google Fonts – Google při tom obdrží vaši IP adresu, cookies ale nenastavuje. Žádné reklamní sítě, měřicí skripty třetích stran, vložená videa ani mapy web nepoužívá; fotky i videa v galerii běží z našeho serveru.</p>

      <h2>Jak volbu změnit</h2>
      <p>Kdykoli ji změníte tlačítkem <strong>Změnit nastavení</strong> na této stránce – znovu se otevře cookie lišta. Cookies můžete také smazat nebo zakázat přímo v nastavení prohlížeče; web pak bude fungovat dál, jen se vás lišta zeptá znovu.</p>

      <h2>Ochrana osobních údajů</h2>
      <p>Jak nakládáme s osobními údaji členů a zájemců o trénink, popisuje dokument <a href="{{ route('downloads') }}" wire:navigate>GDPR JC Raion-Ryu</a> v sekci Ke stažení. S dotazy se ozvěte přes <a href="{{ route('home') }}#kontakt">kontakt</a> na úvodní stránce.</p>
    </div>

    <aside class="cookies-panel">
      <h3>Vaše volba</h3>
      @if ($allowsAnalytics)
        <div class="panel-choice">Přijmout vše</div>
        <p>Web počítá vaši návštěvu v anonymní statistice návštěvnosti.</p>
      @elseif ($decided)
        <div class="panel-choice">Pouze nezbytné</div>
        <p>Web ukládá jen cookies potřebné k fungování stránek.</p>
      @else
        <div class="panel-choice none">Zatím bez volby</div>
        <p>Dokud nerozhodnete, web používá jen nezbytné cookies a návštěvnost nepočítá.</p>
      @endif
      {{-- Alpine $dispatch doručí Livewire událost liště (#[On('cookie-consent-reopen')]). --}}
      <button type="button" x-data x-on:click="$dispatch('cookie-consent-reopen')">Změnit nastavení</button>
    </aside>
  </div>
</main>

{{-- FOOTER (sdílená komponenta) --}}
<x-ui.landing-footer />
</div>
