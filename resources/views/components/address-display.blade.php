@props(['title', 'userAddress', 'addressType', 'selectedId'])

<div class="individual_blocks flow-rootX border-bottom border-secondery">
  <h3 class="font25 c--blackc fw-normal"><strong>{{ $title }}</strong></h3>
  <div class="{{ str_replace('_address', '', $addressType) }}">
    @if (isset($userAddress) && !empty($userAddress))
      @isset($userAddress->name)
        <h4 class="fw-medium font20">{{ $userAddress->name ?? '' }}</h4>
        <p class="mb-0 font16">{{ $userAddress->address_1 ? truncateNoWordBreak($userAddress->address_1,100) : '' }},
          {{ $userAddress->city ?? '' }} <br>
          {{ $userAddress->landmark ?? '' }}<br>
          {{ $userAddress->state->name ?? '' }} - {{ $userAddress->pin ?? '' }},<br>
          {{ $userAddress->state->country->name ?? '' }}<br>
          Phone: {{ $userAddress->phone ?? '' }}</p>
      @endisset
      <a href="javascript:void(0);" class="font16 change-address-btn" data-address-type="{{ $addressType }}">Change
        {{ $title }}</a>
    @else
      <p class="mb-0 text-muted">No {{ $title }} provided</p>
         <a href="javascript:void(0);" class="font16 change-address-btn" data-address-type="{{ $addressType }}">Add
        {{ $title }}</a>
    @endif
  </div>
</div>
@push('component-scripts')
  <script>
    $(document).ready(function () {
      $(document).off('click', '.change-address-btn'); // Prevent duplicate binding
      $(document).on('click', '.change-address-btn', function (e) {

        e.preventDefault();
        let addressType = $(this).data('address-type');
        var addressCount = $('#address_count').val();
        if (addressCount == 0){
          $('#AddNewAddressModal').modal('show');
        }
        else{
          if (addressType === 'shipping') {
            $('input[name="default_address"]').attr('data-attr-type2', addressType);
            if(selectedShippingAddress){
              $(`#default_address_${selectedShippingAddress}`).prop('checked', true);
            }
          }
          if (addressType === 'billing') {
            $('input[name="default_address"]').attr('data-attr-type2', addressType);
            if(selectedBillingAddress){
              $(`#default_address_${selectedBillingAddress}`).prop('checked', true);
            }
          }
          $('#AddressModal').modal('show');
        }
      });
    });
  </script>
@endpush
