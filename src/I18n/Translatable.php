<?php

namespace SteJaySulli\AthanatosCms\I18n;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use SteJaySulli\AthanatosCms\Facades\AthanatosCms;
use Stringable;

class Translatable implements Arrayable, ArrayAccess, JsonSerializable, Stringable
{
    public static function make(mixed $data): self
    {
        return new self((array) $data);
    }

    /**
     * Check if a language code is valid and returns its type. The type can be:
     * 0: invalid
     * 1: simple language code
     * 2: locale code
     */
    public static function checkCodeValidity(string $code): int
    {
        if (preg_match('/^[a-z]{2}$/i', $code)) {
            return 1;
        }
        if (preg_match('/^[a-z]{2}[_-][a-z]{2}$/i', $code)) {
            return 2;
        }

        return 0;
    }

    /**
     * Reformats a language or locale code according to the given format. This will
     * reformat the case of identifiers, truncate a locale to a language code and
     * use either a hyphen or underscore as given in the format parameter.
     *
     * Examples:
     *
     * format("en", "xx_YY") => "en"
     * format("en_GB", "xx_YY") => "en_GB"
     * format("en_GB", "xx-YY") => "en-GB"
     * format("en_GB", "xx") => "en"
     */
    public static function format(string $lang, string $format = 'xx_YY'): ?string
    {
        if (self::checkCodeValidity($lang) === 0) {
            return null;
        }

        for ($i = 0; $i < strlen($format); $i++) {
            if (strlen($lang) <= $i) {
                break;
            }
            if ($i == 2) {
                $lang[$i] = $format[$i];
            } elseif (substr($format, $i, 1) === strtolower(substr($format, $i, 1))) {
                $lang[$i] = strtolower($lang[$i]);
            } else {
                $lang[$i] = strtoupper($lang[$i]);
            }
        }
        $format = substr($lang, 0, strlen($format));
        if (self::checkCodeValidity($format) === 0) {
            return null;
        }

        return $format;
    }

    /**
     * Resolves a language code to a supported language code. This will return the
     * first supported language code that matches the given code or one of its
     * aliases. If no supported language code is found, this will return null.
     * The supported language codes are defined in the configuration file.
     */
    public static function resolveLanguage(array|string|null $resolveLanguage): ?string
    {
        if (empty($resolveLanguage)) {
            return null;
        }

        $format = config('athanatos-cms.language_format', 'xx-YY');

        if (is_array($resolveLanguage)) {
            foreach ($resolveLanguage as $code) {
                if ($lang = self::resolveLanguage($code)) {
                    return $lang;
                }
            }

            return null;
        }

        $resolveLanguage = self::format($resolveLanguage, $format);
        $supportedLanguages = collect(config('athanatos-cms.supported_languages', []))
            ->mapWithKeys(function ($aliases, $code) use ($format) {
                return [self::format($code, $format) => array_map(
                    fn ($alias) => self::format($alias, $format),
                    $aliases
                )];
            });

        foreach ($supportedLanguages as $supportedCode => $aliases) {
            if ($resolveLanguage === $supportedCode || in_array($resolveLanguage, $aliases)) {
                return $supportedCode;
            }
        }

        return null;
    }

    /**
     * Class Constructor - see the make method for a more convenient way to create
     */
    public function __construct(
        private array $data = [],
        private array $parameters = []
    ) {
        //
    }

    /**
     * Get the translation string for the given language (if it exists)
     *
     * @param  string  $lang
     */
    public function getTranslationString(string|array $lang): ?string
    {
        if (! $lang = self::resolveLanguage($lang)) {
            return null;
        }
        if (! empty($this->data[$lang])) {
            return $this->data[$lang];
        }
    }

    public function setTranslationString(string|array $lang, string $translation): self
    {
        if (! $lang = self::resolveLanguage($lang)) {
            return $this;
        }
        $this->data[$lang] = $translation;

        return $this;
    }

    /**
     * Set the parameters for a translation
     *
     * @param  string  $lang
     * @param  string  $translation
     */
    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Get the parameters for a translation
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Transliterate a string using the given parameters, or the parameters set
     * using the setParameters method
     */
    public function transliterateString(string $string, ?array $parameters = null): string
    {
        if (empty($parameters)) {
            $parameters = $this->parameters;
        }
        if (empty($parameters)) {
            return $string;
        }

        return strtr(
            $string,
            collect($parameters)
                ->mapWithKeys(
                    fn ($value, $key) => ["{{$key}}" => $value]
                )
                ->all()
        );
    }

    /**
     * Translate the data into the given language, using the given parameters
     * or the parameters set using the setParameters method.
     *
     * @param  string  $lang
     */
    public function translateInto(string|array $lang, ?array $parameters = null): ?string
    {
        if (! $lang = self::resolveLanguage($lang)) {
            return null;
        }

        if (! empty($this->data[$lang])) {
            if (empty($parameters)) {
                return $this->data[$lang];
            }

            return $this->transliterateString($this->data[$lang], $parameters);
        }

        return null;
    }

    public function translate(?array $parameters = null): string
    {
        $translation = $this->translateInto(
            AthanatosCms::getLang(),
            $parameters
        );
        if (empty($translation) && ! empty($fallback = config('athanatos-cms.fallback_language', ''))) {
            $translation = $this->translateInto(
                $fallback,
                $parameters
            );
        }

        return $translation ?? '';
    }

    public function setTranslationFor(string|array $lang, string $translation): self
    {
        $lang = self::resolveLanguage($lang);
        if (empty($lang)) {
            return $this;
        }
        $this->data[$lang] = $translation;

        return $this;
    }

    public function setTranslation(string|array $lang, string $translation): self
    {
        $this->setTranslationString($lang, $translation);

        return $this;
    }

    // Standard and magic functions...

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->translate();
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toJson(): string
    {
        return json_encode($this);
    }

    public function offsetExists($offset): bool
    {
        $offset = self::resolveLanguage($offset);

        return isset($this->data[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        $offset = self::resolveLanguage($offset);

        return $this->data[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        $offset = self::resolveLanguage($offset);
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        $offset = self::resolveLanguage($offset);
        unset($this->data[$offset]);
    }
}
