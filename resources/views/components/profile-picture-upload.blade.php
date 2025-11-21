<style>
  .component-img-container img {
    max-width: 100%;
    max-height: 100%;
    display: block;
    margin: 0 auto;
  }
</style>
<div class="profile-pic">
  <label class="-label" for="file">
    <span><i class="mdi mdi-camera"></i></span>
  </label>
  <input id="file" type="file" accept="image/*" style="display:none;" />
  <button type="button" class="close" id="remove-pic"><i class="mdi mdi-close"></i></button>
  <img id="cropped-image" src="{{ $imageLink }}" class="rounded-circle avatar-lg img-thumbnail" />
</div>

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


<script>
  document.addEventListener('DOMContentLoaded', function() {
    let imagelink = "{{ $imageLink }}";
    //Check valid url
    const isAbsoluteUrl = /^https?:\/\//.test(imagelink);
    document.getElementById('remove-pic').style.display = isAbsoluteUrl ? '' : 'none';
    let cropper;
    const fileInput = document.getElementById('file');
    const cropButton = document.getElementById('crop-button');
    const croppedImage = document.getElementById('cropped-image');
    const modalElement = document.getElementById('cropper-modal');
    const imageElement = document.getElementById('image');

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
      cropper = new Cropper(imageElement, {
        aspectRatio: 1,
        viewMode: 1,
        ready: function() {
          cropper.setCropBoxData({
            width: 600,
            height: 600
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
      if (!cropper) return;

      const canvas = cropper.getCroppedCanvas({
        width: 600,
        height: 600
      });

      // Convert the canvas to a Blob and check its size
      canvas.toBlob((blob) => {
        if (blob.size > 2 * 1024 * 1024) { // 2MB in bytes
          swalNotify("Error!",
            "The cropped image size exceeds 2MB. Please crop a smaller area or reduce the image resolution.",
            "error");
          return;
        }

        croppedImage.src = canvas.toDataURL();
        modal.hide();
        const formData = new FormData();
        formData.append('image', blob, 'profile.jpg');
        formData.append('_token', "{{ csrf_token() }}");

        fetch("{{ $route }}", {
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
              document.getElementById('admin-avatar').src = data.path;
              $('#remove-pic').show();
              swalNotify("Success!", data.message, "success");
            } else {
              swalNotify("Oops!", data.message, "error");
            }
          })
          .catch((error) => {
            console.error(error);
            swalNotify("Error!", error.responseJSON.message, "error");
          });
      });
    });
  });
</script>
@push('component-scripts')
  <script>
    $('#remove-pic').click(function() {
      swalConfirm("Do you want to remove your profile picture?", "You won't be able to revert this!",
        "warning").then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: "POST",
            url: '{{ route('admin.delete_profile_picture') }}',
            data: {
              _token: "{{ csrf_token() }}"
            },
            success: function(response) {
              if (response.success) {
                $('#cropped-image').attr('src', '{{ $imageLink }}');
                $('#admin-avatar').attr('src', '{{ $imageLink }}');
                $('#remove-pic').hide();
                swalNotify("Success!", response.message, "success");
              } else {
                swalNotify("Oops!", response.message, "error");
              }
            },
            error: function(error) {
              console.error(error);
              swalNotify("Error!", error.responseJSON.message, "error");
            }
          });
        }
      });
    })
  </script>
@endpush
