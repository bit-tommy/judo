{{--
    Certifikáty instruktora — tlačítko, které otevře modal s galerií certifikátů.

    Použití (záměrně mimo fotky instruktora):
        <x-ui.certificates :images="['cer-filip.jpeg', 'cer-filip2.jpeg']" name="Filip Rubínek" />

    Parametry:
      • images — pole názvů souborů (v adresáři `dir`)
      • dir    — adresář pod public/ (výchozí images/certifikaty)
      • label  — text tlačítka (výchozí „Certifikáty")
      • name   — jméno pro alt/aria (volitelné)

    Bez závislostí: Alpine (z Livewire) + sdílené landing tokeny. Styly se vloží
    do hlavičky jen jednou, i když je komponenta na stránce použita vícekrát.
--}}
@props([
    'images' => [],
    'dir' => 'images/certifikaty',
    'label' => 'Certifikáty',
    'name' => null,
])

@php
    $certUrls = collect($images)
        ->filter()
        ->map(fn ($file) => asset(trim($dir, '/').'/'.ltrim($file, '/')))
        ->values();

    $altBase = $name ? $label.' – '.$name : $label;
@endphp

@if ($certUrls->isNotEmpty())
<div class="cert" x-data="{ open: false, i: 0, imgs: @js($certUrls->all()), alt: @js($altBase) }"
     @keydown.escape.window="open = false">
  <button type="button" class="cert-btn" @click="open = true; i = 0">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
      <circle cx="9" cy="9" r="5.3" stroke="currentColor" stroke-width="1.6"/>
      <path d="M5.9 13.2 4.3 20l4.7-2.4L13.7 20l-1.6-6.8" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
    </svg>
    {{ $label }}
    <span class="cert-btn-count">{{ $certUrls->count() }}</span>
  </button>

  <div class="cert-modal" x-show="open" x-cloak x-transition.opacity
       @click.self="open = false" role="dialog" aria-modal="true" aria-label="{{ $altBase }}">
    <div class="cert-modal-box">
      <button type="button" class="cert-close" @click="open = false" aria-label="Zavřít">&times;</button>
      <div class="cert-stage">
        <button type="button" class="cert-nav cert-prev" x-show="imgs.length > 1"
                @click="i = (i - 1 + imgs.length) % imgs.length" aria-label="Předchozí certifikát">&lsaquo;</button>
        <img :src="imgs[i]" :alt="alt + ' ' + (i + 1)">
        <button type="button" class="cert-nav cert-next" x-show="imgs.length > 1"
                @click="i = (i + 1) % imgs.length" aria-label="Další certifikát">&rsaquo;</button>
      </div>
      <div class="cert-dots" x-show="imgs.length > 1">
        <template x-for="(img, n) in imgs" :key="n">
          <button type="button" class="cert-dot" :class="{ 'active': n === i }"
                  @click="i = n" :aria-label="'Certifikát ' + (n + 1)"></button>
        </template>
      </div>
    </div>
  </div>
</div>
@endif

@once
@push('head')
<style>
  .cert { display: inline-block; }
  .cert-btn {
    display: inline-flex; align-items: center; gap: 9px;
    background: transparent; border: 1.5px solid var(--rule); color: var(--ink);
    font-family: var(--sans); font-size: 12px; font-weight: 600;
    letter-spacing: .08em; text-transform: uppercase; cursor: pointer;
    padding: 11px 18px; transition: border-color .2s, color .2s;
  }
  .cert-btn:hover { border-color: var(--red); color: var(--red); }
  .cert-btn svg { flex: none; }
  .cert-btn-count {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 18px; height: 18px; padding: 0 5px; border-radius: 9px;
    background: var(--red); color: #fff; font-size: 11px; line-height: 1; letter-spacing: 0;
  }
  .cert-modal {
    position: fixed; inset: 0; z-index: 10000;
    background: rgba(20,18,14,.80); backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center; padding: 32px;
  }
  .cert-modal-box { position: relative; max-width: 920px; width: 100%; }
  .cert-stage { position: relative; display: flex; align-items: center; justify-content: center; }
  .cert-stage img {
    max-width: 100%; max-height: 82vh; display: block; margin: 0 auto;
    background: #fff; border: 1px solid rgba(255,255,255,.14);
  }
  .cert-close {
    position: absolute; top: -46px; right: 0; z-index: 2;
    width: 38px; height: 38px; background: transparent; border: 1px solid rgba(255,255,255,.4);
    color: #fff; font-size: 20px; line-height: 1; cursor: pointer;
    transition: border-color .2s, background .2s;
  }
  .cert-close:hover { border-color: #fff; background: rgba(255,255,255,.08); }
  .cert-nav {
    position: absolute; top: 50%; transform: translateY(-50%); z-index: 2;
    width: 46px; height: 46px; background: rgba(20,18,14,.55); border: none; color: #fff;
    font-size: 30px; line-height: 1; cursor: pointer; transition: background .2s;
  }
  .cert-nav:hover { background: var(--red); }
  .cert-prev { left: 10px; }
  .cert-next { right: 10px; }
  .cert-dots { display: flex; gap: 8px; justify-content: center; margin-top: 18px; }
  .cert-dot {
    width: 28px; height: 3px; background: rgba(255,255,255,.3);
    border: none; padding: 0; cursor: pointer; transition: background .2s;
  }
  .cert-dot.active { background: var(--red); }
  @media (max-width: 900px) {
    .cert-modal { padding: 16px; }
    .cert-close { top: -42px; }
    .cert-nav { width: 40px; height: 40px; font-size: 26px; }
  }
</style>
@endpush
@endonce
