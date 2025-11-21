@extends('backend.layouts.app')
@section('page-styles')
  <style>
    .thumbnail,
    .avatar-sm {
      max-width: 150px;
      max-height: 150px;
      object-fit: cover;
    }
  </style>
  <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[] />
  <div class="row min-VH">
    <div class="col-xl-12 col-lg-12">
      <div class="card card-h-100">
        <div class="d-flex card-header justify-content-between align-items-center">
          <h4 class="header-title mb-0">{{ $cardHeader }} </h4>
        </div>
        <div class="card-body">
          <ul class="nav nav-pills bg-nav-pills mb-3">
            <li class="nav-item">
              <a href="#upload-b1" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                <i class="mdi mdi-upload-variant d-md-none d-block"></i>
                <span class="d-none d-md-block">Upload Media</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#gallery-b1" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                <i class="mdi mdi-account-circle d-md-none d-block"></i>
                <span class="d-none d-md-block">Gallery</span>
              </a>
            </li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane" id="upload-b1">
              <div class="container mt-5" id="upload-container">
                <form method="post" class="dropzone" id="myAwesomeDropzone" data-plugin="dropzone"
                  data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
                  @csrf
                  <div class="fallback">
                    <input name="files[]" type="file" multiple />
                  </div>
                  <div class="dz-message needsclick">
                    <i class="h1 text-muted ri-upload-cloud-2-line"></i>
                    <h3>Drop files here or click to upload.</h3>
                    <span class="text-muted font-13">Max: 2 MB for images, 10 MB for videos.</span>
                  </div>
                </form>

                <button id="uploadBtn" class="btn btn-primary mt-3" disabled>
                  <span class="spinner-border spinner-border-sm d-none me-1" role="status" id="uploadSpinner"></span>
                  Upload
                </button>

                <div class="dropzone-previews d-flex flex-wrap gap-3 mt-3" id="file-previews"></div>

                <div class="d-none" id="uploadPreviewTemplate">
                  <div class="card mt-1 mb-0 shadow-none border">
                    <div class="p-2">
                      <div class="row align-items-center">
                        <div class="col-auto">
                          <img data-dz-thumbnail src="#" class="avatar-sm rounded bg-light" alt="">
                        </div>
                        <div class="col ps-0">
                          <a href="javascript:void(0);" class="text-muted fw-bold" data-dz-name></a>
                          <p class="mb-0" data-dz-size></p>
                        </div>
                        <div class="col-auto">
                          <!-- Button -->
                          <a href="" class="btn btn-link btn-lg text-muted" data-dz-remove>
                            <i class="ri-close-line"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane show active" id="gallery-b1">
              <p>
              <div class="container mt-5" id="gallery-container">

              </div>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('page-scripts')
  <script src="{{ asset('public/backend/assetss/js/dropzone.min.js') }}"></script>
  <script>
    (function($) {
      refreshGallery();
      "use strict";

      Dropzone.autoDiscover = false;

      const myDropzone = new Dropzone("#myAwesomeDropzone", {
        url: '{{ route('admin.media-gallery.store') }}',
        paramName: "files",
        uploadMultiple: true,
        parallelUploads: 10,
        acceptedFiles: 'image/jpeg,image/png,image/gif,image/webp,video/mp4',
        maxFilesize: 10,
        previewTemplate: $('#uploadPreviewTemplate').html(),
        previewsContainer: '#file-previews',
        autoProcessQueue: false,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        accept: function(file, done) {
          const fileSizeMB = file.size / (1024 * 1024);
          if (file.type.startsWith('image/') && fileSizeMB > 2) {
            swalNotify("Too Large!", "Image exceeds 2 MB limit.", "error");
            this.removeFile(file);
            done("Image exceeds 2 MB.");
          } else if (file.type.startsWith('video/') && fileSizeMB > 10) {
            swalNotify("Too Large!", "Video exceeds 10 MB limit.", "error");
            this.removeFile(file);
            done("Video exceeds 10 MB.");
          } else {
            done();
          }
        },
        init: function() {
          const dz = this;

          this.on('addedfile', function() {
            $('#uploadBtn').prop('disabled', false);
          });

          this.on('removedfile', function() {
            if (dz.getQueuedFiles().length === 0) {
              $('#uploadBtn').prop('disabled', true);
            }
          });

          $('#uploadBtn').on('click', function() {
            if (dz.getQueuedFiles().length > 0) {
              $('#uploadSpinner').removeClass('d-none'); // Show spinner
              $(this).prop('disabled', true);
              dz.processQueue();
            }
          });

          this.on('successmultiple', function(files, response) {
            swalNotify("Success!", response.message || 'Files uploaded successfully.', "success");
            dz.removeAllFiles();
            $('#uploadBtn').prop('disabled', true);
            $('#uploadSpinner').addClass('d-none'); // Hide spinner
          });

          this.on('errormultiple', function(files, response) {
            let message = 'Upload failed.';
            if (typeof response === 'string') {
              message = response;
            } else if (response.message) {
              message = response.message;
            }
            this.removeAllFiles(true);
            $('#uploadBtn').prop('disabled', true);
            $('#uploadSpinner').addClass('d-none'); // Hide spinner
            swalNotify("Error!", message, "error");
          });
        }
      });
    })(jQuery);

    function refreshGallery() {
      $.ajax({
        url: '{{ route('admin.media-gallery.create') }}',
        type: 'GET',
        success: function(response) {
          $('#gallery-container').html(response);
        }
      });
    }

    $('a[data-bs-toggle="tab"][href="#gallery-b1"]').on('shown.bs.tab', function() {
      refreshGallery();
    });
  </script>
@endsection
