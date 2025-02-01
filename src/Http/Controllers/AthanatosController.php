<?php

namespace SteJaySulli\AthanatosCms\Http\Controllers;

use Illuminate\Routing\Controller;
use SteJaySulli\AthanatosCms\Facades\AthanatosCms;
use SteJaySulli\AthanatosCms\Models\AthanatosArticle;

class AthanatosController extends Controller
{
    public function __invoke(AthanatosArticle $article)
    {
        return response()->json([
            "article" => $article,
            "lang" => AthanatosCms::getLang(),
            "session_lang" => AthanatosCms::getSessionLang(),
        ]);
    }
}
