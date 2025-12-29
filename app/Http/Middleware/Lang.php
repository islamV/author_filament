<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Lang
{
    public function handle(Request $request, Closure $next)
    {
        $acceptLanguage = $request->header('Accept-Language');
        $locale = $acceptLanguage ? $this->getLocaleFromHeader($acceptLanguage) : 'en';
        app()->setLocale($locale);

        return $next($request);
    }

    protected function getLocaleFromHeader(string $acceptLanguage): string
    {
        $languages = explode(',', $acceptLanguage);

        foreach ($languages as $lang) {
            $locale = strtolower(trim(explode(';', $lang)[0]));
            $locale = strtok($locale, '-');

            return $locale;
        }

        return 'en';
    }
}
