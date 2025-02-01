<?php

namespace SteJaySulli\AthanatosCms\Middleware;

use SteJaySulli\AthanatosCms\Facades\AthanatosCms;

class AthanatosL10nMiddleware
{
    public function handle($request, $next)
    {
        // Set the language of the application from the URL,
        // the Accept-Language, the cookie, or the session
        foreach (
            [
                AthanatosCms::getLaunguageSegment($request->path()),
                $request->header('Accept-Language'),
                $request->cookie('athanatos_cms_lang'),
                AthanatosCms::getSessionLang(),
            ] as $lang
        ) {
            if (in_array($lang, config('athanatos-cms.supported_languages', []))) {
                AthanatosCms::setLang($lang);
                break;
            }
        }

        return $next($request);
    }
}
