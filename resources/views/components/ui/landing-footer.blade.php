{{--
    Sdílená patička landing designu. Stejně jako navigace sama pozná, zda jsme
    na úvodu (kotvy scrollují v rámci stránky) nebo na podstránce (kotvy míří
    na úvodní stránku).
--}}
@php
    $onHome = request()->routeIs('home');
    $home   = $onHome ? '' : route('home');
@endphp

<footer>
  <div style="display:flex;align-items:center;gap:16px;">
    <x-ui.logo href="{{ route('home') }}" variant="dark" size="52px" />
    <div class="footer-logo">Škola Bojových Umění Rubidó · JC Raion-Ryu/Taijutsu · od roku 2010</div>
  </div>
  <div class="footer-links">
    <a href="{{ $home }}#judo">Judo</a>
    <a href="{{ $home }}#techniky">Techniky</a>
    <a href="{{ route('kodokan.masters-stay') }}">Pobyt mistrů</a>
    <a href="{{ route('downloads') }}">Ke stažení</a>
    <a href="{{ $home }}#kontakt">Kontakt</a>
  </div>
  <div class="footer-copy">© {{ date('Y') }} ŠKOLA BOJOVÝCH UMĚNÍ – RUBIDÓ, z.s.</div>
</footer>
