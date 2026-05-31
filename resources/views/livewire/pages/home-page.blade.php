<?php
use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.landing')]
#[Title('Škola Bojových Umění Rubidó – JC Raion-Ryu')]
class extends Component {}; ?>

<div>

<!-- NAV -->
<nav>
  <x-ui.logo href="#" size="48px" />
  <ul class="nav-links">
    <li><a href="#judo">Judo</a></li>
    <li><a href="#techniky">Techniky</a></li>
    <li><a href="#deti">Děti</a></li>
    <li><a href="#mistri">Japonští mistři</a></li>
    <li><a href="#japonsko">Japonsko</a></li>
    <li><a href="#kontakt">Kontakt</a></li>
  </ul>
  <a href="#kontakt" class="nav-cta">Přijďte trénovat</a>
</nav>

<!-- HERO -->
<section class="hero" style="padding: 0; padding-top: 68px;">
  <div class="hero-left">
    <div class="hero-eyebrow">Od roku 2010 · Praha 8 & Vodochody</div>
    <h1 class="hero-headline">
      Bojové umění.<br>
      <em>Tradice.</em><br>
      <strong>Judo.</strong>
    </h1>
    <p class="hero-body" style="margin-bottom:20px;">
      Kódókan Judo není jen sport — je to cesta, bojové umění s možností soutěže. V naší škole navazujeme na tradiční formu výuky po vzoru zakladatele prof. Jigora Kana. Cvičíme pod vedením japonských mistrů, se kterými se setkáváme osobně. Máme pobočku na Praze 8 a v obci Vodochody.
    </p>
    <p class="hero-body">
      Kódókan Judo je tradiční japonské bojové umění, které založil profesor Jigoró Kanó v roce 1882. Judo překládáme jako „jemná cesta". Od roku 1964 se stalo Judo také olympijským sportem.
    </p>
    <div class="hero-actions">
      <a href="#kontakt" class="btn-primary">Začněte trénovat</a>
      <a href="#judo" class="btn-ghost">Poznat Judo</a>
    </div>
  </div>
  <div class="hero-right">
    <div class="hero-slideshow" aria-label="Fotografie z tréninku">
      @foreach (['hero1','hero2','hero3','hero4','hero5'] as $i => $img)
        <img src="{{ asset('images/hero/' . $img . '.jpeg') }}" alt="Trénink juda v JC Raion-Ryu" class="hero-slide{{ $i === 0 ? ' is-active' : '' }}" {{ $i === 0 ? 'fetchpriority=high' : 'loading=lazy' }}>
      @endforeach
      <div class="hero-slideshow-dots">
        @foreach (['hero1','hero2','hero3','hero4','hero5'] as $i => $img)
          <span class="hero-dot{{ $i === 0 ? ' is-active' : '' }}"></span>
        @endforeach
      </div>
    </div>
    <div class="hero-kanji">柔</div>
    <div class="hero-image-area">
      <div class="hero-stats">
        <div class="stat">
          <span class="stat-num">2010</span>
          <span class="stat-label">Rok vzniku</span>
        </div>
        <div class="stat">
          <span class="stat-num">5+</span>
          <span class="stat-label">Japonských mistrů</span>
        </div>
        <div class="stat">
          <span class="stat-num">1882</span>
          <span class="stat-label">Vznik Kódókanu</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- MAXIMS -->
<div class="maxims">
  <div class="maxims-inner">
    <div class="maxim">
      <span class="maxim-romaji">Ju yoku go o seisu</span>
      <div class="maxim-title">Jemnost ovládá sílu</div>
      <p class="maxim-body">Základní princip Juda — technika a obratnost vítězí nad hrubou silou.</p>
    </div>
    <div class="maxim">
      <span class="maxim-romaji">Jita kyoei</span>
      <div class="maxim-title">Vzájemný prospěch a prosperita</div>
      <p class="maxim-body">Judo se cvičí ve dvojici — spolupráce je podmínkou růstu obou.</p>
    </div>
    <div class="maxim">
      <span class="maxim-romaji">Seiryoku zen yo</span>
      <div class="maxim-title">Minimální úsilí, maximální účinnost</div>
      <p class="maxim-body">Každý pohyb má svůj smysl. Nic navíc, nic zbytečně.</p>
    </div>
  </div>
