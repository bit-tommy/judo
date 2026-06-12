<?php

use App\Http\Middleware\TrackSiteVisit;
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
        // Nepřihlášené posíláme na login administrace (jediná chráněná sekce).
        $middleware->redirectGuestsTo(fn () => route('admin.login'));

        // Anonymní počítání návštěvnosti (viz admin sekce Analytika).
        // Návštěvnický token je náhodné UUID — šifrování není potřeba
        // a token tak přežije i případnou rotaci APP_KEY.
        $middleware->encryptCookies(except: ['rr_visitor']);
        $middleware->web(append: [
            TrackSiteVisit::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
