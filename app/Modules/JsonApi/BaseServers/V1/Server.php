<?php

namespace Modules\JsonApi\BaseServers\V1;

use Illuminate\Support\Facades\Auth;
use LaravelJsonApi\Core\Server\Server as BaseServer;
use LaravelJsonApi\Core\Support\AppResolver;
use LaravelJsonApi\Laravel\LaravelJsonApi;
use Modules\JsonApi\BaseServers\SchemasCollector;

class Server extends BaseServer
{
    /**
     * The base URI namespace for this server.
     *
     * @var string
     */
    protected string $baseUri = '/api/v1';

    protected SchemasCollector $schemasCollector;

    public function __construct(AppResolver $app, string $name)
    {
        parent::__construct($app, $name);

        $this->schemasCollector = resolve(SchemasCollector::class);
    }

    /**
     * Bootstrap the server when it is handling an HTTP request.
     *
     * @return void
     */
    public function serving(): void
    {
        Auth::shouldUse(config('jsonapi.auth'));

        LaravelJsonApi::withCountQueryParameter('with-count');
    }


    protected function allSchemas(): array
    {
        return $this->schemasCollector->collect();
    }
}
