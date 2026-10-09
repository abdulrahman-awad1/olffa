<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * بيتحط بعد middleware الـ auth، فالضيف بيتحوّل لصفحة الدخول من هناك
 * (شوف redirectGuestsTo في bootstrap/app.php). هنا بنتعامل مع اليوزر المسجّل بس.
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->isAdmin(), 403);

        return $next($request);
    }
}
