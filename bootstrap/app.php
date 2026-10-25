<?php

use App\Http\Middleware\RedirectForgeDomain;
use App\Http\Middleware\TrackSiteVisit;
use App\Support\CookieConsent;
use App\Support\SiteVisitRecorder;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Náhradní doména *.on-forge.com → 301 na ostrou doménu (APP_URL).
        $middleware->prepend(RedirectForgeDomain::class);

        // Nepřihlášené posíláme na login administrace (jediná chráněná sekce).
        $middleware->redirectGuestsTo(fn () => route('admin.login'));

        // Anonymní počítání návštěvnosti (viz admin sekce Analytika) a volba
        // z cookie lišty. Návštěvnický token je náhodné UUID a souhlas prostý
        // výčet — šifrování není potřeba, hodnoty jsou čitelné v prohlížeči
        // a přežijí i případnou rotaci APP_KEY.
        $middleware->encryptCookies(except: [SiteVisitRecorder::COOKIE, CookieConsent::COOKIE]);
        $middleware->web(append: [
            TrackSiteVisit::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
