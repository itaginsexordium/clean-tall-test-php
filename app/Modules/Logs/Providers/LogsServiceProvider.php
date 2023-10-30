<?php

namespace Modules\Logs\Providers;

use Illuminate\Support\ServiceProvider;

class LogsServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Logs';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'logs';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        // Logging
        $this->publishes([
            module_path($this->moduleName, 'Config/logging.php') => config_path('logging.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/logging.php'),
            'logging'
        );
    }
}