</div>

<!-- ABOUT / HISTORY -->
<section id="judo">
  <div class="about">
    <div>
      <div class="section-eyebrow">Historie</div>
      <h2 class="section-title">Od chrámu Eishoji<br>na naše tatami</h2>
      <p class="about-body">
        Kódókan Judo vzniklo v roce 1882, kdy profesor Jigoro Kano — sám zprvu terč šikany — otevřel školu v tokijském chrámu s devíti žáky. Vycházel z tradičního ju-jutsu a vytvořil umění dostupné každému: bezpečné, účinné, hluboce filozofické.
      </p>
      <p class="about-body">
        Do Československa přivezl Judo prof. František Smotlacha již v letech 1907–1910. Jigoro Kano naše území osobně navštívil třikrát — v letech 1933 a 1936. V roce 1964 se Judo stalo olympijským sportem.
      </p>
      <p class="about-body">
        Náš oddíl <strong>JC Raion-Ryu</strong> vznikl v září 2010 pod vedením senseie Filipa Rubínka, který osobně studoval pod japonskými mistry včetně velmistra Koshira Tanaky (10. dan), u nějž dosáhl titulu <em>Renshi</em> a hodnosti 6. dan.
      </p>
      <a href="#mistri" class="btn-ghost" style="margin-top:16px; display:inline-block;">Japonští mistři u nás</a>
    </div>
    <div class="about-image has-photo">
      <img src="{{ asset('images/kano-13_orig.jpg') }}" alt="Jigoro Kano, zakladatel juda" class="about-photo" loading="lazy">
      <div class="about-accent" style="z-index: 3;"></div>
      <span class="about-image-label" style="z-index: 3;">Foto: Jigoro Kano / archiv / tatami Kódókan</span>
    </div>
  </div>
</section>

<!-- TECHNIQUES -->
<section id="techniky" class="techniques">
  <div class="section-eyebrow">Techniky</div>
  <h2 class="section-title">Systém Kódókan Judo</h2>
  <div class="techniques-grid">
    <div class="tech-card">
      <span class="tech-num">01</span>
      <div class="tech-name">Tachi-waza<br><small style="font-size:14px;font-family:var(--sans);color:var(--ink-light);">Techniky boje v postoji</small></div>
      <p class="tech-body">Hody rukama (Te-waza), bokem (Koshi-waza) a nohama (Ashi-waza). Základ soutěžního i tradičního juda.</p>
    </div>
    <div class="tech-card">
      <span class="tech-num">02</span>
      <div class="tech-name">Ne-waza<br><small style="font-size:14px;font-family:var(--sans);color:var(--ink-light);">Techniky na zemi</small></div>
      <p class="tech-body">Souhrnný název boje na zemi. Rozlišuje se na techniky Katame-waza (znehybnění) — Osaekomi-waza (držení), Kansetsu-waza (páčení) a Shime-waza (škrcení).</p>
    </div>
    <div class="tech-card">
      <span class="tech-num">03</span>
      <div class="tech-name">Kata<br><small style="font-size:14px;font-family:var(--sans);color:var(--ink-light);">Přesně definované formy</small></div>
      <p class="tech-body">Osm tradičních kat v Kódókanu. Od Nage no Kata po Koshiki no Kata. Cesta k pochopení principů juda.</p>
    </div>
    <div class="tech-card">
      <span class="tech-num">04</span>
      <div class="tech-name">Sutemi-waza<br><small style="font-size:14px;font-family:var(--sans);color:var(--ink-light);">Techniky strhů</small></div>
      <p class="tech-body">Hody pomocí strhu, při nichž bojovník strhne soupeře k zemi a využije toho k hodu. Pokročilá škola juda.</p>
    </div>
    <div class="tech-card">
      <span class="tech-num">05</span>
      <div class="tech-name">Goshin Jutsu<br><small style="font-size:14px;font-family:var(--sans);color:var(--ink-light);">Moderní sebeobrana</small></div>
      <p class="tech-body">21 technik sebeobrany vzniklých po roce 1956. Zahrnuje obranu proti noži, tyči i střelné zbrani.</p>
    </div>
    <div class="tech-card">
      <span class="tech-num">06</span>
      <div class="tech-name">Atemi-waza<br><small style="font-size:14px;font-family:var(--sans);color:var(--ink-light);">Údery a kopy</small></div>
      <p class="tech-body">Techniky úderů na citlivá místa. Součást tradičního juda, dnes zachovány v katách.</p>
    </div>
  </div>
