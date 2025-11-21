<div class="store_left flow-rootX3" id="storeList">
    @if ($stores->isNotEmpty())
        @foreach ($stores as $store)
            <div class="store_card" >
                <figure class="m-0 imageFit"><img src="{{ !empty($store->image) ? asset('public/storage/uploads/stores/' . $store->image) : asset('public/backend/assetss/images/img/default_thumb.jpg') }}" alt="{{ $store->name }}" title="{{ $store->name }}" /></figure>
                <div class="info d-flex flex-column justify-content-between align-items-stretch">
                    <p>
                      <h3 class="card-title">{{ $store->name ?? '' }}</h3>
                      <p class="card-action">{{ Illuminate\Support\Str::limit($store->address ?? '', 60) }}</p>
                    </p>
                    <div class="actions d-flex justify-content-between align-items-center">
                        <a href="{{ $store->location ?? 'Kolkata, West Bengal' }}" target="_blank" title="Get Direction"
                           class="d-flex justify-content-start text-decoration-none align-items-center gap-2 font18 select-store">
                            <span class="material-symbols-outlined font20">location_on</span> Get Direction
                        </a>
                        <a href="tel:{{ $store->phone ?? '' }}" class="btn btn-outline-dark align-items-center px-4 py-2 d-inline-flex align-items-center gap-2" title="Contact"><span class="material-symbols-outlined font22">call</span> Contact</a>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="text-center">
            <p class="font18 text-danger">No stores found.</p>
        </div>
    @endif
</div>
