{{--
    Glossary / slovníček odborných pojmů.

    Automaticky zvýrazní pojmy z config/content/glossary.php v souvislém textu
    a po najetí myší / kliknutí / fokusu zobrazí vysvětlivku. Zvýrazní vždy jen
    PRVNÍ výskyt každého pojmu, aby stránka nepůsobila přeplácaně.

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
    'blockquote',
  ];

  // Klíče seřazené od nejdelšího – delší pojem se spáruje dřív než kratší.
  const KEYS = Object.keys(TERMS).sort((a, b) => b.length - a.length);
  if (!KEYS.length) return;

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
    el.textContent = label;

    const pop = document.createElement('span');
    pop.className = 'glossary-pop';
    pop.setAttribute('role', 'tooltip');

    const term = document.createElement('strong');
    term.className = 'glossary-pop-term';
    term.textContent = canonical;

    const body = document.createElement('span');
    body.className = 'glossary-pop-body';
    body.textContent = explanation;

    pop.appendChild(term);
    pop.appendChild(body);
    el.appendChild(pop);
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

  // Mobil / dotyk: kliknutí přepíná vysvětlivku, klik mimo ji zavře.
  document.addEventListener('click', (e) => {
    const term = e.target.closest('.glossary-term');
    document.querySelectorAll('.glossary-term.is-open').forEach((t) => {
      if (t !== term) t.classList.remove('is-open');
    });
    if (term) {
      e.preventDefault();
      term.classList.toggle('is-open');
    }
  });

  document.addEventListener('keydown', (e) => {
    const active = document.activeElement;
    if (e.key === 'Escape') {
      document.querySelectorAll('.glossary-term.is-open').forEach((t) => t.classList.remove('is-open'));
    } else if ((e.key === 'Enter' || e.key === ' ') && active && active.classList.contains('glossary-term')) {
      e.preventDefault();
      active.classList.toggle('is-open');
    }
  });

  if (document.readyState !== 'loading') run();
  else document.addEventListener('DOMContentLoaded', run);
  // Po Livewire navigaci (wire:navigate) projet znovu nově vykreslený obsah.
  document.addEventListener('livewire:navigated', run);
})();
</script>
@endonce
