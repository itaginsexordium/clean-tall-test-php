<?php

namespace Modules\JsonApi\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\AttachRelationship;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchMany;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchOne;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Store;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Update;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Destroy;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelated;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\DetachRelationship;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelationship;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\UpdateRelationship;

class JsonApiController extends Controller
{
    /**
     * JSON:API Actions
     */
    use FetchMany;
    use FetchOne;
    use Store;
    use Update;
    use Destroy;
    use FetchRelated;
    use FetchRelationship;
    use UpdateRelationship;
    use AttachRelationship;
    use DetachRelationship;

    /**
     * Laravel Controller Traits
     */
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
}
