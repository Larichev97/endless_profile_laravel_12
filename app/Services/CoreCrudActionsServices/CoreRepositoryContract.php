<?php

namespace App\Services\CoreCrudActionsServices;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CoreRepositoryContract
{
    /**
     * @param int $id
     * @param bool $useCache
     *
     * @return mixed
     */
    public function getForEditModel(int $id, bool $useCache = true):mixed;

    /**
     * @param bool $useCache
     *
     * @return Collection
     */
    public function getModelCollection(bool $useCache = true):Collection;

    /**
     * @param int|null $perPage
     * @param string $orderBy
     * @param string $orderWay
     *
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate(int|null $perPage, string $orderBy, string $orderWay): LengthAwarePaginator;

    /**
     * @param string $fieldId
     * @param string $fieldName
     * @param bool $useCache
     *
     * @return Collection
     */
    public function getForDropdownList(string $fieldId, string $fieldName, bool $useCache = true): Collection;

    /**
     * @return void
     */
    public function cleanCache():void;
}
