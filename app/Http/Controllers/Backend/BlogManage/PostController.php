<?php

namespace App\Http\Controllers\Backend\BlogManage;

use App\Contracts\CommonServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BlogManage\PostRequest;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
  protected string $name;

  protected $model;
  public function __construct(protected CommonServiceInterface $commonService)
  {
    $this->name = 'posts';
    $this->model = Post::class;
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  public function index(): View
  {
    return view('backend.pages.blog-manage.posts.index', ['cardHeader' => $this->name . ' List']);
  }

  public function create(): View
  {

    return view('backend.pages.blog-manage.posts.form', ['cardHeader' => 'Create ' . $this->name, 'post' => new $this->model(), 'posts' => Post::all()]);
  }

  public function store(PostRequest $request): JsonResponse
  {
    return $this->model::store($request);
  }

  public function edit($id = ''): View
  {
    $post = Post::find($id);
    if (!$post)
      abort(404);

    return view('backend.pages.blog-manage.posts.form', ['cardHeader' => 'Edit ' . $this->name, 'post' => $post]);
  }

  public function update(PostRequest $request, $id = ''): JsonResponse
  {
    return $this->model::store($request, $id);
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
