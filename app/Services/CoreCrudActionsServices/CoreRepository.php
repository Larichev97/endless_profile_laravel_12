<?php

namespace App\Services\CoreCrudActionsServices;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 *  Entity Repository:
 *   · Can output data sets;
 *   · Cannot create/modify entities;
 */
abstract class CoreRepository implements CoreRepositoryContract
{
    const INPUT_TYPE_TEXT = 'text';
    const INPUT_TYPE_TEXTAREA = 'textarea';
    const INPUT_TYPE_SELECT = 'select';
    const INPUT_TYPE_EMAIL = 'email';
    const INPUT_TYPE_DATE = 'date';
    const INPUT_TYPE_DATETIME = 'datetime';

    /**
     * @var int Default cache lifetime: 1 month
     */
    protected int $cacheLife = 30 * 24 * 60 * 60; // 30 days * 24 hours * 60 minutes * 60 seconds;

    /**
     * @var Model
     */
    protected Model $model;

    public function __construct()
    {
        $this->model = app(abstract: $this->getModelClass());
    }

    /**
     *  In each inheritance class, the required model will be invoked
     *
     * @return string
     */
    abstract protected function getModelClass(): string;

    /**
     *  Clones an object of a particular model
     *
     * @return Application|Model|\Illuminate\Foundation\Application|mixed
     */
    protected function startConditions(): mixed
    {
        return clone $this->model;
    }

    /**
     *  Retrieves one record for a specific {$id} and returns a model with attributes
     *
     * @param int $id Entity's Primary Key
     * @param bool $useCache
     *
     * @return mixed
     */
    public function getForEditModel(int $id, bool $useCache = true): mixed
    {
        $model = $this->startConditions();

        if ($useCache) {
            $result = Cache::remember($this->getModelClass().'-getForEditModel-'.$id, $this->cacheLife, function () use ($model, $id) {
                return $model->query()->find(id: $id);
            });
        } else {
            $result = $model->query()->find(id: $id);
        }

        return $result;
    }

    /**
     *  Gets all records from the model and returns the collection
     *
     * @param bool $useCache
     *
     * @return Collection
     */
    public function getModelCollection(bool $useCache = true): Collection
    {
        $model = $this->startConditions();

        if ($useCache) {
            $result = Cache::remember($this->getModelClass().'-getModelCollection', $this->cacheLife, function () use ($model) {
                return $model->all();
            });
        } else {
            $result = $model->all();
        }

        return $result;
    }

    /**
     *  Gets all model records with available fields from the {$fillable} array for output by the paginator
     *
     * @param int|null $perPage
     * @param string $orderBy
     * @param string $orderWay
     *
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate(int|null $perPage, string $orderBy = 'id', string $orderWay = 'desc'): LengthAwarePaginator
    {
        $model = $this->startConditions();

        $fieldsArray = $model->getFillable();

        /** @var Builder $query */
        $query = $model->query();

        $query->select(columns: $fieldsArray);

        $orderBy = Str::lower($orderBy);
        $orderWay = Str::lower($orderWay);

        if ($orderBy !== 'id' && !in_array($orderBy, $fieldsArray)) {
            $orderBy = 'id';
        }

        if (!in_array($orderWay, ['asc', 'desc'])) {
            $orderWay = 'desc';
        }

        $query->orderBy(column: $orderBy, direction: $orderWay);

        return $query->paginate(perPage: $perPage, columns: $fieldsArray);
    }

    /**
     *  Generates model collections only with fields {$field_id} (for value=‘...’) and {$field_name} (for content inside <option>‘...’</option>)
     *
     * @param string $fieldId
     * @param string $fieldName
     * @param bool $useCache
     *
     * @return Collection
     */
    public function getForDropdownList(string $fieldId, string $fieldName, bool $useCache = true): Collection
    {
        $model = $this->startConditions();

        $columns = implode(', ', [$fieldId, $fieldName]);

        if ($useCache) {
            $result = Cache::remember($this->getModelClass().'-getForDropdownList', $this->cacheLife,
                function () use($model, $columns) {
                    return $model->query()->selectRaw(expression: $columns)->orderBy(column: 'id', direction: 'asc')->toBase()->get();
                }
            );
        } else {
            $result = $model->query()->selectRaw(expression: $columns)->orderBy(column: 'id', direction: 'asc')->toBase()->get();
        }

        return $result;
    }

    /**
     *  List of fields with names to be displayed in the list (route: "{group_name}.index")
     *
     * @return array|string[]
     */
    public function getDisplayedFieldsOnIndexPage(): array
    {
        return [
            'id'            => ['field' => 'id', 'field_input_type' => self::INPUT_TYPE_TEXT, 'field_title' => '#'],
            'created_at'    => ['field' => 'created_at', 'field_input_type' => self::INPUT_TYPE_DATE, 'field_title' => 'Дата создания'],
            'updated_at'    => ['field' => 'updated_at', 'field_input_type' => self::INPUT_TYPE_DATE, 'field_title' => 'Дата редактирования'],
        ];
    }

    /**
     *  Clears the cache for a specific model
     *
     * @return void
     */
    public function cleanCache(): void
    {
        Cache::forget($this->getModelClass().'-getModelCollection');
        Cache::forget($this->getModelClass().'-getForDropdownList');
    }
}
