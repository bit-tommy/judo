<?php

namespace App\Support;

/**
 * Jednoduchá detekce botů podle User-Agent hlavičky (substring match).
 * Vzor převzatý z projektu peanut — pro účely návštěvnosti bohatě stačí.
 */
class BotUserAgent
{
    public static function matches(?string $userAgent): bool
    {
        if ($userAgent === null || trim($userAgent) === '') {
            return true; // prázdný UA bereme jako bota
        }

        $normalized = strtolower($userAgent);

        foreach (self::needles() as $needle) {
            if (str_contains($normalized, $needle)) {
                return true;
            }
        }

        return false;
    }

    /** @return array<int, string> */
    private static function needles(): array
    {
        return [
            'ahrefsbot',
            'applebot',
            'baiduspider',
            'bingbot',
            'bot',
            'bytespider',
            'ccbot',
            'claudebot',
            'crawler',
            'curl',
            'dotbot',
            'duckduckbot',
            'facebookexternalhit',
            'gptbot',
            'headlesschrome',
            'httpclient',
            'mj12bot',
            'petalbot',
            'python-requests',
            'scrapy',
            'semrushbot',
            'slurp',
            'spider',
            'symfony', // výchozí UA testovacího HTTP klienta
            'wget',
            'yandex',
        ];
    }
}
