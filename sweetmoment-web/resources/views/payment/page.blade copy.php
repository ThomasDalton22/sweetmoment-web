<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment - Sweet Moments</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #116d6e;
            --accent-color: #bba016;
            --success-color: #28a745;
            --danger-color: #dc3545;
        }

        .test-notice {
            display: none !important;
        }

        .payment-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .payment-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .payment-header {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .order-summary {
            padding: 2rem;
            border-bottom: 1px solid #eee;
        }

        .payment-methods {
            padding: 2rem;
        }

        .vendor-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .vendor-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .price-breakdown {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 10px;
            margin: 1rem 0;
        }

        .pay-button {
            background: linear-gradient(135deg, var(--success-color), #20c997);
            border: none;
            color: white;
            padding: 1rem 2rem;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }

        .pay-button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        }

        .pay-button:disabled {
            background: #6c757d;
            transform: none;
            box-shadow: none;
            cursor: not-allowed;
        }

        .security-info {
            background: #e3f2fd;
            padding: 1rem;
            border-radius: 10px;
            margin-top: 1rem;
        }

        .back-link {
            color: var(--primary-color);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .debug-info {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 1rem;
            margin-bottom: 1rem;
            font-family: monospace;
            font-size: 0.875rem;
        }

        .fallback-link {
            background: #17a2b8;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
            width: 100%;
            text-align: center;
        }

        @media (max-width: 768px) {
            .payment-container {
                margin: 1rem auto;
            }

            .order-summary,
            .payment-methods {
                padding: 1.5rem;
            }
        }

        @media (max-width: 969px) {
            .overlay2 {
                display: none;

            }
        }
    </style>
</head>

<body style="background-color: #f8f9fa;">

    <div class="overlay"
        style="position: fixed; top: 0; left: 0; width: 100%; height: 70px; background: #002855; z-index: 9999999;">
    </div>

    <div class="overlay2"
        style="position: fixed; top: 70px; right: 0; width: 100px; height: 70px; background: #F8F9FA; z-index: 9999999;">
    </div>

    <div class="payment-container mt-5">
        <a href="{{ route('home') }}" class="back-link">
            <i class="bi bi-arrow-left"></i>
            Back to Orders
        </a>

        <!-- Debug Info -->
        {{-- @if (config('app.debug'))
            <div class="debug-info">
                <strong>🐛 Debug Information:</strong><br>
                <div id="debugInfo">
                    Client Key: {{ config('services.midtrans.client_key') ? 'SET ✅' : 'NOT SET ❌' }}<br>
                    Environment: {{ config('services.midtrans.is_production') ? 'Production 🟢' : 'Sandbox 🟡' }}<br>
                    Expected Snap URL: <span id="expectedSnapUrl"></span><br>
                    Snap Object Available: <span id="snapStatus">Checking...</span><br>
                    Order ID: {{ $order->id }}<br>
                    Amount: Rp {{ number_format($order->total_price) }}
                </div>
            </div>
        @endif --}}

        <div class="payment-card">
            <div class="payment-header">
                <h2><i class="bi bi-credit-card me-2"></i>Complete Your Payment</h2>
                <p class="mb-0">Secure payment powered by Midtrans</p>
            </div>

            <div class="order-summary">
                <h4>Order Summary</h4>

                <div class="vendor-info">
                    <div class="vendor-avatar">
                        {{ strtoupper(substr($order->vendorPackage->vendorProfile->business_name, 0, 2)) }}
                    </div>
                    <div>
                        <h6 class="mb-1">{{ $order->vendorPackage->vendorProfile->business_name }}</h6>
                        <small class="text-muted">{{ $order->vendorPackage->vendorProfile->category->name }}</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <h6>{{ $order->vendorPackage->name }}</h6>
                        <p class="text-muted mb-2">{{ $order->vendorPackage->description }}</p>

                        <div class="mb-2">
                            <strong>Event Date:</strong> {{ date('M d, Y', strtotime($order->event_date)) }}
                        </div>

                        <div class="mb-2">
                            <strong>Customer:</strong> {{ $order->name }}
                        </div>

                        <div class="mb-2">
                            <strong>Phone:</strong> {{ $order->phone }}
                        </div>

                        @if ($order->notes)
                            <div class="mb-2">
                                <strong>Notes:</strong> {{ $order->notes }}
                            </div>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <div class="price-breakdown">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Package Price:</span>
                                <span>Rp {{ number_format($order->vendorPackage->price) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Quantity:</span>
                                <span>{{ $order->qty }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total:</strong>
                                <strong class="text-success">Rp {{ number_format($order->total_price) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="payment-methods">
                <h5>Choose Payment Method</h5>
                <p class="text-muted">Select your preferred payment method. All transactions are secured with 256-bit
                    SSL encryption.</p>

                <button type="button" class="pay-button" id="payButton">
                    <i class="bi bi-shield-check me-2"></i>
                    Pay Now - Rp {{ number_format($order->total_price) }}
                </button>

                <!-- Fallback link for when popup doesn't work -->
                <div id="fallbackContainer" style="display: none;">
                    <p class="text-warning mt-3">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Popup blocked? Use direct payment link:
                    </p>
                    <a href="#" id="fallbackLink" class="fallback-link" target="_blank">
                        <i class="bi bi-external-link me-2"></i>
                        Open Payment Page
                    </a>
                </div>

                <div class="security-info">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-shield-fill-check text-primary me-2"></i>
                        <small>
                            <strong>Secure Payment:</strong> Your payment information is encrypted and secure.
                            We support all major payment methods including credit cards, bank transfers, e-wallets, and
                            more.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Load scripts in the correct order -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.0/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Midtrans Snap - Load this AFTER other scripts -->
    <script>
        // Global variables
        let midtransClientKey = '{{ config('services.midtrans.client_key') }}';
        let isProduction = false;
        let currentSnapToken = null;
        let snapRetryCount = 0;
        const maxSnapRetries = 3;

        // Configure Toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
            "preventDuplicates": true
        };

        // Configure Axios
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute(
            'content');
        axios.defaults.timeout = 30000;

        // Function to load Midtrans Snap script dynamically
        function loadSnapScript() {
            return new Promise((resolve, reject) => {
                // Check if snap is already loaded
                if (window.snap) {
                    console.log('✅ Snap already loaded');
                    resolve(window.snap);
                    return;
                }

                const snapUrl = isProduction ?
                    'https://app.midtrans.com/snap/snap.js' :
                    'https://app.sandbox.midtrans.com/snap/snap.js';

                console.log('📦 Loading Snap from:', snapUrl);

                const script = document.createElement('script');
                script.src = snapUrl;
                script.setAttribute('data-client-key', midtransClientKey);

                script.onload = function() {
                    console.log('✅ Snap script loaded successfully');
                    // Wait a bit for snap to initialize
                    setTimeout(() => {
                        if (window.snap) {
                            console.log('✅ Snap object available');
                            resolve(window.snap);
                        } else {
                            console.error('❌ Snap object not available after script load');
                            reject(new Error('Snap object not available'));
                        }
                    }, 1000);
                };

                script.onerror = function(error) {
                    console.error('❌ Failed to load Snap script:', error);
                    reject(new Error('Failed to load Snap script'));
                };

                document.head.appendChild(script);
            });
        }

        // Function to retry loading Snap
        async function loadSnapWithRetry() {
            for (let i = 0; i < maxSnapRetries; i++) {
                try {
                    await loadSnapScript();
                    return true;
                } catch (error) {
                    console.error(`❌ Snap load attempt ${i + 1} failed:`, error);
                    if (i < maxSnapRetries - 1) {
                        console.log('🔄 Retrying...');
                        await new Promise(resolve => setTimeout(resolve, 2000));
                    }
                }
            }
            return false;
        }

        // Function to update debug info
        function updateDebugInfo() {

        }

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', async function() {
            console.log('🚀 Initializing payment page...');
            console.log('Client Key:', midtransClientKey);
            console.log('Environment:', isProduction ? 'Production' : 'Sandbox');

            updateDebugInfo();

            // Load Snap script
            const snapLoaded = await loadSnapWithRetry();

            if (!snapLoaded) {
                console.error('❌ Failed to load Snap after all retries');
                toastr.error('Failed to load payment system. Please refresh the page.');
                showFallbackOption();
                return;
            }

            updateDebugInfo();
            setupPayButton();
            console.log('✅ Payment page initialized successfully');
        });

        function setupPayButton() {
            const payButton = document.getElementById('payButton');

            payButton.addEventListener('click', function() {
                if (!window.snap) {
                    console.error('❌ Snap not available when pay button clicked');
                    toastr.error('Payment system not ready. Please refresh the page.');
                    showFallbackOption();
                    return;
                }

                initiatePayment();
            });
        }

        function initiatePayment() {
            const payButton = document.getElementById('payButton');

            payButton.disabled = true;
            payButton.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Processing...';

            // console.log('💳 Creating payment...');
            // toastr.info('Preparing payment...');

            axios.post('/payment/{{ $order->id }}/create')
                .then(function(response) {
                    console.log('✅ Payment creation response:', response.data);

                    if (response.data.success && response.data.snap_token) {
                        currentSnapToken = response.data.snap_token;
                        console.log('🎫 Snap token received');



                        openPaymentWindow(response.data.snap_token);
                    } else {
                        console.error('❌ Payment creation failed:', response.data);
                        toastr.error(response.data.message || 'Failed to create payment');
                        resetPayButton();
                    }
                })
                .catch(function(error) {
                    console.error('❌ Payment creation error:', error);

                    let errorMessage = 'Failed to create payment';
                    if (error.response && error.response.data && error.response.data.message) {
                        errorMessage = error.response.data.message;
                    }

                    toastr.error(errorMessage);
                    resetPayButton();
                });
        }

        function openPaymentWindow(snapToken) {
            console.log('🪟 Opening payment window...');

            if (!window.snap) {
                console.error('❌ Snap not available when trying to open payment');
                toastr.error('Payment system not ready');
                showFallbackOption();
                resetPayButton();
                return;
            }

            try {
                // Set a timeout to detect if popup is blocked
                let popupTimeout = setTimeout(() => {
                    console.warn('⚠️ Payment popup might be blocked');
                    showFallbackOption();
                }, 3000);

                $(".overlay2").css("background", "#7C7C7D");

                window.snap.pay(snapToken, {
                    onSuccess: function(result) {
                        clearTimeout(popupTimeout);
                        console.log('✅ Payment success:', result);
                        toastr.success('Payment successful! Redirecting...');

                        setTimeout(() => {
                            window.location.href = '/?route=orders';
                        }, 2000);
                    },

                    onPending: function(result) {
                        clearTimeout(popupTimeout);
                        console.log('⏳ Payment pending:', result);
                        toastr.info('Payment is being processed...');

                        setTimeout(() => {
                            window.location.href = '/?route=orders';
                        }, 2000);
                    },

                    onError: function(result) {
                        clearTimeout(popupTimeout);
                        console.error('❌ Payment error:', result);

                        let errorMsg = 'Payment failed';
                        if (result && result.status_message) {
                            errorMsg += ': ' + result.status_message;
                        }

                        toastr.error(errorMsg);
                        resetPayButton();
                    },

                    onClose: function() {
                        $(".overlay2").css("background", "#F8F9FA");
                        clearTimeout(popupTimeout);
                        console.log('❌ Payment popup closed');
                        toastr.warning('Payment cancelled');
                        resetPayButton();
                    }
                });

            } catch (snapError) {
                console.error('❌ Snap.pay error:', snapError);
                toastr.error('Failed to open payment window');
                showFallbackOption();
                resetPayButton();
            }
        }

        function showFallbackOption() {
            if (!currentSnapToken) return;

            const fallbackContainer = document.getElementById('fallbackContainer');
            const fallbackLink = document.getElementById('fallbackLink');

            const fallbackUrl = isProduction ?
                `https://app.midtrans.com/snap/v4/redirection/${currentSnapToken}` :
                `https://app.sandbox.midtrans.com/snap/v4/redirection/${currentSnapToken}`;

            fallbackLink.href = fallbackUrl;
            fallbackContainer.style.display = 'block';

            console.log('🔗 Fallback link available:', fallbackUrl);
        }

        function resetPayButton() {
            const payButton = document.getElementById('payButton');
            if (payButton) {
                payButton.disabled = false;
                payButton.innerHTML =
                    '<i class="bi bi-shield-check me-2"></i>Pay Now - Rp {{ number_format($order->total_price) }}';
            }
        }

        // Payment status checking
        let statusCheckInterval;
        let statusCheckCount = 0;
        const maxStatusChecks = 60;

        function startStatusCheck() {
            statusCheckInterval = setInterval(function() {
                statusCheckCount++;

                if (statusCheckCount > maxStatusChecks) {
                    clearInterval(statusCheckInterval);
                    return;
                }

                axios.get('/payment/check/{{ $order->id }}')
                    .then(function(response) {
                        if (response.data.success && response.data.status === 'Paid') {
                            clearInterval(statusCheckInterval);
                            toastr.success('Payment confirmed! Redirecting...');

                            setTimeout(() => {
                                window.location.href = '/?route=orders';
                            }, 2000);
                        }
                    })
                    .catch(function(error) {
                        // Silent fail for status checks
                    });
            }, 5000);
        }

        // Start status check
        startStatusCheck();

        // Clean up on page unload
        window.addEventListener('beforeunload', function() {
            if (statusCheckInterval) {
                clearInterval(statusCheckInterval);
            }
        });

        // Debug logging
        @if (config('app.debug'))
            console.log('=== 🐛 PAYMENT DEBUG INFO ===');
            console.log('Client Key:', midtransClientKey);
            console.log('Environment:', isProduction ? 'Production' : 'Sandbox');
            console.log('Order ID:', '{{ $order->id }}');
            console.log('Amount:', {{ $order->total_price }});
            console.log('============================');
        @endif
    </script>
</body>

</html>
