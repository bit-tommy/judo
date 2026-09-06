{{--
    Popup s pozvánkou na víkendový kemp Nebákov (podzim 2026) — partnerská akce
    Ronin Dojo + náš trenér Filip Rubínek.

    DOČASNÉ: od 25. 9. 2026 (den odjezdu na kemp) se komponenta sama přestane
    vykreslovat. Smazat = odstranit tento soubor + řádek <x-ui.camp-popup />
    v resources/views/livewire/pages/home-page.blade.php.

    Zobrazuje se jen na homepage, 4 s po načtení, jednou za návštěvu
    (sessionStorage klíč rr-kemp-2026 se zapíše při zobrazení — schválně
    sessionStorage, ne localStorage: v rámci jedné návštěvy neotravovat, při
    další znovu ukázat).
    Nativní <dialog> + showModal(): focus trap, Escape a ::backdrop zdarma.
--}}
@php
    $campCutoff = \Illuminate\Support\Carbon::parse('2026-09-25');
    $campMailSubject = rawurlencode('Víkendový kemp Nebákov 2026');
@endphp

@if (now()->lt($campCutoff))
<dialog
    class="camp-popup"
    aria-labelledby="camp-title"
    x-data="{
        init() {
            let seen = false;
            try { seen = sessionStorage.getItem('rr-kemp-2026') === '1'; } catch (e) {}
            if (seen) { return; }
            setTimeout(() => {
                if (!this.$el.isConnected || this.$el.open) { return; }
                this.$el.showModal();
                // Jako zobrazené značíme už při otevření, ne při zavření: událost
                // close se nemusí spolehlivě doručit (odchod přes wire:navigate).
                try { sessionStorage.setItem('rr-kemp-2026', '1'); } catch (e) {}
            }, 4000);
        },
    }"
    @click="$event.target === $el && $el.close()"
>
  <div class="camp-popup-box">
    <form method="dialog"><button type="submit" class="camp-popup-close" aria-label="Zavřít">✕</button></form>

    <div class="section-eyebrow">Pozvánka · 25.–28. 9. 2026</div>
    <h2 id="camp-title" class="section-title camp-popup-title">Víkendový kemp Nebákov – podzim 2026</h2>
    <p class="camp-popup-subtitle">Aikido · Hiko-ryu · Judo · Sebeobrana</p>
    <p class="camp-popup-lead">Přihlas se na víkend plný disciplíny, tréninku, pohybových aktivit a tradičních bojových umění pod vedením zkušených mistrů.</p>

    <div class="camp-popup-meta">
      <div>
        <span class="camp-popup-meta-label">Kdy &amp; kde</span>
        <span class="camp-popup-meta-value">25.–28. 9. 2026 · Chata Nebákov</span>
      </div>
      <div>
        <span class="camp-popup-meta-label">Vedou</span>
        <span class="camp-popup-meta-value">Mgr. Martin Snopek – 3. dan Aikido, 2. dan Jiu Jitsu<br>Filip Rubínek, Renshi – 6. dan Hiko-ryu, 3. dan Judo</span>
      </div>
    </div>

    <h3 class="camp-popup-subhead">Program</h3>
    <ul class="camp-popup-program">
      <li><strong>Pá 25. 9.</strong> – příjezd a příprava: stavba tatami, společná večeře, promítání videí</li>
      <li><strong>So–Ne 26.–27. 9.</strong> – Kaizen, Shuhari, Zanshin (program rozdělen na děti a dospělé): dechová cvičení, Aikido, Hiko ryu, Judo, hry a techniky, zbraně, společné posezení u čaje, meditace</li>
      <li><strong>Po 28. 9.</strong> – závěr a odjezd: aplikace technik, Goshin jutsu, Buki waza, úklid a odjezd</li>
    </ul>

    <h3 class="camp-popup-subhead">Praktické informace</h3>
    <ul class="camp-popup-list">
      <li>Příjezd v pá 25. 9. od 17:00</li>
      <li>Páteční večeře formou švédských stolů z vlastních zásob</li>
      <li>So–Ne zajištěna plná penze včetně pitného režimu</li>
      <li>Tréninkové bloky pro děti a pro dospělé</li>
      <li>Odjezd v po 28. 9. od 11:00 (před obědem)</li>
      <li>Povinná výbava: keiko gi, přezůvky, lahev na pití, zbraně</li>
    </ul>

    <p class="camp-popup-price"><strong>Cena:</strong> 3 500 Kč</p>

    <h3 class="camp-popup-subhead">Registrace a kontakt</h3>
    <p class="camp-popup-contacts">
      Hiko-ryu Czech – Filip Rubínek · <a href="https://www.sebeobranapraha.eu" target="_blank" rel="noopener">sebeobranapraha.eu</a> · <a href="mailto:filip@kurzysebeobrany.cz">filip@kurzysebeobrany.cz</a><br>
      Ronin Dojo – Martin Snopek · <a href="https://www.ronin-dojo.cz" target="_blank" rel="noopener">ronin-dojo.cz</a> · <a href="mailto:aikidocimice@email.cz">aikidocimice@email.cz</a>
    </p>

    <div class="camp-popup-actions">
      <a href="mailto:filip@kurzysebeobrany.cz?subject={{ $campMailSubject }}" class="btn-primary">Chci se přihlásit</a>
      <form method="dialog"><button type="submit" class="btn-ghost">Zavřít</button></form>
    </div>
  </div>
