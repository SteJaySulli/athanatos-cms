<?php

namespace SteJaySulli\AthanatosCms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \SteJaySulli\AthanatosCms\AthanatosCms
 */
class AthanatosCms extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \SteJaySulli\AthanatosCms\AthanatosCms::class;
    }
}
