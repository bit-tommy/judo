<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $title ?? 'Škola Bojových Umění Rubidó – JC Raion-Ryu' }}</title>
<meta name="description" content="{{ $metaDescription ?? 'Kódókan Judo – škola bojových umění v Praze. Tréninky pro děti i dospělé pod vedením japonských mistrů.' }}"/>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
  :root {
    --bg: #F7F4EF;
    --bg-dark: #1B1812;
    --ink: #1C1914;
    --ink-mid: #4A4540;
    --ink-light: #8C8680;
    --red: #C0261E;
    --red-muted: #8C1E18;
    --gold: #9A7B3E;
    --rule: rgba(28,25,20,.12);
    --serif: 'Noto Serif', Georgia, serif;
    --sans: 'Noto Sans', Helvetica, Arial, sans-serif;
  }

  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  html { scroll-behavior: smooth; }

  body {
    font-family: var(--sans);
    background: var(--bg);
    color: var(--ink);
    font-size: 16px;
    line-height: 1.6;
    overflow-x: hidden;
  }

  /* ─── NAV ─── */
  nav {
    position: fixed; top: 0; left: 0; right: 0; z-index: 100;
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 48px;
    height: 68px;
    background: rgba(247,244,239,.92);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--rule);
  }
  .nav-logo {
    font-family: var(--serif);
    font-size: 15px;
    font-weight: 600;
    letter-spacing: .04em;
    color: var(--ink);
    text-decoration: none;
    line-height: 1.25;
  }
  .nav-logo span { display: block; font-size: 11px; font-weight: 300; color: var(--ink-light); letter-spacing: .12em; text-transform: uppercase; }
  .nav-links { display: flex; gap: 36px; list-style: none; }
  .nav-links a {
    font-size: 12px; letter-spacing: .1em; text-transform: uppercase;
    color: var(--ink-mid); text-decoration: none; font-weight: 500;
    transition: color .2s;
  }
  .nav-links a:hover { color: var(--red); }
  .nav-cta {
    font-size: 12px; letter-spacing: .1em; text-transform: uppercase;
    background: var(--red); color: #fff; padding: 10px 22px;
    border: none; cursor: pointer; font-weight: 600; font-family: var(--sans);
    transition: background .2s; text-decoration: none;
  }
  .nav-cta:hover { background: var(--red-muted); }

  /* ─── HERO ─── */
  .hero {
    min-height: 100svh;
    display: grid;
    grid-template-columns: 1fr 1fr;
    padding-top: 68px;
    overflow: hidden;
    position: relative;
  }
  .hero-left {
    display: flex; flex-direction: column; justify-content: center;
    padding: 80px 64px 80px 80px;
    position: relative; z-index: 2;
  }
  .hero-eyebrow {
    font-size: 11px; letter-spacing: .2em; text-transform: uppercase;
    color: var(--red); font-weight: 600; margin-bottom: 28px;
    display: flex; align-items: center; gap: 12px;
  }
  .hero-eyebrow::before {
    content: ''; display: block; width: 32px; height: 1px; background: var(--red);
  }
  .hero-headline {
    font-family: var(--serif);
    font-size: clamp(42px, 5vw, 72px);
    font-weight: 300;
    line-height: 1.08;
    letter-spacing: -.01em;
    margin-bottom: 32px;
    color: var(--ink);
  }
  .hero-headline em { font-style: italic; color: var(--red); }
  .hero-headline strong { font-weight: 700; }
  .hero-body {
    font-size: 17px; line-height: 1.75; color: var(--ink-mid);
    max-width: 480px; margin-bottom: 48px;
    font-weight: 300;
  }
  .hero-actions { display: flex; gap: 16px; align-items: center; }
  .btn-primary {
    background: var(--red); color: #fff; padding: 16px 36px;
    font-size: 13px; letter-spacing: .1em; text-transform: uppercase;
    font-weight: 600; font-family: var(--sans); border: none; cursor: pointer;
    text-decoration: none; transition: background .2s;
  }
  .btn-primary:hover { background: var(--red-muted); }
  .btn-ghost {
    background: transparent; color: var(--ink); padding: 16px 36px;
    font-size: 13px; letter-spacing: .1em; text-transform: uppercase;
    font-weight: 500; font-family: var(--sans); border: 1.5px solid var(--ink);
    cursor: pointer; text-decoration: none; transition: all .2s;
  }
  .btn-ghost:hover { border-color: var(--red); color: var(--red); }

  .hero-right {
    position: relative; overflow: hidden;
  }
  .hero-kanji {
    position: absolute; right: -40px; top: 50%; transform: translateY(-50%);
    font-family: var(--serif);
    font-size: 420px; line-height: 1;
    color: rgba(28,25,20,.04);
    pointer-events: none; user-select: none;
    z-index: 0;
    font-weight: 700;
  }
  .hero-image-area {
    width: 100%; height: 100%;
    display: flex; flex-direction: column; justify-content: flex-end;
    padding: 48px;
    background: linear-gradient(135deg, #2a2018 0%, #1B1812 100%);
    position: relative; z-index: 1;
  }
  .hero-image-placeholder {
    position: absolute; inset: 0; z-index: 0;
    background: repeating-linear-gradient(
      -45deg,
      rgba(255,255,255,.025) 0px, rgba(255,255,255,.025) 1px,
      transparent 1px, transparent 8px
    );
  }
  .hero-img-label {
    position: relative; z-index: 2;
    font-family: monospace; font-size: 11px; color: rgba(255,255,255,.3);
    letter-spacing: .12em; text-transform: uppercase;
    border: 1px solid rgba(255,255,255,.1); padding: 8px 14px; display: inline-block;
  }
  .hero-stats {
    position: relative; z-index: 2;
    display: flex; gap: 40px; margin-top: 24px;
  }
  .stat { color: #fff; }
  .stat-num {
    font-family: var(--serif); font-size: 38px; font-weight: 300; display: block;
    line-height: 1;
  }
  .stat-label { font-size: 11px; letter-spacing: .12em; text-transform: uppercase; color: rgba(255,255,255,.4); margin-top: 4px; display: block; }

  /* ─── MAXIMS ─── */
  .maxims {
    background: var(--bg-dark);
    padding: 0;
    overflow: hidden;
  }
  .maxims-inner {
    display: flex;
  }
  .maxim {
    flex: 1;
    padding: 52px 48px;
    border-right: 1px solid rgba(255,255,255,.08);
    position: relative;
  }
  .maxim:last-child { border-right: none; }
  .maxim-jp {
    font-family: var(--serif);
    font-size: 36px; color: rgba(255,255,255,.12);
    position: absolute; bottom: 16px; right: 24px;
    line-height: 1;
  }
  .maxim-title {
    font-family: var(--serif);
    font-size: 20px; color: #fff; font-weight: 300; margin-bottom: 8px;
    font-style: italic;
  }
  .maxim-romaji {
    font-size: 10px; letter-spacing: .2em; text-transform: uppercase;
    color: var(--red); font-weight: 600; margin-bottom: 16px; display: block;
  }
  .maxim-body { font-size: 13px; color: rgba(255,255,255,.45); line-height: 1.6; max-width: 240px; }

  /* ─── SECTIONS ─── */
  section { padding: 120px 80px; }

  .section-eyebrow {
    font-size: 11px; letter-spacing: .2em; text-transform: uppercase;
    color: var(--red); font-weight: 600; margin-bottom: 20px;
    display: flex; align-items: center; gap: 12px;
  }
  .section-eyebrow::before { content:''; display:block; width:32px; height:1px; background:var(--red); }

  .section-title {
    font-family: var(--serif);
    font-size: clamp(32px, 4vw, 52px);
    font-weight: 300; line-height: 1.1;
    letter-spacing: -.01em;
    margin-bottom: 24px;
  }

  /* ─── ABOUT ─── */
  .about {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 96px;
    align-items: center;
  }
  .about-body { font-size: 16px; line-height: 1.8; color: var(--ink-mid); font-weight: 300; margin-bottom: 20px; }
  .about-image {
    aspect-ratio: 4/5;
    background: repeating-linear-gradient(
      -45deg,
      rgba(28,25,20,.04) 0px, rgba(28,25,20,.04) 1px,
      transparent 1px, transparent 8px
    );
    border: 1px solid var(--rule);
    display: flex; align-items: flex-end; padding: 24px;
    position: relative;
    overflow: hidden;
  }
  .about-image-label { font-family: monospace; font-size: 11px; color: var(--ink-light); letter-spacing: .1em; text-transform: uppercase; }
  .about-accent {
    position: absolute; top: 24px; left: 24px;
    width: 3px; height: 60px; background: var(--red);
  }

  /* ─── TECHNIQUES ─── */
  .techniques { background: #F0EDE8; }
  .techniques-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2px;
    margin-top: 64px;
  }
  .tech-card {
    background: var(--bg);
    padding: 40px 36px;
    position: relative; overflow: hidden;
    transition: background .2s;
  }
  .tech-card:hover { background: var(--bg-dark); }
  .tech-card:hover .tech-name { color: #fff; }
  .tech-card:hover .tech-body { color: rgba(255,255,255,.5); }
  .tech-jp {
    position: absolute; bottom: -8px; right: 12px;
    font-family: var(--serif); font-size: 80px;
    color: rgba(28,25,20,.04); line-height: 1;
    pointer-events: none;
  }
  .tech-card:hover .tech-jp { color: rgba(255,255,255,.05); }
  .tech-num {
    font-size: 11px; color: var(--red); font-weight: 600;
    letter-spacing: .1em; margin-bottom: 16px; display: block;
  }
  .tech-name {
    font-family: var(--serif); font-size: 22px; font-weight: 400;
    margin-bottom: 12px; transition: color .2s; line-height: 1.2;
  }
  .tech-body { font-size: 14px; color: var(--ink-mid); line-height: 1.7; transition: color .2s; }

  /* ─── MASTERS ─── */
  .masters { background: var(--bg-dark); color: #fff; }
  .masters .section-title { color: #fff; }
  .masters .section-eyebrow::before { background: var(--red); }
  .masters-intro {
    font-size: 17px; color: rgba(255,255,255,.55); line-height: 1.8;
    max-width: 640px; font-weight: 300; margin-bottom: 80px;
  }
  .masters-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2px;
  }
  .master-card {
    padding: 52px 48px;
    background: rgba(255,255,255,.03);
    border: 1px solid rgba(255,255,255,.06);
    position: relative; overflow: hidden;
    transition: background .25s;
  }
  .master-card:hover { background: rgba(255,255,255,.06); }
  .master-dan {
    display: inline-block;
    background: var(--red); color: #fff;
    font-size: 10px; letter-spacing: .15em; text-transform: uppercase;
    padding: 5px 12px; font-weight: 700; margin-bottom: 20px;
  }
  .master-name {
    font-family: var(--serif); font-size: 24px; font-weight: 300;
    color: #fff; margin-bottom: 8px; line-height: 1.2;
  }
  .master-specialty {
    font-size: 12px; letter-spacing: .1em; text-transform: uppercase;
    color: var(--red); margin-bottom: 20px; font-weight: 600;
  }
  .master-body { font-size: 14px; color: rgba(255,255,255,.45); line-height: 1.75; }
  .master-card-accent {
    position: absolute; right: 0; top: 0; bottom: 0;
    width: 3px; background: rgba(192,38,30,.3);
    opacity: 0; transition: opacity .25s;
  }
  .master-card:hover .master-card-accent { opacity: 1; }

  /* ─── JAPAN EXPERIENCE ─── */
  .japan { display: grid; grid-template-columns: 1fr 1fr; gap: 0; }
  .japan-left { padding: 120px 80px; background: #F0EDE8; }
  .japan-right {
    background: var(--bg-dark);
    padding: 120px 80px;
    position: relative; overflow: hidden;
  }
  .japan-right-body { font-size: 16px; color: rgba(255,255,255,.55); line-height: 1.8; font-weight: 300; }
  .japan-kana {
    position: absolute; bottom: -60px; right: -20px;
    font-family: var(--serif); font-size: 320px; font-weight: 700;
    color: rgba(255,255,255,.025); line-height: 1;
    pointer-events: none;
  }
  .japan-right .section-title { color: #fff; }

  /* ─── CONTACT ─── */
  .contact { background: var(--bg); }
  .contact-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 80px;
    margin-top: 64px;
  }
  .contact-block { border-top: 2px solid var(--ink); padding-top: 32px; }
  .contact-block-title {
    font-size: 11px; letter-spacing: .2em; text-transform: uppercase;
    font-weight: 700; color: var(--ink-mid); margin-bottom: 24px;
  }
  .contact-name {
    font-family: var(--serif); font-size: 22px; font-weight: 400;
    margin-bottom: 16px; line-height: 1.3;
  }
  .contact-detail {
    font-size: 14px; color: var(--ink-mid); line-height: 1.8;
    font-weight: 300;
  }
  .contact-detail strong { font-weight: 600; color: var(--ink); }
  .contact-map {
    margin-top: 80px;
    height: 240px;
    background: repeating-linear-gradient(
      -45deg,
      rgba(28,25,20,.03) 0px, rgba(28,25,20,.03) 1px,
      transparent 1px, transparent 8px
    );
    border: 1px solid var(--rule);
    display: flex; align-items: center; justify-content: center;
    position: relative;
  }
  .contact-map-label { font-family: monospace; font-size: 11px; color: var(--ink-light); letter-spacing: .1em; text-transform: uppercase; }
  .contact-link {
    color: var(--red); text-decoration: none;
    border-bottom: 1px solid rgba(192,38,30,.3); transition: border-color .2s;
  }
  .contact-link:hover { border-color: var(--red); }

  /* ─── SCHEDULE / CALENDAR ─── */
  [x-cloak] { display: none !important; }
  .schedule { background: #F0EDE8; }
  .schedule-intro {
    font-size: 17px; color: var(--ink-mid); line-height: 1.8;
    max-width: 600px; font-weight: 300;
  }
  .schedule-layout {
    display: grid; grid-template-columns: 1.5fr 1fr; gap: 64px;
    margin-top: 56px; align-items: start;
  }

  .calendar { position: relative; }
  .calendar-arrow {
    position: absolute; top: -2px; z-index: 3;
    width: 32px; height: 32px;
    display: flex; align-items: center; justify-content: center;
    background: transparent; border: 1px solid var(--rule); color: var(--ink-mid);
    font-size: 20px; line-height: 1; cursor: pointer;
    transition: all .2s; font-family: var(--sans);
  }
  .calendar-arrow:hover { border-color: var(--red); color: var(--red); }
  .calendar-head .calendar-arrow:first-child { left: 0; }
  .calendar-head .calendar-arrow:last-child { right: 0; }
  .calendar-months { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
  .calendar-month-title {
    font-family: var(--serif); font-size: 18px; font-weight: 400;
    text-align: center; margin-bottom: 20px; color: var(--ink);
  }
  .calendar-dow {
    display: grid; grid-template-columns: repeat(7, 1fr);
    font-size: 10px; letter-spacing: .08em; text-transform: uppercase;
    color: var(--ink-light); text-align: center; margin-bottom: 8px; font-weight: 600;
  }
  .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; }
  .calendar-cell {
    position: relative; aspect-ratio: 1 / 1;
    display: flex; align-items: center; justify-content: center;
    background: transparent; border: none; padding: 0; cursor: default;
    font-family: var(--sans); font-size: 13px; color: var(--ink);
  }
  .calendar-cell.is-out { color: var(--ink-light); opacity: .35; }
  .calendar-num {
    display: flex; align-items: center; justify-content: center;
    width: 30px; height: 30px; border-radius: 50%; transition: all .15s;
  }
  .calendar-cell.has-training { cursor: pointer; }
  .calendar-cell.has-training .calendar-num { font-weight: 600; }
  .calendar-cell.is-today .calendar-num { box-shadow: inset 0 0 0 1.5px var(--ink); }
  .calendar-cell.has-training:hover .calendar-num,
  .calendar-cell.has-training:focus-visible .calendar-num { background: var(--red); color: #fff; box-shadow: none; outline: none; }
  .calendar-cell.is-picked .calendar-num { background: var(--red); color: #fff; box-shadow: none; }
  .calendar-dot {
    position: absolute; bottom: 3px; left: 50%; transform: translateX(-50%);
    width: 4px; height: 4px; border-radius: 50%; background: var(--red);
  }
  .calendar-cell.is-picked .calendar-dot,
  .calendar-cell.has-training:hover .calendar-dot { opacity: 0; }
  .calendar-legend {
    display: flex; gap: 24px; margin-top: 28px;
    font-size: 11px; color: var(--ink-light); letter-spacing: .04em;
  }
  .calendar-legend-item { display: inline-flex; align-items: center; gap: 8px; text-transform: uppercase; font-weight: 600; }
  .calendar-legend-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--red); }
  .calendar-legend-today { width: 14px; height: 14px; border-radius: 50%; box-shadow: inset 0 0 0 1.5px var(--ink); }

  .schedule-detail { border-top: 2px solid var(--ink); padding-top: 28px; }
  .detail-eyebrow {
    font-size: 11px; letter-spacing: .2em; text-transform: uppercase;
    font-weight: 700; color: var(--ink-mid); margin-bottom: 20px;
  }
  .week-row { display: flex; gap: 16px; padding: 14px 0; border-bottom: 1px solid var(--rule); }
  .week-row:last-of-type { border-bottom: none; }
  .week-day { flex: 0 0 78px; font-family: var(--serif); font-size: 15px; color: var(--ink); }
  .week-items { display: flex; flex-direction: column; gap: 6px; }
  .week-item { font-size: 13px; color: var(--ink-mid); line-height: 1.5; }
  .week-item strong { color: var(--ink); font-weight: 600; }
  .detail-hint { margin-top: 20px; font-size: 12px; color: var(--ink-light); font-style: italic; line-height: 1.6; }
  .detail-date {
    font-family: var(--serif); font-size: 22px; font-weight: 400; color: var(--ink);
    margin-bottom: 16px; text-transform: capitalize;
  }
  .detail-train { padding: 18px 0; border-bottom: 1px solid var(--rule); }
  .detail-train:last-child { border-bottom: none; }
  .detail-train-head { display: flex; justify-content: space-between; align-items: baseline; gap: 12px; }
  .detail-train-type { font-family: var(--serif); font-size: 18px; color: var(--ink); }
  .detail-train-time { font-size: 13px; color: var(--red); font-weight: 600; white-space: nowrap; }
  .detail-train-place { font-size: 12px; font-weight: 700; color: var(--ink-mid); margin-top: 6px; text-transform: uppercase; letter-spacing: .06em; }
  .detail-train-loc { font-size: 13px; color: var(--ink-mid); margin-top: 2px; font-weight: 300; }
  .detail-book {
    margin-top: 12px; background: transparent; border: none; color: var(--red);
    font-family: var(--sans); font-size: 12px; font-weight: 600; letter-spacing: .08em;
    text-transform: uppercase; cursor: pointer; padding: 0; transition: color .2s;
  }
  .detail-book:hover { color: var(--red-muted); }

  /* ─── INQUIRY FORM ─── */
  .inquiry {
    margin-top: 96px; display: grid; grid-template-columns: 1fr 1.5fr; gap: 64px;
    align-items: start; border-top: 1px solid var(--rule); padding-top: 64px;
  }
  .inquiry-title { font-family: var(--serif); font-size: clamp(26px, 3vw, 36px); font-weight: 300; line-height: 1.1; margin-bottom: 16px; }
  .inquiry-lead { font-size: 15px; color: var(--ink-mid); line-height: 1.8; font-weight: 300; }
  .inquiry-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 22px; }
  .inquiry-field { display: flex; flex-direction: column; gap: 8px; }
  .inquiry-field-full { grid-column: 1 / -1; }
  .inquiry-label { font-size: 11px; letter-spacing: .12em; text-transform: uppercase; font-weight: 600; color: var(--ink-mid); }
  .inquiry-label em { font-style: normal; color: var(--ink-light); text-transform: none; letter-spacing: 0; font-weight: 400; }
  .inquiry-input {
    font-family: var(--sans); font-size: 15px; color: var(--ink);
    background: var(--bg); border: 1px solid var(--rule); border-radius: 0;
    padding: 12px 14px; width: 100%; transition: border-color .2s;
  }
  .inquiry-input:focus { outline: none; border-color: var(--red); }
  textarea.inquiry-input { resize: vertical; min-height: 96px; }
  .inquiry-error { font-size: 12px; color: var(--red); margin-top: 2px; }
  .inquiry-consent {
    display: flex; gap: 10px; align-items: flex-start; margin-top: 24px;
    font-size: 13px; color: var(--ink-mid); line-height: 1.5; cursor: pointer; max-width: 560px;
  }
  .inquiry-consent input { margin-top: 3px; accent-color: var(--red); width: 16px; height: 16px; flex: none; }
  .inquiry-submit { margin-top: 28px; border: none; cursor: pointer; }
  .inquiry-success { border: 1px solid var(--rule); background: var(--bg); padding: 48px 40px; text-align: center; }
  .inquiry-success-mark { width: 52px; height: 52px; border-radius: 50%; background: var(--red); color: #fff; font-size: 24px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
  .inquiry-success-title { font-family: var(--serif); font-size: 24px; font-weight: 400; margin-bottom: 10px; }
  .inquiry-success-body { font-size: 15px; color: var(--ink-mid); margin-bottom: 28px; font-weight: 300; }

  /* ─── FOOTER ─── */
  footer {
    background: var(--bg-dark); color: rgba(255,255,255,.3);
    padding: 40px 80px;
    display: flex; justify-content: space-between; align-items: center;
  }
  footer .footer-logo {
    font-family: var(--serif); font-size: 14px; color: rgba(255,255,255,.6); font-weight: 300;
  }
  footer .footer-copy { font-size: 12px; letter-spacing: .05em; }
  footer .footer-links { display: flex; gap: 24px; }
  footer .footer-links a { font-size: 12px; color: rgba(255,255,255,.3); text-decoration: none; letter-spacing: .05em; transition: color .2s; }
  footer .footer-links a:hover { color: var(--red); }

  /* ─── DIVIDER ─── */
  .rule { width: 100%; height: 1px; background: var(--rule); }

  /* ─── CHILDREN ─── */
  .children-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 96px;
    align-items: center;
  }
  .children-benefits {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2px;
    margin-top: 64px;
  }
  .children-benefit-card { background: #F0EDE8; padding: 40px 36px; }

  @media (max-width: 900px) {
    nav { padding: 0 24px; }
    .nav-links { display: none; }
    .hero { grid-template-columns: 1fr; min-height: auto; }
    .hero-left { padding: 60px 32px; }
    .hero-right { height: 360px; }
    section { padding: 80px 32px; }
    .about, .japan { grid-template-columns: 1fr; gap: 48px; }
    .japan-left, .japan-right { padding: 64px 32px; }
    .techniques-grid { grid-template-columns: 1fr; }
    .masters-grid { grid-template-columns: 1fr; }
    .contact-grid { grid-template-columns: 1fr; gap: 40px; }
    .maxims-inner { flex-direction: column; }
    footer { flex-direction: column; gap: 16px; text-align: center; }
    footer .footer-links { flex-wrap: wrap; justify-content: center; }
    .children-grid { grid-template-columns: 1fr; gap: 40px; }
    .children-grid .about-image { aspect-ratio: 3/2; }
    .children-benefits { grid-template-columns: 1fr; gap: 12px; }
    .children-benefit-card { padding: 32px 28px; }
    .schedule-layout { grid-template-columns: 1fr; gap: 48px; }
    .calendar-months { grid-template-columns: 1fr; }
    .calendar-month:last-child { display: none; }
    .inquiry { grid-template-columns: 1fr; gap: 40px; margin-top: 64px; }
    .inquiry-grid { grid-template-columns: 1fr; }
  }

  /* ─── GLOSSARY / SLOVNÍČEK POJMŮ ─── */
  .glossary-term {
    position: relative;
    color: inherit;
    cursor: help;
    border-bottom: 1px dotted var(--red);
    transition: color .15s ease;
  }
  .glossary-term:hover,
  .glossary-term:focus-visible,
  .glossary-term.is-open { color: var(--red); }
  .glossary-term:focus-visible { outline: 2px solid var(--red); outline-offset: 3px; }

  .glossary-pop {
    position: absolute;
    left: 50%;
    bottom: calc(100% + 12px);
    transform: translateX(-50%) translateY(4px);
    width: max-content;
    max-width: min(320px, 78vw);
    padding: 16px 18px;
    background: var(--bg-dark);
    color: #EDE8E0;
    font-family: var(--sans);
    font-size: 13px;
    font-weight: 300;
    line-height: 1.6;
    letter-spacing: normal;
    text-transform: none;
    text-align: left;
    font-style: normal;
    border-top: 2px solid var(--red);
    box-shadow: 0 14px 36px rgba(0,0,0,.30);
    z-index: 300;
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transition: opacity .18s ease, transform .18s ease;
  }
  .glossary-pop::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 7px solid transparent;
    border-top-color: var(--bg-dark);
  }
  .glossary-pop-term {
    display: block;
    margin-bottom: 6px;
    font-family: var(--serif);
    font-size: 15px;
    font-weight: 600;
    color: #E0726B;
  }
  .glossary-pop-body { display: block; }

  .glossary-term:hover .glossary-pop,
  .glossary-term:focus-visible .glossary-pop,
  .glossary-term.is-open .glossary-pop {
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(0);
    pointer-events: auto;
  }

  @media (max-width: 900px) {
    .glossary-pop { font-size: 14px; max-width: min(300px, 84vw); }
  }
</style>
@livewireStyles
</head>
<body>
{{ $slot }}
<x-ui.glossary />
@livewireScripts
</body>
</html>
