{{--
    Glossary / slovníček odborných pojmů.

    Automaticky zvýrazní pojmy z config/content/glossary.php v souvislém textu
    a po najetí myší / kliknutí / fokusu zobrazí vysvětlivku. Zvýrazní vždy jen
    PRVNÍ výskyt každého pojmu, aby stránka nepůsobila přeplácaně.

    Vysvětlivka je jeden sdílený prvek vložený přímo do <body> a pozicovaný
    fixně JavaScriptem – tím se vyhne ořezu (overflow) i překryvu (z-index)
    nadřazených sekcí a vždy zůstane v rámci obrazovky.

    Vloženo jednou v layoutu; @once zajistí, že se skript vykreslí jen jednou.
--}}
@once
@php($glossaryTerms = config('content.glossary', []))
<script>
(() => {
  const TERMS = @js($glossaryTerms);

  // Bloky souvislého textu, kde dává zvýraznění smysl (ne nadpisy, menu, formuláře).
  const CONTENT_SELECTORS = [
    '.hero-body', '.about-body', '.master-body', '.masters-intro',
    '.tech-body', '.maxim-body', '.japan-right-body', '.schedule-intro',
    '.lead', '.tanaka-body', '.tl-desc', '.memoriam',
    '.li-intro', '.bio-list', '.inst-list', '.page-sub',
    '.pickup-body', '.gi-body', '.group-body', '.group-list',
    '.comp-body', '.belt-sub', '.belt-note', '.equip-list',
    '.randori-body', '.cta-body',
    'blockquote',
  ];

  // Klíče seřazené od nejdelšího – delší pojem se spáruje dřív než kratší.
  const KEYS = Object.keys(TERMS).sort((a, b) => b.length - a.length);
  if (!KEYS.length) return;

  // Direktiva once je jen server-side dedup v rámci jedné odpovědi; při
  // wire:navigate Livewire stáhne čerstvě vyrenderovanou stránku a skript se
  // přehraje. Window guard zajistí registraci globálních listenerů jen jednou.
  if (window.__glossaryInit) return;
  window.__glossaryInit = true;

  const escapeRe = (s) => s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

  // Jednoslovné pojmy tolerují krátkou českou koncovku (dan → danu/danů),
  // víceslovné a pojmy s pomlčkou se párují přesně.
  const buildPattern = () => KEYS.map((k) => {
    const isSingleWord = /^[\p{L}]+$/u.test(k);
    return escapeRe(k) + (isSingleWord ? '[a-záčďéěíňóřšťúůýž]{0,3}' : '');
  }).join('|');

  // Najde kanonický klíč, který je předponou nalezeného textu (nejdelší vyhrává).
  const KEYS_LC = KEYS.map((k) => k.toLowerCase());
  const resolveKey = (matched) => {
    const lc = matched.toLowerCase();
    for (let i = 0; i < KEYS.length; i++) {
      if (lc.startsWith(KEYS_LC[i])) return KEYS[i];
    }
    return null;
  };

  const makeTermEl = (label, canonical, explanation) => {
    const el = document.createElement('span');
    el.className = 'glossary-term';
    el.tabIndex = 0;
    el.setAttribute('role', 'button');
    el.setAttribute('aria-label', canonical + ' – zobrazit vysvětlivku');
    el.dataset.term = canonical;
    el.dataset.explain = explanation;
    el.textContent = label;
    return el;
  };

  // Projde textové uzly v daném prvku a obalí první (dosud nepoužité) výskyty.
  const processElement = (root, used) => {
    if (root.dataset.glossaryScanned === '1') return;
    root.dataset.glossaryScanned = '1';

    const re = new RegExp('(?<![\\p{L}\\p{N}_])(' + buildPattern() + ')(?![\\p{L}])', 'giu');
    const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
      acceptNode(node) {
        if (!node.nodeValue.trim()) return NodeFilter.FILTER_REJECT;
        if (node.parentElement.closest('.glossary-term')) return NodeFilter.FILTER_REJECT;
        return NodeFilter.FILTER_ACCEPT;
      },
    });

    const nodes = [];
    while (walker.nextNode()) nodes.push(walker.currentNode);

    for (const node of nodes) {
      const text = node.nodeValue;
      re.lastIndex = 0;
      let m, last = 0, frag = null;
      while ((m = re.exec(text)) !== null) {
        const matched = m[1];
        const canonical = resolveKey(matched);
        if (!canonical || used.has(canonical)) continue;
        used.add(canonical);
        if (!frag) frag = document.createDocumentFragment();
        frag.appendChild(document.createTextNode(text.slice(last, m.index)));
        frag.appendChild(makeTermEl(matched, canonical, TERMS[canonical]));
        last = m.index + matched.length;
      }
      if (frag) {
        frag.appendChild(document.createTextNode(text.slice(last)));
        node.parentNode.replaceChild(frag, node);
      }
    }
  };

  const run = () => {
    const used = new Set();
    document.querySelectorAll(CONTENT_SELECTORS.join(',')).forEach((el) => processElement(el, used));
  };

  /* ─── Sdílená vysvětlivka (jeden prvek v <body>, fixní pozice) ─── */
  const MARGIN = 12; // odstup od okraje obrazovky
  let pop, popTerm, popBody, hideTimer = null, pinned = null, current = null;

  const ensurePop = () => {
    if (pop) return;
    pop = document.createElement('div');
    pop.className = 'glossary-pop';
    pop.setAttribute('role', 'tooltip');
    popTerm = document.createElement('strong');
    popTerm.className = 'glossary-pop-term';
    popBody = document.createElement('span');
    popBody.className = 'glossary-pop-body';
    pop.appendChild(popTerm);
    pop.appendChild(popBody);
    document.body.appendChild(pop);
    // Najetí na samotnou bublinu ji nechá otevřenou.
    pop.addEventListener('mouseenter', () => clearTimeout(hideTimer));
    pop.addEventListener('mouseleave', scheduleHide);
  };

  const position = (term) => {
    const r = term.getBoundingClientRect();
    const pw = pop.offsetWidth;
    const ph = pop.offsetHeight;
    const vw = document.documentElement.clientWidth;

    // Vodorovně vystředit na slovo a přidržet v rámci obrazovky.
    let left = r.left + r.width / 2 - pw / 2;
    left = Math.max(MARGIN, Math.min(left, vw - pw - MARGIN));

    // Standardně nad slovem; když se nahoru nevejde, překlopit pod něj.
    let top = r.top - ph - 12;
    const below = top < MARGIN;
    if (below) top = r.bottom + 12;

    pop.style.left = Math.round(left) + 'px';
    pop.style.top = Math.round(top) + 'px';
    pop.classList.toggle('is-below', below);

    // Šipka míří na střed slova, ale zůstane v rámci bubliny.
    const arrow = Math.max(16, Math.min(r.left + r.width / 2 - left, pw - 16));
    pop.style.setProperty('--arrow-left', Math.round(arrow) + 'px');
  };

  const show = (term) => {
    ensurePop();
    clearTimeout(hideTimer);
    current = term;
    popTerm.textContent = term.dataset.term;
    popBody.textContent = term.dataset.explain;
    pop.classList.add('is-visible');
    position(term);
  };

  const hide = () => {
    if (!pop) return;
    pop.classList.remove('is-visible');
    current = null;
  };
  const scheduleHide = () => {
    if (pinned) return;
    clearTimeout(hideTimer);
    hideTimer = setTimeout(hide, 120);
  };
  const forceHide = () => { pinned = null; hide(); };

  // Hover (desktop)
  document.addEventListener('mouseover', (e) => {
    const t = e.target.closest('.glossary-term');
    if (t) show(t);
  });
  document.addEventListener('mouseout', (e) => {
    if (e.target.closest('.glossary-term')) scheduleHide();
  });

  // Klávesnice (Tab + fokus)
  document.addEventListener('focusin', (e) => {
    const t = e.target.closest('.glossary-term');
    if (t) show(t);
  });
  document.addEventListener('focusout', (e) => {
    if (e.target.closest('.glossary-term') && !pinned) scheduleHide();
  });

  // Klik / dotyk: přepíná „připnutí“; klik mimo zavře.
  document.addEventListener('click', (e) => {
    const t = e.target.closest('.glossary-term');
    if (t) {
      e.preventDefault();
      if (pinned === t) { forceHide(); }
      else { pinned = t; show(t); }
      return;
    }
    if (!e.target.closest('.glossary-pop')) forceHide();
  });

  document.addEventListener('keydown', (e) => {
    const active = document.activeElement;
    if (e.key === 'Escape') {
      forceHide();
    } else if ((e.key === 'Enter' || e.key === ' ') && active && active.classList.contains('glossary-term')) {
      e.preventDefault();
      if (pinned === active) forceHide();
      else { pinned = active; show(active); }
    }
  });

  // Při scrollu / změně velikosti udržet bublinu u slova.
  const reposition = () => { if (current) position(current); };
  window.addEventListener('scroll', reposition, true);
  window.addEventListener('resize', reposition);

  // `livewire:navigated` Livewire vyvolá i při prvním načtení stránky (náhrada
  // za DOMContentLoaded) i po každé SPA navigaci – projedeme nový obsah znovu.
  document.addEventListener('livewire:navigated', () => { forceHide(); run(); });
})();
</script>
@endonce
