<?php

namespace SteJaySulli\AthanatosCms\Models;

use Illuminate\Database\Eloquent\Model;
use SteJaySulli\AthanatosCms\I18n\TranslatableCast;
use Illuminate\Support\Str;

class AthanatosArticle extends Model
{
    protected $fillable = [
        'ulid',
        'slug',
        'title',
        'description',
    ];

    protected $casts = [
        'title' => TranslatableCast::class,
        'description' => TranslatableCast::class,
    ];
}
