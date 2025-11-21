<section class="innerstaticBanner">
  <figure><img
      src="{{ !empty($page->feature_image) ? asset('public/storage/uploads/cms_pages/' . $page->feature_image) : asset('public/frontend/assets/img/about/banner.jpg') }}"
      alt="{{ $page->title ?? '' }}" title="{{ $page->title ?? '' }}" class="imageFit" /></figure>
  <div class="container">
    <h1 class="fw-normal c--whitec title font64">{{ $page->title ?? '' }}</h1>
  </div>
</section>

<section class="privacy_Policy">
  <div class="container flow-rootX2">
    {!! $page->body ?? '' !!}
    {{-- <h3>Return & Refund Policy</h3>
    <p>At Mayuri, we want you to love your purchase. If you're not completely satisfied, we’re here to help.</p>

    <h3>Eligibility for Returns</h3>
    <p>You may return most new, unused items within <strong>7 days of delivery</strong> for a full refund or exchange, subject to the following conditions:</p>
    <ul>
      <li>The item must be unused, unassembled, and in its original packaging.</li>
      <li>Return requests must be initiated within 7 days of receiving the product.</li>
      <li>Items marked as final sale, clearance, or customized products are non-returnable.</li>
    </ul>

    <h3>How to Initiate a Return</h3>
    <p>To start a return, please contact our customer support team at <a href="mailto:support@living.com">support@living.com</a> with your order number and reason for return. We’ll provide instructions and a return shipping label (if applicable).</p>

    <h3>Return Shipping</h3>
    <ul>
      <li>If the return is due to our error (e.g., defective or incorrect item), we’ll cover return shipping costs.</li>
      <li>For all other returns, the customer is responsible for return shipping.</li>
    </ul>

    <h3>Refund Process</h3>
    <p>Once we receive and inspect your returned item, your refund will be processed within <strong>5–7 business days</strong>. Refunds will be issued to your original payment method.</p>

    <h3>Damaged or Defective Items</h3>
    <p>If your order arrives damaged or defective, please contact us within <strong>48 hours</strong> of delivery with photos and a description. We'll arrange a replacement or refund as quickly as possible.</p>

    <h3>Exchanges</h3>
    <p>We offer exchanges for eligible items. Contact us to initiate an exchange request and confirm item availability.</p>

    <h3>Contact Us</h3>
    <p>If you have any questions about your return, feel free to reach out to our support team at <a href="mailto:support@living.com">support@living.com</a>.</p> --}}
  </div>
</section>
@include('frontend.includes.banners.keep-flowing')
