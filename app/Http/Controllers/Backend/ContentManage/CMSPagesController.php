<?php

namespace App\Http\Controllers\Backend\ContentManage;

use App\Contracts\CommonServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BulkDestroyRequest;
use App\Http\Requests\Backend\ContentManage\CMSPageRequest;
use App\Models\CmsPage;
use App\Services\Backend\ContentManage\CMSPageService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class CMSPagesController extends Controller
{
  protected string $name = 'CMS Page';
  protected $model = CmsPage::class;
  public function __construct(protected CommonServiceInterface $commonService, protected CMSPageService $cmsPageService)
  {
    view()->share('pageTitle', "Manage {$this->name}s");
  }

  public function index(): View
  {
    return view('backend.pages.content-manage.cms_pages.index', [
      'cardHeader' => "{$this->name}s List"
    ]);
  }

  public function create(): View
  {
    return view('backend.pages.content-manage.cms_pages.form', [
      ...$this->cmsPageService->getCreateData()
    ]);
  }

  public function edit(int $id): View
  {
    return view('backend.pages.content-manage.cms_pages.form', [
      ...$this->cmsPageService->getEditData($id)
    ]);
  }

  public function store(CMSPageRequest $request): JsonResponse
  {
    return $this->cmsPageService->storeData($request);
  }

  public function update(CMSPageRequest $request, int $id = 0): JsonResponse
  {
    return $this->cmsPageService->updateData($request, $id);
  }

  public function destroy($id): JsonResponse
  {
    return $this->commonService->destroy($this->model, $id);
  }

  public function toggle(int $id = 0): JsonResponse
  {
    return $this->commonService->toggle($this->model, $id);
  }

  public function multidestroy(BulkDestroyRequest $request): JsonResponse
  {
    return $this->commonService->multidestroy($request, $this->model);
  }
}
