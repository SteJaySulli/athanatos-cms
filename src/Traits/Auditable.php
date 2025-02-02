<?php

namespace SteJaySulli\AthanatosCms\Traits;

use Illuminate\Database\Eloquent\Model;
use SteJaySulli\AthanatosCms\Models\AthanatosAudit;

trait Auditable
{
    public function getAuditable(): array
    {
        if (property_exists($this, 'auditable')) {
            return $this->auditable;
        }
        return $this->getFillable();
    }

    public function audits()
    {
        return $this->morphMany(AthanatosAudit::class, 'auditable');
    }

    public static function audit(Model $model, string $event, bool $force = true): int
    {
        if ($force) {
            $fields = collect($model->getAuditable())->mapWithKeys(fn($field) => [$field => $model->{$field}]);
        } else {
            $fields = collect($model->getDirty())->filter(fn($value, $key) => in_array($key, $model->getAuditable()));
        }

        $version = ($model->audits()->max('version') ?? 0) + 1;
        foreach ($fields as $field => $new) {
            if (!in_array($field, $model->getAuditable())) {
                continue;
            }
            $model->audits()->create([
                "field" => $field,
                "event" => $event,
                "old" => $model->getOriginal($field),
                "new" => $new,
                "comment" => null, // TODO: Implement commenting
                "version" => $version,
            ]);
        }
        return $version;
    }

    public function getVersion(int $version): self
    {
        $audits = $this->audits()
            ->where('version', '>', $version)
            ->orderBy('version', 'desc')
            ->get();
        foreach ($audits as $audit) {
            $this->{$audit->field} = $audit->old;
        }
        $this->version = $version;
        return $this;
    }

    public static function bootAuditable()
    {
        static::updated(function ($model) {
            $model->version = self::audit($model, 'update', false);
        });
        static::created(function ($model) {
            $model->version = self::audit($model, 'create', true);
            $model->saveQuietly();
        });
        static::deleting(function ($model) {
            $model->version = self::audit($model, 'delete', true);
        });
        static::restored(function ($model) {
            $model->version = self::audit($model, 'restore', true);
            $model->saveQuietly();
        });
        static::forceDeleted(function ($model) {
            $model->version = self::audit($model, 'forceDelete', true);
            $model->saveQuietly();
        });
    }
}
