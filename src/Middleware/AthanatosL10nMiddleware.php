<?php

namespace SteJaySulli\AthanatosCms\Middleware;

use SteJaySulli\AthanatosCms\Facades\AthanatosCms;
use SteJaySulli\AthanatosCms\I18n\Translatable;

class AthanatosL10nMiddleware
{
    public function handle($request, $next)
    {
        $resolvables = array_merge(
            [AthanatosCms::getLanguageSegment($request->path())],
            [AthanatosCms::getSessionLang()],
            explode(',', $request->header('Accept-Language')),
        );


        // Set the language of the application from the URL,
        // the Accept-Language, the cookie, or the session
        $lang = Translatable::resolveLanguage($resolvables);

        logger("Language should now be set to $lang", $resolvables);

        if ($lang) {
            if (config('athanatos-cms.persist_language_in_session', true)) {
                AthanatosCms::setSessionLang($lang);
            }
            AthanatosCms::setLang($lang);
            logger("Language should be set to $lang", $resolvables);
        } else {
            logger("Language could not be set");
        }



        return $next($request);
    }
}
