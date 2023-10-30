<?php

namespace Modules\JsonApi\BaseServers;

use Modules\JsonApi\Actions\CollectSchemasAction;
use Illuminate\Support\Facades\Cache;


class SchemasCollector
{

    private string $key;

    public function __construct(
        private CollectSchemasAction $collectSchemasAction,
    ) {
        $this->key = config('jsonapi.cache.schemas_key');
    }

    public function collect(): array
    {
        $schemas = $this->collectCached();

        if (empty($schemas)) {
            $schemas = $this->collectSchemasAction->execute();
        }

        return $schemas;
    }

    public function cacheExists(): bool
    {
        return Cache::has($this->key);
    }

    public function cache(): bool
    {
        return Cache::forever(
            $this->key,
            $this->collectSchemasAction->execute(),
        );
    }

    public function clearCache(): bool
    {
        return Cache::forget($this->key);
    }

    private function collectCached()
    {
        $schemas = [];

        try {
            $schemas = Cache::get($this->key, []);
        } catch (\Exception $e) {
            logger()->error($e->getMessage());
        }

        return $schemas;
    }
}
