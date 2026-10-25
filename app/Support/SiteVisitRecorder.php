<?php

namespace App\Support;

use App\Models\SiteVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

/**
 * Anonymní počítání návštěvnosti: jeden záznam na návštěvníka a den.
 *
 * Návštěvníka identifikuje náhodný token v cookie `rr_visitor`, do databáze
 * jde jen jeho sha256 hash — žádná IP, žádný user-agent. Volá ho middleware
 * TrackSiteVisit (každé načtení stránky se souhlasem; filtr botů a podmínky
 * requestu řeší on) a cookie lišta v okamžiku udělení souhlasu, aby se
 * započítala i právě probíhající návštěva, kterou middleware už nestihl.
 */
final class SiteVisitRecorder
{
    public const COOKIE = 'rr_visitor';

    /** Platnost návštěvnického tokenu – rok. */
    public const MINUTES = 60 * 24 * 365;

    public static function record(Request $request): void
    {
        $token = $request->cookie(self::COOKIE);

        if (! is_string($token) || $token === '') {
            $token = (string) Str::uuid();
            Cookie::queue(cookie(self::COOKIE, $token, self::MINUTES));
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

    /** Zahodí návštěvnický token z prohlížeče (odvolání souhlasu s analytikou). */
    public static function forget(): void
    {
        Cookie::queue(Cookie::forget(self::COOKIE));
    }
}