</section>

<!-- CHILDREN SECTION -->
<section id="deti" style="background: var(--bg);">
  <div class="children-grid">
    <div class="deti-slideshow" style="aspect-ratio: 3/2;" aria-label="Fotografie dětí na tréninku">
      <div class="about-accent" style="z-index: 3;"></div>
      @foreach (['deti1','deti2','deti3','deti4','deti5','deti6'] as $i => $img)
        <img src="{{ asset('images/deti/' . $img . '.jpeg') }}" alt="Děti na tréninku juda" class="deti-slide{{ $i === 0 ? ' is-active' : '' }}" loading="lazy">
      @endforeach
      <div class="deti-slideshow-dots">
        @foreach (['deti1','deti2','deti3','deti4','deti5','deti6'] as $i => $img)
          <span class="deti-dot{{ $i === 0 ? ' is-active' : '' }}"></span>
        @endforeach
      </div>
    </div>
    <div>
      <div class="section-eyebrow">Judo pro děti</div>
      <h2 class="section-title">Bezpečné umění<br>pro každé dítě</h2>
      <p class="about-body">
        Judo je ideálním sportem pro děti od <strong>5 let</strong> věku. Buduje nejen sebevědomí, koordinaci, respekt a úctu k ostatním, schopnost soustředění se a rozhodnutí, čest a vnitřní hodnoty, ale i návyky sebeobrany. Jedná se o čisté, bezpečné umění. Judo bylo světovou organizací UNESCO doporučeno pro mládež jako vhodný sport.
      </p>
      <p class="about-body">
        V našem oddílu JC Raion-Ryu bereme výuku dětí vážně. Tréninky jsou přizpůsobeny věku, vedeny hravou formou a zaměřeny na postupné budování techniky. Děti se u nás cítí dobře — a výsledky přicházejí samy.
      </p>
      <p class="about-body">
        Judo dětem dává víc než jen pohyb: učí je zvládat prohry, ctít pravidla a pomáhat si navzájem a další hodnoty, které zůstávají na celý život.
      </p>
      <div style="margin-top: 40px; display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0; border: 1.5px solid var(--rule);">
        <div style="padding: 24px 20px; border-right: 1px solid var(--rule); text-align: center;">
          <div style="font-family: var(--serif); font-size: 28px; font-weight: 300; color: var(--ink);">5+</div>
          <div style="font-size: 10px; letter-spacing: .12em; text-transform: uppercase; color: var(--ink-light); margin-top: 4px;">Věk od</div>
        </div>
        <div style="padding: 24px 20px; border-right: 1px solid var(--rule); text-align: center;">
          <div style="font-family: var(--serif); font-size: 28px; font-weight: 300; color: var(--ink);">2×</div>
          <div style="font-size: 10px; letter-spacing: .12em; text-transform: uppercase; color: var(--ink-light); margin-top: 4px;">Týdně</div>
        </div>
        <div style="padding: 24px 20px; text-align: center;">
          <div style="font-family: var(--serif); font-size: 28px; font-weight: 300; color: var(--ink);">∞</div>
          <div style="font-size: 10px; letter-spacing: .12em; text-transform: uppercase; color: var(--ink-light); margin-top: 4px;">Na celý život</div>
        </div>
      </div>
      <a href="#kontakt" class="btn-primary" style="margin-top: 32px; display: inline-block;">Přihlásit dítě</a>
    </div>
  </div>

  <div class="children-benefits">
    <div class="children-benefit-card">
      <div style="width: 40px; height: 40px; background: var(--red); margin-bottom: 20px; display: flex; align-items: center; justify-content: center;">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="8" stroke="white" stroke-width="1.5"/><path d="M10 6v4l3 2" stroke="white" stroke-width="1.5" stroke-linecap="round"/></svg>
      </div>
      <div style="font-family: var(--serif); font-size: 20px; font-weight: 400; margin-bottom: 12px;">Koordinace & pohyb</div>
      <p style="font-size: 14px; color: var(--ink-mid); line-height: 1.7;">Judo rozvíjí prostorovou orientaci, rovnováhu a celkovou motoriku. Děti se pohybují s jistotou a grácií.</p>
    </div>
    <div class="children-benefit-card">
      <div style="width: 40px; height: 40px; background: var(--red); margin-bottom: 20px; display: flex; align-items: center; justify-content: center;">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M10 3l2 5h5l-4 3 1.5 5L10 13l-4.5 3L7 11 3 8h5z" stroke="white" stroke-width="1.5" stroke-linejoin="round"/></svg>
      </div>
      <div style="font-family: var(--serif); font-size: 20px; font-weight: 400; margin-bottom: 12px;">Sebevědomí & respekt</div>
      <p style="font-size: 14px; color: var(--ink-mid); line-height: 1.7;">Postupné zvládání technik buduje zdravé sebevědomí. Úklona před zápasem a po něm učí úctě k soupeři.</p>
    </div>
    <div class="children-benefit-card">
      <div style="width: 40px; height: 40px; background: var(--red); margin-bottom: 20px; display: flex; align-items: center; justify-content: center;">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M6 10c0-2.2 1.8-4 4-4s4 1.8 4 4-1.8 4-4 4" stroke="white" stroke-width="1.5" stroke-linecap="round"/><path d="M10 3v2M10 15v2M3 10h2M15 10h2" stroke="white" stroke-width="1.5" stroke-linecap="round"/></svg>
      </div>
      <div style="font-family: var(--serif); font-size: 20px; font-weight: 400; margin-bottom: 12px;">Soustředění & disciplína</div>
      <p style="font-size: 14px; color: var(--ink-mid); line-height: 1.7;">Rituály tréninku — nástup, ticho, pozornost — přirozeně trénují schopnost soustředit se. Efekt i ve škole.</p>
    </div>
  </div>
