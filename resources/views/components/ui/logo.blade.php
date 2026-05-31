@props([
    'size' => '52px',            // rendered box (loga jsou čtvercová → šířka = výška)
    'href' => '/',               // cíl odkazu
    'variant' => 'default',      // 'dark' = světlá podložka pro tmavé pozadí (footer domovské stránky)
    'label' => 'Judo Club Raion-ryu',
])

@php $isDark = $variant === 'dark'; @endphp

@once
<style>
    /* Střídající se loga poboček (Vodochody / Praha) – čistě CSS crossfade, bez JS. */
    /* Loga mají vlastní bílé pozadí → posadíme je na jemnou bílou kartičku se zaoblením,
       aby vypadala záměrně na světlém (krém/bílá) i tmavém podkladu. Zaoblený čtverec
       ořízne jen prázdné rohy, samotný kruhový znak zůstává netknutý. */
    .rr-logo{
        position:relative;display:inline-block;flex:none;line-height:0;text-decoration:none;
        background:#fff;border-radius:14px;overflow:hidden;
        box-shadow:0 1px 6px rgba(0,0,0,.12), 0 0 0 1px rgba(0,0,0,.05);
    }
    .rr-logo__img{position:absolute;inset:0;width:100%;height:100%;object-fit:contain;}
    .rr-logo__img--a{animation:rrLogoA 10s ease-in-out infinite;}
    .rr-logo__img--b{animation:rrLogoB 10s ease-in-out infinite;}
    /* Tmavé pozadí (footer domovské stránky): výraznější stín, ať kartička vystoupí. */
    .rr-logo--chip{box-shadow:0 3px 14px rgba(0,0,0,.30);}

    @keyframes rrLogoA{
        0%,42%   {opacity:1;transform:scale(1);}
        50%,92%  {opacity:0;transform:scale(.9);}
        100%     {opacity:1;transform:scale(1);}
    }
    @keyframes rrLogoB{
        0%,42%   {opacity:0;transform:scale(.9);}
        50%,92%  {opacity:1;transform:scale(1);}
        100%     {opacity:0;transform:scale(.9);}
    }

    /* Bez tweenování pro uživatele, kteří preferují omezený pohyb – jen tvrdé prostřídání. */
    @keyframes rrLogoAStep{0%{opacity:1;}50%{opacity:0;}100%{opacity:1;}}
    @keyframes rrLogoBStep{0%{opacity:0;}50%{opacity:1;}100%{opacity:0;}}
    @media (prefers-reduced-motion: reduce){
        .rr-logo__img--a{animation:rrLogoAStep 10s steps(1,end) infinite;}
        .rr-logo__img--b{animation:rrLogoBStep 10s steps(1,end) infinite;}
    }
</style>
@endonce

<a
    href="{{ $href }}"
    {{ $attributes->merge(['class' => 'rr-logo' . ($isDark ? ' rr-logo--chip' : '')]) }}
    style="width: {{ $size }}; height: {{ $size }};"
    aria-label="{{ $label }}"
>
    <img src="{{ asset('images/logo-vodochody.jpeg') }}" alt="{{ $label }} – Vodochody" class="rr-logo__img rr-logo__img--a" decoding="async">
    <img src="{{ asset('images/logo-praha.jpeg') }}" alt="" aria-hidden="true" class="rr-logo__img rr-logo__img--b" decoding="async">
</a>