</dialog>

@once
@push('head')
<style>
  .camp-popup {
    padding: 0; border: none; margin: auto;
    width: min(720px, calc(100% - 40px)); max-height: calc(100dvh - 48px); overflow: auto;
    background: var(--bg); border-top: 3px solid var(--red);
    box-shadow: 0 30px 90px rgba(0,0,0,.45);
  }
  /* Animace otevření i zavření: @starting-style + allow-discrete. Starší
     prohlížeče bez podpory dialog jen zobrazí/skryjí bez animace. */
  .camp-popup {
    opacity: 0; transform: translateY(28px) scale(.97);
    transition: opacity .38s ease, transform .45s cubic-bezier(.2,.8,.2,1),
                overlay .45s allow-discrete, display .45s allow-discrete;
  }
  .camp-popup[open] { opacity: 1; transform: none; }
  @starting-style { .camp-popup[open] { opacity: 0; transform: translateY(28px) scale(.97); } }
  .camp-popup::backdrop {
    background: rgba(20,18,14,0); backdrop-filter: blur(0);
    transition: background .45s ease, backdrop-filter .45s ease,
                overlay .45s allow-discrete, display .45s allow-discrete;
  }
  .camp-popup[open]::backdrop { background: rgba(20,18,14,.62); backdrop-filter: blur(4px); }
  @starting-style { .camp-popup[open]::backdrop { background: rgba(20,18,14,0); backdrop-filter: blur(0); } }
  .camp-popup-box { position: relative; padding: 44px; }
  .camp-popup-close {
    position: absolute; top: 12px; right: 12px; z-index: 5;
    width: 40px; height: 40px; background: none; border: none;
    font-size: 22px; line-height: 1; color: var(--ink-light); cursor: pointer;
    font-family: var(--sans); transition: color .2s;
  }
  .camp-popup-close:hover { color: var(--red); }
  .camp-popup-title { font-size: clamp(26px, 3.4vw, 36px); margin-bottom: 12px; }
  .camp-popup-subtitle {
    font-family: var(--sans); font-size: 13px; letter-spacing: .04em;
    color: var(--ink-mid); margin-bottom: 20px;
  }
  .camp-popup-lead { font-size: 16px; line-height: 1.75; color: var(--ink-mid); font-weight: 300; margin-bottom: 32px; }
  .camp-popup-meta { display: grid; gap: 16px; margin-bottom: 32px; padding: 20px 24px; background: rgba(28,25,20,.04); }
  .camp-popup-meta-label {
    display: block; font-size: 11px; letter-spacing: .12em; text-transform: uppercase;
    color: var(--ink-light); font-weight: 600; margin-bottom: 4px;
  }
  .camp-popup-meta-value { display: block; font-size: 15px; color: var(--ink); line-height: 1.6; }
  .camp-popup-subhead {
    font-family: var(--sans); font-size: 13px; letter-spacing: .08em; text-transform: uppercase;
    color: var(--red); font-weight: 600; margin: 32px 0 14px;
  }
  .camp-popup-program, .camp-popup-list { list-style: none; display: grid; gap: 10px; }
  .camp-popup-program li, .camp-popup-list li {
    font-size: 15px; line-height: 1.7; color: var(--ink-mid); padding-left: 18px; position: relative;
  }
  .camp-popup-program li::before, .camp-popup-list li::before {
    content: ''; position: absolute; left: 0; top: 10px; width: 6px; height: 1px; background: var(--red);
  }
  .camp-popup-program li strong, .camp-popup-list li strong { color: var(--ink); }
  .camp-popup-price { font-size: 17px; color: var(--ink); margin: 28px 0; }
  .camp-popup-contacts { font-size: 14px; line-height: 1.8; color: var(--ink-mid); }
  .camp-popup-contacts a { color: var(--red); }
  .camp-popup-actions { display: flex; gap: 16px; flex-wrap: wrap; margin-top: 36px; }

  @media (max-width: 760px) {
    .camp-popup { width: 100%; max-width: none; max-height: 100dvh; }
    .camp-popup-box { padding: 56px 24px 36px; }
  }
  @media (prefers-reduced-motion: reduce) {
    .camp-popup, .camp-popup[open] { transform: none; transition-duration: .2s; }
  }
</style>
@endpush
@endonce
@endif
