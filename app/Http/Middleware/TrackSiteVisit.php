<?php

namespace App\Http\Middleware;

use App\Support\BotUserAgent;
use App\Support\CookieConsent;
use App\Support\SiteVisitRecorder;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * First-party počítání návštěvnosti (vzor z projektu peanut): jeden záznam
 * na návštěvníka a den, viz SiteVisitRecorder. Analytická cookie není
 * nezbytná, takže se počítá jen se souhlasem z cookie lišty (CookieConsent).
 */
class TrackSiteVisit
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldRecord($request, $response)) {
            SiteVisitRecorder::record($request);
        }

        return $response;
    }

    private function shouldRecord(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET')) {
            return false;
        }

        // Administraci ani Livewire endpointy nepočítáme.
        if ($request->is('admin', 'admin/*', 'livewire/*')) {
            return false;
        }

        if (BotUserAgent::matches($request->userAgent())) {
            return false;
        }

        // Bez souhlasu z cookie lišty žádný token ani záznam.
        if (! CookieConsent::allowsAnalytics($request)) {
            return false;
        }

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        return str_contains((string) $response->headers->get('Content-Type'), 'text/html');
    }
}
