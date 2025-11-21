<?php

namespace App\Http\Controllers\Backend\FaqManage;

use App\Contracts\CommonServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ContentManage\FaqRequest;
use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FaqController extends Controller
{
  protected string $name;

  protected $model;
  public function __construct(protected CommonServiceInterface $commonService)
  {
    $this->name = 'faqs';
    $this->model = Faq::class;
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  public function index(): View
  {
    $faqCategories = FaqCategory::get();
    return view('backend.pages.faq-manage.faqs.index', ['cardHeader' => $this->name . ' List', 'faqCategories' => $faqCategories]);
    // return view('backend.pages.faq-manage.faq-categories.index', ['cardHeader' => $this->name . ' List']);
  }

  public function create(): View
  {
    return view('backend.pages.faq-manage.faqs.form', ['cardHeader' => 'Create ' . $this->name, 'faq' => new $this->model(), 'faqCategories' => FaqCategory::all()]);
  }

  public function store(FaqRequest $request): JsonResponse
  {
    return $this->model::store($request);
  }

  public function edit($id = ''): View
  {
    $faq = Faq::find($id);
    if (!$faq)
      abort(404);

    return view('backend.pages.faq-manage.faqs.form', ['cardHeader' => 'Edit ' . $this->name, 'faq' => $faq, 'faqCategories' => FaqCategory::all()]);
  }

  public function update(FaqRequest $request, $id = ''): JsonResponse
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
