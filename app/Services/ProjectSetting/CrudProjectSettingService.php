<?php

namespace App\Services\ProjectSetting;

use App\Services\CoreCrudActionsServices\CoreCrudActionsContract;
use App\Services\CoreCrudActionsServices\CoreFormFieldsDtoContract;
use App\Services\CoreCrudActionsServices\CoreRepositoryContract;
use App\Services\ProjectSetting\DTO\ProjectSettingStoreDTO;
use App\Services\ProjectSetting\DTO\ProjectSettingUpdateDTO;
use App\Services\ProjectSetting\Models\ProjectSetting;

final readonly class CrudProjectSettingService implements CoreCrudActionsContract
{
    /**
     *  Creating a record of the Project Setting
     *
     * @param CoreFormFieldsDtoContract $dto
     *
     * @return bool
     */
    public function processStore(CoreFormFieldsDtoContract $dto): bool
    {
        /** @var ProjectSettingStoreDTO $dto */

        $settingModel = ProjectSetting::query()->create(attributes: $dto->getFormFieldsArray());

        return (bool) $settingModel;
    }

    /**
     *  Updating a record of the Project Setting
     *
     * @param CoreFormFieldsDtoContract $dto
     * @param CoreRepositoryContract $repository
     *
     * @return bool
     */
    public function processUpdate(CoreFormFieldsDtoContract $dto, CoreRepositoryContract $repository): bool
    {
        /** @var ProjectSettingUpdateDTO $dto */

        $settingModel = $repository->getForEditModel(id: (int) $dto->id_setting, useCache: true);

        if (empty($settingModel)) {
            return false;
        }

        /** @var ProjectSetting $settingModel */

        $updateSetting = $settingModel->update(attributes: $dto->getFormFieldsArray());

        return (bool) $updateSetting;
    }

    /**
     *  Full removing a record of the Project Setting
     *
     * @param $id
     * @param CoreRepositoryContract $repository
     *
     * @return bool
     */
    public function processDestroy($id, CoreRepositoryContract $repository): bool
    {
        $settingModel = $repository->getForEditModel(id: (int) $id, useCache: true);

        if (!empty($settingModel)) {
            /** @var ProjectSetting $settingModel */

            // Other logic...

            return (bool) $settingModel->delete();
        }

        return false;
    }
}
