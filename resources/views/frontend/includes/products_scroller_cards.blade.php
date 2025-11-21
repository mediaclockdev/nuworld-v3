@foreach ($products as $product)
  @include('frontend.includes.product-card', ['variants' => $product->variants])
@endforeach
