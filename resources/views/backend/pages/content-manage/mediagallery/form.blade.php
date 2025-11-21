@push('component-styles')
  <style>
    .media-card.selected {
      outline: 2px solid #0d6efd;
    }

    .select-overlay {
      border-radius: 0.375rem;
      z-index: 1;
    }

    .media-card {
      transition: box-shadow 0.2s ease-in-out;
    }

    .media-card:hover {
      box-shadow: 0 0 0 2px #0d6efd33;
    }
  </style>
@endpush
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5>Media Library</h5>
  <button class="btn btn-danger d-none" id="deleteSelectedBtn">Delete Selected</button>
</div>

<div class="gallery row g-3" id="mediaGallery">
  @forelse ($mediaItems as $item)
    <div class="col-6 col-md-3 col-lg-2 media-item position-relative" data-id="{{ Hashids::encode($item->id) }}">
      <div class="card shadow-sm media-card border rounded p-1" style="cursor: pointer;">
        <div class="select-overlay position-absolute top-0 start-0 w-100 h-100 bg-primary bg-opacity-25 d-none"></div>

        @if (str_starts_with($item->file_type, 'image'))
          <img src="{{ asset('public/storage/uploads/media/images/' . $item->file_name) }}" alt="{{ $item->title }}"
            title="{{ $item->title }}" class="card-img-top thumbnail">
        @elseif (str_starts_with($item->file_type, 'video'))
          <video class="card-img-top thumbnail">
            <source src="{{ asset('public/storage/uploads/media/videos/' . $item->file_name) }}"
              type="{{ $item->file_type }}" title="{{ $item->title }}">
          </video>
        @endif

        <div class="card-body py-2 px-2 text-truncate small">
          {{ $item->title ?? 'Untitled' }}
        </div>
      </div>
    </div>
  @empty
    <h2 class="text-center text-muted">No media items found</h2>
  @endforelse
</div>

<script>
  $(function() {
    const selected = new Set();

    function toggleSelection(itemEl) {
      const $item = $(itemEl);
      const id = $item.data('id');
      const card = $item.find('.media-card');
      const overlay = $item.find('.select-overlay');

      if (selected.has(id)) {
        selected.delete(id);
        card.removeClass('selected');
        overlay.addClass('d-none');
      } else {
        selected.add(id);
        card.addClass('selected');
        overlay.removeClass('d-none');
      }

      $('#deleteSelectedBtn').toggleClass('d-none', selected.size === 0);
    }


    $('#deleteSelectedBtn').toggleClass('d-none', selected.size === 0);

    $('#mediaGallery').on('click', '.media-item', function(e) {
      console.log('Clicked item');
      if ($(e.target).is('video, source, button, a')) return;
      toggleSelection(this);
    });


    $('#deleteSelectedBtn').on('click', function() {
      if (selected.size === 0) return;

      if (!confirm('Are you sure you want to delete selected media items?')) return;

      $.ajax({
        url: "{{ route('admin.media-gallery.delete.multiple') }}",
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          ids: Array.from(selected)
        },
        success: function(res) {
          selected.forEach(id => {
            $(`.media-item[data-id="${id}"]`).remove();
          });
          selected.clear();
          $('#deleteSelectedBtn').addClass('d-none');
          swalNotify("Deleted!", "Selected media items were deleted.", "success");
        },
        error: function(err) {
          swalNotify("Error!", "Failed to delete selected items.", "error");
        }
      });
    });
  });
</script>
