<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProjectSetting\CrudProjectSettingService;
use App\Services\ProjectSetting\DTO\ProjectSettingStoreDTO;
use App\Services\ProjectSetting\DTO\ProjectSettingUpdateDTO;
use App\Services\ProjectSetting\Exceptions\ProjectSettingNotFoundException;
use App\Services\ProjectSetting\Repositories\ProjectSettingRepository;
use App\Services\ProjectSetting\Requests\ProjectSettingStoreRequest;
use App\Services\ProjectSetting\Requests\ProjectSettingUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class AdminProjectSettingController extends Controller
{
    const DEFAULT_PER_PAGE = 15;

    /**
     * @param CrudProjectSettingService $crudProjectSettingService
     * @param ProjectSettingRepository $projectSettingRepository
     */
    public function __construct(
        readonly CrudProjectSettingService $crudProjectSettingService,
        readonly ProjectSettingRepository $projectSettingRepository,
    ) {}

    /**
     *  Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        try {
            $projectSettings = $this->projectSettingRepository->getAllWithPaginate(perPage: self::DEFAULT_PER_PAGE, orderWay: 'asc');

            return Inertia::render('admin/project-settings/List', [
                'projectSettings' => $projectSettings,
                'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            ]);
        } catch (Exception $exception) {
            // TODO: add Error vue template
            dd($exception->getMessage());
        }
    }

    /**
     *  Show the form for creating a new resource.
     *
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function create(): Application|Factory|View|\Illuminate\Foundation\Application
    {
        return view('setting.create');
    }

    /**
     *  Store a newly created resource in storage.
     *
     * @param ProjectSettingStoreRequest $request
     *
     * @return Application|Factory|View|\Illuminate\Foundation\Application|RedirectResponse
     */
    public function store(ProjectSettingStoreRequest $request): Factory|View|\Illuminate\Foundation\Application|Application|RedirectResponse
    {
        try {
            $createSetting = $this->crudProjectSettingService->processStore(dto: new ProjectSettingStoreDTO($request));

            if ($createSetting) {
                return redirect()->route('admin.settings.index')->with('success','Настройка успешно создана.');
            }

            return back()->with('error','Ошибка! Настройка не создана.')->withInput();
        } catch (Exception $exception) {
            // TODO: add Error vue template
            dd($exception->getMessage());
        }
    }

    /**
     *  Display the specified resource.
     *
     * @param $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function show($id): Application|Factory|View|\Illuminate\Foundation\Application
    {
        try {
            $setting = $this->projectSettingRepository->getForEditModel(id: (int) $id, useCache: true);

            if (empty($setting)) {
                throw new ProjectSettingNotFoundException(sprintf('Настройка #%s не найдена.', $id));
            }

            return view('setting.show',compact(['setting',]));
        } catch (Exception $exception) {
            // TODO: add Error vue template
            dd($exception->getMessage());
        }
    }

    /**
     *  Show the form for editing the specified resource.
     *
     * @param $id
     *
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function edit($id): Application|Factory|View|\Illuminate\Foundation\Application
    {
        try {
            $setting = $this->projectSettingRepository->getForEditModel(id: (int) $id, useCache: true);

            if (empty($setting)) {
                throw new ProjectSettingNotFoundException(sprintf('Настройка #%s не найдена.', $id));
            }

            return view('setting.edit',compact(['setting',]));
        } catch (Exception $exception) {
            // TODO: add Error vue template
            dd($exception->getMessage());
        }
    }

    /**
     *  Update the specified resource in storage.
     *
     * @param ProjectSettingUpdateRequest $updateRequest
     * @param $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application|RedirectResponse
     */
    public function update(ProjectSettingUpdateRequest $updateRequest, $id): Factory|View|\Illuminate\Foundation\Application|Application|RedirectResponse
    {
        try {
            $updateSetting = $this->crudProjectSettingService->processUpdate(dto: new ProjectSettingUpdateDTO(updateRequest: $updateRequest, id_setting: (int) $id), repository: $this->projectSettingRepository);

            if ($updateSetting) {
                return redirect()->route('admin.settings.index')->with('success', sprintf('Данные настройки #%s успешно обновлены.', $id));
            }

            return back()->with('error', sprintf('Ошибка! Данные настройки #%s не обновлены.', $id))->withInput();
        } catch (Exception $exception) {
            // TODO: add Error vue template
            dd($exception->getMessage());
        }
    }

    /**
     *  Remove the specified resource from storage.
     *
     * @param $id
     *
     * @return \Illuminate\Foundation\Application|Factory|View|Application|RedirectResponse
     */
    public function destroy($id): \Illuminate\Foundation\Application|Factory|View|Application|RedirectResponse
    {
        try {
            $deleteSetting = $this->crudProjectSettingService->processDestroy(id: $id, repository: $this->projectSettingRepository);

            if ($deleteSetting) {
                return redirect()->route('admin.settings.index')->with('success', sprintf('Настройка #%s успешно удалена.', $id));
            }

            return back()->with('error', sprintf('Ошибка! Настройка #%s не удалена.', $id));
        } catch (Exception $exception) {
            // TODO: add Error vue template
            dd($exception->getMessage());
        }
    }
}
