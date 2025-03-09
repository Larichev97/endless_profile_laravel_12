<?php

namespace App\Services\CoreCrudActionsServices;

use Illuminate\Database\Eloquent\Model;

interface CoreCrudActionsApiContract
{
    /**
     * @param CoreFormFieldsDtoContract $dto
     *
     * @return Model|false
     */
    public function processStore(CoreFormFieldsDtoContract $dto): Model|false;

    /**
     * @param CoreFormFieldsDtoContract $dto
     * @param CoreRepositoryContract $repository
     *
     * @return Model|false
     */
    public function processUpdate(CoreFormFieldsDtoContract $dto, CoreRepositoryContract $repository): Model|false;

    /**
     * @param int $id
     * @param CoreRepositoryContract $repository
     * @return bool
     */
    public function processDestroy(int $id, CoreRepositoryContract $repository): bool;
}
