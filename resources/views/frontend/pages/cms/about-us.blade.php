<section class="innerstaticBanner">
  <figure><img
      src="{{ !empty($page->feature_image) ? asset('public/storage/uploads/cms_pages/' . $page->feature_image) : asset('public/frontend/assets/img/about/banner.jpg') }}"
      alt="{{ $page->title ?? '' }}" title="{{ $page->title ?? '' }}" class="imageFit" /></figure>
  <div class="container">
    <h1 class="fw-normal c--whitec title font64">{{ $page->title ?? '' }}</h1>
  </div>
</section>
<section class="welcomeText">
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <h2 class="font56 fw-normal">Welcome to Mayuri, where we believe that every space deserves to tell a unique
          story.</h2>
      </div>
      <div class="col-lg-6">
        <p class="font24">With years of experience in the industry, we’ve cultivated a reputation for delivering
          personalized design solutions tailored to your individual needs. Whether you're furnishing a cozy apartment,
          a spacious home, or a modern office, we’re here to help you transform your space into something truly
          special.</p>
      </div>
    </div>
  </div>
</section>
<section class="yearsexprience">
  <div class="container">
    <div class="inner-content">
      <div class="count">25<em class="c--red">+</em> <span class="font25">Years Experience</span></div>
      <div class="right p-0">
        <figure class="mb-0"><img src="{{ asset('public/frontend/assets/img/about/about-us.png') }}" alt="Mayuri" title="Mayuri"
            class="" /></figure>
      </div>
    </div>
  </div>
</section>
<section class="businessExcellence">
  <div class="container flow-rootX3">
    <h3 class="font56 fw-normal">Lead the future with Mayuri, where<br> digital innovation meets business<br>
      excellence.</h3>
    <figure><img src="{{ asset('public/frontend/assets/img/about/business-excellence.jpg') }}" alt="Mayuri"
        title="Mayuri" class="" /></figure>
    <p class="font24">At Mayuri, your privacy is extremely important to us. This Privacy Policy outlines how we
      collect, use, disclose, and safeguard your personal information when you visit, interact with, or make a
      purchase from our website, <a href="https://mayuriseattle.com/" class="c--red" target="_blank">mayuriseattle.com</a>. We are committed to
      ensuring that your personal data is handled securely and in compliance with all applicable data protection laws
      and regulations.</p>
    <p class="font24">At Mayuri, your privacy is extremely important to us. This Privacy Policy outlines how we
      collect, use, disclose, and safeguard your personal information when you visit, interact with, or make a
      purchase from our website, <a href="https://mayuriseattle.com/" class="c--red" target="_blank">mayuriseattle.com</a>. We are committed to
      ensuring that your personal data is handled securely and in compliance with all applicable data protection laws
      and regulations.</p>
  </div>
</section>
