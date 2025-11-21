@extends('backend.layouts.app')
@section('page-styles')
<link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
<x-breadcrumb :pageTitle="$pageTitle" :skipLevels="[0]" />
<x-form-card :formTitle="$cardHeader" :backRoute="''" :formId="''">
   <div class="row">
      <div class="col-md-4">
         <div class="mb-3 required">
            <label class="form-label">Seeder Name</label>
            <select name="category" aria-label="Default select" class="form-select select2 category" id="category">
               <option value="" selected>Select one</option>
               <option value="1">Furniture</option>
               <option value="2">Sunglasses</option>
               {{-- <option value="3">Beauty Products</option> --}}
               <option value="4">Grocery</option>

            </select>
            <div id="modulename-error-container" style="display: none;"></div>
         </div>
         <div class="mb-3 seed">
            <a href="javascript:void(0);" class="btn btn-primary seeding-table">Seed</a>
         </div>
      </div>
      <div class="col-md-8">
         <div class="mb-3 seed-data" style="display: none;">
            <table class="table table-bordered">
               <thead>
                  <tr>
                     <th>Serial No</th>
                     <th>Seeder Name</th>
                     <th>Status</th>
                  </tr>
               </thead>
               <tbody id="seeder-status-table">
                  <!-- dynamic rows here -->
               </tbody>
            </table>
            <a href="{{ route('home') }}" target="_blank" class="btn btn-success vist-frontend" style="display: none;">Visit Frontend</a>
         </div>
      </div>
   </div>
