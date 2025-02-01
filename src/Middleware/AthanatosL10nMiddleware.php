<?php

namespace SteJaySulli\AthanatosCms\Middleware;

use SteJaySulli\AthanatosCms\Facades\AthanatosCms;
use SteJaySulli\AthanatosCms\I18n\Translatable;

class AthanatosL10nMiddleware
{
    public function handle($request, $next)
    {
        // Set the language of the application from the URL,
        // the Accept-Language, the cookie, or the session
        $lang = Translatable::resolveLanguage(
            [
                AthanatosCms::getLanguageSegment($request->path()),
                $request->header('Accept-Language'),
                $request->cookie('athanatos_cms_lang'),
                AthanatosCms::getSessionLang(),
            ]
        );

        if ($lang) {
            if (config('athanatos-cms.persist_language_in_session', true)) {
                AthanatosCms::setSessionLang($lang);
            }
            AthanatosCms::setLang($lang);
        }

        return $next($request);
    }
}
