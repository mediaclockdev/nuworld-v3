<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;

class ProductCategory extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'parent_id',
    'title',
    'slug',
    'tax',
    'icon',
    'sequence',
    'meta_title',
    'meta_keywords',
    'meta_desc',
    'created_by',
    'updated_by',
    'category_image'
  ];
  //

  public function parent()
  {
    return $this->belongsTo(self::class, 'parent_id');
  }

  public function children()
  {
    return $this->hasMany(self::class, 'parent_id')->orderBy('sequence');
  }

  public function allParents()
  {
    $parents = collect();
    $category = $this;
    while ($category->parent_id != 0 && $category->parent) {
      $parents->push($category->parent);
      $category = $category->parent;
    }
    return $parents;
  }

  public function products()
  {
    return $this->hasMany(Product::class, 'category_id');
  }


  public function collections()
  {
    return $this->belongsToMany(ProductCollection::class, 'product_collection_category');
  }

  public static function store($data, string $id = '')
  {
    //dd($data->image_name);
    if ($data->parent_id != '')
      $parent_id = Hashids::decode($data->parent_id)[0];
    else
      $parent_id = 0;

    $result = self::updateOrCreate(['id' => $id], [
      'parent_id' => $parent_id,
      'title' => $data->categorytitle,
      'slug' => $data->slug,
      'tax' => $data->tax_percentage,
      'icon' => $data->categoryicon,
      'category_image' => $data->image_name,
      'sequence' => $data->sequence,
      'meta_title' => $data->meta_title,
      'meta_keywords' => $data->meta_keywords,
      'meta_desc' => $data->meta_description,
      'updated_by' => user('admin')->id,
      'created_by' => $id ? self::find($id)->created_by : user('admin')->id,
    ])->id;

    if (!$result)
      return response()->json(['success' => false, 'message' => __('response.error' . ($id ? '.update' : '.create'), ['item' => 'Product Category']),]);

    $options = renderCategoryOptions(self::generateTree('active'));

    return response()->json(['success' => true, 'message' => __('response.success.' . ($id ? 'update' : 'create'), ['item' => 'Product Category']), 'options' => $options]);
  }

  public static function remove($id)
  { // first check id exist or not
    $category = self::find($id);
    if (!$category)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Product Category'])]);

    $parentID = self::where([['parent_id', $id], ['deleted_at', null]])->exists(); // then check if this id has sub category

    if ($parentID)
      return response()->json(['success' => false, 'message' => __('response.error.has_associated', ['item1' => 'Product Category', 'item2' => 'Sub Category'])]);

    $product = Product::where('category_id', $id)->exists();
    if ($product)
      return response()->json(['success' => false, 'message' => __('response.error.has_associated', ['item1' => 'Product Category', 'item2' => 'Product'])]);

    $category->delete();
    $category->deleted_by = auth('admin')->id();
    $category->save();

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Product Category'])]);
  }

  public static function toggleStatus($id)
  {
    $update = self::find($id);

    if (!$update)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Product Category'])]);

    $update->status = $update->status == 1 ? 0 : 1;
    $update->updated_by = auth('admin')->id();
    $update->save();

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Category Status']), 'newStatus' => $update->status]);
  }

  public function scopeSearch($query, $value)
  {
    $query->where('title', 'like', '%' . $value . '%')
      ->orWhere('slug', 'like', '%' . $value . '%')
      ->orWhere('meta_title', 'like', '%' . $value . '%');
  }

  public static function generateTree($active = null)
  {
    // Start with top-level categories
    $categories = self::where('parent_id', 0)
      ->when($active, function ($query) {
        return $query->where('status', 1);
      })
      ->whereNull('deleted_at')
      ->get();

    return $categories->map(function ($category) use ($active) {
      return self::buildTreeNode($category, $active);
    });
  }

  protected static function buildTreeNode($category, $active = null)
  {
    return [
      'id' => $category->id,
      'title' => $category->title,
      'status' => $category->status,
      'slug' => $category->slug,
      'parent_id' => $category->parent_id,
      'children' => $category->children()
        ->when($active, function ($query) {
          return $query->where('status', 1);
        })
        ->whereNull('deleted_at')
        ->get()
        ->map(function ($child) use ($active) {
          return self::buildTreeNode($child, $active);
        })
    ];
  }
}
