<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;

new #[Layout('components.layouts.admin-login')]
#[Title('Přihlášení | Administrace JC Raion-Ryu')]
class extends Component {
    public string $email = '';

    public string $password = '';

    public function mount(): void
    {
        if (Auth::check()) {
            $this->redirect(route('admin.dashboard'), navigate: false);
        }
    }

    public function login(): void
    {
        $this->validate(
            [
                'email' => 'required|email',
                'password' => 'required|string',
            ],
            [
                'email.required' => 'Vyplňte prosím e-mail.',
                'email.email' => 'Zadejte platnou e-mailovou adresu.',
                'password.required' => 'Vyplňte prosím heslo.',
            ],
        );

        $key = Str::lower($this->email).'|'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'email' => 'Příliš mnoho pokusů o přihlášení. Zkuste to znovu za '.RateLimiter::availableIn($key).' s.',
            ]);
        }

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            RateLimiter::hit($key, 60);

            throw ValidationException::withMessages([
                'email' => 'Nesprávný e-mail nebo heslo.',
            ]);
        }

        RateLimiter::clear($key);
        session()->regenerate();

        // Přesměrování dělá JS v layoutu — nejdřív přehraje červený wipe.
        $this->dispatch('login-success', redirect: route('admin.dashboard'));
    }
}; ?>

<div class="login">
  <div class="login-side">
    <div class="login-grid-line"></div>
    <a class="login-logo reveal" style="--i: 0" href="{{ route('home') }}">
      Škola Bojových Umění
      <span>Rubidó · JC Raion-Ryu</span>
    </a>
    <div class="login-hero">
      <div class="eyebrow reveal" style="--i: 1">Administrace klubu</div>
      <h1 class="reveal" style="--i: 2">Cesta vede<br>i přes <em>papíry</em>.</h1>
      <p class="reveal" style="--i: 3">Členové, rozvrh, akce, galerie a dokumenty — vše pro chod oddílu na jednom místě.</p>
    </div>
    <div class="login-foot reveal" style="--i: 4">
      <span class="sq"></span>
      <span>Jūdō · jemná cesta</span>
    </div>
  </div>

  <div class="login-form-wrap">
    <form class="login-form" wire:submit="login">
      <div class="eyebrow reveal" style="--i: 2">Přihlášení</div>
      <h2 class="reveal" style="--i: 3">Vítejte zpět<br>na tatami</h2>

      <div class="field reveal" style="--i: 4">
        <label for="login-email">E-mail</label>
        <input type="email" id="login-email" wire:model="email" autocomplete="username" placeholder="vedouci@raion-ryu.cz">
        <div class="field-bar"></div>
        @error('email') <span class="field-error">{{ $message }}</span> @enderror
      </div>
      <div class="field reveal" style="--i: 5">
        <label for="login-pass">Heslo</label>
        <input type="password" id="login-pass" wire:model="password" autocomplete="current-password" placeholder="••••••••">
        <div class="field-bar"></div>
        @error('password') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="login-actions reveal" style="--i: 6">
        <button type="submit" class="btn btn-login" wire:loading.class="loading" wire:target="login">
          <span class="lbl">
            <span wire:loading.remove wire:target="login">Vstoupit do dojo</span>
            <span wire:loading wire:target="login">Ověřuji…</span>
          </span>
          <span class="arr">
            <svg width="16" height="12" viewBox="0 0 16 12" fill="none"><path d="M1 6h13M10 1.5L14.5 6 10 10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
          </span>
        </button>
        <div class="login-note">Přístup mají pouze vedoucí klubu.</div>
      </div>
    </form>
  </div>
</div>
