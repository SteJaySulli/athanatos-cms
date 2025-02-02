<?php

namespace SteJaySulli\AthanatosCms\Models;

use Illuminate\Database\Eloquent\Model;

class AthanatosAudit extends Model
{
    protected $fillable = [
        'user_id',
        'auditable_id',
        'auditable_type',
        'field',
        'field_is_translatable',
        'event',
        'old',
        'new',
        'version',
        'comment',
    ];

    public function getCasts()
    {
        return [
            'old' => 'json',
            'new' => 'json',
        ];
    }

    public function auditable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(config('athanatos-cms.user_model'));
    }
}
