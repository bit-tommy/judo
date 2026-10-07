<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Forge dává každému webu náhradní doménu *.on-forge.com. Aby ji Google
 * neindexoval jako duplicitu, posíláme z ní trvalý redirect (301) na ostrou
 * doménu z APP_URL — cesta i query string zůstávají zachované.
 */
class RedirectForgeDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $target = rtrim((string) config('app.url'), '/');
        $targetHost = (string) parse_url($target, PHP_URL_HOST);

        if (str_ends_with($request->getHost(), '.on-forge.com')
            && $targetHost !== ''
            && ! str_ends_with($targetHost, '.on-forge.com')) {
            return redirect()->to($target.$request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
