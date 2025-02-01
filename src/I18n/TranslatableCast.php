<?php

namespace SteJaySulli\AthanatosCms\I18n;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use SteJaySulli\AthanatosCms\Facades\AthanatosCms;
use ValueError;

class TranslatableCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): Translatable
    {
        return Translatable::make(json_decode($value, true));
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        if ($value instanceof Translatable) {
            $newValue = $value->toArray();
        } elseif (is_array($value)) {
            $newValue = $value;
        } elseif (is_string($value)) {
            $newValue = array_merge(
                json_decode($model->getAttributes()[$key] ?? '{}', true),
                [AthanatosCms::getLang() => $value]
            );
        } else {
            throw new ValueError('The value must be an instance of Translatable, Array or String, not '.gettype($value));
        }

        return json_encode($newValue);
    }
}
