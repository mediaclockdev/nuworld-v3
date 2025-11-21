@extends('backend.layouts.app')
@section('page-styles')

@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[] />

  <div class="container mt-4">
    <!-- Card for Product Categories -->
    <div class="card mb-4">
      <div class="card-header bg-warning text-white">
        <h5 class="mb-0">Import Product Categories</h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <a href="{{ route('admin.csv-template') }}" class="btn btn-outline-primary">
            Download Category CSV Template
          </a>
        </div>
        <form id="uploadForm" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <input type="file" name="csv_file" accept=".csv" required class="form-control">
          </div>
          <button type="submit" class="btn btn-success">Upload Category CSV</button>
          <p class="mt-2 text-muted"><small style="color:red">Note: Upload a valid CSV file with correct attribute
              structure.</small></p>
        </form>
      </div>
    </div>

    <!-- Card for Product Attributes -->
    <div class="card">
      <div class="card-header bg-info text-white">
        <h5 class="mb-0">Import Product Attributes</h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <a href="{{ route('admin.product-attributes-csv-template') }}" class="btn btn-outline-info">
            Download Attribute CSV Template
          </a>
        </div>
        <form id="uploadAttrForm" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <input type="file" name="csv_attr_file" accept=".csv" required class="form-control">
          </div>
          <button type="submit" class="btn btn-success">Upload Attribute CSV</button>
          <p class="mt-2 text-muted"><small style="color:red">Note: Upload a valid CSV file with correct attribute
              structure.</small></p>
        </form>
      </div>
    </div>

    <!-- Card for Product Attributes -->
    <div class="card">
      <div class="card-header bg-success text-white">
        <h5 class="mb-0">Import Products</h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <a href="{{ route('admin.products-csv-template') }}" class="btn btn-outline-info">
            Download Product CSV Template
          </a>
        </div>
        <form id="uploadProductForm" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <input type="file" name="csv_products_file" accept=".csv" required class="form-control">
          </div>
          <button type="submit" class="btn btn-success">Upload Product CSV</button>
          <p class="mt-2 text-muted"><small style="color:red">Note: Upload a valid CSV file with correct attribute
              structure.</small></p>
        </form>
      </div>
    </div>

    {{-- Import Pincode --}}
    <div class="card">
      <div class="card-header bg-Secondary text-white">
        <h5 class="mb-0">Import Pincode</h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <a href="{{ route('admin.pincodes-csv-template') }}" class="btn btn-outline-info">
            Download Pincode CSV Template
          </a>
        </div>
        <form id="uploadPincodeForm" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <input type="file" name="csv_pincodes_file" accept=".csv" required class="form-control">
          </div>
          <button type="submit" class="btn btn-success">Upload Pincode CSV</button>
          <p class="mt-2 text-muted"><small style="color:red">Note: Upload a valid CSV file with correct attribute
              structure.</small></p>
        </form>
      </div>
    </div>
    {{-- Import Pincode --}}

  </div>
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>

  <script>
    // Optional: if you want to manually define the extension method
    $.validator.addMethod("extension", function(value, element, param) {
      param = typeof param === "string" ? param.replace(/,/g, '|') : "csv";
      return this.optional(element) || value.match(new RegExp("\\.(" + param + ")$", "i"));
    }, "Please enter a file with a valid extension.");

    $(document).ready(function() {

      // Add custom file size validation method
      $.validator.addMethod("filesize", function(value, element, param) {
        if (element.files.length === 0) return true; // no file selected yet
        return element.files[0].size <= param;
      }, "File size must be less than {0} bytes.");

      $('#uploadForm').validate({
        rules: {
          csv_file: {
            required: true,
            extension: "csv",
            filesize: 5 * 1024 * 1024 // 5 MB in bytes
          }
        },
        messages: {
          csv_file: {
            required: "Please select a CSV file.",
            extension: "Only .csv files are allowed.",
            filesize: "File size must be less than 5 MB."
          }
        },
        submitHandler: function(form) {
          importCsv();
        }
      });

      function importCsv() {
        var formData = new FormData($('#uploadForm')[0]);

        $.ajax({
          url: "{{ route('admin.csv-import') }}",
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          beforeSend: function() {
            Swal.fire({
              title: 'Uploading...',
              text: 'Please wait while the CSV is being imported.',
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading()
              }
            });
          },
          success: function(response) {
            Swal.fire({
              icon: 'success',
              title: 'Import Successful',
              text: response.msg ?? 'CSV file uploaded and processed successfully!',
            });
            $('#uploadForm')[0].reset();
          },
          error: function(xhr) {
            let errorList = 'Something went wrong.';

            if (xhr.responseJSON && xhr.responseJSON.errors) {
              const errors = xhr.responseJSON.errors;
              errorList = '<ul style="text-align: left;">';
              errors.forEach(function(error) {
                errorList += `<li>${error}</li>`;
              });
              errorList += '</ul>';
            }

            Swal.fire({
              icon: 'error',
              title: 'Upload Failed',
              html: errorList // Use 'html' instead of 'text' for formatted list
            });
          }
        });
      }

    });
    // attributes csv import

    $(document).ready(function() {

      // Add custom file size validation method
      $.validator.addMethod("filesize", function(value, element, param) {
        if (element.files.length === 0) return true; // no file selected yet
        return element.files[0].size <= param;
      }, "File size must be less than {0} bytes.");

      $('#uploadAttrForm').validate({
        rules: {
          csv_attr_file: {
            required: true,
            extension: "csv",
            filesize: 5 * 1024 * 1024 // 5 MB in bytes
          }
        },
        messages: {
          csv_attr_file: {
            required: "Please select a CSV file.",
            extension: "Only .csv files are allowed.",
            filesize: "File size must be less than 5 MB."
          }
        },
        submitHandler: function(form) {
          importAttrCsv();
        }
      });

      function importAttrCsv() {
        var formData = new FormData($('#uploadAttrForm')[0]);

        $.ajax({
          url: "{{ route('admin.product-attributes-csv-import') }}",
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          beforeSend: function() {
            Swal.fire({
              title: 'Uploading...',
              text: 'Please wait while the CSV is being imported.',
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading()
              }
            });
          },
          success: function(response) {
            Swal.fire({
              icon: 'success',
              title: 'Import Successful',
              text: response.msg ?? 'CSV file uploaded and processed successfully!',
            });
            $('#uploadAttrForm')[0].reset();
          },
          error: function(xhr) {
            let errorList = 'Something went wrong.';

            if (xhr.responseJSON && xhr.responseJSON.errors) {
              const errors = xhr.responseJSON.errors;
              errorList = '<ul style="text-align: left;">';
              errors.forEach(function(error) {
                errorList += `<li>${error}</li>`;
              });
              errorList += '</ul>';
            }

            Swal.fire({
              icon: 'error',
              title: 'Upload Failed',
              html: errorList // Use 'html' instead of 'text' for formatted list
            });
          }
        });
      }

    });

    $(document).ready(function() {

      // Add custom file size validation method
      $.validator.addMethod("filesize", function(value, element, param) {
        if (element.files.length === 0) return true; // no file selected yet
        return element.files[0].size <= param;
      }, "File size must be less than {0} bytes.");

      $('#uploadProductForm').validate({
        rules: {
          csv_products_file: {
            required: true,
            extension: "csv",
            filesize: 5 * 1024 * 1024 // 5 MB in bytes
          }
        },
        messages: {
          csv_products_file: {
            required: "Please select a CSV file.",
            extension: "Only .csv files are allowed.",
            filesize: "File size must be less than 5 MB."
          }
        },
        submitHandler: function(form) {
          importProductsCsv();
        }
      });

      function importProductsCsv() {
        var formData = new FormData($('#uploadProductForm')[0]);

        $.ajax({
          url: "{{ route('admin.products-csv-import') }}",
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          beforeSend: function() {
            Swal.fire({
              title: 'Uploading...',
              text: 'Please wait while the CSV is being imported.',
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading()
              }
            });
          },
          success: function(response) {
            Swal.fire({
              icon: 'success',
              title: 'Import Successful',
              text: response.msg ?? 'CSV file uploaded and processed successfully!',
            });
            $('#uploadProductForm')[0].reset();
          },
          error: function(xhr) {
            let errorList = 'Something went wrong.';

            if (xhr.responseJSON && xhr.responseJSON.errors) {
              const errors = xhr.responseJSON.errors;
              errorList = '<ul style="text-align: left;">';
              errors.forEach(function(error) {
                errorList += `<li>${error}</li>`;
              });
              errorList += '</ul>';
            }

            Swal.fire({
              icon: 'error',
              title: 'Upload Failed',
              html: errorList // Use 'html' instead of 'text' for formatted list
            });
          }
        });
      }

    });

    // Import pincode

    $(document).ready(function() {

      // Add custom file size validation method
      $.validator.addMethod("filesize", function(value, element, param) {
        if (element.files.length === 0) return true; // no file selected yet
        return element.files[0].size <= param;
      }, "File size must be less than {0} bytes.");

      $('#uploadPincodeForm').validate({
        rules: {
          csv_pincodes_file: {
            required: true,
            extension: "csv",
            filesize: 5 * 1024 * 1024 // 5 MB in bytes
          }
        },
        messages: {
          csv_pincodes_file: {
            required: "Please select a CSV file.",
            extension: "Only .csv files are allowed.",
            filesize: "File size must be less than 5 MB."
          }
        },
        submitHandler: function(form) {
          importPincodesCsv();
        }
      });

      function importPincodesCsv() {
        var formData = new FormData($('#uploadPincodeForm')[0]);

        $.ajax({
          url: "{{ route('admin.pincodes-csv-import') }}",
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          beforeSend: function() {
            Swal.fire({
              title: 'Uploading...',
              text: 'Please wait while the CSV is being imported.',
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading()
              }
            });
          },
          success: function(response) {
            Swal.fire({
              icon: 'success',
              title: 'Import Successful',
              text: response.msg ?? 'CSV file uploaded and processed successfully!',
            });
            $('#uploadPincodeForm')[0].reset();
          },
          error: function(xhr) {
            let errorList = 'Something went wrong.';

            if (xhr.responseJSON && xhr.responseJSON.errors) {
              const errors = xhr.responseJSON.errors;
              errorList = '<ul style="text-align: left;">';
              errors.forEach(function(error) {
                errorList += `<li>${error}</li>`;
              });
              errorList += '</ul>';
            }

            Swal.fire({
              icon: 'error',
              title: 'Upload Failed',
              html: errorList // Use 'html' instead of 'text' for formatted list
            });
          }
        });
      }

    });
  </script>
@endsection
