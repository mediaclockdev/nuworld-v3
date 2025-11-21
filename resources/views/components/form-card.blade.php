<div class="row min-VH">
  <div class="col-xl-12 col-lg-12">
    <div class="card card-h-100">
      <div class="d-flex card-header justify-content-between align-items-center">
        <h4 class="header-title mb-0">{{ $formTitle }}</h4>
      </div>
      <div class="card-body">
        <form id="{{ $formId }}" multipart="multipart/form-data">
          @csrf
          {{ $slot }}
          @if(request()->segment(3) !== 'new-store-setup')
          <div class="row">
            <div class="col-md-12">
              <div class="d-flex justify-content-between align-items-center">
                <div class="back-btn"><a href="{{ $backRoute }}" title="Back"
                    class="d-flex justify-content-start align-items-center font-16"><i
                      class="uil-angle-left font-18 me-1"></i>Back</a>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>
            @endif
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
