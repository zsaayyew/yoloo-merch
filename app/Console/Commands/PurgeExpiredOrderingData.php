<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:purge-expired-ordering-data')]
#[Description('Command description')]
class PurgeExpiredOrderingData extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
