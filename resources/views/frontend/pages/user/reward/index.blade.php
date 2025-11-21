@extends('frontend.layouts.app')

@section('title', @$title)

@section('content')

  <section class="breadcrumb-wrapper py-4 border-top">
    <div class="container-xxl">
      <ul class="breadcrumbs">
        <li><a href="javascript:void();">Home</a></li>
        <li>Account</li>
      </ul>
    </div>
  </section>
  <section class="furniture_myaccount_wrap pt-4">
    <div class="container flow-rootX3">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="fw-normal mt-0 font45 c--blackc">Account</h1>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="my_account_wrap">
            @include('frontend.pages.user.includes.profile-sidebar')
            <div class="right_content">
              <div class="profile_details overflow-hidden border flow-rootX3 h-100">
                <div class="heading border-bottom pb-4">
                  <h2 class="font25 fw-medium m-0 c--blackc">Reward</h2>
                </div>
                <div class="info flow-rootX2">
                  <div class="reward_box">
                    <div class="left_side">
                      <div class="icon"><span
                          class="material-symbols-outlined c--whitec font45">account_balance_wallet</span></div>
                      <div class="txt">
                        <h4 class="font25 fw-normal c--blackc mb-1">{{ displayPrice($totalReward) }}</h4>
                        <p class="mb-0 c--gry font18">Updated few mins ago</p>
                      </div>
                    </div>
                  </div>
                  <div class="transaction_history">
                    <h4 class="font20 fw-normal mb-4 c--blackc">Transaction History</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Code</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Expiry Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customerRewards as $reward)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($reward->created_at)->format('F d, Y') }}</td>
                                    <td>{{ ucfirst($reward->scratchCardReward->type ?? 'N/A') }}</td>
                                    {{-- <td>{{ $reward->scratch_card_code }}</td> --}}
                                    <td class="copy-code" style="cursor: pointer;" title="Click to copy">{{ $reward->scratch_card_code }}</td>
                                    <td>
                                      @if ($reward->status == 1)
                                          <span class="c--success">
                                            {{ ucfirst($reward->status_text) }}
                                          </span>
                                      @else
                                          <span class="c--error">
                                            {{ ucfirst($reward->status_text) }}
                                          </span>
                                      @endif
                                    </td>
                                    <td>
                                      @if ($reward->status == 1)
                                          <span class="c--success">
                                            +{{ displayPrice($reward->scratchCardReward->value ?? 0) }}
                                          </span>
                                      @else
                                          <span class="c--error">
                                            -{{ displayPrice($reward->scratchCardReward->value ?? 0) }}
                                          </span>
                                      @endif

                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($reward->expiry_date)->format('F d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No transactions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>


                  {{-- <div class="transaction_history">
                    <h4 class="font20 fw-normal mb-4 c--blackc">Transaction History</h4>
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Date</th>
                          <th>Type</th>
                          <th>Amount ($)</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>March 24, 2025</td>
                          <td>Cashback</td>
                          <td><span class="c--success">+60.00</span></td>
                        </tr>
                        <tr>
                          <td>March 24, 2025</td>
                          <td>Refund</td>
                          <td><span class="c--success">+1002.00</span></td>
                        </tr>
                        <tr>
                          <td>March 24, 2025</td>
                          <td>Order #12198</td>
                          <td><span class="c--error">-1002.00</span></td>
                        </tr>
                      </tbody>
                    </table>
                  </div> --}}
                 {{--  <div class="Reward_offer">
                    <h4 class="font20 fw-normal mb-4 c--blackc">Reward Offers</h4>
                    <ul class="Reward_offer_list">
                      <li>Earn extra when you use your Reward!</li>
                      <li>Get 5% cashback when you pay via Reward.</li>
                    </ul>
                  </div> --}}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const codeCells = document.querySelectorAll('.copy-code');
    codeCells.forEach(cell => {
        cell.addEventListener('click', function() {
            const code = this.textContent.trim();
            navigator.clipboard.writeText(code).then(() => {
                const originalText = this.textContent;
                this.textContent = 'Copied!';
                this.style.color = '#28a745';
                setTimeout(() => {
                    this.textContent = originalText;
                    this.style.color = '';
                }, 1000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        });
    });
});
</script>
@endpush
