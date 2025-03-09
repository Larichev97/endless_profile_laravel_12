<?php

namespace App\Services\ProjectSetting\Observers;

use App\Services\ProjectSetting\Models\ProjectSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ProjectSettingCacheObserver
{
    /**
     *  Событие создания записи Модели
     *
     * @param ProjectSetting $model
     * @return void
     */
    public function created(ProjectSetting $model): void
    {
        Cache::forget($model::class.'-getModelCollection');
        Cache::forget($model::class.'-getForDropdownList');
    }

    /**
     *  Событие изменения записи Модели
     *
     * @param ProjectSetting $model
     * @return void
     */
    public function updated(ProjectSetting $model): void
    {
        Cache::forget('project-setting-name-'.Str::lower($model->name));
        Cache::forget($model::class.'-getModelCollection');
        Cache::forget($model::class.'-getForEditModel-'.$model->getKey());
        Cache::forget($model::class.'-getForDropdownList');
    }

    /**
     *  Событие удаления записи Модели
     *
     * @param ProjectSetting $model
     * @return void
     */
    public function deleted(ProjectSetting $model): void
    {
        Cache::forget('project-setting-name-'.Str::lower($model->name));
        Cache::forget($model::class.'-getModelCollection');
        Cache::forget($model::class.'-getForEditModel-'.$model->getKey());
        Cache::forget($model::class.'-getForDropdownList');
    }
}
