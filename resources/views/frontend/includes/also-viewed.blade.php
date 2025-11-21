<section class="furniture__products_scroller_wrap ">
  <div class="container-xxl flow-rootX3">
    <h2 class="fw-normal m-0 font45 c--blackc">People have also viewed</h2>
    <div class="swiperwrp">
      <div class="swiper swiper__pl">
        <div class="swiper-wrapper eq-height">
          @for ($i = 0; $i < 6; $i++)
            <div class="swiper-slide">
              <div class="product-card flow-rootX">
                <div class="main-image-wrap border">
                  <div class="tag primary font12">10% Off</div>
                  <div class="showingbag">
                    <a href="#" title="Add To Cart"><span class="material-symbols-outlined">local_mall</span></a>
                  </div>
                  <div class="main-images ratio ratio-1000x800">
                    <img data-color="blue" class="active"
                      src="{{ asset('public/frontend/assets/img/product/product_thumb.jpg') }}" alt="blue" />
                    <img data-color="pink" src="{{ asset('public/frontend/assets/img/product/po2.jpg') }}"
                      alt="pink" />
                    <img data-color="yellow" src="{{ asset('public/frontend/assets/img/product/po3.jpg') }}"
                      alt="yellow" />
                    <img data-color="black" src="{{ asset('public/frontend/assets/img/product/po4.jpg') }}"
                      alt="black" />
                  </div>
                </div>
                <div class="product-details flow-rootx2">
                  <div class="d-flex gap-3 justify-content-between align-items-center">
                    <div class="category m-0">
                      <h4 class="fw-normal"><a href="#" title="Sofa" class="font14">Sofa</a></h4>
                    </div>
                    <div class="color-option">
                      <div class="circles">
                        <span class="circle active" data-color="blue" data-hex="#9f5762"></span>
                        <span class="circle" data-color="pink" data-hex="#b1a285"></span>
                        <span class="circle" data-color="yellow" data-hex="#1f3952"></span>
                        <span class="circle" data-color="black" data-hex="#0e535a"></span>
                      </div>
                    </div>
                  </div>
                  <div class="product_name">
                    <h3 class="font18 fw-normal"><a href="#" title="Sofa">Premium Sofa</a></h3>
                  </div>
                  <div class="price font18">
                    $1029.00 <span class="old-price ms-2">$1599.00</span>
                  </div>
                </div>
              </div>
            </div>
          @endfor
        </div>
      </div>
      <div class="swiper-nav-inline">
        <div class="swipper_button swiper__pl--prev"><span
            class="material-symbols-outlined font35 c--blackc">arrow_back_ios_new</span></div>
        <div class="swipper_button swiper__pl--next"><span
            class="material-symbols-outlined font35 c--blackc">arrow_forward_ios</span></div>
      </div>
    </div>
  </div>
</section>
