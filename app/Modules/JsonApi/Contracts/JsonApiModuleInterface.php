<?php

namespace Modules\JsonApi\Contracts;

interface JsonApiModuleInterface
{
    public function init(): void;

    public function schemas(): array;
}