{{-- This is FAQ page content --}}
  {{-- ============================================================================================================================================================ --}}
  <section class="faq">
    <div class="container">
      <div class="inner-container">
        <h1 class="font45">Frequently Asked Questions</h1>
        <div class="tabContainer">
          <div class="row">
              <div class="col-2 pr-0">
                  <ul class="tabMenu">
                      @forelse ($faqCategories as $key => $category)
                          <li class="font20">
                              <a href="javascript: void(0);" {{ $key == 0 ? 'class=actv' : '' }}>{{ $category->name }}</a>
                          </li>
                      @empty
                          <li>No categories available</li>
                      @endforelse
                  </ul>
              </div>
              <div class="col-10 pl-0">
                  <div class="tabContents">
                      @forelse ($faqCategories as $key => $category)
                          <div class="tabContent" style="display: {{ $key == 0 ? 'block' : 'none' }};">
                              <div class="title_BtnWrap">
                                  <div class="row">
                                      <div class="col-8">
                                          <h2 class="font25">{{ $category->name }}</h2>
                                          <p class="desc c--gry mb-0">{{ $category->description }}</p>
                                      </div>
                                      @if (!empty($category->btn_text))
                                        <div class="col-4">
                                            <a href="{{ $category->btn_url ?? '#' }}" target="_blank" class="btn btn-outline-dark d-inline-flex px-4 py-3 align-items-center gap-2">{{ $category->btn_text }}</a>
                                        </div>
                                      @endif
                                  </div>
                              </div>
                              <div class="accordWrap accord">
                                  @forelse ($category->faqs as $faqKey => $faq)
                                      <div class="accordion">
                                          <h5 class="accord-btn font20 {{ $faqKey == 0 && $key == 0 ? 'actv' : '' }}">{{ $faq->question }}</h5>
                                          <div class="accord-target" style="display: {{ $faqKey == 0 && $key == 0 ? 'block' : 'none' }};">
                                              <p class="desc c--gry">{{ $faq->answer ?? 'Answer will go here?' }}</p>
                                          </div>
                                      </div>
                                  @empty
                                      <div class="accordion">
                                          <h5 class="font20">No questions available</h5>
                                      </div>
                                  @endforelse
                              </div>
                          </div>
                      @empty
                          <div class="tabContent" style="display: block;">
                              <p>No categories available</p>
                          </div>
                      @endforelse
                  </div>
              </div>
          </div>
      </div>
      </div>
    </div>
  </section>
  {{-- This is FAQ page content --}}
  {{-- ============================================================================================================================================================ --}}
