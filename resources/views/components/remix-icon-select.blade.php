@php
  use Illuminate\Support\Str;
@endphp
<style>
  .select2-container .select2-selection--single .select2-selection__clear {
    position: relative;
    z-index: 9;
    color: var(--ct-danger);
    height: 45px;
    padding-right: 10px;
  }
</style>
<select class="form-select select2" name="{{ $name }}" id="{{ $id }}">
  <option value=""></option>
  @foreach ($remixIcons as $icon)
    <option value="{{ $icon->icon_name }}" data-icon="{{ Str::after($icon->icon_name, 'ri-') }}"
      {{ $icon->icon_name == $selected ? 'selected' : '' }}>
      {{ Str::after($icon->icon_name, 'ri-') }}
    </option>
  @endforeach
</select>

@push('component-scripts')
  <script>
    $('#{{ $id }}').select2({
      placeholder: "Select Icon",
      width: '100%',
      allowClear: true,
      templateResult: formatIcon,
      templateSelection: formatIcon,
      escapeMarkup: function(markup) {
        return markup;
      }
    }).on('change select2:open select2:close', function() {
      $(this).next('.select2-container')
        .find('.select2-selection__clear')
        .attr('title', 'Clear selection');
    });


    function formatIcon(option) {
      if (!option.id) {
        return option.text;
      }
      var icon = $(option.element).data('icon');
      if (!icon) {
        return option.text;
      }
      return `<i class="ri-${icon}" style="margin-right: 10px; font-size: 24px;"></i> ${option.text}`;
    }
  </script>
@endpush
