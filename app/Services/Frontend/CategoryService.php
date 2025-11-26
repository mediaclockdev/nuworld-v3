<?php

namespace App\Services\Frontend;

use App\Http\Requests\Frontend\FilterRequest;
use App\Models\MenuItem;
use App\Models\ProductCategory;
use App\Services\Frontend\ProductService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class CategoryService
{

  public function __construct(protected ProductService $productService) {}

  /**
   * Get Product Categories that have products.
   *
   * @param int $limit
   * @param string|null $orderBy ('latest' or 'oldest' or null)
   * @return \Illuminate\Support\Collection
   */
  public function getCategoriesWithProducts(int $limit = 16, ?string $orderBy = null): Collection
  {
    // $slugs = [
    //   'red-apples',
    //   'hass-avocados',
    //   'chicken',
    //   'white-bread',
    //   'ground-goat',
    // ];

    $query = ProductCategory::whereHas('products')
      // ->whereIn('slug', $slugs)
      // ->orderByRaw("FIELD(slug, '" . implode("','", $slugs) . "')") // preserve the given sequence
      ->when($orderBy, fn($q) => $q->orderBy('created_at', $orderBy === 'latest' ? 'desc' : 'asc'));

    return $limit > 0 ? $query->take($limit)->get() : $query->get();
  }

  /**
   * Retrieve a category by slug or the newest for 'new-collections'.
   */
  public function getCategory(string $slug): ?ProductCategory
  {
    $query = ProductCategory::with('products', 'children');
    $category = $slug === 'new-collections'
      ? $query->orderBy('created_at', 'desc')->first()
      : $query->where('slug', $slug)->first();

    if ($category && $slug === 'new-collections') {
      $category->title = 'New Collections';
    }
    return $category;
  }

  /**
   * Get direct child categories with minimal fields.
   */
  public function getChildCategories(ProductCategory $category): Collection
  {
    return ProductCategory::where('parent_id', $category->id)
      ->orderBy('sequence')
      ->get(['id', 'title', 'slug', 'category_image']);
  }

  /**
   * Recursively find products in a category or its descendants.
   */
  public function findProducts(ProductCategory $category): Collection
  {
    $products = $category->products()->with('variants')->get();

    if ($products->isNotEmpty()) {
      return $products;
    }

    return $this->getChildCategories($category)
      ->reduce(function (?Collection $carry, ProductCategory $subCategory) {
        return $carry ?? $this->findProducts($subCategory);
      }, null) ?? collect([]);
  }

  /**
   * Get products to display, prioritizing first child category.
   */
  public function getDisplayProducts(ProductCategory $category): Collection
  {
    $childCategories = $this->getChildCategories($category);
    $selectedCategory = $childCategories->first();

    return $selectedCategory
      ? $this->findProducts($selectedCategory)
      : $category->products()->with('variants')->get();
  }

  /**
   * Prepare data for the category view.
   */
  public function prepareViewData(ProductCategory $category, Collection $products, FilterRequest $request, string $slug): array
  {
    $product = $products->first();

    if ($product) {
      $product->searchSlug = Str::slug($product->name);
    }

    $params = $request->validated();
    $perPage = (int) ($params['per_page'] ?? 12);
    $page = (int) ($params['page'] ?? 1);
    $keywords = $this->productService->getKeywords($params);
    $priceRange = $product ? $this->productService->getPriceRange($product, $params) : [];
    $variants = $product ? $this->productService->searchVariants(
      $product,
      $keywords,
      $priceRange,
      $params,
      $perPage,
      $page,
      $request->url(),
      $request->query(),
      $slug
    ) : [];
    $relatedProducts = $product ? $this->productService->getRelatedProducts($product) : [];
    $totalVariants = $products->sum(fn($product) => $product->variants->count());
    $selectedFilters = $params['attributes'] ?? [];
    $attributes = $product ? $this->productService->getFilterAttributes($product) : [];

    return [
      'category' => $category,
      'title' => $category->title ?? '',
      'selectedCategory' => $this->getChildCategories($category)->first(),
      'product' => $product,
      'products' => $products,
      'relatedProducts' => $relatedProducts,
      'totalVariants' => $totalVariants,
      'variants' => $variants,
      'selectedFilters' => $selectedFilters,
      'priceRange' => $priceRange,
      'attributes' => $attributes,
    ];
  }

  // public function getNestedCategories($id = 0)
  // {
  //   return ProductCategory::with(['children'])
  //     ->where('parent_id', $id)
  //     ->orderBy('sequence', 'asc')
  //     ->take(7)
  //     ->get(['title', 'id']);
  // }

  // public function getNestedCategories($id = 0)
  // {
  //   return ProductCategory::with(['children.children'])
  //     ->where('parent_id', 0)
  //     ->take(7)
  //     ->get();
  // }

  public function getNestedCategories(int $parentId = 0, int $limit = 7)
  {
    return ProductCategory::with([
      'children' => function ($q) {
        $q->select('id', 'title', 'slug', 'parent_id', 'sequence', 'category_image')
          ->orderBy('sequence')
          ->with([
            'children' => function ($q2) {
              $q2->select('id', 'title', 'slug', 'parent_id', 'sequence', 'category_image')
                ->orderBy('sequence');
            }
          ]);
      }
    ])
      ->select('id', 'title', 'slug', 'parent_id', 'sequence', 'category_image')
      ->where('parent_id', $parentId)   // root categories
      ->orderBy('sequence')
      ->take($limit)
      ->get();
  }

  //$menus =
}
