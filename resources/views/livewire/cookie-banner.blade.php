<?php
use App\Support\CookieConsent;
use App\Support\SiteVisitRecorder;
use Illuminate\Support\Facades\Cookie;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

/**
 * Cookie lišta – souhlas s nepovinnými (analytickými) cookies.
 *
 * Vložena jednou v landing layoutu, takže je na každé živé stránce webu
 * (administrace ji nemá – tam jsou jen nezbytné přihlašovací cookies).
 * Dokud návštěvník nerozhodne, lišta se zobrazuje a web nepočítá
 * návštěvnost (middleware TrackSiteVisit čte CookieConsent).
 *
 * Volba se ukládá v Livewire akci (Cookie::queue do odpovědi), ne
 * v JavaScriptu: při „Přijmout vše" se tak hned započítá i právě probíhající
 * návštěva, kterou middleware už nestihl (souhlas přišel až po načtení stránky).
 *
 * Stránka /cookies lištu znovu otevře událostí `cookie-consent-reopen`
 * a poslouchá `cookie-consent-changed`, aby ukázala aktuální volbu.
 */
new class extends Component
{
    /** Návštěvník už volbu udělal → lišta se nevykresluje. */
    public bool $decided = false;

    public function mount(): void
    {
        $this->decided = CookieConsent::fromRequest(request()) !== null;
    }

    public function acceptAll(): void
    {
        $this->remember(CookieConsent::ALL);

        // První návštěva se započítá hned (viz docblock komponenty).
        SiteVisitRecorder::record(request());
    }

    public function acceptNecessary(): void
    {
        $this->remember(CookieConsent::NECESSARY);

        // Případný dřívější souhlas odvolán → analytický token zahodíme.
        SiteVisitRecorder::forget();
    }

    #[On('cookie-consent-reopen')]
    public function reopen(): void
    {
        $this->decided = false;
    }

    private function remember(string $choice): void
    {
        Cookie::queue(CookieConsent::make($choice));
        $this->decided = true;
        $this->dispatch('cookie-consent-changed', choice: $choice);
    }
}; ?>

<div>
  @unless ($decided)
    <div class="cookie-bar" role="region" aria-label="Nastavení cookies">
      <div class="cookie-bar-text">
        <strong class="cookie-bar-title">Cookies na tomto webu</strong>
        <p>Nezbytné cookies drží web v chodu. Navíc bychom rádi anonymně počítali návštěvnost – ale jen s vaším souhlasem. Žádné reklamy ani sledování napříč weby. <a href="{{ route('cookies') }}" wire:navigate>Více o cookies</a></p>
      </div>
      <div class="cookie-bar-actions">
        <button type="button" class="btn-primary" wire:click="acceptAll" wire:loading.attr="disabled">Přijmout vše</button>
        <button type="button" class="btn-ghost" wire:click="acceptNecessary" wire:loading.attr="disabled">Pouze nezbytné</button>
      </div>
    </div>
  @endunless
</div>
