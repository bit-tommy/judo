<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>{{ $title ?? 'Přihlášení | Administrace JC Raion-Ryu' }}</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,300;0,400;0,600;0,700;1,300&family=IBM+Plex+Sans:wght@300;400;450;500;600&family=IBM+Plex+Mono:wght@400;600&family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

@include('partials.admin-css')
</head>
<body>

{{ $slot }}

{{-- Červený „wipe" přes obrazovku po úspěšném přihlášení. --}}
<div class="wipe" id="wipe"></div>

<script>
  /* Po události `login-success` přehraje wipe a teprve pak přejde do
     administrace (tvrdá navigace — měníme layout i session). */
  document.addEventListener('livewire:init', function () {
    Livewire.on('login-success', function (event) {
      var payload = Array.isArray(event) ? event[0] : event;
      var redirect = (payload && payload.redirect) || '/admin';
      var wipe = document.getElementById('wipe');
      var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

      if (!wipe || reduced) {
        window.location.href = redirect;
        return;
      }

      wipe.classList.add('run');
      // 540 ms = polovina wipe animace, obrazovka je celá červená.
      setTimeout(function () { window.location.href = redirect; }, 540);
    });
  });
</script>

</body>
</html>