</section>

<!-- JAPANESE MASTERS -->
<section id="mistri" class="masters">
  <div class="section-eyebrow">Japonští mistři u nás</div>
  <h2 class="section-title">Přímá linie<br>z Kódókanu</h2>
  <p class="masters-intro">
    Od roku 2004 k nám pravidelně jezdí japonští mistři. Jejich přítomnost formuje náš přístup k judu — vedeme jej tradičním směrem, nikoli ryze sportovním. Každý seminář je nezapomenutelnou zkušeností.
  </p>
  <div class="masters-grid">
    <div class="master-card">
      <div class="master-card-accent"></div>
      <span class="master-dan">7. Dan Judo</span>
      <div class="master-name">Toshikazu Okada Sensei</div>
      <div class="master-specialty">Kata · Ne-waza</div>
      <p class="master-body">Přímý žák Tsunatany Ody senseie — mistra, který spolupracoval přímo s Jigorem Kanem při zakládání Kódókanu. Sensei Okada byl expertem metodické skupiny Kódókanu pro výuku Kata. Navštívil nás opakovaně, dokud nezemřel 20. 12. 2014 ve věku 80 let. Vzpomínáme.</p>
    </div>
    <div class="master-card">
      <div class="master-card-accent"></div>
      <span class="master-dan">8. Dan Judo</span>
      <div class="master-name">Oshio Kaida Sensei</div>
      <div class="master-specialty">Kuzushi · Tai-sabaki</div>
      <p class="master-body">Vychoval mnoho špičkových japonských judistů. Bývalý instruktor Tokijské metropolitní policie. Specializuje se na formy Kuzushi a Tai-sabaki — vychýlení a obraty těla. V penzi stále aktivně cvičí a vede vlastní školu v Japonsku.</p>
    </div>
    <div class="master-card">
      <div class="master-card-accent"></div>
      <span class="master-dan">5. Dan Judo · 4. Dan Ju-jutsu</span>
      <div class="master-name">Yasuaki Kaji Sensei</div>
      <div class="master-specialty">Sebeobrana · Combat Wrestling</div>
      <p class="master-body">Aktivní závodník s tituly v Judo i Combat Wrestlingu. Velitel ochranky japonské vlády, osobní strážce japonského premiéra. Vyučoval speciální složky armády a policie. V Japonsku přijímá žáky pouze na pozvání.</p>
    </div>
    <div class="master-card">
      <div class="master-card-accent"></div>
      <span class="master-dan">10. Dan Hiko-ryu Taijutsu</span>
      <div class="master-name">Velmistr Koshiro Tanaka</div>
      <div class="master-specialty">Samurajský styl · Bojové umění</div>
      <p class="master-body">Výjimečný učitel a bojovník. Původně samurajský styl FUJI-ryu rozšířil o více než 1 000 technik a vytvořil vlastní styl Hiko-ryu Taijutsu a Kodachi, který obohatil svými zkušenostmi z válečných polí. Vyučoval SWAT, MARINES, speciální jednotky v Evropě i Asii. Byl ochráncem Jeho Svatosti Dalajlámy. Je potomkem samurajské rodiny. Sensei Rubínek pod jeho vedením dosáhl titulu <em>Renshi</em> a 6. danu.</p>
    </div>
  </div>

  <div style="margin-top: 2px;">
    <div class="master-card" style="display:flex; gap:48px; align-items:flex-start; padding: 52px 48px;">
      <div class="master-card-accent"></div>
      <div>
        <span class="master-dan">8. Dan Judo</span>
        <div class="master-name">Hirotaka Okada Sensei</div>
        <div class="master-specialty">Mistrovství světa 1987 & 1991 · Olympijský bronz 1992</div>
      </div>
      <p class="master-body" style="flex:1; margin-top: 0; padding-top: 4px;">Mistr světa a olympijský medailista. Pod záštitou ČSJu vedl v Praze v roce 2013 seminář metodiky nácviku technik pro mládež 8–12 let, zaměřený na jejich bezpečné provádění. Dlouholetý trenér japonské reprezentace.</p>
    </div>
  </div>
