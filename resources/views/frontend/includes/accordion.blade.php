<div class="accordion accordion-flush" id="product-acoordion">
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button ps-0 pe-0 collapsed font25" type="button" data-bs-toggle="collapse"
        data-bs-target="#pd-accordion1" aria-expanded="false" aria-controls="pd-accordion1">Product
        Details</button>
    </h2>
    <div id="pd-accordion1" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
      data-bs-parent="#product-acoordion">
      <div class="accordion-body ps-0 pe-0">
        {{-- <div class="d-grid grid-2"> --}}
        {!! $productVariant->product->product_details !!}
        {{-- </div> --}}
        {{-- <div class="d-grid">
          {!! $productVariant->product->product_details !!}
        </div> --}}
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button ps-0 pe-0 collapsed font25" type="button" data-bs-toggle="collapse"
        data-bs-target="#pd-accordion2" aria-expanded="false" aria-controls="pd-accordion2">Specifications</button>
    </h2>
    <div id="pd-accordion2" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
      data-bs-parent="#product-acoordion">
      <div class="accordion-body ps-0 pe-0">
        {!! $productVariant->product->specifications !!}
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button ps-0 pe-0 collapsed font25" type="button" data-bs-toggle="collapse"
        data-bs-target="#pd-accordion3" aria-expanded="false" aria-controls="pd-accordion3">Care &
        Maintenance</button>
    </h2>
    <div id="pd-accordion3" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
      data-bs-parent="#product-acoordion">
      <div class="accordion-body ps-0 pe-0">
        {!! $productVariant->product->care_maintenance !!}
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button ps-0 pe-0 collapsed font25" type="button" data-bs-toggle="collapse"
        data-bs-target="#pd-accordion5" aria-expanded="false" aria-controls="pd-accordion5">Warranty</button>
    </h2>
    <div id="pd-accordion5" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
      data-bs-parent="#product-acoordion">
      <div class="accordion-body ps-0 pe-0">
        {!! $productVariant->product->warranty !!}
      </div>
    </div>
  </div>
</div>
