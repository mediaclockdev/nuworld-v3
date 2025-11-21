<div class="modal genericmodal fade AddNewAddressModal" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel"
  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font24 fw-normal address-modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body flow-rootX2">
        <div class="border-top"></div>
        <form class="allForm" id="add_address_form">
          @csrf
          <input type="hidden" name="id" id="address_id">

          <div class="form-element name">
            <label class="form-label">Name <em>*</em></label>
            <input name="name" id="name" type="text" class="form-field only-alphabet-symbols">
          </div>
          <div class="form-element mobile phone-input-container has-value">
            <input type="text" class="form-field only-numbers" name="phone" id="custom-phone"
            autocomplete="new-phone" inputmode="numeric" placeholder="Phone" value="">
            <i class="msg-error"></i>
            <label class="form-label">Phone <em>*</em></label>
            <div class="custom-phone-error-container"></div>
          </div>
          <div class="form-element address">
            <label class="form-label">Address <em>*</em></label>
            <input name="address" type="text" class="form-field" value="">
          </div>
          <div class="form-element locality">
            <label class="form-label">Landmark <em>*</em></label>
            <input name="landmark" type="text" class="form-field" value="">
          </div>
          <div class="form-element city">
            <label class="form-label">City / District <em>*</em></label>
            <input name="city" type="text" class="form-field" value="">
          </div>
          <div class="form-element state">
            <label class="form-label">State <em>*</em></label>
            <select class="form-field form-select" name="state" id="state">
              <option value="">Select State</option>
              @forelse ($states as $state)
                <option value="{{ Hashids::encode($state->id) }}"> {{ $state->name }}</option>
              @empty
              @endforelse
            </select>
          </div>
          <div class="form-element pincode">
            <label class="form-label">Pincode <em>*</em></label>
            <input name="pincode" type="text" class="form-field">
          </div>
          <div class="form-check m-0 defaultaddress mb-4">
            <input class="form-check-input" type="checkbox" name="is_default" id="defaultaddress">
            <label class="form-check-label font14" for="defaultaddress">Make this as my default address</label>
          </div>
          <div class="action d-flex align-items-center gap-4 pt-2">
            <button type="button" class="btn btn-outline-dark w-50 btn-lg py-2 custom-btn-close" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-dark w-50 btn-lg py-2">Save Details</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