</section>

<!-- JAPAN EXPERIENCE -->
<section id="japonsko" class="japan" style="padding: 0;">
  <div class="japan-left">
    <div class="section-eyebrow">Trénink v Japonsku</div>
    <h2 class="section-title">Přímo ve škole<br>Kódókan v Tokiu</h2>
    <p class="about-body">
      V letech 2016 a 2019 absolvovali někteří instruktoři klubu studijní pobyty v Japonsku. Tréninky probíhaly přímo ve škole Kódókan v Tokiu i v dalších tradičních školách v okolí.
    </p>
    <p class="about-body">
      Zkušenosti z tréninků přenášíme do výuky v našem klubu. Tato přímá spojitost s japonskou tradicí odlišuje JC Raion-Ryu od většiny českých oddílů.
    </p>
    <div style="margin-top:40px; border-top: 1px solid var(--rule); padding-top: 32px;">
      <div class="section-eyebrow" style="margin-bottom: 12px;">Osobní odkaz</div>
      <blockquote style="font-family:var(--serif);font-size:18px;font-style:italic;font-weight:300;line-height:1.6;color:var(--ink-mid);">
        „Měl jsem možnost mistra Vladimíra Lorenze zažít osobně v letech 2004–2010. Nikdy nezapomenu na jeho odkaz. Děkuji!"
      </blockquote>
      <p style="font-size:12px;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-light);margin-top:12px;font-weight:600;">— Filip Rubínek, sensei · 6. dan</p>
    </div>
  </div>
  <div class="japan-right">
    <div class="japan-kana">道</div>
    <div class="section-eyebrow" style="color:var(--red);">Vladimír Lorenz</div>
    <h2 class="section-title" style="color:#fff;">Legenda<br>českého juda</h2>
    <p class="japan-right-body">
      Vladimír Lorenz (1925–2010), držitel 8. danu Judo, byl klíčovou postavou bojových umění v ČR. Za druhé světové války člen protifašistického odboje. Osobní sekretář a žák japonského mistra Dr. Yunyu Kitayami, který mu zprostředkoval setkání s legendou — Kyuze Mifune (10. dan).
    </p>
    <p class="japan-right-body" style="margin-top:20px;">
      Zakladatel Aikido v Česku, zakladatel oddílu Judo v Hradci Králové a dalších oddílů po celé ČR. Nezapomenutelná osobnost, jejíž odkaz žije dál.
    </p>
    <div style="margin-top: 48px; display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
      <div>
        <div style="font-family:var(--serif);font-size:32px;font-weight:300;color:#fff;">8. dan</div>
        <div style="font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.3);margin-top:4px;">Kódókan Judo</div>
      </div>
      <div>
        <div style="font-family:var(--serif);font-size:32px;font-weight:300;color:#fff;">1925–2010</div>
        <div style="font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.3);margin-top:4px;">Osobní legenda</div>
      </div>
    </div>
  </div>
