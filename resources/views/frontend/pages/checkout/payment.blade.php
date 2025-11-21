<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stripe Checkout</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Stripe.js -->
  <script src="https://js.stripe.com/v3/"></script>
  <style>
    #card-element {
      border: 1px solid #ced4da;
      padding: 10px;
      border-radius: 0.375rem;
      background-color: #fff;
    }

    .btn-pay:disabled {
      background-color: #6c757d;
      border-color: #6c757d;
      cursor: not-allowed;
    }
  </style>
</head>

<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card shadow-sm">
          <div class="card-body p-4">
            <h1 class="card-title text-center mb-4">Stripe Payment</h1>

            @if (session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif
            @if (session('error'))
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            <form action="{{ route('pay') }}" method="POST" id="payment-form">
              @csrf
              <div class="mb-4">
                <h5 class="mb-3">Order Details</h5>
                <p class="mb-1"><strong>Order Number:</strong> {{ $paymentDetails['order']['order_number'] }}</p>
                <p class="mb-0"><strong>Order Total:</strong>
                  {{ displayPrice($paymentDetails['order']['net_total']) }}</p>
                <input type="hidden" name="order_number" value="{{ $paymentDetails['order']['order_number'] }}">
              </div>
              <div class="mb-4">
                <label for="card-element" class="form-label">Credit or Debit Card</label>
                <div id="card-element" class="form-control"></div>
              </div>
              <button type="submit" class="btn btn-pay w-100 btn-success" id="pay-button">
                <span id="button-text">Pay Now</span>
                <span id="button-spinner" class="spinner-border spinner-border-sm d-none" role="status"
                  aria-hidden="true"></span>
              </button>
              <span id="button-spinner" class="spinner-border spinner-border-sm d-none" role="status"
                aria-hidden="true"></span>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const stripe = Stripe('{{ $paymentDetails['stripePublicKey'] }}');
    const elements = stripe.elements();
    const cardElement = elements.create('card', {
      style: {
        base: {
          fontSize: '16px',
          color: '#495057',
          '::placeholder': {
            color: '#6c757d',
          },
        },
      },
    });
    cardElement.mount('#card-element');

    const form = document.getElementById('payment-form');
    const payButton = document.getElementById('pay-button');
    const buttonText = document.getElementById('button-text');
    const buttonSpinner = document.getElementById('button-spinner');

    form.addEventListener('submit', async (event) => {
      event.preventDefault();
      payButton.disabled = true;
      buttonText.classList.add('d-none');
      buttonSpinner.classList.remove('d-none');

      const {
        token,
        error
      } = await stripe.createToken(cardElement);
      if (error) {
        payButton.disabled = false;
        buttonText.classList.remove('d-none');
        buttonSpinner.classList.add('d-none');

        const errorElement = document.createElement('div');
        errorElement.className = 'alert alert-danger mt-3';
        errorElement.textContent = error.message;
        form.prepend(errorElement);
        return;
      } else {
        const hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);
        form.submit();
      }
    });
  </script>
</body>

</html>
