<?php

namespace SteJaySulli\AthanatosCms\Traits;

use Illuminate\Support\Facades\Session;

trait AthanatosCmsI18n
{
    static private string $lang = "";

    public function getLang()
    {
        if (empty(self::$lang)) {
            self::$lang = $this->getSessionLang();
        }
        return self::$lang;
    }

    public function getSessionLang(): string
    {
        if (Session::has('athanatos_cms_lang')) {
            return Session::get('athanatos_cms_lang');
        }
        return config('athanatos-cms.default_language', "");
    }

    public function setLang(?string $lang = null): self
    {
        self::$lang = $lang ?? "";
        if (config('athanatos-cms.set_locale', false)) {
            app()->setLocale(self::$lang);
        }
        return $this;
    }

    public function setSessionLang(?string $lang = null): self
    {
        $this->setLang($lang);
        Session::put('athanatos_cms_lang', $this->getLang());
        return $this;
    }

    public function translate(array|object $data, string $fallback = ""): string
    {
        $data = (array)$data;
        $lang = $this->getLang();
        $fallbackLang = config('athanatos-cms.fallback_language', "");

        return $data[$lang] ?? $data[$fallbackLang] ?? $fallback;
    }

    public function getLanguageSegment(string $url): string
    {
        $languageSegmentIndex = count(
            array_filter(
                explode(
                    "/",
                    config('athanatos-cms.base_url', "/")
                )
            )
        );

        $urlParts = array_filter(explode("/", $url));
        return isset($urlParts[$languageSegmentIndex]) ?
            $urlParts[$languageSegmentIndex] :
            "";
    }

    public function languageUri(string $uri): string
    {
        $parts = array_merge(
            explode("/", config('athanatos-cms.base_url', "/")),
            [$this->getLang()],
            explode("/", $uri)
        );
        return "/" . implode("/", array_filter($parts));
    }
}
