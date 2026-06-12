<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.landing', [
    'metaDescription' => 'Fotogalerie a videa Judo Clubu Raion-ryu – semináře, soustředění, tábory, závody, ukázky a pobyty japonských mistrů od roku 2010.',
])]
#[Title('Galerie | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        $g = config('content.gallery', ['categories' => [], 'albums' => []]);

        // Alba ze scraperu (config) + alba nahraná přes administraci (DB).
        // Obě sady mají identický tvar pole, klientský JS se nemění.
        $albums = collect($g['albums'])
            ->concat(\App\Models\GalleryAlbum::query()->get()->map(fn ($a) => $a->toPublicArray()))
            ->sortByDesc('year')
            ->values()
            ->all();

        // Counts for the filter chips (computed server-side).
        $catCounts = [];
        foreach ($g['categories'] as $slug => $label) {
            $catCounts[$slug] = collect($albums)->filter(fn ($a) => in_array($slug, $a['cats']))->count();
        }
        $years = collect($albums)->pluck('year')->filter()->unique()->sortDesc()->values();

        return [
            'categories'  => $g['categories'],
            'catCounts'   => $catCounts,
            'albums'      => $albums,
            'years'       => $years,
            'totalAlbums' => count($albums),
            'totalPhotos' => collect($albums)->sum('photos'),
            'totalVideos' => collect($albums)->sum('videos'),
        ];
    }
}; ?>

