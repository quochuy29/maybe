<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Stancl\Tenancy\Jobs\MigrateDatabase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class MigrateTenancyModuleCustom extends MigrateDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var TenantWithDatabase */
    protected $tenant;
    /**
     * Create a new job instance.
     */
    public function __construct(TenantWithDatabase $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->loadPaths();
        parent::handle();
    }

    public function loadPaths()
    {
        $paths   = [];
        $modules = array_filter(
            json_decode(
                file_get_contents(base_path('modules_statuses.json')),
                true
            )
        );

        foreach (array_keys($modules) as $module) {
            $paths[] = base_path("Modules/$module/Database/Migrations");
        }

        $config = 'tenancy.migration_parameters.--path';
        $paths  = array_unique(array_merge(config($config), $paths));

        return config([$config => $paths]);
    }
}
