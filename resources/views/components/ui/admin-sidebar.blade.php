{{--
    Sidebar administrace (na mobilu horní lišta — řeší CSS v admin-css).
    Aktivní položka podle aktuální routy; badge u Členů = počet poptávek
    z webu, které vedoucí ještě nevyřídil.
--}}
@php
    $pendingInquiries = \App\Models\Inquiry::unhandled()->count();

    $items = [
        ['route' => 'admin.dashboard', 'n' => '01', 'label' => 'Přehled',   'badge' => 0],
        ['route' => 'admin.members',   'n' => '02', 'label' => 'Členové',   'badge' => $pendingInquiries],
        ['route' => 'admin.schedule',  'n' => '03', 'label' => 'Rozvrh',    'badge' => 0],
        ['route' => 'admin.events',    'n' => '04', 'label' => 'Akce',      'badge' => 0],
        ['route' => 'admin.gallery',   'n' => '05', 'label' => 'Galerie',   'badge' => 0],
        ['route' => 'admin.documents', 'n' => '06', 'label' => 'Dokumenty', 'badge' => 0],
        ['route' => 'admin.pricing',   'n' => '07', 'label' => 'Ceník',     'badge' => 0],
        ['route' => 'admin.analytics', 'n' => '08', 'label' => 'Analytika', 'badge' => 0],
    ];
@endphp
<aside class="side">
  <a class="side-logo" href="{{ route('admin.dashboard') }}" wire:navigate>
    Raion-Ryu
    <span>Administrace</span>
  </a>

  <nav class="side-nav">
    @foreach ($items as $item)
      <a class="nav-item {{ request()->routeIs($item['route']) ? 'active' : '' }}"
         href="{{ route($item['route']) }}" wire:navigate>
        <span class="n">{{ $item['n'] }}</span>{{ $item['label'] }}
        @if ($item['badge'] > 0)
          <span class="nav-badge">{{ $item['badge'] }}</span>
        @endif
      </a>
    @endforeach
  </nav>

  <div class="side-user">
    <div class="side-avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
    <div>
      <div class="side-user-name">{{ auth()->user()->name ?? '' }}</div>
      <div class="side-user-role">Vedoucí klubu</div>
    </div>
    <form method="POST" action="{{ route('admin.logout') }}">
      @csrf
      <button type="submit" class="side-logout" title="Odhlásit se">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M6 2H3v12h3M10.5 11l3-3-3-3M13.5 8H6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path></svg>
      </button>
    </form>
  </div>
</aside>
