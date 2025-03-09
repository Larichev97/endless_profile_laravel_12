<?php

namespace App\Services\CoreCrudActionsServices;

interface CoreCrudActionsContract
{
    public function processStore(CoreFormFieldsDtoContract $dto): bool;
    public function processUpdate(CoreFormFieldsDtoContract $dto, CoreRepositoryContract $repository): bool;
    public function processDestroy(int $id, CoreRepositoryContract $repository): bool;
}
