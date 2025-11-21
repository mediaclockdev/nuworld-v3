<?php

namespace Database\Seeders;

use App\Models\SubModule;
use App\Models\SubmodulePermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class SubModuleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public array $permissions = [1, 2, 3, 4, 5];

  public function run(): void
  {
    Schema::disableForeignKeyConstraints();
    SubmodulePermission::truncate();
    SubModule::truncate();
    Schema::enableForeignKeyConstraints();

    $submodules = [
      // module_id => 1 (Master Settings)
      ['id' => 1, 'name' => 'Modules', 'route_name' => 'admin.modules', 'module_id' => 1, 'sequence' => 1, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 2, 'name' => 'SubModules', 'route_name' => 'admin.submodules', 'module_id' => 1, 'sequence' => 2, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],

      ['id' => 3, 'name' => 'Permissions', 'route_name' => 'admin.permissions', 'module_id' => 1, 'sequence' => 3, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 4, 'name' => 'Departments', 'route_name' => 'admin.departments', 'module_id' => 1, 'sequence' => 4, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 5, 'name' => 'Store Settings', 'route_name' => 'admin.site_settings', 'module_id' => 1, 'sequence' => 5, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 6, 'name' => 'Currency', 'route_name' => 'admin.currencies', 'module_id' => 1, 'sequence' => 6, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],

      ['id' => 7, 'name' => 'States', 'route_name' => 'admin.states', 'module_id' => 1, 'sequence' => 7, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 8, 'name' => 'Payment Options', 'route_name' => 'admin.payments', 'module_id' => 1, 'sequence' => 8, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 9, 'name' => 'New Store Setup', 'route_name' => 'admin.new-store-setup', 'module_id' => 1, 'sequence' => 9, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],

      // module_id => 2 (Admin Settings)
      ['id' => 10, 'name' => 'Roles', 'route_name' => 'admin.roles', 'module_id' => 2, 'sequence' => 1, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 11, 'name' => 'Users', 'route_name' => 'admin.users', 'module_id' => 2, 'sequence' => 2, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],

      // module_id => 3 (Content Management)
      ['id' => 12, 'name' => 'Banners', 'route_name' => 'admin.banners', 'module_id' => 3, 'sequence' => 1, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 13, 'name' => 'CMS Pages', 'route_name' => 'admin.cms-pages', 'module_id' => 3, 'sequence' => 2, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 14, 'name' => 'Media Gallery', 'route_name' => 'admin.media-gallery', 'module_id' => 3, 'sequence' => 3, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 0],
      ['id' => 15, 'name' => 'Menu Builder', 'route_name' => 'admin.menu-builder', 'module_id' => 3, 'sequence' => 4, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 16, 'name' => 'FAQs', 'route_name' => 'admin.faqs', 'module_id' => 3, 'sequence' => 5, 'icon' => 'ri-add-box-line', 'created_by' => 1, 'updated_by' => NULL, 'status' => 1],


      // module_id => 4 (Product Management)
      ['id' => 17, 'name' => 'Product Categories', 'route_name' => 'admin.product-categories', 'module_id' => 4, 'sequence' => 1, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 18, 'name' => 'Product Attributes', 'route_name' => 'admin.product-attributes', 'module_id' => 4, 'sequence' => 2, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 19, 'name' => 'Attribute Values', 'route_name' => 'admin.product-attribute-values', 'module_id' => 4, 'sequence' => 3, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 20, 'name' => 'Products', 'route_name' => 'admin.products', 'module_id' => 4, 'sequence' => 4, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 21, 'name' => 'Product Variations', 'route_name' => 'admin.product-variations', 'module_id' => 4, 'sequence' => 5, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 22, 'name' => 'Ratings', 'route_name' => 'admin.product-reviews', 'module_id' => 4, 'sequence' => 6, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 23, 'name' => 'Product FAQs', 'route_name' => 'admin.product-faqs', 'module_id' => 4, 'sequence' => 7, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 24, 'name' => 'Import CSV', 'route_name' => 'admin.import-csv', 'module_id' => 4, 'sequence' => 8, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],

      // module_id => 5 (Order Management)
      ['id' => 25, 'name' => 'Customers', 'route_name' => 'admin.customers', 'module_id' => 5, 'sequence' => 1, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 26, 'name' => 'Pincode', 'route_name' => 'admin.pincode', 'module_id' => 5, 'sequence' => 2, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 27, 'name' => 'Orders', 'route_name' => 'admin.orders', 'module_id' => 5, 'sequence' => 3, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 28, 'name' => 'Shipment Management', 'route_name' => 'admin.shipment-management', 'module_id' => 5, 'sequence' => 4, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 29, 'name' => 'Wishlist', 'route_name' => 'admin.wishlist', 'module_id' => 5, 'sequence' => 5, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 30, 'name' => 'Cart', 'route_name' => 'admin.cart', 'module_id' => 5, 'sequence' => 6, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 31, 'name' => 'Return Requests', 'route_name' => 'admin.return-requests', 'module_id' => 5, 'sequence' => 7, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 32, 'name' => 'Scratch Card Rewards Management', 'route_name' => 'admin.scratch-card-rewards', 'module_id' => 5, 'sequence' => 8, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 33, 'name' => 'Customer Rewards Management', 'route_name' => 'admin.customer-rewards', 'module_id' => 5, 'sequence' => 9, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 34, 'name' => 'Charges', 'route_name' => 'admin.charges', 'module_id' => 5, 'sequence' => 10, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],

      // module_id => 6 (Promotions)
      ['id' => 35, 'name' => 'Coupons', 'route_name' => 'admin.coupons', 'module_id' => 6, 'sequence' => 1, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 36, 'name' => 'Promotion', 'route_name' => 'admin.promotion', 'module_id' => 6, 'sequence' => 2, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 37, 'name' => 'Newsletters', 'route_name' => 'admin.newsletter', 'module_id' => 6, 'sequence' => 3, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],

      // module_id => 7 (Reports and Analytics)
      ['id' => 38, 'name' => 'Sales Analytics', 'route_name' => 'admin.sales-analytics', 'module_id' => 7, 'sequence' => 1, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 39, 'name' => 'Customer Analytics', 'route_name' => 'admin.customer-analytics', 'module_id' => 7, 'sequence' => 2, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 40, 'name' => 'Inventory Analytics', 'route_name' => 'admin.inventory-analytics', 'module_id' => 7, 'sequence' => 3, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 41, 'name' => 'Conversion Analytics', 'route_name' => 'admin.conversion-analytics', 'module_id' => 7, 'sequence' => 4, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 42, 'name' => 'Return Analytics', 'route_name' => 'admin.return-analytics', 'module_id' => 7, 'sequence' => 5, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 43, 'name' => 'Product-Performance Analytics', 'route_name' => 'admin.product-performance_analytics', 'module_id' => 7, 'sequence' => 6, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 44, 'name' => 'Profit Reports', 'route_name' => 'admin.profit-reports', 'module_id' => 7, 'sequence' => 7, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],

      // module_id => 7 (Inventory Management)
      ['id' => 45, 'name' => 'Stock Product Management', 'route_name' => 'admin.inventory', 'module_id' => 8, 'sequence' => 1, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 46, 'name' => 'Replenishment & Ordering', 'route_name' => 'admin.replenishment-ordering', 'module_id' => 8, 'sequence' => 2, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 0],
      ['id' => 47, 'name' => 'Transfers & Returns', 'route_name' => 'admin.transfers-returns', 'module_id' => 8, 'sequence' => 3, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 0],

      ['id' => 48, 'name' => 'Blogs', 'route_name' => 'admin.blogs', 'module_id' => 9, 'sequence' => 1, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 49, 'name' => 'Post', 'route_name' => 'admin.posts', 'module_id' => 9, 'sequence' => 2, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
      ['id' => 50, 'name' => 'Contacts', 'route_name' => 'admin.contacts', 'module_id' => 10, 'sequence' => 1, 'icon' => NULL, 'created_by' => 1, 'updated_by' => 1, 'status' => 1],
    ];

    SubModule::insert($submodules);

    foreach ($submodules as $submodule) {
      foreach ($this->permissions as $permission) {
        $submodulePermissionArray[] = [
          'sub_module_id' => $submodule['id'],
          'permission_id' => $permission
        ];
      }
    }

    if (!empty($submodulePermissionArray))
      SubmodulePermission::insert($submodulePermissionArray);
  }
}
