<?php

namespace Modules\JsonApi\Actions;

use Nwidart\Modules\Laravel\LaravelFileRepository;

class CollectSchemasAction
{
    public function __construct(
        private LaravelFileRepository $module,
    ) {
    }

    public function execute(): array
    {
        $modules = $this->module->allEnabled();
        $servers = config('jsonapi.servers');

        return $this->collectSchemas($modules, $servers);
    }

    private function collectSchemas(array $modules, array $servers): array
    {
        $schemas = [];
        foreach ($modules as $module => $data) {
            foreach ($servers as $server => $namespace) {
                $server = ucfirst($server);
                $moduleClass = "Modules\\{$module}\JsonApi\\{$server}\JsonApiModule";

                if (!class_exists($moduleClass)) {
                    continue;
                }

                $schemas = [
                    ...$schemas,
                    ...app($moduleClass)->schemas()
                ];
            }
        }

        return $schemas;
    }
}
