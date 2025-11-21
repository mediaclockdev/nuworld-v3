<?php
namespace App\Services\Backend\StoreManage;

use App\Models\Country;
use App\Models\Store;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Services\Backend\BaseFormService;
use App\Services\ImageUploadService;

class StoreService extends BaseFormService
{

  public function __construct(protected ImageUploadService $imageUploadService)
  {
    parent::__construct(Store::class, 'Store', 'store');
  }

  /**
   * Prepare data for blog create form.
   *
   * @return array
   */
  public function getCreateData(): array
  {
    $stores    = Store::all();
    $countries = Country::all();

    return [
      ...$this->getBaseCreateData(),
      'stores' => $stores,
      'countries' => $countries
    ];
  }

  /**
   * Prepare data for blog edit form.
   *
   * @param string $id
   * @return array
   * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
   */
  public function getEditData(string $id): array
  {
    $stores    = Store::all();
    $countries = Country::all();

    return [
      ...$this->getBaseEditData($id),
      'stores' => $stores,
      'countries' => $countries
    ];
  }

  /**
   * Store a new blog with image handling.
   *
   * @param Request $request
   * @return array
   */

  public function storeData(Request $request)
  {
    $this->handleImageUpload($request);
    return Store::store($request);
  }

  public function updateData(Request $request, int $id)
  {
    $storeObj = Store::findOrFail($id);
    $this->handleImageUpload($request, $storeObj->image ?? null);
    return Store::store($request, $id);
  }

  /**
   * Handle image upload for blog creation or update.
   *
   * @param Request $request
   * @param string|null $oldImage
   * @return void
   */
  protected function handleImageUpload(Request $request, ?string $oldImage = null): void
  {
    if (!$request->hasFile('image')) {
      if ($oldImage) {
        $request->merge(['image_name' => $oldImage]);
      }
      return;
    }

    if ($oldImage) {
      Storage::disk('public')->delete([
        "uploads/stores/{$oldImage}",
        "uploads/stores/thumbnail/{$oldImage}",
      ]);
    }

    $upload = $this->imageUploadService->uploadImage(
      $request->file('image'),
      'stores',
      '',
      true
    );

    if (empty($upload['filename'])) {
      abort(response()->json(['success' => false, 'message' => 'Failed to upload image'], 500));
    }

    $request->merge(['image_name' => $upload['filename']]);
  }
}
