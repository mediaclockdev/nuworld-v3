  @foreach ($items as $item)
    <li class="dd-item" data-id="{{ $item->id }}" data-name="{{ $item->title }}" data-slug="{{ $item->slug }}"
      data-new="0" data-deleted="0">
      <div class="dd-handle">{{ $item->title }}</div>
      <span class="button-edit btn btn-success btn-sm" data-owner-id="{{ $item->id }}">
        <i class="ri-pencil-line"></i>
      </span>
      <span class="button-delete btn btn-danger btn-sm" data-owner-id="{{ $item->id }}">
        <i class="ri-delete-bin-line"></i>
      </span>
      @if ($item->children->count())
        <ol class="dd-list">
          @include('backend.pages.content-manage.menu-builder.menu-item', ['items' => $item->children])
        </ol>
      @endif
    </li>
  @endforeach
