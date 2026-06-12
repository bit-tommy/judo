<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>{{ $title ?? 'Administrace | JC Raion-Ryu' }}</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,300;0,400;0,600;0,700;1,300&family=IBM+Plex+Sans:wght@300;400;450;500;600&family=IBM+Plex+Mono:wght@400;600&family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

@include('partials.admin-css')
</head>
<body>

<div class="admin">
  <x-ui.admin-sidebar />
  <main class="main">
    {{ $slot }}
  </main>
</div>

{{-- Toast — plní ho Livewire event `toast` (viz skript níže). --}}
<div class="toast" id="toast" role="status" aria-live="polite">
  <span class="sq"></span>
  <span id="toast-msg"></span>
</div>

<script>
  /* Toast notifikace. Skript přežívá wire:navigate (guard proti dvojí
     registraci); element #toast se hledá až při události, protože navigace
     vyměňuje celé <body>. */
  (function () {
    if (window.__rrAdminToast) return;
    window.__rrAdminToast = true;

    var timer;

    document.addEventListener('livewire:init', function () {
      Livewire.on('toast', function (event) {
        var payload = Array.isArray(event) ? event[0] : event;
        var msg = (payload && payload.message) || '';
        var toast = document.getElementById('toast');
        if (!toast || msg === '') return;

        document.getElementById('toast-msg').textContent = msg;
        toast.classList.add('show');
        clearTimeout(timer);
        timer = setTimeout(function () { toast.classList.remove('show'); }, 2600);
      });
    });
  })();
</script>

</body>
</html>
