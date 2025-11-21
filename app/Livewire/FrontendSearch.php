<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\SearchQuery;
use Livewire\Component;
use Illuminate\Support\Str;

class FrontendSearch extends Component
{
  public $search_products = '';
  public $showResults = false;

  public function updatedSearchProducts()
  {
    $this->showResults = !empty($this->search_products);

    $query = trim($this->search_products);
    $query = strtolower(preg_replace('/\s+/', ' ', $query));
    if (strlen($query) < 3 || str_word_count($query) < 2) {
      return;
    }

    if (session('last_logged_search_query') === $query) {
      return;
    }

    SearchQuery::log(
      query: $query,
      userId: user() ? user()->id : null,
      ip: request()->ip()
    );

    session(['last_logged_search_query' => $query]);
  }
  public function getVariantsBkp()
  {
    if (empty($this->search_products)) {
      return collect();
    }

    $searchTerms = array_filter(explode(' ', trim($this->search_products)));

    return Product::with(['category', 'variants.images.gallery'])
      ->whereHas('variants', function ($query) use ($searchTerms) {
        foreach ($searchTerms as $term) {
          $query->where(function ($subQuery) use ($term) {
            $subQuery->where('name', 'like', '%' . $term . '%')
              ->orWhere('sku', 'like', '%' . $term . '%');
          });
        }
      })
      ->get()
      ->flatMap(function ($product) use ($searchTerms) {
        return $product->variants->filter(function ($variant) use ($searchTerms) {
          // Only include variants that match all terms
          $name = strtolower($variant->name ?? '');
          $sku = strtolower($variant->sku ?? '');
          foreach ($searchTerms as $term) {
            $term = strtolower($term);
            if (!str_contains($name, $term) && !str_contains($sku, $term)) {
              return false;
            }
          }
          return true;
        })->map(function ($variant) use ($product) {
          $variant->product_name = $product->name;
          $variant->category_name = $product->category->title ?? 'No Category';
          return $variant;
        });
      })
      ->take(10);
  }

  public function getVariants()
  {
    if (empty($this->search_products)) {
      return collect();
    }

    $searchTerms = array_filter(explode(' ', trim($this->search_products)));

    // First, try exact matching
    $exactVariants = Product::with(['category', 'variants.images.gallery'])
      ->whereHas('variants', function ($query) use ($searchTerms) {
        foreach ($searchTerms as $term) {
          $query->where(function ($subQuery) use ($term) {
            $subQuery->where('name', 'like', '%' . $term . '%')
              ->orWhere('sku', 'like', '%' . $term . '%');
          });
        }
      })
      ->get()
      ->flatMap(function ($product) use ($searchTerms) {
        return $product->variants->filter(function ($variant) use ($searchTerms) {
          // Only include variants that match all terms
          $name = strtolower($variant->name ?? '');
          $sku = strtolower($variant->sku ?? '');
          foreach ($searchTerms as $term) {
            $term = strtolower($term);
            if (!str_contains($name, $term) && !str_contains($sku, $term)) {
              return false;
            }
          }
          return true;
        })->map(function ($variant) use ($product) {
          $variant->product_name = $product->name;
          $variant->category_name = $product->category->title ?? 'No Category';
          $variant->match_score = 100; // Exact match
          return $variant;
        });
      });

    // If exact matches are found, return them (up to 10)
    if ($exactVariants->isNotEmpty()) {
      return $exactVariants->take(10);
    }

    // If no exact matches, perform fuzzy matching
    $allVariants = Product::with(['category', 'variants.images.gallery'])
      ->get()
      ->flatMap(function ($product) {
        return $product->variants->map(function ($variant) use ($product) {
          $variant->product_name = $product->name;
          $variant->category_name = $product->category->title ?? 'No Category';
          return $variant;
        });
      });

    $searchQuery = strtolower(trim($this->search_products));
    $fuzzyVariants = $allVariants->map(function ($variant) use ($searchQuery) {
      $name = strtolower($variant->name ?? '');
      $sku = strtolower($variant->sku ?? '');

      // Calculate similarity percentage between search query and variant name/SKU
      similar_text($searchQuery, $name, $percentName);
      similar_text($searchQuery, $sku, $percentSku);

      $variant->match_score = max($percentName, $percentSku);

      return $variant;
    })->filter(function ($variant) {
      // Only include variants with a similarity score above a threshold (e.g., 35%)
      return $variant->match_score > 35;
    })->sortByDesc('match_score')->take(10);

    return $fuzzyVariants;
  }

  public function generateSearchUrl($variant)
  {
    // Always return product detail page by variant SKU
    return route('product.show', ['variant' => $variant->sku]);
  }

  public function clearSearch()
  {
    $this->reset(['search_products', 'showResults']);
  }

  public function render()
  {
    $variants = $this->showResults ? $this->getVariants() : collect();

    return view('livewire.frontend-search', [
      'variants' => $variants,
      'suggestions' => $variants->take(5),
    ]);
  }
}
