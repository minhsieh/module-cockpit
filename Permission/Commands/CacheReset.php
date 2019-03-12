<?php

namespace Modules\Permission\Commands;

use Illuminate\Console\Command;
use Modules\Permission\PermissionRegistrar;

class CacheReset extends Command
{
    protected $signature = 'permission:cache-reset';

    protected $description = 'Reset the permission cache';

    public function handle()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->info('Permission cache flushed.');
    }
}
