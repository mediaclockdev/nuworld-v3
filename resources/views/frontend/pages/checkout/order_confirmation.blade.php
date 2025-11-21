@extends('frontend.layouts.app')
@push('styles')
  <style>
    .tooltip-inner {
      background-color: #333;
      color: #fff;
      font-size: 14px;
      padding: 8px 12px;
      border-radius: 6px;
    }

    .tooltip.bs-tooltip-top .tooltip-arrow::before {
      border-top-color: #333;
    }

    #scratchCard {
      cursor: pointer;
    }
  </style>
@endpush

@section('title', @$title)
@section('content')
  <section class="breadcrumb-wrapper py-4 border-top">
    <div class="container-xxl">
      <ul class="breadcrumbs">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li>Order Confirmation</li>
      </ul>
    </div>
  </section>

  <section class="furniture_order_confirmation_wrap">
    <div class="container-xxl flow-rootX3">
      <div class="order_received_heading">
        <div class="icon">
          <figure class="mb-0"><img src="{{ asset('public/frontend/assets/img/success-tick.gif') }}" alt="Success"
              title="Success" class="" /></figure>
        </div>
        <div class="head">
          <h1 class="fw-normal mt-0 font45 c--blackc">Your order has been received!</h1>
          <p class="fw-normal m-0 font18 c--blackc">Thank you for your purchase! Your furniture order Order ID: <span
              class="c--primary">#{{ $order->order_number }}</span> is <br>confirmed. We'll update you shortly with
            packing and shipping details. </p>
          <div class="btnwrap">
            <a href="{{ route('orders') }}" class="btn btn-outline-dark px-4 py-2">View All Orders</a>
            <a href="{{ route('home') }}" class="btn btn-dark btn-lg px-4 py-2">Continue Shopping</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Scratch Card Modal -->
  <div class="modal fade" id="scratchCardModal" tabindex="-1" aria-labelledby="scratchCardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="scratchCardModalLabel">Scratch to Reveal Your Reward!</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <div style="position: relative; display: inline-block; width: 300px; height: 150px;">
            <!-- Reward code text (HTML) -->
            <div id="rewardText"
              style="width: 300px; height: 150px;
                            display: flex; align-items: center; justify-content: center;
                            background: #fff; border: 1px solid #ccc; border-radius: 10px;
                            font-size: 24px; font-weight: bold; cursor: pointer;
                            position: absolute; top: 0; left: 0; z-index: 1;">
            </div>

            <!-- Scratch canvas -->
            <canvas id="scratchCard" width="300" height="150"
              style="border: 1px solid #ccc; border-radius: 10px; position: relative; z-index: 2;">
            </canvas>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(document).ready(function() {
      // Check if redirected from confirmation and not already shown
      const urlParams = new URLSearchParams(window.location.search);
      const fromConfirmation = urlParams.get('from') === 'confirmation';
      const orderNumber = '{{ $order->order_number }}';
      const storageKey = 'rewardChecked_' + orderNumber;

      if (fromConfirmation && !sessionStorage.getItem(storageKey)) {
        $.ajax({
          url: '{{ route('order.getReward', ['order_number' => $order->order_number]) }}',
          method: 'GET',
          dataType: 'json',
          success: function(response) {
            if (response.success && response.reward) {
              initScratchCard(response.reward);
              $('#scratchCardModal').modal('show');
            }
            sessionStorage.setItem(storageKey, 'true');
          },
          error: function(xhr) {
            iziNotify("Error!", 'Failed to load reward.', "error");
            sessionStorage.setItem(storageKey, 'true');
          }
        });
      }

      function initScratchCard(reward) {
        const rewardDiv = document.getElementById('rewardText');
        const scratchCanvas = document.getElementById('scratchCard');
        const scratchCtx = scratchCanvas.getContext('2d');

        // Figure out reward text
        let rewardText = '';
        if (reward.type === 'coupon' && reward.coupon_code) {
          rewardText = reward.coupon_code;
        } else if (reward.type === 'fixed') {
          rewardText = `$${reward.value} off!`;
        } else if (reward.type === 'percentage') {
          rewardText = `${reward.value}% off!`;
        } else {
          rewardText = 'No reward';
        }

        // Set reward text in the div
        rewardDiv.textContent = rewardText;
        // Add visual feedback for clickability
        rewardDiv.style.cursor = rewardText !== 'No reward' ? 'pointer' : 'default';
        rewardDiv.setAttribute('data-bs-toggle', 'tooltip');
        rewardDiv.setAttribute('data-bs-placement', 'top');
        rewardDiv.setAttribute('title', rewardText !== 'No reward' ? 'Click to copy!' : '');

        // Initialize Bootstrap tooltip
        const tooltip = new bootstrap.Tooltip(rewardDiv);

        // Draw scratchable layer on top canvas
        scratchCtx.fillStyle = '#ccc';
        scratchCtx.fillRect(0, 0, 300, 150);

        let isScratching = false;
        let isFullyScratched = false;

        const scratch = (x, y) => {
          scratchCtx.globalCompositeOperation = 'destination-out';
          scratchCtx.beginPath();
          scratchCtx.arc(x, y, 25, 0, Math.PI * 2);
          scratchCtx.fill();
        };

        const getCoords = (e, isTouch = false) => {
          const rect = scratchCanvas.getBoundingClientRect();
          return isTouch ? {
            x: e.touches[0].clientX - rect.left,
            y: e.touches[0].clientY - rect.top
          } : {
            x: e.clientX - rect.left,
            y: e.clientY - rect.top
          };
        };

        const handleMove = (e, isTouch = false) => {
          if (!isScratching || isFullyScratched) return;
          if (isTouch) e.preventDefault();
          const {
            x,
            y
          } = getCoords(e, isTouch);
          scratch(x, y);
          checkScratchProgress();
        };

        scratchCanvas.addEventListener('mousedown', () => isScratching = true);
        scratchCanvas.addEventListener('mouseup', () => isScratching = false);
        scratchCanvas.addEventListener('mousemove', (e) => handleMove(e, false));

        scratchCanvas.addEventListener('touchstart', () => isScratching = true);
        scratchCanvas.addEventListener('touchend', () => isScratching = false);
        scratchCanvas.addEventListener('touchmove', (e) => handleMove(e, true));

        function checkScratchProgress() {
          if (isFullyScratched) return;
          const imageData = scratchCtx.getImageData(0, 0, scratchCanvas.width, scratchCanvas.height).data;
          let transparentPixels = 0;
          let totalPixels = scratchCanvas.width * scratchCanvas.height;
          for (let i = 3; i < imageData.length; i += 4) {
            if (imageData[i] === 0) transparentPixels++;
          }
          const percentage = (transparentPixels / totalPixels) * 100;

          if (percentage > 50) {
            // Reveal completely and disable canvas
            scratchCtx.clearRect(0, 0, scratchCanvas.width, scratchCanvas.height);
            scratchCanvas.style.pointerEvents = 'none'; // Disable canvas interactions
            isFullyScratched = true;
          }
        }

        // Click-to-copy functionality
        rewardDiv.addEventListener('click', () => {
          if (!isFullyScratched || !rewardText || rewardText === 'No reward') {
            if (!isFullyScratched) {
              iziNotify("Error!", 'Please scratch to reveal the reward first.', "error");
            }
            return;
          }
          navigator.clipboard.writeText(rewardText).then(() => {
            iziNotify("", 'Coupon code copied to clipboard!', "success");

          }).catch(err => {
            iziNotify("Error!", 'Failed to copy coupon code. Please try again.', "error");
          });
        });
      }
    });
  </script>
@endpush
