<?php

namespace SteJaySulli\AthanatosCms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use SteJaySulli\AthanatosCms\Facades\AthanatosCms;
use SteJaySulli\AthanatosCms\I18n\TranslatableCast;

class AthanatosArticle extends Model
{
    protected $fillable = [
        'ulid',
        'uri',
        'title',
        'description',
    ];

    protected $casts = [
        'title' => TranslatableCast::class,
        'description' => TranslatableCast::class,
    ];

    public function getUrlAttribute(): string
    {
        return url()->to($this->language_uri);
    }

    public function getLanguageUriAttribute(): string
    {
        return AthanatosCms::languageUri($this->uri);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->ulid = Str::ulid();
        });

        static::saving(function ($model) {
            // Sluggify and normalise given url
            $model->uri = AthanatosCms::normaliseUri($model->uri);
        });
    }

    public static function fromUrl(string $url): self
    {
        $uri = AthanatosCms::getUriFromUrl($url);

        return self::where('uri', 'like', $uri)->firstOrFail();
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $value = AthanatosCms::normaliseUri($value);
        $value = preg_replace('#^/#', '', $value);
        $value = preg_replace('#/$#', '', $value);

        return $this->where('uri', $value)
            ->orWhere('ulid', $value)
            ->firstOrFail();
    }
}
