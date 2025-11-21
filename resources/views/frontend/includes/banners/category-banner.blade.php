 @php
   $settings = [];
   if (!empty($categoryBanner) && isset($categoryBanner->settings)) {
       $settings = json_decode($categoryBanner->settings, true);
   }
 @endphp
 <!-- <section class="furniture__home_content_blockwrp category_hero bg--blackc fullBG c--whitec bg-- mt-0">
   <div class="container-xxl">
     <div class="row">
       <div class="col-lg-12">
         <div class="inside text-center">
           <h1 class="font140 text-center fw-normal c--whitec" data-parallax-strength-vertical="-2.5"
             data-parallax-height="-2.5"><span data-parallax-target>{{ $categoryBanner->title ?? '' }}</span></h1>
           <figure data-parallax-strength-vertical="2.5" data-parallax-height="2.5"><img
               src="{{ !empty($settings['image']) ? asset(config('defaults.banner_image_path') . $settings['image']) : asset('public/frontend/assets/img/category/category_hero.png') }}"
               alt="{{ $settings['alt_text'] ?? '' }}" title="{{ $categoryBanner->title ?? '' }}"
               data-parallax-target />
           </figure>
           {{-- <p class="act font25" data-parallax-strength-vertical="-2.5" data-parallax-height="-2.5">
                <span data-parallax-target></span>
              </p> --}}
           {!! $settings['content'] ?? '' !!}
         </div>
       </div>
     </div>
   </div>
 </section> -->
  <section class="furniture__home_content_blockwrp category_hero bg--blackc fullBG c--whitec bg-- mt-0">
    <figure class="mb-0"><img
        src="{{ !empty($settings['image']) ? asset(config('defaults.banner_image_path') . $settings['image']) : asset('public/frontend/assets/img/category/category_hero.png') }}"
        alt="{{ $settings['alt_text'] ?? '' }}" title="{{ $categoryBanner->title ?? '' }}"/>
    </figure>

 </section>
