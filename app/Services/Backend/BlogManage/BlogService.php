<?php

namespace App\Services\Backend\BlogManage;

use App\Models\Blog;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Services\Backend\BaseFormService;
use App\Services\ImageUploadService;

class BlogService extends BaseFormService
{

  public function __construct(protected ImageUploadService $imageUploadService)
  {
    parent::__construct(Blog::class, 'Blog', 'blog');
  }

  /**
   * Prepare data for blog create form.
   *
   * @return array
   */
  public function getCreateData(): array
  {
    $posts = Post::all();

    return [
      ...$this->getBaseCreateData(),
      'posts' => $posts,
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
    $posts = Post::all();
    return [
      ...$this->getBaseEditData($id),
      'posts' => $posts,
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
    return Blog::store($request);
  }

  public function updateData(Request $request, int $id)
  {
    $blog = Blog::findOrFail($id);
    $this->handleImageUpload($request, $blog->image ?? null);
    return Blog::store($request, $id);
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
        "uploads/blogs/{$oldImage}",
        "uploads/blogs/thumbnail/{$oldImage}",
      ]);
    }

    $upload = $this->imageUploadService->uploadImage(
      $request->file('image'),
      'blogs',
      '',
      true
    );

    if (empty($upload['filename'])) {
      abort(response()->json(['success' => false, 'message' => 'Failed to upload image'], 500));
    }

    $request->merge(['image_name' => $upload['filename']]);
  }
}
