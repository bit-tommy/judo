<?php
use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.landing')]
#[Title('Pobyt japonských mistrů u nás – JC Raion-Ryu')]
class extends Component {}; ?>

<div class="pobyt-page">

<style>
  /* ─── Stránka „Pobyt japonských mistrů u nás" ─────────────────────────────
     Styly jsou cíleně scopované pod .pobyt-page, aby přebily globální pravidla
     landing layoutu (.hero, .masters, .master-* apod.) bez vedlejších efektů
     na ostatní stránky. Navbar a footer používají sdílený design layoutu. */

  .pobyt-page img { display: block; }

  /* ─── HERO (tmavé, dvousloupcové s fotkami) ─── */
  .pobyt-page .hero {
    min-height: 78svh;
    display: grid; grid-template-columns: 1fr 1fr;
    padding-top: 68px;
    background: var(--bg-dark);
    position: relative; overflow: hidden;
  }
  .pobyt-page .hero-left {
    display: flex; flex-direction: column; justify-content: center;
    padding: 80px 64px 80px 80px; position: relative; z-index: 2;
  }
  .pobyt-page .hero-kanji {
    position: absolute; left: 40%; top: 50%; transform: translate(-50%,-50%);
    font-family: var(--serif); font-size: 360px; line-height: 1;
    color: rgba(255,255,255,.03); font-weight: 700; pointer-events: none;
    user-select: none; z-index: 0;
  }
  .pobyt-page .breadcrumb {
    font-size: 11px; letter-spacing: .15em; text-transform: uppercase;
    color: rgba(255,255,255,.4); margin-bottom: 28px;
    display: flex; gap: 8px; align-items: center; flex-wrap: wrap;
  }
  .pobyt-page .breadcrumb a { color: rgba(255,255,255,.4); text-decoration: none; transition: color .2s; }
  .pobyt-page .breadcrumb a:hover { color: var(--red); }
  .pobyt-page .hero-headline {
    font-family: var(--serif); font-size: clamp(38px, 4.5vw, 64px);
    font-weight: 300; line-height: 1.08; letter-spacing: -.01em;
    margin-bottom: 28px; color: #fff;
  }
  .pobyt-page .hero-headline em { font-style: italic; color: var(--red); }
  .pobyt-page .hero-body {
    font-size: 16px; line-height: 1.8; color: rgba(255,255,255,.55);
    max-width: 480px; font-weight: 300; margin-bottom: 0;
  }
  .pobyt-page .hero-photos {
    position: relative; overflow: hidden;
    display: grid; grid-template-rows: 1fr 1fr; gap: 2px;
  }
  .pobyt-page .hero-photos img { width: 100%; height: 100%; object-fit: cover; filter: grayscale(.15) contrast(1.02); }

  /* ─── SECTION SHELL ─── */
  .pobyt-page section { padding: 110px 80px; }
  .pobyt-page .lead {
    font-size: 17px; line-height: 1.85; color: var(--ink-mid);
    max-width: 720px; font-weight: 300;
  }
  .pobyt-page .lead strong { font-weight: 600; color: var(--ink); }

  /* ─── MASTERS (světlé, střídavé řádky) ─── */
  .pobyt-page .masters { background: var(--bg); color: var(--ink); }
  .pobyt-page .master {
    display: grid; grid-template-columns: 380px 1fr; gap: 56px;
    align-items: start; padding: 64px 0; border-top: 1px solid var(--rule);
  }
  .pobyt-page .master:last-of-type { border-bottom: 1px solid var(--rule); }
  .pobyt-page .master.reverse { grid-template-columns: 1fr 380px; }
  .pobyt-page .master.reverse .master-photo-wrap { order: 2; }
  .pobyt-page .master-photo-wrap { position: relative; }
  .pobyt-page .master-photo {
    width: 100%; aspect-ratio: 3/4; object-fit: cover;
    border: 1px solid var(--rule); filter: grayscale(.1) contrast(1.02); background: #ece8e1;
  }
  .pobyt-page .master-photo-accent { position: absolute; top: -1px; left: -1px; width: 4px; height: 64px; background: var(--red); }
  .pobyt-page .master-dan {
    display: inline-block; background: var(--red); color: #fff;
    font-size: 10px; letter-spacing: .15em; text-transform: uppercase;
    padding: 6px 14px; font-weight: 700; margin-bottom: 20px;
  }
  .pobyt-page .master-name {
    font-family: var(--serif); font-size: 32px; font-weight: 300;
    line-height: 1.15; margin-bottom: 10px; color: var(--ink);
  }
  .pobyt-page .master-specialty {
    font-size: 12px; letter-spacing: .1em; text-transform: uppercase;
    color: var(--red); margin-bottom: 24px; font-weight: 600;
  }
  .pobyt-page .master-body { font-size: 15px; line-height: 1.8; color: var(--ink-mid); font-weight: 300; margin-bottom: 16px; }
  .pobyt-page .master-body strong { font-weight: 600; color: var(--ink); }
  .pobyt-page .master-body em { font-style: italic; }
  .pobyt-page .achievements { list-style: none; margin: 20px 0; display: flex; flex-direction: column; gap: 8px; }
  .pobyt-page .achievements li {
    font-size: 14px; color: var(--ink-mid); padding-left: 24px; position: relative; font-weight: 300;
  }
  .pobyt-page .achievements li::before {
    content: '1.'; position: absolute; left: 0; color: var(--red); font-weight: 700; font-family: var(--serif);
  }
  .pobyt-page .memoriam {
    margin-top: 16px; padding: 16px 20px; border-left: 3px solid var(--red);
    background: rgba(192,38,30,.04); font-size: 14px; color: var(--ink-mid); font-style: italic; line-height: 1.7;
  }
  .pobyt-page .memoriam strong { font-style: normal; color: var(--red); letter-spacing: .04em; }

  /* ─── TANAKA FEATURE (tmavé) ─── */
  .pobyt-page .tanaka { background: var(--bg-dark); color: #fff; position: relative; overflow: hidden; }
  .pobyt-page .tanaka .section-title { color: #fff; }
  .pobyt-page .tanaka-kana {
    position: absolute; right: -40px; bottom: -80px;
    font-family: var(--serif); font-size: 320px; font-weight: 700;
    color: rgba(255,255,255,.025); line-height: 1; pointer-events: none; user-select: none;
  }
  .pobyt-page .tanaka-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 56px; align-items: center; position: relative; z-index: 1; }
  .pobyt-page .tanaka-body { font-size: 15px; line-height: 1.85; color: rgba(255,255,255,.6); font-weight: 300; margin-bottom: 16px; }
  .pobyt-page .tanaka-body strong { color: #fff; font-weight: 600; }
  .pobyt-page .tanaka-photos { display: grid; grid-template-columns: 1fr 1fr; gap: 2px; }
  .pobyt-page .tanaka-photos img { width: 100%; aspect-ratio: 3/4; object-fit: cover; filter: grayscale(.2) contrast(1.05); }
  .pobyt-page .tanaka-units { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 24px; }
  .pobyt-page .unit-chip {
    font-size: 10px; letter-spacing: .1em; text-transform: uppercase;
    color: rgba(255,255,255,.6); border: 1px solid rgba(255,255,255,.15);
    padding: 7px 14px; font-weight: 500;
  }

  /* ─── ČASOVÁ OSA / GALERIE ─── */
  .pobyt-page .timeline { background: #F0EDE8; }
  .pobyt-page .tl-entry {
    display: grid; grid-template-columns: 220px 1fr; gap: 48px;
    padding: 48px 0; border-top: 1px solid var(--rule); align-items: start;
  }
  .pobyt-page .tl-entry:last-child { border-bottom: 1px solid var(--rule); }
  .pobyt-page .tl-date {
    font-family: var(--serif); font-size: 22px; font-weight: 400; color: var(--ink); line-height: 1.2; margin-bottom: 8px;
  }
  .pobyt-page .tl-place { font-size: 12px; letter-spacing: .1em; text-transform: uppercase; color: var(--red); font-weight: 600; }
  .pobyt-page .tl-desc { font-size: 14px; color: var(--ink-mid); line-height: 1.7; margin-top: 12px; font-weight: 300; }
  .pobyt-page .tl-photos { display: grid; gap: 2px; }
  .pobyt-page .tl-photos.g1 { grid-template-columns: 1fr; max-width: 420px; }
  .pobyt-page .tl-photos.g2 { grid-template-columns: 1fr 1fr; }
  .pobyt-page .tl-photos.g3 { grid-template-columns: 1fr 1fr 1fr; }
  .pobyt-page .tl-photos img {
    width: 100%; aspect-ratio: 4/3; object-fit: cover;
    filter: grayscale(.12) contrast(1.02); background: #e3ded6;
    transition: filter .3s, transform .3s;
  }
  .pobyt-page .tl-photos img:hover { filter: grayscale(0) contrast(1.05); transform: scale(1.01); }

  /* ─── CTA ─── */
  .pobyt-page .cta { background: var(--bg); text-align: center; padding: 100px 80px; }
  .pobyt-page .cta .section-eyebrow { justify-content: center; }
  .pobyt-page .cta .section-eyebrow::before { display: none; }
  .pobyt-page .cta-title { font-family: var(--serif); font-size: clamp(28px,3.4vw,42px); font-weight: 300; margin-bottom: 16px; }
  .pobyt-page .cta-sub { font-size: 16px; color: var(--ink-mid); font-weight: 300; margin-bottom: 36px; max-width: 540px; margin-left: auto; margin-right: auto; line-height: 1.7; }
  .pobyt-page .cta-actions { display: flex; gap: 16px; justify-content: center; }

  @media (max-width: 900px) {
    .pobyt-page .hero { grid-template-columns: 1fr; }
    .pobyt-page .hero-left { padding: 56px 28px; }
    .pobyt-page .hero-photos { height: 320px; }
    .pobyt-page section { padding: 64px 28px; }
    .pobyt-page .master, .pobyt-page .master.reverse { grid-template-columns: 1fr; gap: 28px; }
    .pobyt-page .master.reverse .master-photo-wrap { order: 0; }
    .pobyt-page .master-photo { aspect-ratio: 4/3; }
    .pobyt-page .tanaka-grid { grid-template-columns: 1fr; gap: 40px; }
    .pobyt-page .tl-entry { grid-template-columns: 1fr; gap: 20px; }
    .pobyt-page .tl-photos.g3 { grid-template-columns: 1fr 1fr; }
    .pobyt-page .cta-actions { flex-direction: column; align-items: center; }
  }
</style>

{{-- NAV (sdílená komponenta) --}}
<x-ui.landing-nav />

{{-- HERO --}}
<section class="hero">
  <div class="hero-kanji">師</div>
  <div class="hero-left">
    <div class="breadcrumb">
      <a href="{{ route('home') }}">Úvod</a> <span>/</span>
      <a href="{{ route('home') }}#mistri">Kódókan Judo</a> <span>/</span>
      <span style="color:rgba(255,255,255,.65);">Pobyt mistrů</span>
    </div>
    <div class="hero-eyebrow">Od roku 2004</div>
    <h1 class="hero-headline">Pobyt japonských<br><em>mistrů</em> u nás</h1>
    <p class="hero-body">
      Tréninkové pobyty mistrů, kteří přinášejí tradiční Judo přímo z Japonska. Díky nim vedeme náš oddíl jiným směrem než ryze sportovním — cestou úcty, techniky a hloubky.
    </p>
  </div>
  <div class="hero-photos">
    <img src="{{ asset('images/mistri/2015.jpg') }}" alt="Trénink s japonskými mistry" loading="eager" fetchpriority="high">
    <img src="{{ asset('images/mistri/20155.jpg') }}" alt="Seminář japonského juda" loading="lazy">
  </div>
</section>

{{-- INTRO --}}
<section style="padding-bottom: 40px;">
  <div class="section-eyebrow">Tréninkový pobyt japonských mistrů</div>
  <h2 class="section-title">Judo jiným směrem<br>než ryze sportovním</h2>
  <p class="lead">
    Japonští mistři do naší republiky jezdí <strong>od roku 2004</strong>. Měli jsme možnost pod nimi několikrát ročně cvičit a od té doby se snažíme vést judo tradiční cestou. Dodnes spolupracujeme s lidmi, kteří se pravidelně vzdělávají v Japonsku, a s mistry, jejichž odkaz předáváme dál.
  </p>
</section>

{{-- MASTERS --}}
<section class="masters" style="padding-top: 20px;">

  {{-- Okada --}}
  <div class="master">
    <div class="master-photo-wrap">
      <div class="master-photo-accent"></div>
      <img class="master-photo" src="{{ asset('images/mistri/toshikazu-okada.jpg') }}" alt="Toshikazu Okada Sensei" loading="lazy">
    </div>
    <div>
      <span class="master-dan">7. Dan Kódókan Judo</span>
      <div class="master-name">Toshikazu Okada Sensei</div>
      <div class="master-specialty">Judo-Kata · Ne-waza</div>
      <p class="master-body">
        Byl přímým žákem <strong>Tsunatany Ody senseie</strong> — mistra, který spolupracoval s Jigorem Kanem při zakládání Kódókanu, „kolébky juda". Sensei Okada se specializoval na výuku Judo-Kata a techniky Ne-waza, tedy boj na zemi: držení, škrcení, páčení, přechody, úniky a kombinace.
      </p>
      <p class="master-body">
        Rád cestoval po celém světě a vyučoval v řadě škol. V Kódókanu v Tokiu působil jako expert v metodické skupině, která má na starosti výuku Kódókan Judo Kata.
      </p>
      <div class="memoriam">
        Dne 20. 12. 2014 nás mistr navždy opustil ve věku 80 let. Vzpomínáme a děkujeme, že nám ukázal cestu Juda, po které jej můžeme následovat. <strong>Nikdy nezapomeneme.</strong>
      </div>
    </div>
  </div>

  {{-- Kaida --}}
  <div class="master reverse">
    <div>
      <span class="master-dan">8. Dan Kódókan Judo</span>
      <div class="master-name">Toshio Kaida Sensei</div>
      <div class="master-specialty">Kuzushi · Tai-sabaki</div>
      <p class="master-body">
        Sensei Kaida vychoval spoustu špičkových japonských judistů. V současné době je v penzi, přesto se ale stále věnuje cvičení juda. V Japonsku vede vlastní školu juda a kliniku.
      </p>
      <p class="master-body">
        V minulosti působil jako instruktor <strong>Tokijské metropolitní policie</strong>. Jeho specializací jsou mimo jiné různé formy Kuzushi a Tai-sabaki — vychýlení a obraty těla.
      </p>
    </div>
    <div class="master-photo-wrap">
      <div class="master-photo-accent"></div>
      <img class="master-photo" src="{{ asset('images/mistri/kajda.jpg') }}" alt="Toshio Kaida Sensei" loading="lazy">
    </div>
  </div>

  {{-- Kaji --}}
  <div class="master">
    <div class="master-photo-wrap">
      <div class="master-photo-accent"></div>
      <img class="master-photo" src="{{ asset('images/mistri/20156.jpg') }}" alt="Yasuaki Kaji Sensei" loading="lazy">
    </div>
    <div>
      <span class="master-dan">5. Dan Judo · 4. Dan Ju-jutsu · 2. Dan Sambo · 2. Dan Combat Wrestling</span>
      <div class="master-name">Yasuaki Kaji Sensei</div>
      <div class="master-specialty">Sebeobrana · Combat Wrestling · Bodyguarding</div>
      <p class="master-body">
        Mistr je stále aktivní závodník, účastní se mnoha šampionátů v Judo a Combat-Wrestlingu nejen v Japonsku. Mezi jeho úspěchy patří:
      </p>
      <ul class="achievements">
        <li>místo na US International Combat Wrestling Championship (62 kg)</li>
        <li>místo na All Japan Masters in Combat Wrestling</li>
        <li>místo na All Japan Masters in Judo (66 kg, M4)</li>
      </ul>
      <p class="master-body">
        V Japonsku má vlastní dojo, kde vyučuje špičku Japonska — přijímá žáky pouze na speciální pozvánku. Má bohaté zkušenosti z oblasti sebeobrany a bodyguardingu. V minulosti byl <strong>velitelem ochranky japonské vlády</strong> a řadu let působil jako osobní strážce japonského ministerského předsedy. Vyučoval také speciální složky armády a policie.
      </p>
    </div>
  </div>

  {{-- Hirotaka Okada --}}
  <div class="master reverse">
    <div>
      <span class="master-dan">8. Dan Kódókan Judo</span>
      <div class="master-name">Hirotaka Okada Sensei</div>
      <div class="master-specialty">Mistr světa 1987 &amp; 1991 · Olympijský bronz 1992</div>
      <p class="master-body">
        Mistr světa z let 1987 a 1991, bronzový medailista z OH 1992 a dlouholetý trenér japonské reprezentace.
      </p>
      <p class="master-body">
        Seminář trenérů v ČR s mistrem Okadou proběhl v Praze <strong>27. 10. 2013</strong> pod záštitou ČSJu. Spočíval v metodice nácviku technik v postoji i na zemi pro mládež ve věku osmi až dvanácti let, se zaměřením především na jejich bezpečné provádění.
      </p>
    </div>
    <div class="master-photo-wrap">
      <div class="master-photo-accent"></div>
      <img class="master-photo" src="{{ asset('images/mistri/20157.jpg') }}" alt="Seminář s Hirotaka Okada" loading="lazy">
    </div>
  </div>

  {{-- Fujita --}}
  <div class="master">
    <div class="master-photo-wrap">
      <div class="master-photo-accent"></div>
      <img class="master-photo" src="{{ asset('images/mistri/fujita.JPG') }}" alt="Fujita Sensei" loading="lazy">
    </div>
    <div>
      <span class="master-dan">7. Dan Kódókan · Tokio</span>
      <div class="master-name">Fujita Sensei</div>
      <div class="master-specialty">Goshin Jutsu · Soukromé lekce</div>
      <p class="master-body">
        Soukromé lekce juda a Goshin Jutsu pod vedením Fujita senseie (7. dan) přímo v <strong>Kódókanu v Tokiu</strong> — v srdci světového juda. Jedinečná možnost čerpat z nejvyššího pramene tradiční výuky.
      </p>
    </div>
  </div>

</section>

{{-- TANAKA FEATURE --}}
<section class="tanaka">
  <div class="tanaka-kana">武</div>
  <div class="section-eyebrow" style="position:relative;z-index:1;">Velmistr</div>
  <h2 class="section-title" style="position:relative;z-index:1;">Koshiro Tanaka<br>10. Dan Hiko-ryu Taijutsu</h2>
  <div class="tanaka-grid">
    <div>
      <p class="tanaka-body">
        Jedná se o rodinný styl senseie Tanaky. Toto bojové umění vychází ze samurajského stylu, jehož spoluzakladatelem byl mistr Takeda — učitel Morihei Ueshiby, zakladatele Aikido.
      </p>
      <p class="tanaka-body">
        Mistr Tanaka je velmi energický, výjimečný člověk a velký učitel. V 80. letech bojoval 6 let ve válce v Afghánistánu a v roce 2000 v Iráku, aby zjistil, zda je připraven zemřít. <strong>Obohatil tento styl o více než 1 000 technik</strong> a o zkušenosti z bitevních polí.
      </p>
      <p class="tanaka-body">
        Dodnes vyučuje speciální jednotky po celém světě. Spolupracujeme s ním od června roku 2016 ve spolupráci s dojo Hiko-ryu v Budapešti. Vedoucí Hiko-ryu Taijutsu Czech <strong>Filip Rubínek sensei</strong> pod jeho vedením dosáhl titulu Renshi a 6. danu.
      </p>
      <div class="tanaka-units">
        <span class="unit-chip">USA · SWAT Team</span>
        <span class="unit-chip">USA · Marines</span>
        <span class="unit-chip">Thajsko · Protidrogová jednotka</span>
        <span class="unit-chip">Francie · Policie</span>
        <span class="unit-chip">Ochranka Dalajlámy</span>
        <span class="unit-chip">Vietnam · Filipíny</span>
      </div>
    </div>
    <div class="tanaka-photos">
      <img src="{{ asset('images/mistri/Tt.JPG') }}" alt="Velmistr Tanaka" loading="lazy">
      <img src="{{ asset('images/mistri/myy.jpg') }}" alt="Trénink Hiko-ryu Taijutsu" loading="lazy">
      <img src="{{ asset('images/mistri/jas.jpg') }}" alt="Filip Rubínek s velmistrem Tanakou" loading="lazy">
      <img src="{{ asset('images/mistri/IMG_20170211_081327.jpg') }}" alt="Seminář Taijutsu" loading="lazy">
    </div>
  </div>
</section>

{{-- ČASOVÁ OSA / GALERIE --}}
<section class="timeline">
  <div class="section-eyebrow">Galerie pobytů a seminářů</div>
  <h2 class="section-title">Společná cesta<br>2016 — 2022</h2>

  <div class="tl-entry">
    <div class="tl-meta">
      <div class="tl-date">2016 — 2017</div>
      <div class="tl-place">Japonsko · Říjen — Únor</div>
      <p class="tl-desc">Tréninkové pobyty přímo v Japonsku a začátek spolupráce s velmistrem Tanakou.</p>
    </div>
    <div class="tl-photos g2">
      <img src="{{ asset('images/mistri/IMG_3026.JPG') }}" alt="Japonsko 2016" loading="lazy">
      <img src="{{ asset('images/mistri/IMG_7662.JPG') }}" alt="Trénink v Japonsku" loading="lazy">
    </div>
  </div>

  <div class="tl-entry">
    <div class="tl-meta">
      <div class="tl-date">Berlín 2017</div>
      <div class="tl-place">Mistři z Kódókanu</div>
      <p class="tl-desc">Školení juda pod japonskými mistry z Kódókanu v Berlíně.</p>
    </div>
    <div class="tl-photos g2">
      <img src="{{ asset('images/mistri/00002.jpg') }}" alt="Školení Berlín 2017" loading="lazy">
      <img src="{{ asset('images/mistri/000001.JPG') }}" alt="Mistři z Kódókanu" loading="lazy">
    </div>
  </div>

  <div class="tl-entry">
    <div class="tl-meta">
      <div class="tl-date">Praha 2018</div>
      <div class="tl-place">Velmistr Tanaka &amp; tým</div>
      <p class="tl-desc">Školení v Praze s velmistrem Tanakou a jeho týmem z Japonska. V květnu navázal policejní seminář Taijutsu.</p>
    </div>
    <div class="tl-photos g3">
      <img src="{{ asset('images/mistri/xxxx.jpg') }}" alt="Školení Praha 2018" loading="lazy">
      <img src="{{ asset('images/mistri/DSCF0321.JPG') }}" alt="Policejní seminář Taijutsu" loading="lazy">
      <img src="{{ asset('images/mistri/DSCF0245.JPG') }}" alt="Policejní seminář Taijutsu 2018" loading="lazy">
    </div>
  </div>

  <div class="tl-entry">
    <div class="tl-meta">
      <div class="tl-date">Duben 2019</div>
      <div class="tl-place">Honbu Dójó · Tokio</div>
      <p class="tl-desc">Celý tým Hiko-ryu Taijutsu Czech s velmistrem Tanakou v hlavním dojo v Tokiu.</p>
    </div>
    <div class="tl-photos g1">
      <img src="{{ asset('images/mistri/jj.jpg') }}" alt="Tým Hiko-ryu Czech v Tokiu 2019" loading="lazy">
    </div>
  </div>

  <div class="tl-entry">
    <div class="tl-meta">
      <div class="tl-date">Květen — Červen 2022</div>
      <div class="tl-place">Budapešť &amp; Praha</div>
      <p class="tl-desc">Velmistr Tanaka opět v Evropě — Siken dojo v Budapešti a hombu dojo v Praze s českým Hiko týmem.</p>
    </div>
    <div class="tl-photos g3">
      <img src="{{ asset('images/mistri/FB_IMG_1653473518868.jpg') }}" alt="Tanaka v Evropě 2022" loading="lazy">
      <img src="{{ asset('images/mistri/IMG_20220602_202501.jpg') }}" alt="Praha hombu dojo 2022" loading="lazy">
      <img src="{{ asset('images/mistri/IMG_20220526_091718.jpg') }}" alt="Siken dojo Budapešť 2022" loading="lazy">
    </div>
  </div>

  <div class="tl-entry">
    <div class="tl-meta">
      <div class="tl-date">Listopad 2022</div>
      <div class="tl-place">Berlín · Školení Kata</div>
      <p class="tl-desc">Školení kata pod mistry z Kódókanu — Motonari Sameshima sensei (8. dan, specialista na Ju-no kata) a Shuji Ohshima sensei (7. dan, mistr světa v Nage-no katě).</p>
    </div>
    <div class="tl-photos g2">
      <img src="{{ asset('images/mistri/IMG_20221104_171703.jpg') }}" alt="Školení kata Berlín 2022" loading="lazy">
      <img src="{{ asset('images/mistri/IMG_20220526_091346.jpg') }}" alt="Velmistr Tanaka s Filipem Rubínkem 2022" loading="lazy">
    </div>
  </div>

</section>

{{-- CTA --}}
<section class="cta">
  <div class="section-eyebrow">Přijďte na tatami</div>
  <div class="cta-title">Trénujte v tradici japonských mistrů</div>
  <p class="cta-sub">Zkušenosti z pobytů japonských mistrů přenášíme přímo do výuky našeho oddílu. Přijďte se podívat — bez závazků.</p>
  <div class="cta-actions">
    <a href="{{ route('home') }}#kontakt" class="btn-primary">Kontakt &amp; tréninky</a>
    <a href="{{ route('home') }}" class="btn-ghost">Zpět na úvod</a>
  </div>
</section>

{{-- FOOTER (sdílená komponenta) --}}
<x-ui.landing-footer />

</div>
