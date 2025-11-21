<section class="furniture__book_collection_wrp">
  <div class="container-xxl">
    <div class="row">
      <div class="col-lg-12 flow-rootX3">
        <div class="header">
          <h3 class="font45 fw-normal"><a href="{{ $collectionWrap->hyper_link ?? '#' }}">Book From this
              Collections</a></h3>
          <h4 class="font35 fw-normal">{!! $collectionWrap->content ?? '' !!}</h4>
        </div>
        <div class="imagewrp">
          <figure><img src="{{ !empty($collectionWrap->image) ? asset('public/storage/uploads/banners/' . $collectionWrap->image) : asset('public/frontend/assets/img/home/book_collection.jpg') }}
            " alt="{{ $collectionWrap->alt_text ?? '' }}"
              class="imageFit" /></figure>
          <div class="iconswrp one">
            <a href="#" title="Table">
              <div class="point"></div>
            </a>
            <div class="info flow-rootX">
              <a href="#" title=""></a>
              <h5 class="font20 m-0">Green Elegant Table</h5>
              <div class="price c--primary font20">$1500 <span
                  class="material-symbols-outlined">arrow_forward_ios</span></div>
            </div>
          </div>
          <div class="iconswrp two">
            <a href="#" title="Sofa">
              <div class="point"></div>
            </a>
            <div class="info flow-rootx">
              <a href="#" title=""></a>
              <h5 class="font20 m-0">Green Elegant Sofa</h5>
              <div class="price c--primary font20">$3000 <span
                  class="material-symbols-outlined">arrow_forward_ios</span></div>
            </div>
          </div>
          <div class="iconswrp three">
            <a href="#" title="Chair">
              <div class="point"></div>
            </a>
            <div class="info flow-rootx">
              <a href="#" title=""></a>
              <h5 class="font20 m-0">Cream Elegant Chair</h5>
              <div class="price c--primary font20">$2000 <span
                  class="material-symbols-outlined">arrow_forward_ios</span></div>
            </div>
          </div>
          <div class="iconswrp four">
            <a href="#" title="Lamp">
              <div class="point"></div>
            </a>
            <div class="info flow-rootx">
              <a href="#" title=""></a>
              <h5 class="font20 m-0">Black Side Lamp Shade</h5>
              <div class="price c--primary font20">$1000 <span
                  class="material-symbols-outlined">arrow_forward_ios</span></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
