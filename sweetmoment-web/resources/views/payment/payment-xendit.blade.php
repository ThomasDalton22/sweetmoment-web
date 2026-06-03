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

        .payment-method-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .payment-method-btn {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 1.5rem 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .payment-method-btn:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .payment-method-btn.active {
            border-color: var(--primary-color);
            background: #f0f8ff;
        }

        .payment-method-btn i {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
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

        .payment-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .payment-modal.active {
            display: flex;
        }

        .payment-modal-content {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            max-width: 500px;
            width: 90%;
            text-align: center;
        }

        .payment-instructions {
            background: #fff8dc;
            padding: 1.5rem;
            border-radius: 10px;
            margin: 1rem 0;
            text-align: left;
        }

        .copy-button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 0.5rem;
        }

        @media (max-width: 768px) {
            .payment-container {
                margin: 1rem auto;
            }

            .order-summary,
            .payment-methods {
                padding: 1.5rem;
            }

            .payment-method-grid {
                grid-template-columns: repeat(2, 1fr);
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

        <div class="payment-card">
            <div class="payment-header">
                <h2><i class="bi bi-credit-card me-2"></i>Complete Your Payment</h2>
                <p class="mb-0">Secure payment powered by Xendit</p>
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
                <p class="text-muted">Select your preferred payment method. All transactions are secured with
                    encryption.</p>

                <div class="payment-method-grid">
                    <div class="payment-method-btn" data-method="credit_card">
                        <i class="bi bi-credit-card-2-front"></i>
                        <div><strong>Credit Card</strong></div>
                        <small class="text-muted">Visa, Mastercard</small>
                    </div>
                    <div class="payment-method-btn" data-method="bca_va">
                        <i class="bi bi-bank"></i>
                        <div><strong>BCA VA</strong></div>
                        <small class="text-muted">Virtual Account</small>
                    </div>
                    <div class="payment-method-btn" data-method="bni_va">
                        <i class="bi bi-bank"></i>
                        <div><strong>BNI VA</strong></div>
                        <small class="text-muted">Virtual Account</small>
                    </div>
                    <div class="payment-method-btn" data-method="bri_va">
                        <i class="bi bi-bank"></i>
                        <div><strong>BRI VA</strong></div>
                        <small class="text-muted">Virtual Account</small>
                    </div>
                    <div class="payment-method-btn" data-method="mandiri_va">
                        <i class="bi bi-bank"></i>
                        <div><strong>Mandiri VA</strong></div>
                        <small class="text-muted">Virtual Account</small>
                    </div>
                    <div class="payment-method-btn" data-method="e_wallet">
                        <i class="bi bi-wallet2"></i>
                        <div><strong>E-Wallet</strong></div>
                        <small class="text-muted">OVO, DANA, etc</small>
                    </div>
                    <div class="payment-method-btn" data-method="qris">
                        <i class="bi bi-qr-code"></i>
                        <div><strong>QRIS</strong></div>
                        <small class="text-muted">Scan QR Code</small>
                    </div>
                    <div class="payment-method-btn" data-method="retail">
                        <i class="bi bi-shop"></i>
                        <div><strong>Retail</strong></div>
                        <small class="text-muted">Alfamart, Indomaret</small>
                    </div>
                </div>

                <button type="button" class="pay-button" id="payButton" disabled>
                    <i class="bi bi-shield-check me-2"></i>
                    Select Payment Method
                </button>

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

    <!-- Payment Modal -->
    <div class="payment-modal" id="paymentModal">
        <div class="payment-modal-content">
            <div id="modalContent"></div>
            <button class="btn btn-secondary mt-3" onclick="closePaymentModal()">Close</button>
        </div>
    </div>

    <!-- Load scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.0/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // Global variables
        let selectedPaymentMethod = null;
        let currentInvoiceUrl = null;

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

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Initializing payment page...');
            setupPaymentMethodSelection();
            setupPayButton();
            startStatusCheck();
            console.log('✅ Payment page initialized successfully');
        });

        function setupPaymentMethodSelection() {
            const methodButtons = document.querySelectorAll('.payment-method-btn');
            const payButton = document.getElementById('payButton');

            methodButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    methodButtons.forEach(btn => btn.classList.remove('active'));

                    // Add active class to clicked button
                    this.classList.add('active');

                    // Store selected method
                    selectedPaymentMethod = this.getAttribute('data-method');

                    // Enable pay button
                    payButton.disabled = false;
                    payButton.innerHTML =
                        `<i class="bi bi-shield-check me-2"></i>Pay Now - Rp {{ number_format($order->total_price) }}`;
                });
            });
        }

        function setupPayButton() {
            const payButton = document.getElementById('payButton');

            payButton.addEventListener('click', function() {
                if (!selectedPaymentMethod) {
                    toastr.warning('Please select a payment method');
                    return;
                }

                initiatePayment();
            });
        }

        function initiatePayment() {
            const payButton = document.getElementById('payButton');

            payButton.disabled = true;
            payButton.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Processing...';

            axios.post('/payment/{{ $order->id }}/create', {
                    payment_method: selectedPaymentMethod
                })
                .then(function(response) {
                    console.log('✅ Payment creation response:', response.data);

                    if (response.data.success) {
                        handlePaymentResponse(response.data);
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

        function handlePaymentResponse(data) {
            currentInvoiceUrl = data.invoice_url;

            if (data.payment_method === 'credit_card' || data.payment_method === 'e_wallet') {
                // Redirect to Xendit hosted page
                window.location.href = data.invoice_url;
            } else if (data.payment_method === 'qris') {
                // Show QR code
                showQRISModal(data);
            } else {
                // Show VA/retail instructions
                showVAModal(data);
            }
        }

        function showQRISModal(data) {
            const modalContent = document.getElementById('modalContent');
            modalContent.innerHTML = `
                <h4><i class="bi bi-qr-code text-primary"></i> QRIS Payment</h4>
                <div class="payment-instructions">
                    <p><strong>Scan QR Code to Pay:</strong></p>
                    <div class="text-center my-3">
                        <img src="${data.qr_string}" alt="QRIS" style="max-width: 250px;" class="img-fluid">
                    </div>
                    <p class="text-muted">Open your e-wallet app and scan this QR code</p>
                    <p><strong>Amount:</strong> Rp ${data.amount.toLocaleString('id-ID')}</p>
                </div>
                <a href="${data.invoice_url}" target="_blank" class="btn btn-primary">
                    <i class="bi bi-box-arrow-up-right me-2"></i>View Full Instructions
                </a>
            `;

            document.getElementById('paymentModal').classList.add('active');
            toastr.success('Payment created! Scan the QR code to complete payment');
        }

        function showVAModal(data) {
            const modalContent = document.getElementById('modalContent');

            let paymentInfo = '';
            if (data.va_number) {
                paymentInfo = `
                    <div class="payment-instructions">
                        <p><strong>Virtual Account Number:</strong></p>
                        <div class="d-flex align-items-center justify-content-center my-3">
                            <h3 class="mb-0 text-primary">${data.va_number}</h3>
                            <button class="copy-button" onclick="copyToClipboard('${data.va_number}')">
                                <i class="bi bi-clipboard"></i> Copy
                            </button>
                        </div>
                        <p><strong>Bank:</strong> ${data.bank_name || 'Bank Transfer'}</p>
                        <p><strong>Amount:</strong> Rp ${data.amount.toLocaleString('id-ID')}</p>
                        <hr>
                        <p class="text-muted"><small>Transfer the exact amount to complete payment</small></p>
                    </div>
                `;
            } else if (data.retail_outlet_name) {
                paymentInfo = `
                    <div class="payment-instructions">
                        <p><strong>Payment Code:</strong></p>
                        <div class="d-flex align-items-center justify-content-center my-3">
                            <h3 class="mb-0 text-primary">${data.payment_code}</h3>
                            <button class="copy-button" onclick="copyToClipboard('${data.payment_code}')">
                                <i class="bi bi-clipboard"></i> Copy
                            </button>
                        </div>
                        <p><strong>Retail:</strong> ${data.retail_outlet_name}</p>
                        <p><strong>Amount:</strong> Rp ${data.amount.toLocaleString('id-ID')}</p>
                        <hr>
                        <p class="text-muted"><small>Show this code at the store to complete payment</small></p>
                    </div>
                `;
            }

            modalContent.innerHTML = `
                <h4><i class="bi bi-bank text-primary"></i> Payment Instructions</h4>
                ${paymentInfo}
                <a href="${data.invoice_url}" target="_blank" class="btn btn-primary">
                    <i class="bi bi-box-arrow-up-right me-2"></i>View Full Instructions
                </a>
            `;

            document.getElementById('paymentModal').classList.add('active');
            toastr.success('Payment created! Complete the payment using the instructions');
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.remove('active');
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                toastr.success('Copied to clipboard!');
            }).catch(function(error) {
                console.error('Copy failed:', error);
                toastr.error('Failed to copy');
            });
        }

        function resetPayButton() {
            const payButton = document.getElementById('payButton');
            if (payButton && selectedPaymentMethod) {
                payButton.disabled = false;
                payButton.innerHTML =
                    '<i class="bi bi-shield-check me-2"></i>Pay Now - Rp {{ number_format($order->total_price) }}';
            }
        }

        // Payment status checking
        let statusCheckInterval;
        let statusCheckCount = 0;
        const maxStatusChecks = 120; // Check for 10 minutes

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
                            closePaymentModal();
                            toastr.success('Payment confirmed! Redirecting...');

                            setTimeout(() => {
                                window.location.href = '/?route=orders';
                            }, 2000);
                        }
                    })
                    .catch(function(error) {
                        // Silent fail for status checks
                    });
            }, 5000); // Check every 5 seconds
        }

        // Clean up on page unload
        window.addEventListener('beforeunload', function() {
            if (statusCheckInterval) {
                clearInterval(statusCheckInterval);
            }
        });
    </script>
</body>

</html>
