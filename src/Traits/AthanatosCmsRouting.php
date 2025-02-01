<?php

namespace SteJaySulli\AthanatosCms\Traits;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use SteJaySulli\AthanatosCms\Http\Controllers\AthanatosController;
use SteJaySulli\AthanatosCms\Middleware\AthanatosL10nMiddleware;
use SteJaySulli\AthanatosCms\Models\AthanatosArticle;

trait AthanatosCmsRouting
{
    public function normaliseUri(...$uris): string
    {
        $uri = implode('/', $uris);
        $uri = preg_replace('/[?#].*$/', '', $uri);
        $uri = preg_replace('#^'.url()->to('').'#', '', $uri);

        return '/'.implode(
            '/',
            array_map(
                fn ($part) => Str::slug($part),
                array_filter(
                    strpos($uri, '/') !== false ?
                        preg_split('#/#', (string) $uri) :
                        [(string) $uri]
                )
            )
        );
    }

    public function getLanguageSegment(string $url): string
    {
        $url = $this->normaliseUri($url);
        $languageSegmentIndex = count(
            array_filter(
                explode(
                    '/',
                    config('athanatos-cms.base_url', '/')
                )
            )
        );
        $urlParts = array_values(
            array_filter(explode('/', $url))
        );

        return isset($urlParts[$languageSegmentIndex]) ?
            $urlParts[$languageSegmentIndex] :
            '';
    }

    public function languageUri(string $uri): string
    {
        return $this->normaliseUri(
            $this->getLang(),
            $uri
        );
    }

    public function normalUri(string $uri): string
    {
        return preg_replace(
            '#^'.$this->languageUri('').'#i',
            '',
            $this->normaliseUri(
                $uri
            )
        );
    }

    public function getUriFromUrl(string $url): string
    {
        $uri = $this->normaliseUri(
            $url
        );

        $prefix = $this->normaliseUri(
            config('athanatos-cms.base_url', '/'),
            $this->getLang()
        );

        $uri = preg_replace('#^'.$prefix.'#', '', $uri);

        return $uri;
    }

    private function routesForLanguage()
    {
        return function () {
            Route::get('{article}', AthanatosController::class)
                ->where('article', '.*')
                ->name('article');
        };
    }

    public function routes()
    {
        return Route::middleware(config('athanatos-cms.middleware', [AthanatosL10nMiddleware::class]))
            ->prefix(config('athanatos-cms.base_url', '/'))
            ->name('athanatos-cms.')
            ->group(function () {
                foreach ($this->getSupportedLanguages() as $lang) {
                    Route::prefix($lang)
                        ->name("$lang.")
                        ->group($this->routesForLanguage());
                }
                if (config('athanatos-cms.use_languageless_urls', true)) {
                    if (config('athanatos-cms.redirect_to_language', true)) {
                        Route::get('{article}', function (AthanatosArticle $article) {
                            return redirect()->to($article->url);
                        })
                            ->where('article', '.*');
                    } else {
                        $this->routesForLanguage()();
                    }
                }
            });
    }
}
