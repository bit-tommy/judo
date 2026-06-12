<?php

namespace App\Http\Middleware;

use App\Models\SiteVisit;
use App\Support\BotUserAgent;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * First-party počítání návštěvnosti (vzor z projektu peanut): jeden záznam
 * na návštěvníka a den. Návštěvníka identifikuje anonymní cookie token,
 * do databáze se ukládá jen jeho sha256 hash — žádná IP, žádný user-agent.
 */
class TrackSiteVisit
{
    private const COOKIE = 'rr_visitor';

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldRecord($request, $response)) {
            $this->record($request);
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

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        return str_contains((string) $response->headers->get('Content-Type'), 'text/html');
    }

    private function record(Request $request): void
    {
        $token = $request->cookie(self::COOKIE);

        if (! is_string($token) || $token === '') {
            $token = (string) Str::uuid();
            Cookie::queue(cookie(self::COOKIE, $token, 60 * 24 * 365));
        }

        $hash = hash('sha256', $token);
        $date = now()->toDateString();

        // Cache guard drží opakované návštěvy (běžný případ) mimo databázi.
        if (! Cache::add("site_visit:{$hash}:{$date}", true, now()->endOfDay())) {
            return;
        }

        SiteVisit::insertOrIgnore([
            'visitor_hash' => $hash,
            'visit_date' => $date,
            'created_at' => now(),
        ]);
    }
}
