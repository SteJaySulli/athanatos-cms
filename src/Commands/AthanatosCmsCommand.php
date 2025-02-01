<?php

namespace SteJaySulli\AthanatosCms\Commands;

use Illuminate\Console\Command;

class AthanatosCmsCommand extends Command
{
    public $signature = 'athanatos-cms';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
