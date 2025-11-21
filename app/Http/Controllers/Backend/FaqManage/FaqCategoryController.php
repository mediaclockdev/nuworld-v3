<?php

namespace App\Http\Controllers\Backend\FaqManage;

use App\Contracts\CommonServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ContentManage\FaqCategoryRequest;
use App\Models\FaqCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FaqCategoryController extends Controller
{
  protected string $name;

  protected $model;
  public function __construct(protected CommonServiceInterface $commonService)
  {
    $this->name = 'Faq Categories';
    $this->model = FaqCategory::class;
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  public function index(): View
  {
    // abort(404);
    return view('backend.pages.faq-manage.faq-categories.index', ['cardHeader' => $this->name . ' List']);
  }

  public function create(): View
  {
    // return view('backend.pages.faq-manage.faqs.form', ['cardHeader' => 'Create ' . $this->name, 'faq' => new $this->model(), 'faqCategories' => FaqCategory::all()]);
    return view('backend.pages.faq-manage.faq-categories.form', ['cardHeader' => 'Create ' . $this->name, 'faqCategory' => new $this->model()]);
  }

  public function store(FaqCategoryRequest $request): JsonResponse
  {
    return $this->model::store($request);
  }

  public function edit($id = ''): View
  {
    $faqCategory = FaqCategory::find($id);
    if (!$faqCategory)
      abort(404);

    return view('backend.pages.faq-manage.faq-categories.form', ['cardHeader' => 'Edit ' . $this->name, 'faqCategory' => $faqCategory]);
  }

  public function update(FaqCategoryRequest $request, $id = ''): JsonResponse
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