<div class="galerie-page">
<style>
  /* ─── Stránka „Galerie" – styly scopované pod .galerie-page.
     Navbar i footer jsou sdílené komponenty (landing layout); proměnné
     --bg/--bg-dark/--ink/--red/--serif/--sans dědíme z :root layoutu. ───── */

  .galerie-page svg, .galerie-page img { display: block; }
  .galerie-page [x-cloak] { display: none !important; }

  /* ─── HEADER ─── */
  .galerie-page .gp-header {
    padding: 140px 80px 56px;
    background: var(--bg-dark); color: #fff;
    position: relative; overflow: hidden;
  }
  .galerie-page .gp-breadcrumb {
    font-size: 11px; letter-spacing: .15em; text-transform: uppercase;
    color: rgba(255,255,255,.4); margin-bottom: 22px; display: flex; gap: 8px;
    align-items: center; flex-wrap: wrap; position: relative; z-index: 1;
  }
  .galerie-page .gp-breadcrumb a { color: rgba(255,255,255,.4); text-decoration: none; transition: color .2s; }
  .galerie-page .gp-breadcrumb a:hover { color: var(--red); }
  .galerie-page .gp-eyebrow {
    font-size: 11px; letter-spacing: .2em; text-transform: uppercase;
    color: var(--red); font-weight: 600; margin-bottom: 18px;
    display: flex; align-items: center; gap: 12px; position: relative; z-index: 1;
  }
  .galerie-page .gp-eyebrow::before { content:''; display:block; width:32px; height:1px; background:var(--red); }
  .galerie-page .gp-page-title {
    font-family: var(--serif); font-size: clamp(36px, 4.4vw, 58px);
    font-weight: 300; line-height: 1.08; color: #fff; margin-bottom: 16px; position: relative; z-index: 1;
  }
  .galerie-page .gp-page-sub { font-size: 16px; color: rgba(255,255,255,.5); font-weight: 300; max-width: 600px; line-height: 1.7; position: relative; z-index: 1; }
  .galerie-page .gp-stats { display: flex; gap: 48px; margin-top: 40px; position: relative; z-index: 2; flex-wrap: wrap; }
  .galerie-page .gp-stat-num { font-family: var(--serif); font-size: 34px; font-weight: 300; color: #fff; line-height: 1; }
  .galerie-page .gp-stat-label { font-size: 11px; letter-spacing: .12em; text-transform: uppercase; color: rgba(255,255,255,.4); margin-top: 6px; }

  /* ─── FILTER BAR (sticky pod navem) ─── */
  .galerie-page .gp-filterbar {
    position: sticky; top: 68px; z-index: 80;
    background: rgba(247,244,239,.94); backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--rule); padding: 0 80px;
  }
  .galerie-page .gp-filterbar-inner {
    display: flex; align-items: center; justify-content: space-between;
    gap: 16px 24px; flex-wrap: wrap; padding: 12px 0;
  }
  .galerie-page .gp-filters { display: flex; align-items: center; gap: 28px; flex-wrap: wrap; }
  .galerie-page .gp-filter-group { display: flex; align-items: center; gap: 8px; }
  .galerie-page .gp-filter-label {
    font-size: 10px; letter-spacing: .16em; text-transform: uppercase;
    color: var(--ink-light); font-weight: 600;
  }
  /* „Vše" button */
  .galerie-page .gp-fbtn {
    font-size: 12px; letter-spacing: .04em; font-weight: 600; font-family: var(--sans);
    color: var(--ink-mid); background: transparent; border: 1px solid var(--rule);
    padding: 8px 16px; cursor: pointer; transition: all .18s;
  }
  .galerie-page .gp-fbtn:hover { color: var(--ink); border-color: var(--ink-light); }
  .galerie-page .gp-fbtn.active { color: #fff; background: var(--bg-dark); border-color: var(--bg-dark); }
  /* dropdown */
  .galerie-page .gp-dd { position: relative; }
  .galerie-page .gp-dd-trigger {
    display: inline-flex; align-items: center; justify-content: space-between; gap: 10px; min-width: 150px;
    font-size: 12px; letter-spacing: .04em; font-weight: 600; font-family: var(--sans);
    color: var(--ink-mid); background: transparent; border: 1px solid var(--rule);
    padding: 8px 14px; cursor: pointer; transition: all .18s;
  }
  .galerie-page .gp-dd-trigger:hover { color: var(--ink); border-color: var(--ink-light); }
  .galerie-page .gp-dd-trigger.active { color: #fff; background: var(--red); border-color: var(--red); }
  .galerie-page .gp-dd-caret { font-size: 9px; line-height: 1; transition: transform .2s; }
  .galerie-page .gp-dd-trigger[aria-expanded="true"] .gp-dd-caret { transform: rotate(180deg); }
  .galerie-page .gp-dd-menu {
    position: absolute; top: calc(100% + 6px); left: 0; z-index: 50;
    background: var(--bg); border: 1px solid var(--rule); box-shadow: 0 16px 40px rgba(0,0,0,.14);
    padding: 6px; min-width: 240px; max-height: 60vh; overflow-y: auto;
  }
  .galerie-page .gp-dd-menu--years { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2px; }
  .galerie-page .gp-dd-item {
    display: flex; align-items: center; justify-content: space-between; gap: 12px; width: 100%;
    font-size: 13px; font-weight: 400; color: var(--ink-mid); background: transparent; border: none;
    padding: 9px 12px; cursor: pointer; font-family: var(--sans); text-align: left; transition: background .15s, color .15s;
  }
  .galerie-page .gp-dd-menu--years .gp-dd-item { justify-content: center; }
  .galerie-page .gp-dd-item:hover { background: #F0EDE8; color: var(--red); }
  .galerie-page .gp-dd-item.active { background: var(--red); color: #fff; }
  .galerie-page .gp-dd-item-count { font-size: 11px; opacity: .5; font-family: monospace; }
  /* „Videa ↓" skok */
  .galerie-page .gp-jump {
    display: inline-flex; align-items: center; gap: 8px;
    font-size: 12px; letter-spacing: .08em; text-transform: uppercase; font-weight: 600; font-family: var(--sans);
    color: #fff; background: var(--red); border: none; padding: 9px 18px; cursor: pointer; transition: background .2s;
  }
  .galerie-page .gp-jump:hover { background: var(--red-muted); }
  /* tlačítko nahoru */
  .galerie-page .gp-totop {
    position: fixed; bottom: 28px; right: 28px; z-index: 95;
    width: 46px; height: 46px; display: flex; align-items: center; justify-content: center;
    background: var(--bg-dark); color: #fff; border: 1px solid rgba(255,255,255,.16); cursor: pointer;
    opacity: 0; visibility: hidden; transform: translateY(8px);
    transition: opacity .25s, transform .25s, background .2s;
  }
  .galerie-page .gp-totop.show { opacity: 1; visibility: visible; transform: translateY(0); }
  .galerie-page .gp-totop:hover { background: var(--red); }

  /* ─── GALLERY GRID ─── */
  .galerie-page .gp-gallery { padding: 56px 80px 100px; background: var(--bg); }
  .galerie-page .gp-empty {
    text-align: center; padding: 80px 20px;
    font-family: var(--serif); font-size: 22px; font-weight: 300; color: var(--ink-light);
  }
  .galerie-page .gp-grid[data-layout="classic"] {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 36px 32px;
  }
  .galerie-page .gp-grid[data-layout="classic"] .gp-cover { aspect-ratio: 4/3; }
  .galerie-page .gp-grid[data-layout="classic"] .gp-cover img { width: 100%; height: 100%; object-fit: cover; }
  .galerie-page .gp-grid[data-layout="mozaika"] { column-count: 3; column-gap: 32px; }
  .galerie-page .gp-grid[data-layout="mozaika"] .gp-album { break-inside: avoid; margin-bottom: 40px; width: 100%; }
  .galerie-page .gp-grid[data-layout="mozaika"] .gp-cover img { width: 100%; height: auto; }

  .galerie-page .gp-album {
    display: block; text-align: left; background: transparent; border: none;
    padding: 0; cursor: pointer; width: 100%; transition: transform .25s ease; font-family: var(--sans);
  }
  .galerie-page .gp-album:hover { transform: translateY(-4px); }
  .galerie-page .gp-cover {
    position: relative; overflow: hidden; width: 100%;
    border: 1px solid var(--rule); background: #ECE8E1;
  }
  .galerie-page .gp-cover img { transition: transform .5s ease; }
  .galerie-page .gp-album:hover .gp-cover img { transform: scale(1.04); }
  .galerie-page .gp-cat {
    position: absolute; top: 0; left: 0; z-index: 3;
    font-size: 10px; letter-spacing: .12em; text-transform: uppercase; font-weight: 700;
    color: #fff; background: var(--red); padding: 6px 11px;
  }
  .galerie-page .gp-count, .galerie-page .gp-vflag {
    position: absolute; bottom: 12px; z-index: 3;
    display: flex; align-items: center; gap: 7px;
    background: rgba(20,18,14,.78); color: #fff; backdrop-filter: blur(4px);
    font-family: monospace; font-size: 12px; letter-spacing: .04em; padding: 6px 10px;
  }
  .galerie-page .gp-count { left: 12px; }
  .galerie-page .gp-vflag { right: 12px; font-size: 11px; }
  .galerie-page .gp-meta { padding: 18px 2px 0; }
  .galerie-page .gp-title {
    font-family: var(--serif); font-size: 19px; font-weight: 400; line-height: 1.25;
    color: var(--ink); transition: color .18s;
  }
  .galerie-page .gp-album:hover .gp-title { color: var(--red); }
  .galerie-page .gp-date { font-size: 13px; color: var(--ink-light); font-weight: 300; margin-top: 5px; }

  /* ─── VIDEA ─── */
  .galerie-page .gp-videos { background: #F0EDE8; padding: 100px 80px; }
  .galerie-page .gp-videos .gp-eyebrow { color: var(--red); margin-bottom: 20px; }
  .galerie-page .gp-videos-title {
    font-family: var(--serif); font-size: clamp(30px, 3.6vw, 46px);
    font-weight: 300; line-height: 1.12; letter-spacing: -.01em; margin-bottom: 16px;
  }
  .galerie-page .gp-videos-lead { font-size: 16px; color: var(--ink-mid); font-weight: 300; max-width: 620px; line-height: 1.7; margin-bottom: 52px; }
  .galerie-page .gp-vgrid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2px; }
  .galerie-page .gp-vcard { background: var(--bg); display: flex; flex-direction: column; cursor: pointer; border: none; padding: 0; text-align: left; font-family: var(--sans); }
  .galerie-page .gp-vthumb { position: relative; aspect-ratio: 16/9; overflow: hidden; background: var(--bg-dark); }
  .galerie-page .gp-vthumb img { width: 100%; height: 100%; object-fit: cover; opacity: .85; transition: opacity .2s, transform .4s; }
  .galerie-page .gp-vcard:hover .gp-vthumb img { opacity: 1; transform: scale(1.04); }
  .galerie-page .gp-vplay { position: absolute; inset: 0; z-index: 2; display: flex; align-items: center; justify-content: center; }
  .galerie-page .gp-vplay span {
    width: 60px; height: 60px; background: rgba(192,38,30,.92);
    display: flex; align-items: center; justify-content: center; transition: transform .2s, background .2s;
  }
  .galerie-page .gp-vcard:hover .gp-vplay span { transform: scale(1.08); background: var(--red); }
  .galerie-page .gp-vbody { padding: 20px 22px 24px; }
  .galerie-page .gp-vtitle { font-family: var(--serif); font-size: 17px; font-weight: 400; line-height: 1.3; color: var(--ink); }
  .galerie-page .gp-vdate { font-size: 12px; color: var(--ink-light); margin-top: 6px; }
  .galerie-page .gp-videos-more { margin-top: 48px; display: flex; justify-content: center; }
  .galerie-page .gp-more-btn {
    background: transparent; border: 1.5px solid var(--ink); color: var(--ink);
    padding: 14px 34px; font-size: 12px; letter-spacing: .1em; text-transform: uppercase;
    font-weight: 600; font-family: var(--sans); cursor: pointer; transition: all .2s;
  }
  .galerie-page .gp-more-btn:hover { border-color: var(--red); color: var(--red); }

  /* ─── OVERLAY (album) + LIGHTBOX (foto/video) ─── */
  .galerie-page .gp-overlay, .galerie-page .gp-lightbox {
    position: fixed; inset: 0; display: none; opacity: 0; transition: opacity .25s ease;
  }
  .galerie-page .gp-overlay { z-index: 1000; background: rgba(15,13,10,.97); flex-direction: column; overflow: hidden; }
  .galerie-page .gp-overlay.open, .galerie-page .gp-lightbox.open { display: flex; opacity: 1; }
  .galerie-page .gp-ov-header {
    display: flex; align-items: flex-start; justify-content: space-between; gap: 24px;
    padding: 26px 48px 22px; border-bottom: 1px solid rgba(255,255,255,.1); flex-shrink: 0;
  }
  .galerie-page .gp-ov-eyebrow { font-size: 10px; letter-spacing: .18em; text-transform: uppercase; color: var(--red); font-weight: 700; margin-bottom: 10px; }
  .galerie-page .gp-ov-title { font-family: var(--serif); font-size: 26px; font-weight: 300; color: #fff; line-height: 1.15; }
  .galerie-page .gp-ov-sub { font-size: 13px; color: rgba(255,255,255,.45); margin-top: 8px; letter-spacing: .02em; }
  .galerie-page .gp-close {
    background: transparent; border: 1px solid rgba(255,255,255,.2); color: rgba(255,255,255,.7);
    width: 44px; height: 44px; cursor: pointer; font-size: 20px; flex-shrink: 0; line-height: 1;
    transition: border-color .2s, color .2s;
  }
  .galerie-page .gp-close:hover { border-color: var(--red); color: #fff; }
  .galerie-page .gp-ov-thumbs {
    flex: 1 1 auto; min-height: 0; overflow-y: auto; padding: 30px 48px 56px;
    display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 10px; align-content: start;
  }
  /* poměr 4:3 přes padding-bottom (neprůstřelné – <button> v gridu ignoruje
     jak aspect-ratio, tak content-výšku, a slil náhledy do proužků) */
  .galerie-page .gp-ov-thumb {
    position: relative; display: block; width: 100%; height: 0; padding: 0 0 75% 0;
    border: none; cursor: pointer; overflow: hidden; background: #2a2723;
    outline: 1px solid transparent; transition: transform .2s, outline-color .2s;
  }
  .galerie-page .gp-ov-thumb img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; display: block; }
  .galerie-page .gp-ov-thumb:hover { transform: scale(1.015); outline-color: var(--red); }
  .galerie-page .gp-ov-thumb .gp-ov-vbadge {
    position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; z-index: 2;
  }
  .galerie-page .gp-ov-thumb .gp-ov-vbadge span { width: 44px; height: 44px; background: rgba(192,38,30,.9); display: flex; align-items: center; justify-content: center; }
  .galerie-page .gp-ov-loading { color: rgba(255,255,255,.5); font-family: var(--serif); font-size: 18px; font-weight: 300; padding: 40px; grid-column: 1/-1; text-align: center; }

  /* lightbox */
  .galerie-page .gp-lightbox { z-index: 1100; background: rgba(8,7,5,.985); align-items: center; justify-content: center; }
  .galerie-page .gp-lb-stage {
    max-width: min(90vw, 1400px); max-height: 82vh; display: flex; align-items: center; justify-content: center;
  }
  .galerie-page .gp-lb-stage img, .galerie-page .gp-lb-stage video {
    max-width: min(90vw, 1400px); max-height: 82vh; width: auto; height: auto;
    border: 1px solid rgba(255,255,255,.12); background: #000;
  }
  .galerie-page .gp-lb-caption {
    position: fixed; bottom: 28px; left: 50%; transform: translateX(-50%); text-align: center; color: rgba(255,255,255,.6); max-width: 80vw;
  }
  .galerie-page .gp-lb-counter { font-family: monospace; font-size: 13px; letter-spacing: .1em; color: #fff; }
  .galerie-page .gp-lb-info { font-size: 11px; letter-spacing: .14em; text-transform: uppercase; color: rgba(255,255,255,.4); margin-top: 6px; }
  .galerie-page .gp-lb-arrow {
    position: fixed; top: 50%; transform: translateY(-50%); width: 56px; height: 56px;
    background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.16); color: #fff; cursor: pointer;
    display: flex; align-items: center; justify-content: center; transition: background .2s, border-color .2s;
  }
  .galerie-page .gp-lb-arrow:hover { background: var(--red); border-color: var(--red); }
  .galerie-page .gp-lb-prev { left: 32px; } .galerie-page .gp-lb-next { right: 32px; }
  .galerie-page .gp-lb-close {
    position: fixed; top: 28px; right: 32px; width: 44px; height: 44px; background: transparent;
    border: 1px solid rgba(255,255,255,.2); color: rgba(255,255,255,.7); cursor: pointer; font-size: 20px; line-height: 1;
    transition: border-color .2s, color .2s; z-index: 2;
  }
  .galerie-page .gp-lb-close:hover { border-color: var(--red); color: #fff; }

  @media (max-width: 1100px) {
    .galerie-page .gp-grid[data-layout="classic"] { grid-template-columns: repeat(2, 1fr); }
    .galerie-page .gp-grid[data-layout="mozaika"] { column-count: 2; }
    .galerie-page .gp-vgrid { grid-template-columns: 1fr 1fr; }
  }
  @media (max-width: 760px) {
    .galerie-page .gp-header { padding: 116px 26px 44px; }
    .galerie-page .gp-stats { gap: 28px; }
    .galerie-page .gp-filterbar { padding: 0 26px; }
    .galerie-page .gp-gallery { padding: 40px 26px 72px; }
    .galerie-page .gp-grid[data-layout="classic"] { grid-template-columns: 1fr; }
    .galerie-page .gp-grid[data-layout="mozaika"] { column-count: 1; }
    .galerie-page .gp-videos { padding: 64px 26px; }
    .galerie-page .gp-vgrid { grid-template-columns: 1fr; }
    .galerie-page .gp-ov-header { padding: 22px 24px; }
    .galerie-page .gp-ov-thumbs { padding: 24px; grid-template-columns: repeat(auto-fill, minmax(120px,1fr)); }
    .galerie-page .gp-ov-title { font-size: 21px; }
    .galerie-page .gp-lb-arrow { width: 44px; height: 44px; }
    .galerie-page .gp-lb-prev { left: 12px; } .galerie-page .gp-lb-next { right: 12px; }
  }
</style>

<x-ui.landing-nav />

{{-- ─── HEADER ─── --}}
<header class="gp-header">
  <div class="gp-breadcrumb"><a href="{{ route('home') }}">Úvod</a> <span>/</span> <span>Klub</span> <span>/</span> <span style="color:rgba(255,255,255,.65);">Galerie</span></div>
  <div class="gp-eyebrow">Fotogalerie &amp; videa</div>
  <h1 class="gp-page-title">Galerie z akcí</h1>
  <p class="gp-page-sub">Semináře, soustředění, tábory, závody a ukázky bojových umění — i pobyty japonských mistrů u nás. Vyberte album a prolistujte fotky, nebo se podívejte na videa z tatami.</p>
  <div class="gp-stats">
    <div><div class="gp-stat-num">{{ $totalAlbums }}</div><div class="gp-stat-label">Alb</div></div>
    <div><div class="gp-stat-num">{{ number_format($totalPhotos, 0, ',', ' ') }}</div><div class="gp-stat-label">Fotografií</div></div>
    <div><div class="gp-stat-num">{{ $totalVideos }}</div><div class="gp-stat-label">Videí</div></div>
    <div><div class="gp-stat-num">2010</div><div class="gp-stat-label">Od roku</div></div>
  </div>
</header>

{{-- ─── FILTER BAR ─── --}}
<div class="gp-filterbar">
  <div class="gp-filterbar-inner">
    <div class="gp-filters">
      {{-- Kategorie --}}
      <div class="gp-filter-group" id="gp-filter-cats">
        <span class="gp-filter-label">Kategorie</span>
        <button type="button" class="gp-fbtn active" data-cat="all">Vše</button>
        <div class="gp-dd" x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false">
          <button type="button" class="gp-dd-trigger" @click="open = !open" :aria-expanded="open.toString()">
            <span id="gp-cat-current">Vyberte kategorii</span>
            <span class="gp-dd-caret" aria-hidden="true">▾</span>
          </button>
          <div class="gp-dd-menu" x-show="open" x-cloak x-transition.opacity.duration.150ms @click="open = false">
            @foreach($categories as $slug => $label)
              @if(($catCounts[$slug] ?? 0) > 0)
                <button type="button" class="gp-dd-item" data-cat="{{ $slug }}">
                  <span>{{ $label }}</span><span class="gp-dd-item-count">{{ $catCounts[$slug] }}</span>
                </button>
              @endif
            @endforeach
          </div>
        </div>
      </div>
      {{-- Rok --}}
      <div class="gp-filter-group" id="gp-filter-years">
        <span class="gp-filter-label">Rok</span>
        <button type="button" class="gp-fbtn active" data-year="all">Vše</button>
        <div class="gp-dd" x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false">
          <button type="button" class="gp-dd-trigger" @click="open = !open" :aria-expanded="open.toString()">
            <span id="gp-year-current">Vyberte rok</span>
            <span class="gp-dd-caret" aria-hidden="true">▾</span>
          </button>
          <div class="gp-dd-menu gp-dd-menu--years" x-show="open" x-cloak x-transition.opacity.duration.150ms @click="open = false">
            @foreach($years as $y)
              <button type="button" class="gp-dd-item" data-year="{{ $y }}">{{ $y }}</button>
            @endforeach
          </div>
        </div>
      </div>
    </div>
    <button type="button" class="gp-jump" id="gp-videos-jump">
      Videa
      <svg width="14" height="14" viewBox="0 0 20 20" fill="none"><path d="M10 4v12M5 11l5 5 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>
  </div>
</div>

{{-- ─── GALLERY ─── --}}
<section class="gp-gallery">
  <div class="gp-grid" id="gp-grid" data-layout="classic">
    @foreach($albums as $i => $a)
      <button class="gp-album"
              data-idx="{{ $i }}"
              data-cats="{{ implode(' ', $a['cats']) }}"
              data-year="{{ $a['year'] }}"
              data-src="{{ $a['data'] }}"
              data-title="{{ $a['title'] }}"
              data-cat="{{ $categories[$a['cats'][0]] ?? '' }}"
              data-sub="{{ $a['date'] }} · {{ $a['photos'] }} fotografií{{ $a['videos'] > 0 ? ' · ' . $a['videos'] . ' videí' : '' }}">
        <div class="gp-cover">
          @if($a['cover'])
            <img src="{{ $a['cover'] }}" alt="{{ $a['title'] }}" loading="lazy">
          @endif
          @if($categories[$a['cats'][0]] ?? false)
            <span class="gp-cat">{{ $categories[$a['cats'][0]] }}</span>
          @endif
          <span class="gp-count">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><rect x="1" y="2.5" width="12" height="9" stroke="currentColor" stroke-width="1.2"/><circle cx="4.6" cy="5.6" r="1.1" fill="currentColor"/><path d="M2 11l3.2-3 2.3 2 2.4-2.6L12 11" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/></svg>
            {{ $a['photos'] }}
          </span>
          @if($a['videos'] > 0)
            <span class="gp-vflag">▶ {{ $a['videos'] }}</span>
          @endif
        </div>
        <div class="gp-meta">
          <div class="gp-title">{{ $a['title'] }}</div>
          <div class="gp-date">{{ $a['date'] }}</div>
        </div>
      </button>
    @endforeach
  </div>
  <div class="gp-empty" id="gp-empty" style="display:none;">Pro zvolený filtr nejsou žádná alba.</div>
</section>

{{-- ─── VIDEA ─── --}}
<section class="gp-videos">
  <div class="gp-eyebrow">Videogalerie</div>
  <h2 class="gp-videos-title">Videa z tréninků,<br>seminářů a soutěží</h2>
  <p class="gp-videos-lead">Záběry z ukázek, seminářů s japonskými mistry, táborů a závodů. Celkem {{ $totalVideos }} videí — klikněte pro přehrání.</p>
  <div class="gp-vgrid" id="gp-vgrid"></div>
  <div class="gp-videos-more" id="gp-videos-more" style="display:none;">
    <button class="gp-more-btn" id="gp-more-btn">Načíst další videa</button>
  </div>
</section>

<x-ui.landing-footer />

{{-- ─── OVERLAY: album → mřížka náhledů ─── --}}
<div class="gp-overlay" id="gp-overlay" aria-hidden="true">
  <div class="gp-ov-header">
    <div>
      <div class="gp-ov-eyebrow" id="gp-ov-eyebrow"></div>
      <div class="gp-ov-title" id="gp-ov-title"></div>
      <div class="gp-ov-sub" id="gp-ov-sub"></div>
    </div>
    <button class="gp-close" id="gp-ov-close" aria-label="Zavřít album">✕</button>
  </div>
  <div class="gp-ov-thumbs" id="gp-ov-thumbs"></div>
</div>

{{-- ─── LIGHTBOX: foto/video ─── --}}
<div class="gp-lightbox" id="gp-lightbox" aria-hidden="true">
  <button class="gp-lb-close" id="gp-lb-close" aria-label="Zavřít">✕</button>
  <button class="gp-lb-arrow gp-lb-prev" id="gp-lb-prev" aria-label="Předchozí">
    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M12.5 4L6.5 10l6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
  </button>
  <div class="gp-lb-stage" id="gp-lb-stage"></div>
  <button class="gp-lb-arrow gp-lb-next" id="gp-lb-next" aria-label="Další">
    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M7.5 4l6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
  </button>
  <div class="gp-lb-caption">
    <div class="gp-lb-counter" id="gp-lb-counter"></div>
    <div class="gp-lb-info" id="gp-lb-info"></div>
  </div>
</div>

{{-- ─── ZPĚT NAHORU ─── --}}
<button class="gp-totop" id="gp-totop" aria-label="Nahoru na začátek">
  <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M10 16V4M5 9l5-5 5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
</button>

@verbatim
<script>
(function () {
  if (window.__galerieInit) return;        // jen jednou (Livewire SPA navigace)
  window.__galerieInit = true;

  const $ = (s, r = document) => r.querySelector(s);
  const root = () => document.querySelector('.galerie-page');

  let curCat = 'all', curYear = 'all';
  const albumCache = {};          // src -> {photos, videos, title, date}
  let lbMedia = [];               // aktuální sada v lightboxu
  let lbIndex = 0;
  let lbContext = '';             // 'album' | 'videos'

  /* ─── FILTRY (dropdowny + stav v URL) ─── */
  function applyFilters() {
    const r = root(); if (!r) return;
    let shown = 0;
    r.querySelectorAll('.gp-album').forEach(card => {
      const cats = (card.dataset.cats || '').split(' ');
      const okCat  = curCat === 'all' || cats.includes(curCat);
      const okYear = curYear === 'all' || card.dataset.year === curYear;
      const vis = okCat && okYear;
      card.style.display = vis ? '' : 'none';
      if (vis) shown++;
    });
    const empty = $('#gp-empty', r);
    if (empty) empty.style.display = shown ? 'none' : 'block';
  }
  function setCat(slug, updateURL = true) {
    const r = root(); const grp = $('#gp-filter-cats', r);
    curCat = slug && slug !== 'all' && grp.querySelector(`.gp-dd-item[data-cat="${slug}"]`) ? slug : 'all';
    grp.querySelector('.gp-fbtn').classList.toggle('active', curCat === 'all');
    let label = 'Vyberte kategorii';
    grp.querySelectorAll('.gp-dd-item').forEach(it => {
      const on = it.dataset.cat === curCat;
      it.classList.toggle('active', on);
      if (on) label = it.querySelector('span').textContent;
    });
    grp.querySelector('.gp-dd-trigger').classList.toggle('active', curCat !== 'all');
    $('#gp-cat-current', r).textContent = curCat === 'all' ? 'Vyberte kategorii' : label;
    applyFilters(); if (updateURL) syncURL();
  }
  function setYear(val, updateURL = true) {
    const r = root(); const grp = $('#gp-filter-years', r); val = String(val || 'all');
    curYear = val !== 'all' && grp.querySelector(`.gp-dd-item[data-year="${val}"]`) ? val : 'all';
    grp.querySelector('.gp-fbtn').classList.toggle('active', curYear === 'all');
    grp.querySelectorAll('.gp-dd-item').forEach(it => it.classList.toggle('active', it.dataset.year === curYear));
    grp.querySelector('.gp-dd-trigger').classList.toggle('active', curYear !== 'all');
    $('#gp-year-current', r).textContent = curYear === 'all' ? 'Vyberte rok' : curYear;
    applyFilters(); if (updateURL) syncURL();
  }
  function syncURL() {
    const p = new URLSearchParams();
    if (curCat !== 'all') p.set('kategorie', curCat);
    if (curYear !== 'all') p.set('rok', curYear);
    const qs = p.toString();
    history.replaceState(null, '', qs ? location.pathname + '?' + qs : location.pathname);
  }
  function fromURL() {
    const p = new URLSearchParams(location.search);
    setCat(p.get('kategorie') || 'all', false);
    setYear(p.get('rok') || 'all', false);
  }
  function wireFilters() {
    const r = root();
    $('#gp-filter-cats', r).addEventListener('click', e => {
      const b = e.target.closest('[data-cat]'); if (b) setCat(b.dataset.cat);
    });
    $('#gp-filter-years', r).addEventListener('click', e => {
      const b = e.target.closest('[data-year]'); if (b) setYear(b.dataset.year);
    });
  }

  /* ─── ALBUM OVERLAY ─── */
  async function openAlbum(card) {
    const r = root();
    const src = card.dataset.src;
    $('#gp-ov-eyebrow', r).textContent = card.dataset.cat || '';
    $('#gp-ov-title', r).textContent = card.dataset.title || '';
    $('#gp-ov-sub', r).textContent = card.dataset.sub || '';
    const wrap = $('#gp-ov-thumbs', r);
    wrap.innerHTML = '<div class="gp-ov-loading">Načítám fotky…</div>';
    openOverlay();

    let data = albumCache[src];
    if (!data) {
      try { data = await (await fetch(src)).json(); albumCache[src] = data; }
      catch (e) { wrap.innerHTML = '<div class="gp-ov-loading">Album se nepodařilo načíst.</div>'; return; }
    }
    const media = buildMedia(data);
    wrap.innerHTML = '';
    media.forEach((m, i) => {
      const t = document.createElement('button');
      t.className = 'gp-ov-thumb';
      const img = document.createElement('img');
      img.loading = 'lazy'; img.src = m.poster; img.alt = m.caption || '';
      t.appendChild(img);
      if (m.type === 'video') {
        const b = document.createElement('span'); b.className = 'gp-ov-vbadge';
        b.innerHTML = '<span><svg width="16" height="16" viewBox="0 0 20 20" fill="none"><path d="M7 5l8 5-8 5z" fill="#fff"/></svg></span>';
        t.appendChild(b);
      }
      t.addEventListener('click', () => openLightbox(media, i, 'album', card.dataset.title));
      wrap.appendChild(t);
    });
  }
  function buildMedia(data) {
    const out = [];
    (data.photos || []).forEach(p => out.push({ type: 'img', src: p.f, poster: p.t, caption: p.c || '' }));
    (data.videos || []).forEach(v => out.push({ type: 'video', src: v.s, poster: v.p, caption: v.t || '' }));
    return out;
  }
  function openOverlay() {
    const ov = $('#gp-overlay', root());
    ov.classList.add('open'); ov.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }
  function closeOverlay() {
    const ov = $('#gp-overlay', root());
    ov.classList.remove('open'); ov.setAttribute('aria-hidden', 'true');
    if (!$('#gp-lightbox', root()).classList.contains('open')) document.body.style.overflow = '';
  }

  /* ─── LIGHTBOX ─── */
  function openLightbox(media, index, context, label) {
    lbMedia = media; lbIndex = index; lbContext = label || '';
    renderLightbox();
    const lb = $('#gp-lightbox', root());
    lb.classList.add('open'); lb.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }
  function renderLightbox() {
    const r = root();
    const m = lbMedia[lbIndex];
    const stage = $('#gp-lb-stage', r);
    stage.innerHTML = '';
    if (m.type === 'video') {
      const v = document.createElement('video');
      v.src = m.src; v.controls = true; v.autoplay = true; v.playsInline = true; v.poster = m.poster || '';
      stage.appendChild(v);
    } else {
      const img = document.createElement('img');
      img.src = m.src; img.alt = m.caption || '';
      stage.appendChild(img);
    }
    $('#gp-lb-counter', r).textContent = (lbIndex + 1) + ' / ' + lbMedia.length;
    $('#gp-lb-info', r).textContent = [lbContext, m.caption].filter(Boolean).join(' — ');
    const single = lbMedia.length < 2;
    $('#gp-lb-prev', r).style.display = single ? 'none' : '';
    $('#gp-lb-next', r).style.display = single ? 'none' : '';
  }
  function lbStep(d) {
    if (!lbMedia.length) return;
    lbIndex = (lbIndex + d + lbMedia.length) % lbMedia.length;
    renderLightbox();
  }
  function closeLightbox() {
    const lb = $('#gp-lightbox', root());
    const v = lb.querySelector('video'); if (v) v.pause();
    lb.classList.remove('open'); lb.setAttribute('aria-hidden', 'true');
    if (!$('#gp-overlay', root()).classList.contains('open')) document.body.style.overflow = '';
  }

  /* ─── VIDEA (sekce) ─── */
  let allVideos = [], vShown = 0;
  const V_BATCH = 24;
  async function initVideos() {
    const r = root(); const grid = $('#gp-vgrid', r); if (!grid) return;
    try { allVideos = await (await fetch('/galerie-media/videos.json')).json(); }
    catch (e) { grid.innerHTML = ''; return; }
    renderVideoBatch();
    $('#gp-more-btn', r).addEventListener('click', renderVideoBatch);
  }
  function renderVideoBatch() {
    const r = root(); const grid = $('#gp-vgrid', r);
    const slice = allVideos.slice(vShown, vShown + V_BATCH);
    slice.forEach((v, k) => {
      const idx = vShown + k;
      const card = document.createElement('button');
      card.className = 'gp-vcard';
      card.innerHTML =
        '<div class="gp-vthumb"><img loading="lazy" src="' + v.p + '" alt="">' +
        '<span class="gp-vplay"><span><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M7 5l8 5-8 5z" fill="#fff"/></svg></span></span></div>' +
        '<div class="gp-vbody"><div class="gp-vtitle">' + escapeHtml(v.t || v.a) + '</div>' +
        '<div class="gp-vdate">' + escapeHtml(v.a) + (v.y ? ' · ' + v.y : '') + '</div></div>';
      card.addEventListener('click', () => {
        const media = allVideos.map(x => ({ type: 'video', src: x.s, poster: x.p, caption: x.t || x.a }));
        openLightbox(media, idx, 'videos', 'Videogalerie');
      });
      grid.appendChild(card);
    });
    vShown += slice.length;
    $('#gp-videos-more', r).style.display = vShown < allVideos.length ? 'flex' : 'none';
  }
  function escapeHtml(s) { return (s || '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c])); }

  /* ─── WIRE UP (per stránku / DOM) ─── */
  function init() {
    const r = root(); if (!r || r.dataset.ready) return; r.dataset.ready = '1';
    wireFilters();
    fromURL();                                   // stav filtrů z URL (sdílení odkazem)
    r.querySelectorAll('.gp-album').forEach(card =>
      card.addEventListener('click', () => openAlbum(card)));
    $('#gp-ov-close', r).addEventListener('click', closeOverlay);
    $('#gp-lb-close', r).addEventListener('click', closeLightbox);
    $('#gp-lb-prev', r).addEventListener('click', () => lbStep(-1));
    $('#gp-lb-next', r).addEventListener('click', () => lbStep(1));
    $('#gp-lightbox', r).addEventListener('click', e => { if (e.target.id === 'gp-lightbox') closeLightbox(); });
    $('#gp-videos-jump', r).addEventListener('click', () => {
      const v = r.querySelector('.gp-videos'); if (v) v.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
    $('#gp-totop', r).addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    initVideos();
  }

  /* Globální listenery – jen jednou (přežijí Livewire SPA navigaci). */
  document.addEventListener('keydown', e => {
    const r = root(); if (!r) return;
    const lb = $('#gp-lightbox', r), ov = $('#gp-overlay', r);
    if (lb && lb.classList.contains('open')) {
      if (e.key === 'Escape') closeLightbox();
      else if (e.key === 'ArrowRight') lbStep(1);
      else if (e.key === 'ArrowLeft') lbStep(-1);
    } else if (ov && ov.classList.contains('open') && e.key === 'Escape') closeOverlay();
  });
  window.addEventListener('scroll', () => {
    const t = root() && document.getElementById('gp-totop');
    if (t) t.classList.toggle('show', window.scrollY > 600);
  }, { passive: true });
  document.addEventListener('livewire:navigated', init);

  if (document.readyState === 'loading')
    document.addEventListener('DOMContentLoaded', init);
  else init();
})();
</script>
@endverbatim
</div>