</x-form-card>
<!-- end row -->
@endsection
@section('page-scripts')
<script src="{{ asset('/public/backend/assetss/select2/select2.min.js') }}"></script>
<script>
  $('#category').select2({
      'placeholder': 'Select a Category',
    });
   $(document).ready(function() {
       let category = null; // selected category
       let seeders = [];

       $('.category').on('change', function() {
           category = $(this).val();
           if (!category) {

             $('#modulename-error-container').show().html('<span class="text-danger">Please select a category first.</span>');
             return;
         }
         else{
           $('#modulename-error-container').hide();
         }
           $('.seed-data').hide();
           $('#seeder-status-table').empty();
           $('.vist-frontend').hide();

           if (category == 1) {
               seeders = [
                   { id: 1, title: 'Product Category Seeder', seederName: 'ProductCategorySeeder'},
                   { id: 2, title: 'Product Attribute, Attribute Value Seeder',seederName: 'ProductAttributeValueSeeder'},
                   { id: 3, title: 'Product Seeder, Product Variant Attribute Seeder, Inventory Seeder',seederName: 'ProductSeeder'},
                   { id: 4, title: 'Product Variant Image Seeder',seederName: 'ProductVariantImageSeeder'},
                   { id: 5, title: 'Custom Banner Seeder',seederName: 'CustomBannerSeeder'},
                   { id: 6, title: 'CMS Page Seeder',seederName: 'CmsPageSeeder'}
               ];
               $('.seed').show();
           }
            else if (category == 2) {
               seeders = [
                   { id: 1, title: 'Product Category Seeder', seederName: 'ProductCategorySeeder'},
                   { id: 2, title: 'Product Attribute, Attribute Value Seeder',seederName: 'ProductAttributeValueSeeder'},
                   { id: 3, title: 'Product Seeder, Product Variant Attribute Seeder, Inventory Seeder',seederName: 'ProductSeeder'},
                   { id: 4, title: 'Product Variant Image Seeder',seederName: 'ProductVariantImageSeeder'},
                   { id: 5, title: 'Custom Banner Seeder',seederName: 'CustomBannerSeeder'},
                   { id: 6, title: 'CMS Page Seeder',seederName: 'CmsPageSeeder'}
               ];
               $('.seed').show();
           }
           else if (category == 3) {
               seeders = [
                   { id: 1, title: 'Product Category Seeder', seederName: 'ProductCategorySeeder'},
                   { id: 2, title: 'Product Attribute, Attribute Value Seeder',seederName: 'ProductAttributeValueSeeder'},
                   { id: 3, title: 'Product Seeder, Product Variant Attribute Seeder, Inventory Seeder',seederName: 'ProductSeeder'},
                   { id: 4, title: 'Product Variant Image Seeder',seederName: 'ProductVariantImageSeeder'},
                   { id: 5, title: 'Custom Banner Seeder',seederName: 'CustomBannerSeeder'},
                   { id: 6, title: 'CMS Page Seeder',seederName: 'CmsPageSeeder'}
               ];
               $('.seed').show();
           }
           else if (category == 4) {
               seeders = [
                   { id: 1, title: 'Product Category Seeder', seederName: 'ProductCategorySeeder'},
                   { id: 2, title: 'Product Attribute, Attribute Value Seeder',seederName: 'ProductAttributeValueSeeder'},
                   { id: 3, title: 'Product Seeder, Product Variant Attribute Seeder, Inventory Seeder',seederName: 'ProductSeeder'},
                   { id: 4, title: 'Product Variant Image Seeder',seederName: 'ProductVariantImageSeeder'},
                   { id: 5, title: 'Custom Banner Seeder',seederName: 'CustomBannerSeeder'},
                   { id: 6, title: 'CMS Page Seeder',seederName: 'CmsPageSeeder'}
               ];
               $('.seed').show();
           }
           else {
               $('.seed').hide();
           }
       });

       $('.seeding-table').off('click').on('click', function(e) {
           e.preventDefault();

           if (!category) {
               // alert('Please select a category first.');
               $('#modulename-error-container').show().html('<span class="text-danger">Please select a Seeder first.</span>');
               return;
           }

           swalConfirm("Are you sure?", "You won't be able to revert this!").then((result) => {
               if (result.isConfirmed) {
                   $('#seeder-status-table').empty(); // clear table before starting

                   seeders.forEach((seeder) => {
                       $('#seeder-status-table').append(`
                           <tr id="seeder-row-${seeder.id}">
                               <td>${seeder.id}</td>
                               <td>${seeder.title}</td>
                               <td><span class="badge bg-warning" id="status-${seeder.id}">Pending</span></td>
                           </tr>
                       `);
                   });

                   runSeeding(0); // start seeding from first
                   $('.seed-data').show(); // show seed data
               }
           });
       });

       function runSeeding(index) {
           if (index >= seeders.length) {
               let allSeedingDone = true;
               $('#seeder-status-table tr').each(function() {
                   if ($(this).find('td').eq(2).text().trim() != 'Done') {
                       allSeedingDone = false;
                       return false;
                   }
               });
               if (allSeedingDone) {
                   $('.vist-frontend').show();
               }
               return; // All done
           }
           let seeder = seeders[index];
           // Update status to Seeding
           $(`#status-${seeder.id}`).removeClass('bg-warning bg-success').addClass('bg-primary').text('Seeding...');
           // Ajax call to backend for each seeder
           setTimeout(() => {
             $.ajax({
                 url: `{{ route('admin.truncate-and-seed') }}`,
                 method: 'POST',
                 data: {
                     category: category,
                     title: seeder.title,
                     seeder_name: seeder.seederName,
                     _token: '{{ csrf_token() }}'
                 },
                 success: function(response) {
                     $(`#status-${seeder.id}`).removeClass('bg-primary').addClass('bg-success').text('Done');
                     runSeeding(index + 1); // seed next
                 },
                 error: function(error) {
                     $(`#status-${seeder.id}`).removeClass('bg-primary').addClass('bg-danger').text('Failed');
                     console.log(error.responseJSON.message);
                     swalNotify("Error!", error.responseJSON.message, "error");
                 }
             });
           }, 1000);
       }
   });
</script>
@endsection
