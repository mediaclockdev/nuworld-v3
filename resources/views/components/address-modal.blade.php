@props(['addresses'])
<div class="modal genericmodal fade" id="AddressModal" tabindex="-1" aria-labelledby="AddressModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font24 fw-normal">Address</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body flow-rootX2">
        <div class="border-top"></div>
        <a href="javascript:void(0);"
          class="btn btn-outline-dark d-inline-flex px-4 py-3 align-items-center gap-2 add-new-address-btn"
          title="Add New Address">
          <span class="material-symbols-outlined">add</span> Add New Address
        </a>
        <div class="profile_details overflow-hidden flow-rootX3 h-100">
          <div class="info flow-rootX2">
            <div class="address_block flow-rootX">
              @forelse ($addresses as $address)
                @include('frontend.includes.address-block', ['singleAddress' => $address])
              @empty
                <p id="no-address">No addresses available.</p>
              @endforelse
              <input type="button" class="btn btn-dark w-100 py-3 mt-3 submit-address-btn" value="Submit">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@push('component-scripts')
  <script>
    /* let selectedShippingAddress  = `{{ session("selected_shipping_address") ?? ''}} `;
    let selectedBillingAddress = `{{ session('selected_billing_address') ?? '' }}`; */
    let selectedShippingAddress = @json(session('selected_shipping_address'));
    if (!selectedShippingAddress) {
        selectedShippingAddress = $('#shipping_address').val();
    }

    let selectedBillingAddress = @json(session('selected_billing_address'));
    if (!selectedBillingAddress) {
        selectedBillingAddress = $('#billing_address').val();
    }

    $(document).ready(function () {
      // let addressType = $('input[name="default_address"]:checked').data('attr-type2') || 'shipping';
      $('.add-new-address-btn').on('click', function(e) {
        $('#AddressModal').modal('hide');
        $('#AddNewAddressModal').modal('show');
      });

      $('.submit-address-btn').on('click', function (e) {
        e.preventDefault();

        const id = $('input[name="default_address"]:checked').val();
        const $addr = $('#address_' + id);
        if (!$addr.length) return;
        let addressCount =  parseInt($('#address_count').val());
        // Re-fetch addressType
        let addressType = $('input[name="default_address"]:checked').attr('data-attr-type2') || 'shipping';
        const target = '.' + addressType.replace('_address', '');
        const label = addressType.includes('shipping') ? 'Shipping' : 'Billing';

        // Save selected address to session
        $.ajax({
          url: `{{ route('address.set-selected-address') }}`,
          method: 'POST',
          data: {
            id: id,
            type: addressType,
            _token: `{{ csrf_token() }}`
          },
          success: function () {
            if (addressCount == 1) {
              $('.shipping').html(`${$addr.html()}
                <a href="javascript:void(0);" class="font16 change-address-btn" data-address-type="shipping">
                  Change Shipping Address
                </a>`);

              $('.billing').html(`${$addr.html()}
                <a href="javascript:void(0);" class="font16 change-address-btn" data-address-type="billing">
                  Change Billing Address
                </a>`);

              $('#shipping_address').val(id);
              $('#billing_address').val(id);
            }
            else{
              if(label == 'Shipping') {
                selectedShippingAddress= id;
              } else {
                selectedBillingAddress = id;
              }
              $(target).html(`${$addr.html()}
                <a href="javascript:void(0);" class="font16 change-address-btn" data-address-type="${addressType}">
                  Change ${label} Address
                </a>`);

              $('#' + addressType + '_address').val(id);
            }

            $('#AddressModal').modal('hide');
            // window.location.reload();
          }
        });
      });

    });
</script>

@endpush
