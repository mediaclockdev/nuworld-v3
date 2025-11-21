<div class="permissionsblkwp verticalscroll">
  @foreach ($modules as $module)
    <div class="permissionsblk roles">
      {{-- <!-- Module Checkbox --> --}}
      <h5 class="mb-3 text-dark font-17 fw-medium">
        <div class="form-check">
          <input type="checkbox" class="form-check-input check-all-module" id="module_{{ $module['id'] }}"
            data-module-id="{{ $module['id'] }}">
          <label class="form-check-label" for="module_{{ $module['id'] }}">{{ $module['name'] }}</label>
        </div>
      </h5>
      @foreach ($module['submodules'] as $submodule)
        {{-- <!-- Submodule Checkbox --> --}}
        <div class="individualrow d-flex justify-content-between align-items-center p-2">
          <div class="itemhead">
            <div class="form-check">
              <input type="checkbox" class="form-check-input check-all-submodule" id="submodule_{{ $submodule['id'] }}"
                data-submodule-id="{{ $submodule['id'] }}" data-module-id="{{ $module['id'] }}">
              <label class="form-check-label" for="submodule_{{ $submodule['id'] }}">{{ $submodule['name'] }}</label>
            </div>
          </div>
          {{-- <!-- Permissions --> --}}
          <div class="items d-flex justify-content-end align-items-center">
            @foreach ($submodule['permissions'] as $permission)
              <div class="form-check">
                <input type="checkbox"
                  class="form-check-input submodule_{{ $submodule['id'] }}_permission module_{{ $module['id'] }}_permission check-permission"
                  id="submodule_{{ $submodule['id'] }}_permission{{ $permission['id'] }}"
                  data-permission-id="{{ $permission['id'] }}" data-submodule-id="{{ $submodule['id'] }}"
                  data-module-id="{{ $module['id'] }}" name="permissions[{{ $submodule['id'] }}][]"
                  value="{{ Hashids::encode($permission['id']) }}" @if (isset($checkedPermissions[$submodule['id']]) && in_array($permission['id'], $checkedPermissions[$submodule['id']])) checked @endif>
                <label class="form-check-label"
                  for="submodule_{{ $submodule['id'] }}_permission{{ $permission['id'] }}">{{ $permission['name'] }}</label>
              </div>
            @endforeach
          </div>
        </div>
      @endforeach
    </div>
  @endforeach
</div>

@push('component-scripts')
  <script>
    function updateSubmoduleState(e){const d=$(`.submodule_${e}_permission`).length,t=$(`.submodule_${e}_permission:checked`).length,o=$(`#submodule_${e}`);t===d?o.prop("checked",!0).prop("indeterminate",!1):t>0?o.prop("checked",!1).prop("indeterminate",!0):o.prop("checked",!1).prop("indeterminate",!1)}function updateModuleState(e){const d=$(`.check-all-submodule[data-module-id="${e}"]`).length,t=$(`.check-all-submodule[data-module-id="${e}"]:checked`).length,o=$(`.check-all-submodule[data-module-id="${e}"]:indeterminate`).length,i=$(`#module_${e}`);t===d&&0===o?i.prop("checked",!0).prop("indeterminate",!1):t>0||o>0?i.prop("checked",!1).prop("indeterminate",!0):i.prop("checked",!1).prop("indeterminate",!1)}$(".check-permission").on("change",(function(){const e=$(this).data("submodule-id"),d=$(this).data("module-id"),t=$(this).data("permission-id"),o=$(`.submodule_${e}_permission[id="submodule_${e}_permission1"]`);1!==t&&this.checked&&o.prop("checked",!0),1!==t||this.checked||$(`.submodule_${e}_permission`).prop("checked",!1),updateSubmoduleState(e),updateModuleState(d)})),$(".check-all-submodule").on("change",(function(){const e=$(this).data("submodule-id"),d=$(this).data("module-id");$(`.submodule_${e}_permission`).prop("checked",this.checked),updateModuleState(d)})),$(".check-all-module").on("change",(function(){const e=$(this).data("module-id"),d=$(this).is(":checked");$(`.module_${e}_permission`).prop("checked",d),$(`.check-all-submodule[data-module-id="${e}"]`).prop("checked",d),$(`.check-all-submodule[data-module-id="${e}"]`).prop("indeterminate",!1)})),$(".check-permission").each((function(){const e=$(this).data("submodule-id"),d=$(this).data("module-id");updateSubmoduleState(e),updateModuleState(d)}));

  </script>
@endpush
