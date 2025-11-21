<div class="d-flex filter-small justify-content-start align-items-center mb-3 pb-3 border-bottom">
  <div class="d-flex align-items-center">
    <label class="me-2 fw-semibold">Show rows:</label>
    <select class="form-select w-auto" wire:model.live.delay="perPage">
      <option value="10">10</option>
      <option value="20">20</option>
      <option value="50">50</option>
      <option value="100">100</option>
    </select>
  </div>
  <button id="deleteBtn" class="btn btn-sm btn-danger ms-2" style="display: none;">Delete</button>
</div>
