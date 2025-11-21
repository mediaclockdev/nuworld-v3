@extends('backend.layouts.app')
@section('page-styles')
  <link rel="stylesheet" href="{{ asset('/public/common/css/nestable.min.css') }}">
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[] />
  <x-basic-card :cardHeader="$cardHeader">
    <div class="row g-4">
      <div class="col-lg-4 col-md-12">
        <form class="mb-4" id="menu-add">
          <h3 class="text-xl font-semibold mb-3">Add Menu</h3>
          <div class="row mb-3">
            <div class="col-6">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="radioDefault" id="radioCustom" checked>
                <label class="form-check-label" for="radioCustom">
                  Custom
                </label>
              </div>

            </div>
            <div class="col-6">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="radioDefault" id="radioCategory">
                <label class="form-check-label" for="radioCategory">
                  Category
                </label>
              </div>
            </div>
          </div>
          <div id="addCustomContainer">
            <div class="mb-3 required">
              <label for="addInputName" class="form-label">Name</label>
              <input type="text" class="form-control" id="addInputName" placeholder="Item name">
            </div>
            <div class="mb-3 required">
              <label for="addInputSlug" class="form-label">Link</label>
              <input type="text" class="form-control" id="addInputSlug" placeholder="item-link">
            </div>
          </div>
          <div class="mb-3" id="addCategoryContainer" style="display: none;">
            <label for="parent_id" class="form-label">Parent Category</label>
            <select name="parent_category" id="parent_category" class="form-select select2">
              <option value="">None </option>
              {!! renderCategoryOptions($categories, 0) !!}
            </select>
            <div id="parent_id-error-container"></div>
          </div>
          <button type="submit" class="btn btn-primary">
            Add Item
          </button>
        </form>
        <form id="menu-editor" style="display: none;">
          <h3 class="text-xl font-semibold mb-3">Editing <span id="currentEditName"></span></h3>
          <div class="mb-3 required">
            <label for="editInputName" class="form-label">Name</label>
            <input type="text" class="form-control" id="editInputName" placeholder="Item name" required>
          </div>
          <div class="mb-3 required">
            <label for="editInputSlug" class="form-label">Link</label>
            <input type="text" class="form-control " id="editInputSlug" placeholder="item-link">
          </div>
          <button type="submit" class="btn btn-info">Save</button>
          <button type="button" class="btn btn-secondary" id="cancelEdit">Cancel</button>
        </form>
      </div>
      <div class="col-lg-8 col-md-12">
        <h3 class="text-xl font-semibold mb-3">Menu</h3>
        <div class="loading">Loading menu...</div>
        <div class="cf nestable-lists">
          <div class="dd" id="nestable">
            <ol class="dd-list"></ol>
          </div>
        </div>
        <div class="output-container">
          <textarea id="nestable-output" class="form-control" style="display: none"></textarea>
          <h4>Only Top 7 Menus will be displayed.</h4>
          <button id="save-menu" class="btn btn-success">Save Menu</button>
        </div>
      </div>
    </div>
  </x-basic-card>
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/common/js/custom_input.js?v=' . time()) }}"></script>
  <script src="{{ asset('/public/common/js/nestable.min.js?v=' . time()) }}"></script>
  <script>
    $(() => {
      const $nestable = $('#nestable');
      const $output = $('#nestable-output');
      const $menuAdd = $('#menu-add');
      const $menuEditor = $('#menu-editor');
      const $editInputName = $('#editInputName');
      const $editInputSlug = $('#editInputSlug');
      const $currentEditName = $('#currentEditName');
      const $loading = $('.loading');
      let newIdCount = 1;
      let categoryList = {!! $categories !!};

      $('input[name="radioDefault"]').on('click', function() {
        const isCategory = this.id === 'radioCategory';
        $('#addCategoryContainer').toggle(isCategory);
        $('#addCustomContainer').toggle(!isCategory);
        $('#addInputName, #addInputSlug').prop('disabled', isCategory);
      });

      const updateOutput = () => window.JSON ?
        $output.val(JSON.stringify($nestable.nestable('serialize'))) :
        swalNotify('Error', 'JSON not supported', 'error');

      const initNestable = () => {
        $nestable.nestable({
          maxDepth: 3
        }).on('change', updateOutput);
        $nestable.off('click', '.button-delete, .button-edit')
          .on('click', '.button-delete', e => deleteItem($(e.currentTarget).data('owner-id')))
          .on('click', '.button-edit', e => prepareEdit($(e.currentTarget).data('owner-id')));
        updateOutput();
      };

      const loadMenuItems = () => $.get("{{ route('admin.menu-builder.items') }}")
        .done(response => {
          $('#nestable > .dd-list').html(response);
          $loading.hide();
          initNestable();
        })
        .fail(xhr => {
          console.error('Load failed:', xhr.responseText);
          swalNotify('Error', 'Failed to load menu items', 'error');
          $loading.hide();
        });

      const deleteItem = ownerId => {
        const $target = $nestable.find(`[data-id="${ownerId}"]`);
        if (!$target.length) return console.error('Target not found:', ownerId);
        swalConfirm("Are you sure?", "This will delete sub-menu items too").then(
          result => result.isConfirmed && $target.fadeOut(() => $target.remove() && updateOutput())
        );
      };

      const prepareEdit = ownerId => {
        const $target = $nestable.find(`[data-id="${ownerId}"]`);
        $menuAdd.hide();
        $editInputName.val($target.data('name'));
        $editInputSlug.val($target.data('slug'));
        $currentEditName.text($target.data('name'));
        $menuEditor.data('owner-id', ownerId).fadeIn();
      };

      const editItem = e => {
        e.preventDefault();
        const $target = $nestable.find(`[data-id="${$menuEditor.data('owner-id')}"]`);
        $target.data({
            name: $editInputName.val(),
            slug: $editInputSlug.val()
          })
          .find('> .dd-handle').text($editInputName.val());
        $menuEditor.hide();
        $menuAdd.show();
        updateOutput();
        swalNotify('Success', 'Menu item updated', 'success');
      };

      const addItem = e => {
        e.preventDefault();
        const isCustom = $('#radioCustom').is(':checked');
        $('#addInputName, #addInputSlug').prop('disabled', !isCustom);

        const topLevelItems = $nestable.find('.dd-list').first().children('.dd-item').length;
        if (topLevelItems >= 7) {
          swalNotify('Error', 'Only 7 top-level Menus will appear', 'error');
        }
        let newName, newSlug;
        if (isCustom) {
          newName = $('#addInputName').val().trim();
          newSlug = $('#addInputSlug').val().trim();
          if (!newName || !newSlug) return swalNotify('Error', 'Name and Link is required', 'error');
          if (isDuplicate(newName, newSlug)) return swalNotify('Error', 'Name and Slug must be unique', 'error');
        } else {
          const $selected = $('#parent_category option:selected');
          newName = cleanText($selected.text());
          newSlug = $selected.data('slug');
          if (!newSlug) return swalNotify('Error', 'Select a category', 'error');
          if (isDuplicate(newName, newSlug)) return swalNotify('Error', 'Name and Slug must be unique', 'error');
        }

        const findCategoryBySlug = (slug, categories) =>
          categories.reduce((found, cat) =>
            found || (cat.slug === slug ? cat : cat.children ? findCategoryBySlug(slug, cat.children) : null),
            null
          );

        const generateCategoryHtml = (category, isNew = true) => {
          const newId = `new-${newIdCount++}`;
          let html = `
            <li class="dd-item" data-id="${newId}" data-name="${category.title}" data-slug="${category.slug}" data-new="${isNew ? 1 : 0}" data-deleted="0">
              <div class="dd-handle">${category.title}</div>
              <span class="button-edit btn btn-success btn-sm" data-owner-id="${newId}"><i class="ri-pencil-line"></i></span>
              <span class="button-delete btn btn-danger btn-sm" data-owner-id="${newId}"><i class="ri-delete-bin-line"></i></span>
          `;
          if (category.children?.length) {
            html += '<ol class="dd-list">' + category.children.map(child => generateCategoryHtml(child, isNew))
              .join('') + '</ol>';
          }
          return html + '</li>';
        };

        const category = isCustom ? {
          title: newName,
          slug: newSlug,
          children: []
        } : findCategoryBySlug(newSlug, categoryList);
        if (!category) return swalNotify('Error', 'Category not found', 'error');

        $nestable.find('.dd-list').first().append(generateCategoryHtml(category));
        initNestable();
        $menuAdd[0].reset();
        if (!isCustom) $('#radioCategory').prop('checked', true).trigger('change');
        swalNotify('Success', 'Menu item added', 'success');
      };

      const cleanText = text => text.replace(/^(\s| )*-+(\s| )+/, '').replace(/\s*\(.*?\)\s*$/, '').trim();

      const isDuplicate = (name, slug) => {
        let isDuplicate = false;
        $nestable.find('.dd-item').each((_, item) => {
          const $item = $(item);
          if ($item.data('name') === name || $item.data('slug') === slug) {
            isDuplicate = true;
            return false;
          }
        });
        return isDuplicate;
      };

      const saveMenu = () => $.ajax({
        url: "{{ route('admin.menu-builder.save') }}",
        method: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          menu: JSON.stringify($nestable.nestable('serialize'))
        },
        success: () => {
          swalNotify('Success', 'Menu saved', 'success');
          loadMenuItems();
        },
        error: () => swalNotify('Error', 'Failed to save menu', 'error')
      });

      $('#cancelEdit').on('click', () => $menuEditor.hide() && $menuAdd.show());
      $menuEditor.on('submit', editItem);
      $menuAdd.on('submit', addItem);
      $('#save-menu').on('click', saveMenu);
      loadMenuItems();
    });
  </script>
@endsection
