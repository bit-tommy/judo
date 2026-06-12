<?php

use App\Models\Document;
use App\Models\Inquiry;
use App\Models\SiteVisit;
use Illuminate\Support\Carbon;
use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;

new #[Layout('components.layouts.admin')]
#[Title('Analytika | Administrace JC Raion-Ryu')]
class extends Component {
    /** Zvolené období grafu: 7 / 30 / 90 dní / vše. */
    public string $period = '30';

    public function setPeriod(string $period): void
    {
        $this->period = in_array($period, ['7', '30', '90', 'vse'], true) ? $period : '30';
    }

    public function with(): array
    {
        $from = $this->periodStart();
        $daily = $this->dailyVisitors($from);

        return [
            'today' => SiteVisit::whereDate('visit_date', today())->count(),
            'last7' => SiteVisit::where('visit_date', '>=', today()->subDays(6))->count(),
            'last30' => SiteVisit::where('visit_date', '>=', today()->subDays(29))->count(),
            'total' => SiteVisit::count(),
            'chart' => $this->buildChart($daily),
            'periodVisitors' => array_sum($daily),
            'periodInquiries' => Inquiry::where('created_at', '>=', $from->startOfDay())->count(),
            'topDocuments' => Document::where('downloads', '>', 0)->orderByDesc('downloads')->take(10)->get(),
        ];
    }

    private function periodStart(): Carbon
    {
        if ($this->period === 'vse') {
            $first = SiteVisit::min('visit_date');
            $start = $first !== null ? Carbon::parse($first) : today()->subDays(29);

            // Aspoň 30 dní, nejvýše rok (ať je graf čitelný).
            return $start->min(today()->subDays(29))->max(today()->subDays(364));
        }

        return today()->subDays((int) $this->period - 1);
    }

    /**
     * Denní počty unikátních návštěvníků s doplněnými nulami.
     *
     * @return array<string, int> klíč = Y-m-d
     */
    private function dailyVisitors(Carbon $from): array
    {
        $counts = SiteVisit::selectRaw('visit_date, count(*) as c')
            ->where('visit_date', '>=', $from->toDateString())
            ->groupBy('visit_date')
            ->pluck('c', 'visit_date')
            ->mapWithKeys(fn ($c, $d) => [Carbon::parse($d)->toDateString() => (int) $c]);

        $daily = [];
        for ($cursor = $from->copy(); $cursor->lte(today()); $cursor->addDay()) {
            $daily[$cursor->toDateString()] = $counts[$cursor->toDateString()] ?? 0;
        }

        return $daily;
    }

    /**
     * Geometrie SVG area grafu (počítá se na serveru, bez JS knihoven).
     *
     * @param  array<string, int>  $daily
     * @return array{w: int, h: int, line: string, area: string, points: array<int, array{x: float, y: float, label: string, count: int}>, max: int, mid: float, baseline: float, top: float, padL: int}
     */
    private function buildChart(array $daily): array
    {
        $w = 720;
        $h = 220;
        $padL = 38;
        $padR = 12;
        $padT = 14;
        $padB = 26;
        $iw = $w - $padL - $padR;
        $ih = $h - $padT - $padB;

        $counts = array_values($daily);
        $dates = array_keys($daily);
        $n = count($counts);
        $max = max(1, ...($counts ?: [0]));

        $points = [];
        foreach ($counts as $i => $count) {
            $date = Carbon::parse($dates[$i]);
            $points[] = [
                'x' => $padL + ($n > 1 ? $i / ($n - 1) * $iw : $iw / 2),
                'y' => $padT + $ih - ($count / $max) * $ih,
                'label' => $date->day.'. '.$date->month.'.',
                'count' => $count,
            ];
        }

        $line = '';
        foreach ($points as $i => $point) {
            $line .= ($i === 0 ? 'M' : ' L').round($point['x'], 1).' '.round($point['y'], 1);
        }

        $baseline = $padT + $ih;
        $area = $points === [] ? '' : $line
            .' L'.round(end($points)['x'], 1).' '.$baseline
            .' L'.round($points[0]['x'], 1).' '.$baseline.' Z';

        return [
            'w' => $w,
            'h' => $h,
            'line' => $line,
            'area' => $area,
            'points' => $points,
            'max' => $max,
            'mid' => $padT + $ih / 2,
            'baseline' => $baseline,
            'top' => (float) $padT,
            'padL' => $padL,
        ];
    }
}; ?>

