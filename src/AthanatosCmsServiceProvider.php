<?php

namespace SteJaySulli\AthanatosCms;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use SteJaySulli\AthanatosCms\Commands\AthanatosCmsCommand;

class AthanatosCmsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('athanatos-cms')
            ->hasConfigFile()
            ->hasViews()
            // ->hasMigration('0010_create_athanatos_articles_table')
            ->hasMigrations([
                '0006_create_athanatos_audits_table',
                '0010_create_athanatos_articles_table',
            ])
            ->hasCommand(AthanatosCmsCommand::class);
    }
}
