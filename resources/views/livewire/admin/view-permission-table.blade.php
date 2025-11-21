<div class="permissionsblkwp verticalscroll">
  @foreach ($modules as $module)
    <div class="permissionsblk">
      <h5 class="mb-2 text-dark font-17 fw-medium">
        <span>{{ $module['name'] }}</span>
      </h5>
      @foreach ($module['submodules'] as $submodule)
        <div class="individualrow d-flex justify-content-between align-items-center p-2">
          <div class="itemhead">{{ $submodule['name'] }}</div>
          <div class="items d-flex justify-content-end align-items-center">
            @foreach ($submodule['permissions'] as $permission)
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="customCheck1"
                  @if (
                      $roleID == 1 ||
                          (isset($checkedPermissions[$submodule['id']]) &&
                              in_array($permission['id'], $checkedPermissions[$submodule['id']]))) checked
                  @else
                  disabled @endif>
                <label class="form-check-label fw-normal" for="customCheck1">{{ $permission['name'] }}</label>
              </div>
            @endforeach
          </div>
        </div>
      @endforeach
    </div>
  @endforeach
</div>
