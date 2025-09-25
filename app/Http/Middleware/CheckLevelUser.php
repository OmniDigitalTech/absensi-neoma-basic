<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Router;
use RealRashid\SweetAlert\Facades\Alert;

class CheckLevelUser
{
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  array  ...$middlewares
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$middlewares)
    {
        foreach ($middlewares as $middleware) {
            // Check if the middleware matches the role of the authenticated user
            if ($middleware == auth()->user()->is_admin) {

                // Resolve the middleware name from the kernel
                $middlewareClass = $this->router->getMiddleware()[$middleware] ?? null;

                if ($middlewareClass) {
//                    $response = app()->make($middlewareClass)->handle($request, function ($request) use ($next) {
                        return $next($request);
//                    });

//                    if ($response instanceof \Illuminate\Http\Response) {
//                        return $response;
//                    }
                } else {
                    Log::warning("Middleware $middleware not found in kernel.");
                }
            }
        }

        Alert::warning('Ups!', 'Something wrong happened! Try again later!');
        return redirect('/');
    }
}
