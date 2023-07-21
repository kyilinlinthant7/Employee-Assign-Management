<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

/**
* @author Kyi Lin Lin Thant
* @create 05/07/2023
* @return mixed
*/
class LanguageChangeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // language switch
        $locale = session('locale', 'en');
        App::setLocale($locale);

        return $next($request);
    }
}
