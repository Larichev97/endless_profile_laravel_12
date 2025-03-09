<?php

namespace App\Services\ProjectSetting\DTO;


use App\Services\CoreCrudActionsServices\CoreFormFieldsDtoContract;
use App\Services\ProjectSetting\Requests\ProjectSettingStoreRequest;

final readonly class ProjectSettingStoreDTO implements CoreFormFieldsDtoContract
{
    public string $name;
    public string $value;

    /**
     * @param ProjectSettingStoreRequest $storeRequest
     */
    public function __construct(ProjectSettingStoreRequest $storeRequest)
    {
        $this->name = (string) $storeRequest->validated('name');
        $this->value = (string) $storeRequest->validated('value');
    }

    /**
     * @return array
     */
    public function getFormFieldsArray(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
        ];
    }
}
