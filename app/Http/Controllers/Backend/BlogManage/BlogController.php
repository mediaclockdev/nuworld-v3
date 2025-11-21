<?php

namespace App\Http\Controllers\Backend\BlogManage;

use App\Contracts\CommonServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BlogManage\BlogRequest;
use App\Models\Blog;
use App\Services\Backend\BlogManage\BlogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class BlogController extends Controller
{
  protected string $name = "Blog";
  protected $model = Blog::class;

  public function __construct(protected CommonServiceInterface $commonService, protected BlogService $blogService)
  {
    view()->share('pageTitle', "Manage {$this->name}s");
  }

  public function index(): View
  {
    return view('backend.pages.blog-manage.blogs.index', ['cardHeader' => "Manage {$this->name}s List"]);
  }

  public function create(): View
  {
    return view('backend.pages.blog-manage.blogs.form', $this->blogService->getCreateData());
  }

  public function store(BlogRequest $request)
  {
    return $this->blogService->storeData($request);
  }
  public function edit(int $id): View
  {
    return view('backend.pages.blog-manage.blogs.form', $this->blogService->getEditData($id));
  }

  public function update(BlogRequest $request, $id = '')
  {
    return $this->blogService->updateData($request, $id);
  }

  public function toggle($id): JsonResponse
  {
    return $this->model::toggleStatus($id);
  }
  public function destroy(int $id = 0): JsonResponse
  {
    return $this->commonService->destroy($this->model, $id);
  }
}
