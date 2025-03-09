<?php

namespace App\Services\ProjectSetting\DTO;

use App\Services\CoreCrudActionsServices\CoreFormFieldsDtoContract;
use App\Services\ProjectSetting\Requests\ProjectSettingUpdateRequest;

final readonly class ProjectSettingUpdateDTO implements CoreFormFieldsDtoContract
{
    public string $name;
    public string $value;

    /**
     * @param ProjectSettingUpdateRequest $updateRequest
     * @param int $id_setting
     */
    public function __construct(ProjectSettingUpdateRequest $updateRequest, public int $id_setting)
    {
        $this->name = (string) $updateRequest->validated('name');
        $this->value = (string) $updateRequest->validated('value');
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
