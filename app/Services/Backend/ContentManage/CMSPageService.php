<?php

namespace App\Services\Backend\ContentManage;

use App\Models\CmsPage;
use App\Services\Backend\BaseFormService;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\Storage;

class CMSPageService extends BaseFormService
{
  public function __construct(protected ImageUploadService $imageUploadService)
  {
    parent::__construct(CmsPage::class, 'CMS Page', 'cmsPage');
  }

  public function getCreateData(): array
  {
    return [
      ...$this->getBaseCreateData(),
    ];
  }

  public function getEditData($id = 0)
  {
    return [
      ...$this->getBaseEditData($id),
    ];
  }

  public function storeData($request)
  {
    $this->handleImageUpload($request);
    return CmsPage::store($request);
  }

  public function updateData($request, int $id)
  {
    $cmsPage = CmsPage::findOrFail($id);

    $this->handleImageUpload($request, $cmsPage->feature_image ?? null);

    return CmsPage::store($request, $id);
  }

  protected function handleImageUpload($request, ?string $oldImage = null): void
  {
    if (!$request->hasFile('cms_image')) {
      if ($oldImage) {
        $request->merge(['image_name' => $oldImage]);
      }
      return;
    }

    if ($oldImage) {
      Storage::disk('public')->delete([
        "uploads/cms_pages/{$oldImage}",
        "uploads/cms_pages/thumbnail/{$oldImage}",
      ]);
    }

    $upload = $this->imageUploadService->uploadImage(
      $request->file('cms_image'),
      'cms_pages',
      '',
      true
    );

    if (empty($upload['filename'])) {
      abort(response()->json(['success' => false, 'message' => 'Failed to upload image'], 500));
    }

    $request->merge(['image_name' => $upload['filename']]);
  }
}
