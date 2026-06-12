{{--
    Styly administrace — sdílí je layout `admin` (shell se sidebarem)
    a `admin-login` (samostatný login). Vychází 1:1 z designového handoff
    souboru admin/admin.css (Claude Design); vynechané jsou jen prototypové
    věci (Tweaks panel, přepínání hustoty/animací) a na konci je blok
    „implementační doplňky" pro prvky, které prototyp neměl (chybové hlášky,
    textarea, file input, graf…).
--}}
<style>
  /* ════════════════════════════════════════════════════════
     JC RAION-RYU — ADMINISTRACE
     Designové tokeny sdílené s veřejným webem
     ════════════════════════════════════════════════════════ */
  :root {
    --bg: #F7F4EF;
    --bg-soft: #F0EDE8;
    --bg-dark: #1B1812;
    --bg-darker: #14110D;
    --ink: #1C1914;
    --ink-mid: #4A4540;
    --ink-light: #8C8680;
    --red: #C0261E;
    --red-muted: #8C1E18;
    --rule: rgba(28,25,20,.12);
    --rule-dark: rgba(255,255,255,.09);
    --serif: 'Noto Serif', Georgia, serif;
    --ui: 'IBM Plex Sans', 'Noto Sans', Helvetica, sans-serif;
    --mono: 'IBM Plex Mono', monospace;
    --pad: 20px;
    --speed: 1;
  }

  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  html { height: 100%; }
  body {
    font-family: var(--ui);
    background: var(--bg);
    color: var(--ink);
    font-size: 14px; line-height: 1.55;
    min-height: 100%;
    overflow-x: hidden;
  }
  button { font-family: inherit; cursor: pointer; }
  input, select, textarea { font-family: inherit; }
  img { display: block; }
  [x-cloak] { display: none !important; }

  /* ─── sdílené atomy ─── */
  .eyebrow {
    font-size: 10px; letter-spacing: .22em; text-transform: uppercase;
    color: var(--red); font-weight: 600;
    display: flex; align-items: center; gap: 10px;
  }
  .eyebrow::before { content: ''; width: 26px; height: 1px; background: var(--red); }
  .mono { font-family: var(--mono); }

  .btn {
    display: inline-flex; align-items: center; gap: 10px;
    background: var(--red); color: #fff; border: none;
    padding: 12px 22px; font-size: 11px; font-weight: 600;
    letter-spacing: .12em; text-transform: uppercase;
    text-decoration: none;
    transition: background .25s, transform .25s;
  }
  .btn:hover { background: var(--red-muted); }
  .btn:active { transform: translateY(1px); }
  .btn.ghost {
    background: transparent; color: var(--ink);
    border: 1px solid var(--ink); font-weight: 500;
  }
  .btn.ghost:hover { border-color: var(--red); color: var(--red); background: transparent; }
  .btn.subtle {
    background: transparent; color: var(--ink-light); border: none;
    padding: 8px 10px; letter-spacing: .1em;
  }
  .btn.subtle:hover { color: var(--red); background: transparent; }
  .btn[disabled] { opacity: .55; pointer-events: none; }

  .tag {
    display: inline-block; font-size: 9px; font-weight: 700;
    letter-spacing: .14em; text-transform: uppercase; padding: 4px 9px;
    white-space: nowrap;
  }
  .tag.red { background: var(--red); color: #fff; }
  .tag.dark { background: var(--ink); color: #fff; }
  .tag.line { border: 1px solid var(--rule); color: var(--ink-mid); }
  .tag.faint { background: rgba(28,25,20,.07); color: var(--ink-mid); }

  /* pruhovaný placeholder (alba bez coveru) */
  .ph {
    background-color: #ECE8E1;
    background-image: repeating-linear-gradient(-45deg,
      rgba(28,25,20,.05) 0px, rgba(28,25,20,.05) 1px, transparent 1px, transparent 9px);
  }
  .ph-tag {
    font-family: var(--mono); font-size: 10px; letter-spacing: .12em;
    text-transform: uppercase; color: var(--ink-light);
  }

  /* reveal animace — stagger přes --i (nastavuje se inline ve značce) */
  @media (prefers-reduced-motion: no-preference) {
    .reveal {
      opacity: 0; transform: translateY(14px);
      animation: rise calc(.65s * var(--speed)) cubic-bezier(.22,1,.36,1) forwards;
      animation-delay: calc(var(--i, 0) * .07s * var(--speed));
    }
  }
  @keyframes rise { to { opacity: 1; transform: none; } }

  /* ════════════════════════════════════════════════════════
     LOGIN
     ════════════════════════════════════════════════════════ */
  .login {
    position: fixed; inset: 0; z-index: 60;
    display: grid; grid-template-columns: 43% 57%;
    background: var(--bg);
  }

  .login-side {
    background: var(--bg-dark); color: #fff;
    position: relative; overflow: hidden;
    display: flex; flex-direction: column; justify-content: space-between;
    padding: 44px 48px;
  }
  .login-grid-line {
    position: absolute; left: 48px; top: 0; bottom: 0; width: 1px;
    background: var(--rule-dark); pointer-events: none;
  }
  .login-logo {
    color: #fff; text-decoration: none;
    font-family: var(--serif); font-size: 16px; font-weight: 600;
    letter-spacing: .04em; line-height: 1.3; position: relative; z-index: 2;
    padding-left: 24px;
  }
  .login-logo span {
    display: block; font-size: 10px; font-weight: 300; color: var(--ink-light);
    letter-spacing: .16em; text-transform: uppercase; margin-top: 3px;
  }
  .login-hero { position: relative; z-index: 2; padding-left: 24px; }
  .login-hero .eyebrow { margin-bottom: 22px; }
  .login-hero h1 {
    font-family: var(--serif); font-weight: 300;
    font-size: clamp(38px, 4.6vw, 64px); line-height: 1.04;
    letter-spacing: -.01em;
  }
  .login-hero h1 em { font-style: italic; color: var(--red); }
  .login-hero p {
    margin-top: 20px; max-width: 360px;
    font-size: 13px; font-weight: 300; line-height: 1.75;
    color: rgba(255,255,255,.45);
  }
  .login-foot {
    position: relative; z-index: 2; padding-left: 24px;
    display: flex; align-items: baseline; gap: 14px;
    font-family: var(--mono); font-size: 10px; letter-spacing: .1em;
    color: rgba(255,255,255,.3); text-transform: uppercase;
  }
  .login-foot .sq { width: 7px; height: 7px; background: var(--red); align-self: center; }

  .login-form-wrap {
    display: flex; align-items: center; justify-content: center;
    padding: 48px; position: relative;
  }
  .login-form { width: 100%; max-width: 380px; }
  .login-form .eyebrow { margin-bottom: 14px; }
  .login-form h2 {
    font-family: var(--serif); font-size: 30px; font-weight: 300;
    line-height: 1.15; margin-bottom: 38px;
  }

  .field { position: relative; margin-bottom: 30px; }
  .field label {
    display: block; font-size: 10px; font-weight: 600;
    letter-spacing: .18em; text-transform: uppercase;
    color: var(--ink-light); margin-bottom: 8px;
    transition: color .25s;
  }
  .field input {
    width: 100%; background: transparent; border: none;
    border-bottom: 1px solid var(--ink);
    padding: 8px 0 12px; font-size: 16px; color: var(--ink);
    font-family: var(--ui); outline: none;
  }
  .field .field-bar {
    position: absolute; left: 0; bottom: 0; height: 2px; width: 100%;
    background: var(--red); transform: scaleX(0); transform-origin: left;
    transition: transform .4s cubic-bezier(.22,1,.36,1);
  }
  .field:focus-within label { color: var(--red); }
  .field:focus-within .field-bar { transform: scaleX(1); }

  .login-actions { margin-top: 40px; }
  .btn-login {
    width: 100%; justify-content: space-between;
    padding: 17px 24px; font-size: 12px;
    display: flex; position: relative; overflow: hidden;
  }
  .btn-login .arr { transition: transform .3s; }
  .btn-login:hover .arr { transform: translateX(5px); }
  .btn-login.loading { pointer-events: none; }
  .btn-login.loading .arr { animation: slideArr .7s ease infinite; }
  @keyframes slideArr {
    0% { transform: translateX(0); opacity: 1; }
    60% { transform: translateX(16px); opacity: 0; }
    61% { transform: translateX(-12px); opacity: 0; }
    100% { transform: translateX(0); opacity: 1; }
  }
  .login-note {
    margin-top: 22px; font-family: var(--mono); font-size: 10.5px;
    letter-spacing: .06em; color: var(--ink-light); text-align: center;
  }

  /* červený „wipe" při přihlášení */
  .wipe {
    position: fixed; inset: 0; z-index: 80; background: var(--red);
    transform: scaleX(0); transform-origin: left; pointer-events: none;
  }
  .wipe.run { animation: wipeRun calc(1.1s * var(--speed)) cubic-bezier(.7,0,.2,1) forwards; }
  @keyframes wipeRun {
    0% { transform: scaleX(0); transform-origin: left; }
    48% { transform: scaleX(1); transform-origin: left; }
    52% { transform: scaleX(1); transform-origin: right; }
    100% { transform: scaleX(0); transform-origin: right; }
  }

  /* ════════════════════════════════════════════════════════
     ADMIN SHELL
     ════════════════════════════════════════════════════════ */
  .admin { display: grid; grid-template-columns: 244px 1fr; min-height: 100vh; }

  /* ─── sidebar ─── */
  .side {
    background: var(--bg-dark); color: #fff;
    position: sticky; top: 0; height: 100vh;
    display: flex; flex-direction: column;
    border-right: 1px solid var(--rule-dark);
  }
  .side-logo {
    padding: 26px 26px 22px;
    font-family: var(--serif); font-size: 14px; font-weight: 600;
    letter-spacing: .04em; line-height: 1.3;
    border-bottom: 1px solid var(--rule-dark);
    color: #fff; text-decoration: none; display: block;
  }
  .side-logo span {
    display: block; font-size: 9.5px; font-weight: 300; color: var(--ink-light);
    letter-spacing: .16em; text-transform: uppercase; margin-top: 3px;
  }
  .side-nav { flex: 1; padding: 18px 0; overflow-y: auto; }
  .nav-item {
    display: flex; align-items: center; gap: 14px;
    width: 100%; text-align: left; background: none; border: none;
    padding: 13px 26px; color: rgba(255,255,255,.5);
    font-size: 12.5px; letter-spacing: .04em; font-weight: 450;
    position: relative; transition: color .25s, background .25s;
    text-decoration: none;
  }
  .nav-item .n {
    font-family: var(--mono); font-size: 9.5px; color: rgba(255,255,255,.25);
    letter-spacing: .08em; min-width: 18px; transition: color .25s;
  }
  .nav-item::before {
    content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px;
    background: var(--red); transform: scaleY(0);
    transition: transform .3s cubic-bezier(.22,1,.36,1);
  }
  .nav-item:hover { color: #fff; }
  .nav-item.active { color: #fff; background: rgba(255,255,255,.04); }
  .nav-item.active::before { transform: scaleY(1); }
  .nav-item.active .n { color: var(--red); }
  .nav-badge {
    font-family: var(--mono); font-size: 9px; background: var(--red); color: #fff;
    padding: 2px 6px; font-weight: 700;
  }

  .side-user {
    border-top: 1px solid var(--rule-dark); padding: 18px 26px;
    display: flex; align-items: center; gap: 12px;
  }
  .side-avatar {
    width: 34px; height: 34px; background: var(--red); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-family: var(--serif); font-size: 13px; flex-shrink: 0;
  }
  .side-user-name { font-size: 12px; color: #fff; line-height: 1.3; }
  .side-user-role { font-size: 9.5px; letter-spacing: .1em; text-transform: uppercase; color: var(--ink-light); }
  .side-logout {
    margin-left: auto; background: none; border: none; color: rgba(255,255,255,.3);
    padding: 6px; transition: color .25s;
  }
  .side-logout:hover { color: var(--red); }

  /* ─── hlavní obsah ─── */
  .main { min-width: 0; padding: 0 44px 64px; position: relative; }
  .main-head {
    display: flex; align-items: flex-end; justify-content: space-between;
    gap: 24px; padding: 36px 0 24px;
    border-bottom: 1px solid var(--rule); margin-bottom: 30px;
    flex-wrap: wrap;
  }
  .main-head .eyebrow { margin-bottom: 10px; }
  .main-title {
    font-family: var(--serif); font-size: clamp(26px, 3vw, 38px);
    font-weight: 300; line-height: 1.08; letter-spacing: -.01em;
  }
  .main-date {
    font-family: var(--mono); font-size: 10.5px; letter-spacing: .08em;
    text-transform: uppercase; color: var(--ink-light);
  }
  .head-actions { display: flex; gap: 12px; align-items: center; }

  /* ════════════════════════════════════════════════════════
     DASHBOARD
     ════════════════════════════════════════════════════════ */
  .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1px; background: var(--rule); border: 1px solid var(--rule); }
  .stat {
    background: var(--bg); padding: var(--pad) calc(var(--pad) + 4px);
    position: relative; overflow: hidden; transition: background .3s;
  }
  .stat:hover { background: var(--bg-soft); }
  .stat-label { font-size: 9.5px; letter-spacing: .16em; text-transform: uppercase; color: var(--ink-light); font-weight: 600; margin-bottom: 10px; }
  .stat-num { font-family: var(--serif); font-size: 36px; font-weight: 300; line-height: 1; }
  .stat-sub { font-family: var(--mono); font-size: 10px; color: var(--ink-light); margin-top: 8px; letter-spacing: .04em; }
  .stat-sub em { font-style: normal; color: var(--red); }

  .dash-grid { display: grid; grid-template-columns: 1.2fr .8fr; gap: 26px; margin-top: 26px; align-items: start; }

  .card { border: 1px solid var(--rule); background: var(--bg); }
  .card-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: var(--pad) calc(var(--pad) + 4px);
    border-bottom: 1px solid var(--rule);
  }
  .card-title { font-family: var(--serif); font-size: 17px; font-weight: 400; }
  .card-link {
    background: none; border: none; font-size: 10px; font-weight: 600;
    letter-spacing: .14em; text-transform: uppercase; color: var(--ink-light);
    transition: color .2s; text-decoration: none;
  }
  .card-link:hover { color: var(--red); }

  .row-list { list-style: none; }
  .row-list li {
    display: flex; align-items: center; gap: 16px;
    padding: calc(var(--pad) - 4px) calc(var(--pad) + 4px);
    border-bottom: 1px solid var(--rule);
    transition: background .25s;
  }
  .row-list li:last-child { border-bottom: none; }
  .row-list li:hover { background: var(--bg-soft); }
  .row-time { font-family: var(--mono); font-size: 11px; color: var(--red); min-width: 86px; letter-spacing: .02em; }
  .row-main { flex: 1; min-width: 0; }
  .row-title { font-size: 13.5px; font-weight: 500; }
  .row-sub { font-size: 11.5px; color: var(--ink-light); margin-top: 1px; }

  /* tmavá karta „nejbližší akce" */
  .card.dark { background: var(--bg-dark); color: #fff; border-color: var(--bg-dark); position: relative; overflow: hidden; }
  .card.dark .inner { padding: calc(var(--pad) + 8px) calc(var(--pad) + 6px); position: relative; z-index: 2; }
  .card.dark .eyebrow { margin-bottom: 16px; }
  .card.dark h3 { font-family: var(--serif); font-size: 22px; font-weight: 300; line-height: 1.25; margin-bottom: 10px; }
  .card.dark p { font-size: 12.5px; color: rgba(255,255,255,.5); line-height: 1.7; font-weight: 300; }
  .card.dark .count {
    margin-top: 22px; display: flex; align-items: baseline; gap: 10px;
    font-family: var(--mono); font-size: 11px; letter-spacing: .08em;
    text-transform: uppercase; color: rgba(255,255,255,.4);
  }
  .card.dark .count strong { font-family: var(--serif); font-size: 34px; font-weight: 300; color: #fff; letter-spacing: 0; }

  /* ════════════════════════════════════════════════════════
     ČLENOVÉ
     ════════════════════════════════════════════════════════ */
  .toolbar { display: flex; align-items: center; gap: 18px; margin-bottom: 22px; flex-wrap: wrap; }
  .search { position: relative; flex: 1; min-width: 200px; max-width: 320px; }
  .search input {
    width: 100%; background: transparent; border: none;
    border-bottom: 1px solid var(--rule); padding: 9px 0 9px 26px;
    font-size: 13px; outline: none; color: var(--ink); transition: border-color .25s;
  }
  .search input:focus { border-color: var(--red); }
  .search svg { position: absolute; left: 0; top: 50%; transform: translateY(-50%); color: var(--ink-light); }
  .filters { display: flex; gap: 4px; }
  .filter {
    background: none; border: none; padding: 8px 14px;
    font-size: 10.5px; font-weight: 600; letter-spacing: .1em; text-transform: uppercase;
    color: var(--ink-light); position: relative; transition: color .2s;
  }
  .filter::after {
    content: ''; position: absolute; left: 14px; right: 14px; bottom: 2px; height: 2px;
    background: var(--red); transform: scaleX(0); transition: transform .3s cubic-bezier(.22,1,.36,1);
  }
  .filter:hover { color: var(--ink); }
  .filter.active { color: var(--ink); }
  .filter.active::after { transform: scaleX(1); }
  .toolbar .btn { margin-left: auto; }

  .table { width: 100%; border-collapse: collapse; border: 1px solid var(--rule); }
  .table thead th {
    text-align: left; font-size: 9.5px; font-weight: 600; letter-spacing: .16em;
    text-transform: uppercase; color: var(--ink-light);
    padding: 12px calc(var(--pad) + 2px); border-bottom: 1px solid var(--rule);
    background: var(--bg-soft);
  }
  .table tbody td {
    padding: calc(var(--pad) - 5px) calc(var(--pad) + 2px);
    border-bottom: 1px solid var(--rule); font-size: 13px; vertical-align: middle;
  }
  .table tbody tr { cursor: pointer; transition: background .25s; }
  .table tbody tr:hover { background: var(--bg-soft); }
  .table tbody tr:last-child td { border-bottom: none; }
  .member-name { font-weight: 500; display: flex; align-items: center; gap: 12px; }
  .member-ini {
    width: 30px; height: 30px; flex-shrink: 0;
    background: var(--bg-dark); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-family: var(--serif); font-size: 11px;
  }
  td.num { font-family: var(--mono); font-size: 12px; color: var(--ink-mid); }
  .table .contact { color: var(--ink-mid); font-size: 12.5px; }
  .row-arrow { color: var(--ink-light); transition: transform .25s, color .25s; }
  .table tbody tr:hover .row-arrow { transform: translateX(4px); color: var(--red); }

  /* ════════════════════════════════════════════════════════
     ROZVRH
     ════════════════════════════════════════════════════════ */
  .week { display: grid; grid-template-columns: repeat(5, 1fr); gap: 1px; background: var(--rule); border: 1px solid var(--rule); }
  .day { background: var(--bg); min-height: 300px; display: flex; flex-direction: column; }
  .day-head {
    padding: 14px 16px; border-bottom: 1px solid var(--rule);
    display: flex; align-items: baseline; justify-content: space-between;
    background: var(--bg-soft);
  }
  .day-name { font-size: 11px; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; }
  .day-body { padding: 12px; display: flex; flex-direction: column; gap: 10px; flex: 1; }
  .slot {
    border: 1px solid var(--rule); padding: 13px 14px; background: var(--bg);
    position: relative; transition: border-color .25s, transform .25s; cursor: default;
  }
  .slot::before { content: ''; position: absolute; left: -1px; top: -1px; bottom: -1px; width: 3px; background: var(--ink-light); transition: background .25s; }
  .slot:hover { border-color: var(--ink); transform: translateY(-2px); }
  .slot.r::before { background: var(--red); }
  .slot.d::before { background: var(--ink); }
  .slot-time { font-family: var(--mono); font-size: 10.5px; color: var(--ink-light); letter-spacing: .04em; margin-bottom: 5px; }
  .slot-name { font-family: var(--serif); font-size: 15px; font-weight: 400; line-height: 1.2; }
  .slot-meta { font-size: 11px; color: var(--ink-light); margin-top: 4px; }
  .day-empty {
    flex: 1; display: flex; align-items: center; justify-content: center;
    font-family: var(--mono); font-size: 10px; letter-spacing: .14em;
    text-transform: uppercase; color: rgba(28,25,20,.25);
  }
  .sched-legend { display: flex; gap: 26px; margin-top: 18px; flex-wrap: wrap; }
  .leg { display: flex; align-items: center; gap: 9px; font-size: 11px; letter-spacing: .08em; text-transform: uppercase; color: var(--ink-mid); }
  .leg i { width: 10px; height: 10px; display: block; }

  /* ════════════════════════════════════════════════════════
     AKCE
     ════════════════════════════════════════════════════════ */
  .events { list-style: none; border: 1px solid var(--rule); }
  .event {
    display: flex; align-items: center; gap: 26px;
    padding: var(--pad) calc(var(--pad) + 6px);
    border-bottom: 1px solid var(--rule);
    transition: background .25s;
  }
  .event:last-child { border-bottom: none; }
  .event:hover { background: var(--bg-soft); }
  .event-date { text-align: center; min-width: 64px; flex-shrink: 0; }
  .event-day { font-family: var(--serif); font-size: 30px; font-weight: 300; line-height: 1; }
  .event-month { font-family: var(--mono); font-size: 10px; letter-spacing: .18em; text-transform: uppercase; color: var(--red); margin-top: 4px; }
  .event-rule { width: 1px; align-self: stretch; background: var(--rule); flex-shrink: 0; }
  .event-main { flex: 1; min-width: 0; }
  .event-title { font-family: var(--serif); font-size: 18px; font-weight: 400; line-height: 1.25; }
  .event-meta { font-size: 12px; color: var(--ink-light); margin-top: 4px; display: flex; gap: 16px; flex-wrap: wrap; }
  .event .tag { flex-shrink: 0; }

  /* ════════════════════════════════════════════════════════
     GALERIE
     ════════════════════════════════════════════════════════ */
  .gal-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
  .album { border: 1px solid var(--rule); background: var(--bg); transition: transform .3s cubic-bezier(.22,1,.36,1), box-shadow .3s; cursor: pointer; }
  .album:hover { transform: translateY(-4px); box-shadow: 0 14px 30px -18px rgba(28,25,20,.35); }
  .album-cover { aspect-ratio: 16/10; display: flex; align-items: center; justify-content: center; border-bottom: 1px solid var(--rule); position: relative; overflow: hidden; }
  .album-cover img { width: 100%; height: 100%; object-fit: cover; }
  .album-count {
    position: absolute; right: 0; bottom: 0; background: var(--bg-dark); color: #fff;
    font-family: var(--mono); font-size: 9.5px; letter-spacing: .1em; padding: 5px 10px;
    white-space: nowrap;
  }
  .album-info { padding: 15px 18px; display: flex; align-items: center; justify-content: space-between; gap: 10px; }
  .album-name { font-family: var(--serif); font-size: 15px; font-weight: 400; }
  .album-date { font-family: var(--mono); font-size: 10px; color: var(--ink-light); letter-spacing: .06em; }
  .album.upload { border: 1.5px dashed var(--ink-light); background: transparent; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; min-height: 220px; color: var(--ink-light); transition: border-color .25s, color .25s, transform .3s; }
  .album.upload:hover { border-color: var(--red); color: var(--red); transform: none; box-shadow: none; }
  .album.upload .plus { font-family: var(--serif); font-size: 34px; font-weight: 300; line-height: 1; }
  .album.upload .lbl { font-size: 10px; font-weight: 600; letter-spacing: .16em; text-transform: uppercase; }

  /* ════════════════════════════════════════════════════════
     DOKUMENTY
     ════════════════════════════════════════════════════════ */
  .docs { list-style: none; border: 1px solid var(--rule); }
  .doc {
    display: flex; align-items: center; gap: 20px;
    padding: calc(var(--pad) - 2px) calc(var(--pad) + 6px);
    border-bottom: 1px solid var(--rule); transition: background .25s;
  }
  .doc:last-child { border-bottom: none; }
  .doc:hover { background: var(--bg-soft); }
  .doc-ext {
    font-family: var(--mono); font-size: 9px; font-weight: 700; letter-spacing: .1em;
    border: 1px solid var(--ink); padding: 6px 8px; flex-shrink: 0;
  }
  .doc-name { font-size: 13.5px; font-weight: 500; flex: 1; min-width: 0; }
  .doc-name span { display: block; font-family: var(--mono); font-size: 10px; font-weight: 400; color: var(--ink-light); margin-top: 2px; letter-spacing: .04em; }
  .doc-dl { font-family: var(--mono); font-size: 11px; color: var(--ink-mid); white-space: nowrap; }
  .doc-dl em { font-style: normal; color: var(--red); }
  .doc-actions { display: flex; gap: 4px; }

  /* ════════════════════════════════════════════════════════
     MODAL
     ════════════════════════════════════════════════════════ */
  .modal-bg {
    position: fixed; inset: 0; z-index: 90;
    background: rgba(20,17,13,.55); backdrop-filter: blur(3px);
    display: flex; align-items: center; justify-content: center; padding: 28px;
    opacity: 0; pointer-events: none; transition: opacity .35s;
  }
  .modal-bg.open { opacity: 1; pointer-events: auto; }
  .modal {
    background: var(--bg); width: 100%; max-width: 520px; max-height: 88vh; overflow-y: auto;
    transform: translateY(26px) scale(.985); opacity: 0;
    transition: transform .45s cubic-bezier(.22,1,.36,1), opacity .35s;
    position: relative;
  }
  .modal-bg.open .modal { transform: none; opacity: 1; }
  .modal-band { height: 4px; background: var(--red); }
  .modal-inner { padding: 34px 38px 38px; }
  .modal-close {
    position: absolute; top: 16px; right: 16px; background: none; border: none;
    color: var(--ink-light); font-size: 18px; line-height: 1; padding: 8px;
    transition: color .2s, transform .3s;
  }
  .modal-close:hover { color: var(--red); transform: rotate(90deg); }
  .modal .eyebrow { margin-bottom: 12px; }
  .modal h3 { font-family: var(--serif); font-size: 26px; font-weight: 300; line-height: 1.15; margin-bottom: 26px; }
  .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px 26px; margin-bottom: 28px; }
  .detail-item .k { font-size: 9.5px; font-weight: 600; letter-spacing: .16em; text-transform: uppercase; color: var(--ink-light); margin-bottom: 5px; }
  .detail-item .v { font-size: 14px; }
  .detail-item .v.mono { font-family: var(--mono); font-size: 12.5px; }
  .modal-actions { display: flex; gap: 12px; flex-wrap: wrap; }
  .modal .field { margin-bottom: 22px; }
  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0 24px; }
  .field select {
    width: 100%; background: transparent; border: none; border-bottom: 1px solid var(--ink);
    padding: 8px 0 12px; font-size: 15px; color: var(--ink); outline: none;
  }

  /* toast */
  .toast {
    position: fixed; bottom: 30px; left: 50%; z-index: 120;
    transform: translate(-50%, 80px);
    background: var(--bg-dark); color: #fff;
    padding: 14px 24px; font-size: 12px; letter-spacing: .06em;
    display: flex; align-items: center; gap: 12px;
    transition: transform .5s cubic-bezier(.22,1,.36,1);
    pointer-events: none;
  }
  .toast.show { transform: translate(-50%, 0); }
  .toast .sq { width: 8px; height: 8px; background: var(--red); flex-shrink: 0; }

  /* ════════════════════════════════════════════════════════
     IMPLEMENTAČNÍ DOPLŇKY (nad rámec prototypu)
     ════════════════════════════════════════════════════════ */
  .field textarea {
    width: 100%; background: transparent; border: none;
    border-bottom: 1px solid var(--ink);
    padding: 8px 0 12px; font-size: 15px; color: var(--ink);
    font-family: var(--ui); outline: none; resize: vertical; min-height: 58px;
  }
  .field input[type="file"] {
    border: 1.5px dashed var(--ink-light); border-bottom-style: dashed;
    padding: 14px; font-size: 12px; color: var(--ink-mid); width: 100%;
  }
  .field-error {
    display: block; margin-top: 7px;
    font-family: var(--mono); font-size: 10.5px; letter-spacing: .04em;
    color: var(--red);
  }
  .check {
    display: flex; align-items: center; gap: 10px;
    font-size: 12.5px; color: var(--ink-mid); cursor: pointer; margin-bottom: 22px;
  }
  .check input { accent-color: var(--red); width: 15px; height: 15px; flex-shrink: 0; }
  .empty-note {
    border: 1px dashed var(--rule); padding: 28px; text-align: center;
    font-family: var(--mono); font-size: 11px; letter-spacing: .12em;
    text-transform: uppercase; color: var(--ink-light);
  }
  .doc.is-hidden { opacity: .45; }
  .section-sub {
    font-family: var(--serif); font-size: 20px; font-weight: 400;
    margin: 38px 0 16px; display: flex; align-items: baseline; gap: 12px;
  }
  .section-sub .cnt { font-family: var(--mono); font-size: 10.5px; color: var(--ink-light); letter-spacing: .08em; }
  .album .album-src {
    position: absolute; left: 0; bottom: 0; background: rgba(28,25,20,.6); color: #fff;
    font-family: var(--mono); font-size: 9px; letter-spacing: .1em; padding: 5px 10px;
    text-transform: uppercase;
  }
  .album-tools { display: flex; gap: 2px; padding: 0 12px 12px; }
  .chart-card { border: 1px solid var(--rule); background: var(--bg); padding: 22px 24px 14px; margin-top: 26px; }
  .chart-head { display: flex; align-items: baseline; justify-content: space-between; gap: 16px; margin-bottom: 14px; flex-wrap: wrap; }
  .chart-title { font-family: var(--serif); font-size: 17px; font-weight: 400; }
  .chart-svg { width: 100%; height: auto; display: block; }
  .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 26px; margin-top: 26px; align-items: start; }

  /* ════════════════════════════════════════════════════════
     RESPONSIVE
     ════════════════════════════════════════════════════════ */
  @media (max-width: 1180px) {
    .stats { grid-template-columns: repeat(2, 1fr); }
    .week { grid-template-columns: repeat(2, 1fr); }
    .day { min-height: 0; }
    .day-empty { padding: 18px 0; }
    .gal-grid { grid-template-columns: repeat(2, 1fr); }
    .dash-grid { grid-template-columns: 1fr; }
    .two-col { grid-template-columns: 1fr; }
  }
  @media (max-width: 900px) {
    .login { grid-template-columns: 1fr; grid-template-rows: auto 1fr; position: absolute; min-height: 100vh; }
    .login-side { padding: 28px 28px 32px; gap: 28px; }
    .login-kanji { font-size: 120px; right: 8px; }
    .login-hero h1 { font-size: 34px; }
    .login-hero p { display: none; }
    .login-foot { display: none; }
    .login-form-wrap { padding: 40px 28px 64px; align-items: flex-start; }

    .admin { grid-template-columns: 1fr; }
    .side {
      position: sticky; top: 0; height: auto; z-index: 40;
      flex-direction: row; align-items: center;
      border-right: none; border-bottom: 1px solid var(--rule-dark);
    }
    .side-logo { padding: 14px 18px; border-bottom: none; flex-shrink: 0; }
    .side-logo span { display: none; }
    .side-nav {
      display: flex; padding: 0 6px; overflow-x: auto; scrollbar-width: none;
    }
    .side-nav::-webkit-scrollbar { display: none; }
    .nav-item { padding: 20px 13px; white-space: nowrap; width: auto; }
    .nav-item .n { display: none; }
    .nav-item::before { width: auto; height: 3px; top: auto; left: 13px; right: 13px; bottom: 0; transform: scaleX(0); }
    .nav-item.active::before { transform: scaleX(1); }
    .side-user { border-top: none; padding: 10px 14px; margin-left: auto; flex-shrink: 0; }
    .side-user-name, .side-user-role { display: none; }
    .main { padding: 0 22px 56px; }
    .toolbar .btn { margin-left: 0; width: 100%; justify-content: center; }
    .table thead { display: none; }
    .table, .table tbody, .table tr, .table td { display: block; }
    .table tr { padding: 14px 16px; border-bottom: 1px solid var(--rule); }
    .table td { border: none; padding: 3px 0; }
    .table .row-arrow { display: none; }
    .week { grid-template-columns: 1fr; }
    .gal-grid { grid-template-columns: 1fr; }
    .event { gap: 16px; flex-wrap: wrap; }
    .event-rule { display: none; }
    .doc { flex-wrap: wrap; gap: 12px; }
    .form-row { grid-template-columns: 1fr; }
    .modal-inner { padding: 28px 24px 30px; }
    .detail-grid { grid-template-columns: 1fr; }
  }
  @media (max-width: 560px) {
    .stats { grid-template-columns: 1fr 1fr; }
    .stat-num { font-size: 28px; }
    .main-head { padding-top: 24px; }
  }
</style>
