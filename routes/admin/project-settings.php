<?php

use App\Http\Controllers\Admin\AdminProjectSettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('project-settings')->group(function () {
    //Route::resource('admin.project-setting', AdminProjectSettingController::class);

    // Index: вывод списка ресурсов
    Route::get('/list', [AdminProjectSettingController::class, 'index'])->name('admin.project-setting.index');

    // Create: отображение формы создания ресурса
    Route::get('/create', [AdminProjectSettingController::class, 'create'])->name('admin.project-setting.create');

    // Store: сохранение нового ресурса
    Route::post('/', [AdminProjectSettingController::class, 'store'])->name('admin.project-setting.store');

    // Show: отображение конкретного ресурса
    Route::get('/{id}', [AdminProjectSettingController::class, 'show'])->name('admin.project-setting.show');

    // Edit: отображение формы редактирования ресурса
    Route::get('/{id}/edit', [AdminProjectSettingController::class, 'edit'])->name('admin.project-setting.edit');

    // Update: обновление конкретного ресурса
    Route::put('/{id}', [AdminProjectSettingController::class, 'update'])->name('admin.project-setting.update');

    // Destroy: удаление конкретного ресурса
    Route::delete('/{id}', [AdminProjectSettingController::class, 'destroy'])->name('admin.project-setting.destroy');
});
