<?php

namespace App\Services\ProjectSetting\Repositories;

use App\Services\ProjectSetting\Models\ProjectSetting as Model;
use App\Services\CoreCrudActionsServices\CoreRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

final class ProjectSettingRepository extends CoreRepository
{
    /**
     * App\Services\ProjectSetting\Models\ProjectSetting
     *
     * @return string
     */
    protected function getModelClass(): string
    {
        return Model::class;
    }

    /**
     *  [Override]
     *
     * @param int|null $perPage
     * @param string $orderBy
     * @param string $orderWay
     *
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate(int|null $perPage, string $orderBy = 'id', string $orderWay = 'desc'): LengthAwarePaginator
    {
        return parent::getAllWithPaginate($perPage, $orderBy, $orderWay);
    }

    /**
     *  [Override]
     *
     * @param string $fieldId
     * @param string $fieldName
     * @param bool $useCache
     *
     * @return Collection
     */
    public function getForDropdownList(string $fieldId, string $fieldName, bool $useCache = true): Collection
    {
        return parent::getForDropdownList($fieldId, $fieldName, $useCache);
    }

    /**
     *  Gets the Value {value} of the setting's Name {name}
     *
     * @param string $name
     * @param bool $useCache
     *
     * @return string
     */
    public function getSettingValueByName(string $name, bool $useCache = true): string
    {
        $model = $this->startConditions();

        if ($useCache) {
            $result = Cache::remember('project-setting-name-'.Str::lower($name), $this->cacheLife, function () use ($model, $name) {
                return (string) $model->query()->where('name', '=', $name)->value('value');
            });
        } else {
            $result = (string) $model->query()->where('name', '=', $name)->value('value');
        }

        return $result;
    }
}
