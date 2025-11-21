<section class="innerstaticBanner">
    <figure><img src="{{ !empty($page->feature_image) ? asset('public/storage/uploads/cms_pages/' . $page->feature_image) : asset('public/frontend/assets/img/about/banner.jpg') }}" alt="{{ $page->title ?? '' }}" title="{{ $page->title ?? '' }}" class="imageFit"/></figure>
    <div class="container">
      <h1 class="fw-normal c--whitec title font64">{{ $page->title ?? '' }}</h1>
    </div>
</section>
<section class="careers_section">
  <div class="container flow-rootX2">
    <h3>Careers at Mayuri</h3>
    <p>At <strong>Mayuri</strong>, we believe in creating beautiful spaces — not just for our customers, but for our team as well. If you're passionate about design, technology, and delivering an exceptional shopping experience, we’d love to hear from you!</p>

    <h3>Why Join Us?</h3>
    <ul>
      <li><strong>Innovative Environment:</strong> We embrace creativity, ideas, and innovation across all departments.</li>
      <li><strong>Collaborative Culture:</strong> Work alongside passionate, supportive, and driven team members.</li>
      <li><strong>Growth Opportunities:</strong> We support personal and professional development at every level.</li>
      <li><strong>Employee Discounts:</strong> Enjoy special discounts on our premium product range.</li>
      <li><strong>Flexible Work Options:</strong> Depending on the role, remote and hybrid opportunities are available.</li>
    </ul>

    <h3>Current Openings</h3>
    <p>We’re currently hiring for the following roles:</p>
    <ul>
      <li>Customer Support Executive</li>
      <li>Digital Marketing Specialist</li>
      <li>Product Photographer</li>
      <li>UI/UX Designer</li>
      <li>Warehouse Operations Coordinator</li>
    </ul>
    <p>If you don’t see a role that fits you, we still encourage you to get in touch — we’re always looking for talented individuals to join our journey.</p>

    <h3>How to Apply</h3>
    <p>Email your resume and a brief cover letter to <a href="mailto:careers@living.com">careers@living.com</a>. Please mention the position you’re applying for in the subject line.</p>

    <h3>Location</h3>
    <p>Our headquarters is located in Bangalore, India. Some roles may be available remotely or in a hybrid setting.</p>

    <h3>Join Us</h3>
    <p>Be part of a team that’s redefining home and lifestyle shopping. Let’s build something amazing together.</p>
  </div>
</section>
