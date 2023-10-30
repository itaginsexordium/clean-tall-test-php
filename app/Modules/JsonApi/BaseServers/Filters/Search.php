<?php

namespace Modules\JsonApi\BaseServers\Filters;

use LaravelJsonApi\Eloquent\Contracts\Filter;
use LaravelJsonApi\Contracts\Schema\Schema;
use LaravelJsonApi\Contracts\Schema\ID;
use LaravelJsonApi\Eloquent\Schema as EloquentSchema;
use Illuminate\Database\Eloquent\Model;
use LaravelJsonApi\Eloquent\Filters\Concerns\HasDelimiter;
use RomanStruk\ManticoreScoutEngine\Mysql\Builder;

class Search implements Filter
{
    use HasDelimiter;

    /**
     * @var ID
     */
    private ID $field;

    /**
     * @var string|null
     */
    private ?string $column;

    /**
     * @var string
     */
    private string $key;

    /**
     * @var string
     */
    private ?string $secondParam;
    /**
     * Create a new filter.
     *
     * @param Schema $schema
     * @param string|null $key
     * @return static
     */
    public static function make(Schema $schema, ?string  $secondParam = null,  ?string $key = null): self
    {
        if ($schema instanceof EloquentSchema) {
            return new static(
                $schema->id(),
                $schema->idColumn(),
                $key,
                $secondParam
            );
        }

        return new static(
            $schema->id(),
            $schema->idKeyName(),
            $key,
            $secondParam
        );
    }

    /**
     * WhereIdIn constructor.
     *
     * @param ID $field
     * @param string|null $column
     * @param string|null $key
     */
    private function __construct(ID $field, ?string $column, ?string $key, ?string $secondParam = null)
    {
        $this->field = $field;
        $this->column = $column;
        $this->key = $key ?: 'search';
        $this->secondParam = $secondParam ?: null;
    }

    /**
     * @inheritDoc
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * @inheritDoc
     */
    public function apply($query, $value)
    {
        $filter = request()->filter;


        $whereParams = [];
        $secondParam = $this->secondParam;
        if ($this->delimiter && is_string($filter[$secondParam])) {
            $whereParams = ('' !== $filter[$secondParam]) ? explode($this->delimiter, $filter[$secondParam]) : [];
        }

        $cache = $query->getModel()->search($value, function (Builder $builder) use ($whereParams, $secondParam) {

            if (!empty($secondParam) && !empty($whereParams)) {
                $builder->whereIn($secondParam, $whereParams);
            }

            $builder->take(1000)->setProximitySearchOperator(3);
            return $builder;
        })->get();

        $cacheIds = [];
        foreach ($cache as $cacheItem) {
            $cacheIds[] = $cacheItem->manticore_id;
        }

        return $query->whereIn(
            $this->qualifyColumn($query->getModel()),
            $cacheIds,
        );
    }

    /**
     * @inheritDoc
     */
    public function isSingular(): bool
    {
        return false;
    }

    /**
     * Get the column for the ID.
     *
     * @return string|null
     */
    protected function column(): ?string
    {
        return $this->column;
    }

    /**
     * Get the qualified column for the supplied model.
     *
     * @param Model $model
     * @return string
     */
    protected function qualifyColumn(Model $model): string
    {
        if ($column = $this->column()) {
            return $model->qualifyColumn($column);
        }

        return $model->qualifyColumn(
            $model->getRouteKeyName(),
        );
    }
}
