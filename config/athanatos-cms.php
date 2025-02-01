<?php

// config for SteJaySulli/AthanatosCms
return [
    /**
     * The base URL of the pages served by the application; the URIs of
     * articles will be appended to this URL.
     */
    "base_url" => "/",

    /**
     * The base URL of the admin panel; the URIs of admin pages will be
     * appended to this URL.
     */
    "adin_url" => "/admin",

    /**
     * The default locale of the application. If no lanugage is set by other
     * means (explicitly, from the session, from the URL, etc), this will be
     * used
     */
    "default_language" => "en",

    /**
     * The fallback locale of the application. When trying to retrieve a
     * translation for a given locale, if the translation does not exist, this
     * locale will be used instead.
     */
    "fallback_language" => "en",

    /**
     * The supported locales of the application. If a locale is not in this
     * list, some parts of the internationalisation and localisation of
     * Athanatos CMS may not work correctly.
     *
     * The keys of this array are the language codes; these can be simple language
     * codes such as "en", "fr", "de", etc, or they can be more complex locale codes
     * such as "en_GB", "en_US", "en_CA", etc.
     *
     * The values of this array are arrays of aliases for the language. For example,
     * the English language has the aliases "en_GB", "en_US", "en_CA", and "en_AU";
     * if a "en_GB" or "en_US" is used, the language will be set to "en".
     */
    "supported_languages" => [
        "en" => ["en_GB", "en_US", "en_CA", "en_AU"],
        "fr" => ["fr_FR", "fr_CA"],
        "de" => ["de_DE", "de_AT", "de_CH"],
        "es" => ["es_ES", "es_MX", "es_AR"],
        "it" => ["it_IT", "it_CH"],
        "pt" => ["pt_PT", "pt_BR"],
        "ru" => ["ru_RU"],
        "zh" => ["zh_CN", "zh_TW"],
        "ja" => ["ja_JP"],
        "ko" => ["ko_KR"],
    ],

    "language_format" => "xx-YY",

    /**
     * By default the Athanatos CMS will not set the locale of the application.
     * If you want to set the locale of the application you can set this to
     * true, which should align other features' internationalisation with the
     * CMS.
     */
    'set_locale' => false,
];
