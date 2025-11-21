<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayPal Checkout</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #paypal-button-container {
            margin-top: 20px;
            position: relative;
        }

        .spinner {
            display: none;
            position: absolute;
            top: 58%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 3rem;
            height: 3rem;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            z-index: 9999;
        }
        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="card-title text-center mb-4">PayPal Payment</h1>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="mb-4">
                            <h5 class="mb-3">Order Details</h5>
                            <p><strong>Order Number:</strong> {{ $paymentDetails['order']['order_number'] }}</p>
                            <p><strong>Order Total:</strong> {{ displayPrice($paymentDetails['order']['net_total']) }}</p>
                        </div>

                        <div id="paypal-button-container">
                            <div id="spinner" class="spinner"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Scripts - Footer -->
    <script type="text/javascript" src="{{ asset('/public/frontend/assets/js/alert.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let isLoading = false;
        let paypalButtonInstance = null;
        // Dynamically load PayPal SDK
        function loadPayPalSDK(callback) {
            const script = document.createElement('script');
            script.src = "https://www.paypal.com/sdk/js?client-id={{ $paymentDetails['paypalClientId'] }}&currency={{ $paymentDetails['currencyCode'] }}&components=buttons,hosted-fields";
            script.onload = callback;
            script.onerror = function() {
                iziNotify('Oops!', 'Failed to load PayPal SDK', 'error');
            };
            document.body.appendChild(script);
        }

        // Load SDK and initialize PayPal buttons
        loadPayPalSDK(function () {
            paypal.Buttons({
                createOrder: function(data, actions) {
                    if (!isLoading) {
                        isLoading = true;
                        $('#spinner').show();
                    }
                    return fetch('{{ route('paypal.create') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_number: '{{ $paymentDetails['order']['order_number'] }}'
                        })
                    }).then(function(res) {
                        if (!res.ok) {
                            throw new Error('Failed to create order: ' + res.status);
                        }
                        return res.json();
                    }).then(function(data) {
                        if (data.error) {
                            throw new Error(data.error);
                        }
                        return data.id;
                    }).finally(function() {
                        isLoading = false;
                        $('#spinner').hide();
                    });
                },
                onClick: function() {
                    if (!isLoading) {
                        isLoading = true;
                        $('#spinner').show();
                    }
                },
                onApprove: function(data) {
                    if (paypalButtonInstance) {
                        paypalButtonInstance.disable();
                    }
                    $('#spinner').show();
                    return fetch('{{ route('paypal.capture') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_id: data.orderID,
                            order_number: '{{ $paymentDetails['order']['order_number'] }}'
                        })
                    }).then(function(res) {
                        if (!res.ok) {
                            throw new Error('Failed to capture payment: ' + res.status);
                        }
                        return res.json();
                    }).then(function(details) {
                        $('#spinner').hide();
                        if (details.success && details.redirect) {
                            window.location.href = details.redirect;
                        } else {
                            iziNotify('Oops!', details.error || 'Unknown error occurred', 'error');
                        }
                    }).catch(function(err) {
                        $('#spinner').hide();
                        iziNotify('Oops!', err.message, 'error');
                    }).finally(function() {
                        isLoading = false;
                        if (paypalButtonInstance) {
                            paypalButtonInstance.enable();
                        }
                    });
                },
                onError: function(err) {
                    $('#spinner').hide();
                    iziNotify('Oops!', err.message, 'error');
                    isLoading = false;
                }
            }).render('#paypal-button-container').then(function(instance) {
                paypalButtonInstance = instance;
            });
        });
    </script>
</body>
</html>
