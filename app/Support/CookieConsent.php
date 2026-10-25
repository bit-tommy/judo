<?php

namespace App\Support;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * Souhlas s cookies z lišty na webu.
 *
 * Volba návštěvníka se drží v nešifrované cookie `rr_cookie_consent`
 * (`all` = i anonymní analytika, `necessary` = jen nezbytné cookies).
 * Dokud cookie chybí nebo má neznámou hodnotu, lišta se zobrazuje
 * a návštěvnost se nepočítá (viz TrackSiteVisit).
 */
final class CookieConsent
{
    public const COOKIE = 'rr_cookie_consent';

    public const ALL = 'all';

    public const NECESSARY = 'necessary';

    /** Platnost volby – rok, stejně jako návštěvnický token. */
    public const MINUTES = 60 * 24 * 365;

    /**
     * Volba z requestu, nebo null, když návštěvník ještě nerozhodl.
     *
     * @return self::ALL|self::NECESSARY|null
     */
    public static function fromRequest(Request $request): ?string
    {
        $value = $request->cookie(self::COOKIE);

        return in_array($value, [self::ALL, self::NECESSARY], true) ? $value : null;
    }

    public static function allowsAnalytics(Request $request): bool
    {
        return self::fromRequest($request) === self::ALL;
    }

    /** Cookie s danou volbou připravená k zařazení do odpovědi (Cookie::queue). */
    public static function make(string $choice): Cookie
    {
        return cookie(self::COOKIE, $choice, self::MINUTES);
    }
}
