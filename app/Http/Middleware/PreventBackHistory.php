<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * بيمنع المتصفح من تخزين الصفحات المحمية (الأدمن، الداشبورد، صفحة الشكر).
 * من غيره، زرار "رجوع" بعد تسجيل الخروج بيعرض نسخة الصفحة المحفوظة
 * (ببيانات حساسة) من غير ما يسأل السيرفر.
 */
class PreventBackHistory
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');

        return $response;
    }
}