</section>

<!-- CONTACT -->
<section id="kontakt" class="contact">
  <div class="section-eyebrow">Kontakt & Tréninky</div>
  <h2 class="section-title">Přijďte na tatami</h2>
  <p style="font-size:17px;color:var(--ink-mid);line-height:1.8;max-width:600px;font-weight:300;margin-bottom:16px;">
    Trénujeme na Praze 8 a v obci Vodochody. Vítáme začátečníky i pokročilé, děti i dospělé. Stačí přijít a podívat se — bez závazků.
  </p>
  <div class="contact-grid">
    <div class="contact-block">
      <div class="contact-block-title">Spolek</div>
      <div class="contact-name">ŠKOLA BOJOVÝCH UMĚNÍ – RUBIDÓ, z.s.</div>
      <p class="contact-detail">
        <strong>IČ:</strong> 24925454<br>
        Československé armády 363<br>
        Odolená Voda, 250 70<br><br>
        <strong>Oddíl:</strong> JC Raion-Ryu<br>
        <strong>Web:</strong> judopraha.eu
      </p>
    </div>
    <div class="contact-block">
      <div class="contact-block-title">Živnost</div>
      <div class="contact-name">Filip Rubínek</div>
      <p class="contact-detail">
        <strong>IČ:</strong> 76621723<br>
        <strong>Tel:</strong> <a href="tel:+420777166156" class="contact-link">777 166 156</a><br>
        Československé armády 363<br>
        Odolená Voda, 250 70<br><br>
        Škola bojových umění Rubidó<br>
        Oddíl JC Raion-Ryu
      </p>
    </div>
    <div class="contact-block">
      <div class="contact-block-title">Praha 8</div>
      <div class="contact-name" style="font-size:18px;">Pobočka Praha 8</div>
      <p class="contact-detail">
        <strong>Judo:</strong> Za Invalidovnou 579/3<br>
        <strong>Taijutsu:</strong> Dojo Kundratka 19,<br>
        areál MP hl. m. Prahy<br><br>
        <strong>Web:</strong> sebeobranapraha.eu
      </p>
    </div>
    <div class="contact-block">
      <div class="contact-block-title">Vodochody</div>
      <div class="contact-name" style="font-size:18px;">Pobočka Vodochody</div>
      <p class="contact-detail">
        Průběžná 50<br>
        Vodochody, 250 69<br><br>
        Pravidelné tréninky<br>
        pro všechny věkové kategorie.
      </p>
    </div>
  </div>
</section>

<!-- TRAINING CALENDAR & INQUIRY -->
<livewire:training-calendar />

<!-- FOOTER -->
<footer>
  <div style="display:flex;align-items:center;gap:16px;">
    <x-ui.logo href="#" variant="dark" size="52px" />
    <div class="footer-logo">Škola Bojových Umění Rubidó · JC Raion-Ryu/Taijutsu · od roku 2010</div>
  </div>
  <div class="footer-links">
    <a href="#judo">Judo</a>
    <a href="#techniky">Techniky</a>
    <a href="#mistri">Mistři</a>
    <a href="#kontakt">Kontakt</a>
  </div>
  <div class="footer-copy">© 2025 ŠKOLA BOJOVÝCH UMĚNÍ – RUBIDÓ, z.s.</div>
</footer>

</div>