<section class="panel">
  <div class="main-head">
    <div>
      <div class="eyebrow reveal" style="--i: 0">Analytika</div>
      <h1 class="main-title reveal" style="--i: 1">Návštěvnost webu</h1>
    </div>
    <div class="main-date reveal" style="--i: 2">anonymní měření · bez IP adres</div>
  </div>

  <div class="stats reveal" style="--i: 3">
    <div class="stat">
      <div class="stat-label">Dnes</div>
      <div class="stat-num">{{ $today }}</div>
      <div class="stat-sub">unikátních návštěvníků</div>
    </div>
    <div class="stat">
      <div class="stat-label">Posledních 7 dní</div>
      <div class="stat-num">{{ $last7 }}</div>
      <div class="stat-sub">návštěvnických dní</div>
    </div>
    <div class="stat">
      <div class="stat-label">Posledních 30 dní</div>
      <div class="stat-num">{{ $last30 }}</div>
      <div class="stat-sub">návštěvnických dní</div>
    </div>
    <div class="stat">
      <div class="stat-label">Celkem</div>
      <div class="stat-num">{{ $total }}</div>
      <div class="stat-sub">od spuštění měření</div>
    </div>
  </div>

  <div class="chart-card reveal" style="--i: 4">
    <div class="chart-head">
      <div class="chart-title">Unikátní návštěvníci po dnech</div>
      <div class="filters">
        @foreach (['7' => '7 dní', '30' => '30 dní', '90' => '90 dní', 'vse' => 'Vše'] as $value => $label)
          <button type="button" class="filter {{ $period === $value ? 'active' : '' }}" wire:click="setPeriod('{{ $value }}')">{{ $label }}</button>
        @endforeach
      </div>
    </div>

    <svg class="chart-svg" viewBox="0 0 {{ $chart['w'] }} {{ $chart['h'] }}" role="img" aria-label="Graf denní návštěvnosti">
      <defs>
        <linearGradient id="visitFill" x1="0" y1="0" x2="0" y2="1">
          <stop offset="0%" stop-color="#C0261E" stop-opacity=".22"/>
          <stop offset="100%" stop-color="#C0261E" stop-opacity="0"/>
        </linearGradient>
      </defs>

      {{-- mřížka + popisky osy Y --}}
      @foreach ([['y' => $chart['top'], 'label' => $chart['max']], ['y' => $chart['mid'], 'label' => round($chart['max'] / 2)], ['y' => $chart['baseline'], 'label' => 0]] as $grid)
        <line x1="{{ $chart['padL'] }}" y1="{{ $grid['y'] }}" x2="{{ $chart['w'] - 12 }}" y2="{{ $grid['y'] }}" stroke="rgba(28,25,20,.08)" stroke-width="1"/>
        <text x="{{ $chart['padL'] - 8 }}" y="{{ $grid['y'] + 3 }}" text-anchor="end" font-size="9" fill="#8C8680" font-family="IBM Plex Mono, monospace">{{ $grid['label'] }}</text>
      @endforeach

      @if ($chart['area'] !== '')
        <path d="{{ $chart['area'] }}" fill="url(#visitFill)"/>
        <path d="{{ $chart['line'] }}" fill="none" stroke="#C0261E" stroke-width="1.8" stroke-linejoin="round" stroke-linecap="round"/>
      @endif

      {{-- neviditelné hover zóny s nativním tooltipem --}}
      @foreach ($chart['points'] as $point)
        <g>
          <circle cx="{{ round($point['x'], 1) }}" cy="{{ round($point['y'], 1) }}" r="8" fill="transparent">
            <title>{{ $point['label'] }} — {{ $point['count'] }} {{ $point['count'] === 1 ? 'návštěvník' : ($point['count'] >= 2 && $point['count'] <= 4 ? 'návštěvníci' : 'návštěvníků') }}</title>
          </circle>
          @if ($point['count'] > 0)
            <circle cx="{{ round($point['x'], 1) }}" cy="{{ round($point['y'], 1) }}" r="2" fill="#C0261E"/>
          @endif
        </g>
      @endforeach

      {{-- popisky osy X (první a poslední den) --}}
      @if ($chart['points'] !== [])
        <text x="{{ round($chart['points'][0]['x'], 1) }}" y="{{ $chart['h'] - 8 }}" font-size="9" fill="#8C8680" font-family="IBM Plex Mono, monospace">{{ $chart['points'][0]['label'] }}</text>
        <text x="{{ round(end($chart['points'])['x'], 1) }}" y="{{ $chart['h'] - 8 }}" text-anchor="end" font-size="9" fill="#8C8680" font-family="IBM Plex Mono, monospace">{{ end($chart['points'])['label'] }}</text>
      @endif
    </svg>
  </div>

  <div class="two-col">
    <div class="card reveal" style="--i: 5">
      <div class="card-head">
        <div class="card-title">Nejstahovanější dokumenty</div>
        <a class="card-link" href="{{ route('admin.documents') }}" wire:navigate>Dokumenty →</a>
      </div>
      @if ($topDocuments->isEmpty())
        <div class="empty-note" style="border: none;">Zatím žádná stažení — počítadlo běží od nasazení.</div>
      @else
        <ul class="row-list">
          @foreach ($topDocuments as $document)
            <li wire:key="top-doc-{{ $document->id }}">
              <span class="row-time">{{ $document->downloads }}×</span>
              <div class="row-main">
                <div class="row-title">{{ $document->title }}</div>
                <div class="row-sub">{{ $document->isExternal() ? 'externí odkaz' : $document->filename }}</div>
              </div>
            </li>
          @endforeach
        </ul>
      @endif
    </div>

    <div class="card reveal" style="--i: 6">
      <div class="card-head">
        <div class="card-title">Za zvolené období</div>
      </div>
      <ul class="row-list">
        <li>
          <span class="row-time">{{ $periodVisitors }}</span>
          <div class="row-main">
            <div class="row-title">Návštěvnických dní</div>
            <div class="row-sub">součet unikátních návštěvníků po dnech</div>
          </div>
        </li>
        <li>
          <span class="row-time">{{ $periodInquiries }}</span>
          <div class="row-main">
            <div class="row-title">Poptávek z formuláře</div>
            <div class="row-sub">odeslané dotazy a objednávky tréninků</div>
          </div>
        </li>
      </ul>
      <div style="padding: 16px 24px 20px; font-size: 11.5px; line-height: 1.7; color: var(--ink-light); border-top: 1px solid var(--rule);">
        Měření je anonymní — ukládá se jen otisk náhodného cookie tokenu a datum.
        Žádné IP adresy, žádné chování, boti se nepočítají.
      </div>
    </div>
  </div>
</section>
