<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $title ?? 'Judo Club Raion-ryu | Kódókan Judo Praha & Vodochody' }}</title>

<x-seo :title="$title ?? null" :description="$metaDescription ?? null" :image="$ogImage ?? null" />

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
  .nav-links a:hover, .nav-links a.active { color: var(--red); }
  .nav-cta {
    font-size: 12px; letter-spacing: .1em; text-transform: uppercase;
    background: var(--red); color: #fff; padding: 10px 22px;
    border: none; cursor: pointer; font-weight: 600; font-family: var(--sans);
    transition: background .2s; text-decoration: none;
  }
  .nav-cta:hover { background: var(--red-muted); }
  .nav-right { display: flex; align-items: center; gap: 16px; }

  /* ─── NAV DROPDOWN (desktop) ─── */
  .nav-dd { position: relative; padding-bottom: 24px; margin-bottom: -24px; }
  .nav-dd-trigger {
    font-size: 12px; letter-spacing: .1em; text-transform: uppercase;
    color: var(--ink-mid); font-weight: 500; font-family: var(--sans);
    background: none; border: none; padding: 0; cursor: pointer;
    display: inline-flex; align-items: center; gap: 6px; transition: color .2s;
  }
  .nav-dd:hover .nav-dd-trigger, .nav-dd-trigger:hover, .nav-dd-trigger.active { color: var(--red); }
  .nav-dd-caret { font-size: 9px; line-height: 1; transition: transform .2s; }
  .nav-dd-trigger[aria-expanded="true"] .nav-dd-caret, .nav-dd-caret.is-open { transform: rotate(180deg); }
  .nav-dd-menu {
    /* Zarovnáno k pravé hraně triggeru – žádný transform, aby nekolidoval
       s Alpine x-transition (jinak menu po zobrazení „cukne" stranou). */
    position: absolute; top: 100%; right: 0;
    min-width: 250px; list-style: none; padding: 8px; z-index: 200;
    background: var(--bg); border: 1px solid var(--rule);
    box-shadow: 0 16px 40px rgba(0,0,0,.14);
  }
  .nav-dd-menu--left { left: 0; right: auto; }
  .nav-dd-menu li { list-style: none; }
  .nav-dd-menu a {
    display: block; padding: 11px 14px; text-transform: none; letter-spacing: 0;
    font-size: 14px; font-weight: 400; color: var(--ink-mid); text-decoration: none;
    transition: background .15s, color .15s;
  }
  .nav-dd-menu a:hover, .nav-dd-menu a.active { background: #F0EDE8; color: var(--red); }

  /* ─── HAMBURGER + MOBILNÍ PANEL ─── */
  .nav-burger {
    display: none; width: 40px; height: 40px; flex: none;
    align-items: center; justify-content: center;
    background: none; border: none; cursor: pointer;
    font-size: 22px; line-height: 1; color: var(--ink); font-family: var(--sans);
  }
  .nav-mobile {
    position: absolute; top: 68px; left: 0; right: 0;
    background: var(--bg); border-bottom: 1px solid var(--rule);
    box-shadow: 0 16px 40px rgba(0,0,0,.10);
    display: flex; flex-direction: column; padding: 8px 24px 20px;
  }
  .nav-mobile > a, .nav-mobile-group > button {
    font-size: 13px; letter-spacing: .08em; text-transform: uppercase; font-weight: 600;
    color: var(--ink); text-decoration: none; font-family: var(--sans);
    padding: 14px 0; border: none; border-bottom: 1px solid var(--rule);
    background: none; width: 100%; text-align: left; cursor: pointer;
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
  }
  .nav-mobile > a:hover, .nav-mobile-group > button:hover,
  .nav-mobile > a.active { color: var(--red); }
  .nav-mobile-sub { display: flex; flex-direction: column; padding: 4px 0 8px 14px; }
  .nav-mobile-sub a {
    font-size: 14px; font-weight: 400; color: var(--ink-mid); text-decoration: none;
    padding: 10px 0; border-bottom: 1px solid var(--rule);
  }
  .nav-mobile-sub a:last-child { border-bottom: none; }
  .nav-mobile-sub a:hover, .nav-mobile-sub a.active { color: var(--red); }
  .nav-mobile .nav-cta {
    display: block; width: 100%; text-align: center; margin-top: 18px;
    padding: 14px; border-bottom: none;
  }

  /* ─── PLOVOUCÍ MOBILNÍ CTA ─── */
  /* Skryté na desktopu; zobrazí se jen v mobilním breakpointu níže. */
  .floating-cta { display: none; }

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
  .hero-note {
    margin-top: 18px; font-size: 12px; letter-spacing: .06em;
    text-transform: uppercase; color: var(--red); font-weight: 600;
  }

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
    background: linear-gradient(to top, rgba(27,24,18,.82) 0%, rgba(27,24,18,.25) 45%, rgba(27,24,18,.45) 100%);
    position: relative; z-index: 2;
    pointer-events: none;
  }
  .hero-slideshow {
    position: absolute; inset: 0; z-index: 1;
    overflow: hidden;
    background: var(--bg-dark);
  }
  .hero-slide {
    position: absolute; inset: 0;
    width: 100%; height: 100%;
    object-fit: cover;
    opacity: 0;
    transform: scale(1.05);
    transition: opacity 1.2s ease, transform 6s ease;
    will-change: opacity, transform;
  }
  .hero-slide.is-active {
    opacity: 1;
    transform: scale(1);
    z-index: 1;
  }
  .hero-slideshow-dots {
    position: absolute;
    bottom: 22px; right: 28px;
    z-index: 3;
    display: flex; gap: 8px;
  }
  .hero-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: rgba(255,255,255,.4);
    transition: background .4s ease, transform .4s ease;
  }
  .hero-dot.is-active {
    background: var(--red);
    transform: scale(1.25);
  }
  @media (prefers-reduced-motion: reduce) {
    .hero-slide { transition: opacity .4s ease; transform: none; }
    .hero-slide.is-active { transform: none; }
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
  .about-image.has-photo { background: var(--ink); }
  .about-image.has-photo .about-image-label { color: rgba(255,255,255,.85); }
  .about-photo {
    position: absolute; inset: 0;
    width: 100%; height: 100%;
    object-fit: cover;
    z-index: 1;
  }
  .about-image.has-photo::after {
    content: "";
    position: absolute; inset: 0;
    z-index: 2;
    pointer-events: none;
    background: linear-gradient(to top, rgba(28,25,20,.45) 0%, transparent 50%);
  }
  .about-accent {
    position: absolute; top: 24px; left: 24px;
    width: 3px; height: 60px; background: var(--red);
  }

  /* ─── DĚTI SLIDESHOW ─── */
  .deti-slideshow {
    position: relative;
    overflow: hidden;
    border: 1px solid var(--rule);
    background: var(--ink);
  }
  .deti-slide {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0;
    transform: scale(1.04);
    transition: opacity 1.2s ease, transform 6s ease;
    will-change: opacity, transform;
  }
  .deti-slide.is-active {
    opacity: 1;
    transform: scale(1);
    z-index: 1;
  }
  .deti-slideshow::after {
    content: "";
    position: absolute;
    inset: 0;
    z-index: 2;
    pointer-events: none;
    background: linear-gradient(to top, rgba(28,25,20,.35) 0%, transparent 45%);
  }
  .deti-slideshow-dots {
    position: absolute;
    bottom: 18px; right: 20px;
    z-index: 3;
    display: flex; gap: 8px;
  }
  .deti-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: rgba(255,255,255,.4);
    transition: background .4s ease, transform .4s ease;
  }
  .deti-dot.is-active {
    background: var(--red);
    transform: scale(1.25);
  }
  @media (prefers-reduced-motion: reduce) {
    .deti-slide { transition: opacity .4s ease; transform: none; }
    .deti-slide.is-active { transform: none; }
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
  .masters-more {
    margin-top: 2px;
    display: flex; align-items: center; justify-content: space-between; gap: 24px;
    padding: 36px 48px;
    background: var(--red); color: #fff; text-decoration: none;
    font-family: var(--serif); font-size: clamp(20px, 2.4vw, 28px); font-weight: 300;
    line-height: 1.2; transition: background .25s;
  }
  .masters-more:hover { background: var(--red-muted); }
  .masters-more-eyebrow {
    display: block; font-family: var(--sans); font-size: 11px; font-weight: 600;
    letter-spacing: .2em; text-transform: uppercase; color: rgba(255,255,255,.7); margin-bottom: 8px;
  }
  .masters-more-arrow { font-size: 32px; line-height: 1; flex: none; transition: transform .25s; }
  .masters-more:hover .masters-more-arrow { transform: translateX(6px); }
  @media (max-width: 900px) {
    .masters-more { padding: 28px 28px; font-size: 20px; }
  }

  /* ─── JAPAN EXPERIENCE ─── */
  .japan { display: grid; grid-template-columns: 1fr 1fr; gap: 0; }
  .japan-left { padding: 120px 80px; background: #F0EDE8; }
  .japan-right {
    background: var(--bg-dark);
    padding: 120px 80px;
    position: relative; overflow: hidden;
  }
  .japan-right-body { font-size: 16px; color: rgba(255,255,255,.55); line-height: 1.8; font-weight: 300; }
  .japan-right .section-title { color: #fff; }
  .japan-right .lorenz-portrait {
    float: right; width: 190px; max-width: 42%;
    margin: 4px 0 22px 34px; aspect-ratio: 2/3; object-fit: cover;
    border: 1px solid rgba(255,255,255,.14); filter: grayscale(.15) contrast(1.02);
  }

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
  /* malá ikonka mapy vedle adresy v kontaktech */
  .map-pin {
    display: inline-flex; align-items: center; justify-content: center;
    width: 22px; height: 22px; margin-left: 7px; vertical-align: -5px;
    color: var(--red); border: 1px solid var(--rule); border-radius: 50%;
    transition: color .2s, border-color .2s, background .2s;
  }
  .map-pin:hover { border-color: var(--red); background: #fff; }
  .map-pin svg { width: 12px; height: 12px; display: block; }
  .contact-cta-line { font-size: 15px; color: var(--ink-mid); font-weight: 400; margin: 4px 0 8px; }

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
  .calendar-cell.has-training, .calendar-cell.has-event { cursor: pointer; }
  .calendar-cell.has-training .calendar-num,
  .calendar-cell.has-event .calendar-num { font-weight: 600; }
  .calendar-cell.is-today .calendar-num { box-shadow: inset 0 0 0 1.5px var(--ink); }
  .calendar-cell.has-training:hover .calendar-num,
  .calendar-cell.has-event:hover .calendar-num,
  .calendar-cell.has-training:focus-visible .calendar-num,
  .calendar-cell.has-event:focus-visible .calendar-num { background: var(--red); color: #fff; box-shadow: none; outline: none; }
  .calendar-cell.is-picked .calendar-num { background: var(--red); color: #fff; box-shadow: none; }
  .calendar-dots {
    position: absolute; bottom: 3px; left: 50%; transform: translateX(-50%);
    display: flex; gap: 3px; align-items: center;
  }
  .calendar-dot { width: 4px; height: 4px; border-radius: 50%; background: var(--red); }
  .calendar-dot.event { background: var(--gold); }
  .calendar-dot.cancelled { background: transparent; box-shadow: inset 0 0 0 1.2px var(--ink-light); }
  .calendar-cell.is-picked .calendar-dots,
  .calendar-cell.has-training:hover .calendar-dots,
  .calendar-cell.has-event:hover .calendar-dots { opacity: 0; }
  .calendar-legend {
    display: flex; gap: 24px; margin-top: 28px;
    font-size: 11px; color: var(--ink-light); letter-spacing: .04em;
  }
  .calendar-legend-item { display: inline-flex; align-items: center; gap: 8px; text-transform: uppercase; font-weight: 600; }
  .calendar-legend-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--red); }
  .calendar-legend-dot.event { background: var(--gold); }
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

  /* zrušený trénink — přeškrtnutě */
  .detail-train.is-cancelled .detail-train-type,
  .detail-train.is-cancelled .detail-train-time { text-decoration: line-through; color: var(--ink-light); }
  .detail-train.is-cancelled .detail-train-place,
  .detail-train.is-cancelled .detail-train-loc { color: var(--ink-light); }

  /* štítky v detailu dne (Zrušeno / Mimořádný / Akce) */
  .detail-flag {
    display: inline-block; font-size: 9px; font-weight: 700;
    letter-spacing: .12em; text-transform: uppercase; padding: 3px 8px;
    margin-left: 8px; vertical-align: middle; white-space: nowrap;
  }
  .detail-flag.cancelled { background: var(--red); color: #fff; }
  .detail-flag.extra { background: var(--ink); color: #fff; }
  .detail-flag.event { background: var(--gold); color: #fff; margin-left: 12px; }

  /* akce klubu v detailu dne */
  .detail-event { padding: 18px 0; border-bottom: 1px solid var(--rule); }
  .detail-event:last-of-type { border-bottom: 1px solid var(--rule); }
  .detail-event .detail-book { display: inline-block; text-decoration: none; }
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
    scroll-margin-top: 88px;
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
    display: flex; flex-direction: column; gap: 28px;
  }
  footer .footer-main {
    display: flex; justify-content: space-between; align-items: center; gap: 24px;
  }
  footer .footer-logo {
    font-family: var(--serif); font-size: 14px; color: rgba(255,255,255,.6); font-weight: 300;
  }
  footer .footer-copy { font-size: 12px; letter-spacing: .05em; }
  footer .footer-links { display: flex; gap: 24px; }
  footer .footer-links a { font-size: 12px; color: rgba(255,255,255,.3); text-decoration: none; letter-spacing: .05em; transition: color .2s; }
  footer .footer-links a:hover { color: var(--red); }
  footer .footer-credit {
    display: flex; align-items: center; justify-content: center; gap: 10px;
    width: 100%; padding-top: 24px; border-top: 1px solid rgba(255,255,255,.08);
    font-size: 12px; letter-spacing: .05em; color: rgba(255,255,255,.4);
    text-decoration: none; transition: color .2s;
  }
  footer .footer-credit:hover { color: rgba(255,255,255,.75); }
  footer .footer-credit strong { font-weight: 600; color: rgba(255,255,255,.6); transition: color .2s; }
  footer .footer-credit:hover strong { color: var(--red); }
  footer .footer-credit-logo { width: 26px; height: 26px; display: block; flex: none; }

  /* Sociální sítě (patička + mobilní menu) — výchozí varianta tmavé ikony na světlém podkladu (menu),
     v patičce přebarveno na světlé ikony na tmavém podkladu. */
  .footer-social { display: flex; gap: 14px; align-items: center; }
  .footer-social a {
    width: 34px; height: 34px; flex: none;
    display: inline-flex; align-items: center; justify-content: center;
    border: 1px solid var(--rule); color: var(--ink-mid);
    transition: color .2s, border-color .2s;
  }
  .footer-social a:hover { color: var(--red); border-color: var(--red); }
  .footer-social svg { width: 16px; height: 16px; display: block; }
  footer .footer-social a { border-color: rgba(255,255,255,.14); color: rgba(255,255,255,.55); }
  footer .footer-social a:hover { color: var(--red); border-color: var(--red); }

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

  /* ─── FAQ ─── */
  .faq-section { background: var(--bg); }
  .faq-list {
    margin-top: 48px; border-top: 1px solid var(--rule);
    display: grid; grid-template-columns: 1fr 1fr; column-gap: 64px;
  }
  .faq-item { border-bottom: 1px solid var(--rule); }
  .faq-q {
    font-family: var(--serif); font-size: 18px; font-weight: 400; color: var(--ink);
    padding: 22px 0; cursor: pointer; list-style: none;
    display: flex; justify-content: space-between; align-items: center; gap: 16px;
  }
  .faq-q::-webkit-details-marker { display: none; }
  .faq-item summary::after { content: '+'; color: var(--red); font-size: 22px; line-height: 1; flex: none; }
  .faq-item[open] summary::after { content: '−'; }
  .faq-a {
    font-size: 15px; color: var(--ink-mid); line-height: 1.8; font-weight: 300;
    padding: 0 0 24px; max-width: 680px;
  }
  .faq-a a {
    color: var(--red); text-decoration: none;
    border-bottom: 1px solid rgba(192,38,30,.3); transition: border-color .2s;
  }
  .faq-a a:hover { border-color: var(--red); }

  @media (max-width: 900px) {
    nav { padding: 0 24px; }
    .nav-links { display: none; }
    .nav-burger { display: inline-flex; }
    .nav-right > .nav-cta { display: none; }
    .floating-cta {
      display: flex; align-items: center; justify-content: center; gap: 8px;
      position: fixed; left: 16px; right: 16px;
      bottom: max(16px, env(safe-area-inset-bottom));
      z-index: 9000; background: var(--red); color: #fff;
      font-size: 13px; font-weight: 600; letter-spacing: .08em; text-transform: uppercase;
      font-family: var(--sans); text-decoration: none; padding: 15px 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,.25);
    }
    .floating-cta:active { background: var(--red-muted); }
    .hero { grid-template-columns: 1fr; min-height: auto; }
    .hero-left { padding: 60px 32px; }
    .hero-right { height: 360px; }
    section { padding: 80px 32px; }
    .about, .japan { grid-template-columns: 1fr; gap: 48px; }
    .japan-left, .japan-right { padding: 64px 32px; }
    .japan-right .lorenz-portrait { width: 150px; margin: 0 0 22px 24px; }
    .techniques-grid { grid-template-columns: 1fr; }
    .masters-grid { grid-template-columns: 1fr; }
    .contact-grid { grid-template-columns: 1fr; gap: 40px; }
    .faq-list { grid-template-columns: 1fr; column-gap: 0; }
    .maxims-inner { flex-direction: column; }
    footer .footer-main { flex-direction: column; gap: 16px; text-align: center; }
    footer .footer-links { flex-wrap: wrap; justify-content: center; }
    footer .footer-social { justify-content: center; }
    .nav-mobile .footer-social { margin-top: 16px; justify-content: center; }
    .children-grid { grid-template-columns: 1fr; gap: 40px; }
    .children-grid .about-image,
    .children-grid .deti-slideshow { aspect-ratio: 3/2; }
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
    color: inherit;
    cursor: help;
    border-bottom: 1px dotted var(--red);
    transition: color .15s ease;
  }
  .glossary-term:hover,
  .glossary-term:focus-visible { color: var(--red); }
  .glossary-term:focus-visible { outline: 2px solid var(--red); outline-offset: 3px; }

  /* Sdílená bublina – fixní pozice, pozicovaná JS; mimo tok i overflow sekcí. */
  .glossary-pop {
    position: fixed;
    top: 0;
    left: 0;
    max-width: min(340px, 90vw);
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
    z-index: 9999;
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transform: translateY(4px);
    transition: opacity .16s ease, transform .16s ease;
  }
  .glossary-pop.is-visible {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
    transform: translateY(0);
  }
  .glossary-pop::after {
    content: '';
    position: absolute;
    left: var(--arrow-left, 50%);
    top: 100%;
    transform: translateX(-50%);
    border: 7px solid transparent;
    border-top-color: var(--bg-dark);
  }
  .glossary-pop.is-below::after {
    top: auto;
    bottom: 100%;
    border-top-color: transparent;
    border-bottom-color: var(--bg-dark);
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

  @media (max-width: 900px) {
    .glossary-pop { font-size: 14px; }
  }
</style>
@stack('head')
@livewireStyles
</head>
<body>
{{ $slot }}
<x-ui.glossary />
@livewireScripts
<script>
  (function () {
    // Tělo skriptu Livewire přehraje při každé SPA navigaci (wire:navigate).
    // Spustíme ho proto jen jednou a globální listenery registrujeme jednou –
    // jinak by se hromadily a init by běžel opakovaně.
    if (window.__landingSlideshows) return;
    window.__landingSlideshows = true;

    var CONFIGS = [
      { root: '.deti-slideshow', slide: '.deti-slide', dot: '.deti-dot' },
      { root: '.hero-slideshow', slide: '.hero-slide', dot: '.hero-dot' }
    ];
    var timers = [];

    function initSlideshow(show, slideSel, dotSel) {
      if (!show || show.dataset.slideshowReady) return;
      var slides = Array.prototype.slice.call(show.querySelectorAll(slideSel));
      var dots = Array.prototype.slice.call(show.querySelectorAll(dotSel));
      if (slides.length < 2) return;
      show.dataset.slideshowReady = '1';

      var current = 0;
      function go(next) {
        slides[current].classList.remove('is-active');
        if (dots[current]) dots[current].classList.remove('is-active');
        current = next;
        slides[current].classList.add('is-active');
        if (dots[current]) dots[current].classList.add('is-active');
      }
      timers.push(setInterval(function () {
        go((current + 1) % slides.length);
      }, 5000));
    }

    function initAll() {
      CONFIGS.forEach(function (cfg) {
        document.querySelectorAll(cfg.root).forEach(function (show) {
          initSlideshow(show, cfg.slide, cfg.dot);
        });
      });
    }

    // `livewire:navigated` vyvolá Livewire i při prvním načtení stránky
    // (náhrada za DOMContentLoaded), takže pokryje initial load i navigace.
    document.addEventListener('livewire:navigated', initAll);
    // Před odchodem ze stránky zahodíme intervaly, ať neběží nad odpojeným DOM.
    document.addEventListener('livewire:navigating', function () {
      timers.forEach(clearInterval);
      timers.length = 0;
    });
  })();
</script>
</body>
</html>
