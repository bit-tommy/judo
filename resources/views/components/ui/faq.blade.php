{{--
    Časté dotazy (FAQ).

    Vykreslí otázky z config/content/faq.php jako nativní rozbalovací <details>
    (bez JS, přístupné, přežije wire:navigate) a zároveň vloží do <head>
    strukturovaná data FAQPage (JSON-LD) pro vyhledávače.

    Zabaluje se do <section id="faq" class="faq-section"> v rámci stránky.

    Pozn.: PHP píšeme jen jedním blokem; blokovou a inline formu PHP direktivy
    ve stejném souboru nemíchat — Blade by oba úseky spojil v jeden.
--}}
@php
    $faq = config('content.faq', []);

    // JSON-LD stavíme zde (uvnitř PHP bloku Blade neskenuje direktivy, takže
    // klíče '@context' / '@type' nepadnou za oběť direktivám) – jako <x-seo>.
    $faqJsonLd = empty($faq) ? null : json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array_map(fn ($item) => [
            '@type' => 'Question',
            'name' => $item['q'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => strip_tags($item['a']),
            ],
        ], $faq),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

@if (! empty($faq))
  <div class="section-eyebrow">Časté dotazy</div>
  <h2 class="section-title">Než přijdete poprvé</h2>

  <div class="faq-list">
    @foreach ($faq as $item)
      <details class="faq-item">
        <summary class="faq-q">{{ $item['q'] }}</summary>
        <div class="faq-a">{!! $item['a'] !!}</div>
      </details>
    @endforeach
  </div>

  @push('head')
    <script type="application/ld+json">{!! $faqJsonLd !!}</script>
  @endpush
@endif
