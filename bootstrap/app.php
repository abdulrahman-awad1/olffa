<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\PreventBackHistory;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'no-store' => PreventBackHistory::class,
        ]);

        // الضيف اللي يفتح صفحة محمية: صفحات الأدمن -> دخول الأدمن، غيرها -> دخول اليوزر.
        $middleware->redirectGuestsTo(
            fn (Request $request) => $request->is('admin', 'admin/*')
                ? route('admin.login')
                : route('login')
        );

        // اللي مسجّل دخول وفتح صفحة للضيوف بس (login / register): نوديه لصفحته.
        $middleware->redirectUsersTo(
            fn (Request $request) => $request->user()?->isAdmin()
                ? route('admin.dashboard')
                : route('dashboard')
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
