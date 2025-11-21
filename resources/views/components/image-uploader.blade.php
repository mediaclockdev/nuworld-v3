@push('component-styles')
  <link rel="stylesheet" href="{{ asset('/public/backend/assetss/cropper/cropper.min.css') }}" />
  <script src="{{ asset('/public/backend/assetss/cropper/cropper.min.js') }}"></script>
@endpush

<div class="site-upload-pic">
  <label class="-label" for="file">
    <span><i class="mdi mdi-camera"></i></span>
  </label>
  <input id="file" type="file" accept="image/jpeg, image/png, image/webp" data-route="{{ $route }}"
    style="display:none;" />
  <img id="cropped-image" src="{{ $imageLink }}" class="" />
</div>
<span class="text-muted" style="font-size: 12px;">Only JPG, PNG & WebP (Max 2MB size) are allowed. </span>
<div id="cropper-modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="standard-modalLabel">Upload Image</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <div class="component-img-container">
          <img id="image" src="" alt="Picture" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="crop-button">Crop</button>
      </div>
    </div>
  </div>
</div>
@push('component-scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      let cropper;
      const fileInput = document.getElementById('file');
      const cropButton = document.getElementById('crop-button');
      const croppedImage = document.getElementById('cropped-image');
      const modalElement = document.getElementById('cropper-modal');
      const imageElement = document.getElementById('image');

      if (!fileInput || !cropButton || !croppedImage || !modalElement || !imageElement) {
        console.error("One or more required DOM elements are missing.");
        return;
      }

      const modal = new bootstrap.Modal(modalElement);

      fileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
          const imageURL = URL.createObjectURL(file);
          imageElement.src = imageURL;

          imageElement.onload = () => {
            modal.show();
          };
        }
      });

      modalElement.addEventListener('shown.bs.modal', function() {
        if (cropper) {
          cropper.destroy();
        }

        // Get resolution from Laravel Blade (default: 600x600)
        const targetWidth = {{ $resolution[0] ?? 600 }};
        const targetHeight = {{ $resolution[1] ?? 600 }};
        const aspectRatio = targetWidth / targetHeight;

        cropper = new Cropper(imageElement, {
          aspectRatio: (targetWidth && targetHeight) ? aspectRatio :
          NaN, // Maintain aspect ratio if resolution is provided
          viewMode: 1,
          autoCropArea: 0.8, // Crop box takes 80% of the image initially
          responsive: true,
          ready: function() {
            cropper.setCropBoxData({
              width: targetWidth,
              height: targetHeight
            });
          },
        });
      });

      modalElement.addEventListener('hidden.bs.modal', function() {
        if (cropper) {
          cropper.destroy();
          cropper = null;
        }
      });

      cropButton.addEventListener('click', function() {
        if (!cropper) {
          console.error("Cropper is not initialized.");
          return;
        }

        // Get the cropped canvas with the correct resolution
        const targetWidth = {{ $resolution[0] ?? 600 }};
        const targetHeight = {{ $resolution[1] ?? 600 }};
        const croppedCanvas = cropper.getCroppedCanvas({
          width: (targetWidth && targetHeight) ? targetWidth : undefined,
          height: (targetWidth && targetHeight) ? targetHeight : undefined,
        });

        if (!croppedCanvas) {
          console.error("Failed to get cropped canvas.");
          return;
        }

        croppedCanvas.toBlob((blob) => {
          if (!blob) {
            console.error("Failed to create blob from canvas.");
            return;
          }

          // Get max size in KB (default: 2048KB / 2MB)
          const maxSizeKB = {{ $max_size ?? 2048 }};
          if (blob.size > maxSizeKB * 1024) {
            swalNotify("Error!",
              `The cropped image size exceeds ${maxSizeKB} KB. Please crop a smaller area or reduce the resolution.`,
              "error");
            return;
          }

          croppedImage.src = croppedCanvas.toDataURL();
          modal.hide();

          const formData = new FormData();
          formData.append('image', blob, 'croppedImage.jpg');
          formData.append('_token', "{{ csrf_token() }}");

          const route = fileInput.dataset.route;
          if (!route) {
            console.error("Route is not defined.");
            return;
          }

          fetch(route, {
              method: 'POST',
              body: formData,
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
              },
            })
            .then((response) => response.json())
            .then((data) => {
              if (data.success) {
                croppedImage.src = data.path;
                document.getElementById('cropped-image').src = data.path;
                swalNotify("Success!", data.message, "success");
                document.getElementById('header-logo').src = data.path;
              } else {
                swalNotify("Oops!", data.message, "error");
              }
            })
            .catch((error) => {
              console.error(error);
              swalNotify("Error!", error.message || "An unexpected error occurred.", "error");
            });
        });
      });
    });
  </script>
@endpush
