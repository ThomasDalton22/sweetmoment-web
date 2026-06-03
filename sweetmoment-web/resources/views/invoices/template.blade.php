<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            color: #333;
            line-height: 1.6;
        }

        .container {
            width: 100%;
            max-width: 720px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            margin-bottom: 30px;
            border-bottom: 3px solid #116d6e;
            padding-bottom: 20px;
        }

        .company-info {
            float: left;
            width: 50%;
        }

        .company-name {
            font-size: 24pt;
            font-weight: bold;
            color: #116d6e;
            margin-bottom: 5px;
        }

        .company-tagline {
            font-size: 10pt;
            color: #666;
            margin-bottom: 10px;
        }

        .company-details {
            font-size: 9pt;
            color: #666;
        }

        .invoice-info {
            float: right;
            width: 45%;
            text-align: right;
        }

        .invoice-title {
            font-size: 28pt;
            font-weight: bold;
            color: #116d6e;
            margin-bottom: 10px;
        }

        .invoice-details {
            font-size: 10pt;
            color: #666;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #116d6e;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #bba016;
        }

        .customer-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .customer-info table {
            width: 100%;
        }

        .customer-info td {
            padding: 5px;
            font-size: 10pt;
        }

        .customer-info td:first-child {
            font-weight: bold;
            width: 120px;
            color: #555;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.items thead {
            background-color: #116d6e;
            color: white;
        }

        table.items th,
        table.items td {
            padding: 12px 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table.items th {
            font-weight: bold;
            font-size: 10pt;
        }

        table.items td {
            font-size: 10pt;
        }

        table.items tbody tr:hover {
            background-color: #f8f9fa;
        }

        table.items tbody tr:last-child td {
            border-bottom: 2px solid #116d6e;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .summary {
            float: right;
            width: 45%;
            margin-top: 20px;
        }

        .summary table {
            width: 100%;
            font-size: 10pt;
        }

        .summary td {
            padding: 8px 10px;
        }

        .summary td:first-child {
            text-align: right;
            font-weight: bold;
            color: #555;
        }

        .summary td:last-child {
            text-align: right;
            width: 40%;
        }

        .summary .total-row {
            border-top: 2px solid #116d6e;
            font-size: 12pt;
            font-weight: bold;
        }

        .summary .total-row td {
            padding-top: 15px;
            color: #116d6e;
        }

        .payment-info {
            clear: both;
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-top: 30px;
            border-left: 4px solid #116d6e;
        }

        .payment-info h4 {
            color: #116d6e;
            font-size: 11pt;
            margin-bottom: 8px;
        }

        .payment-info p {
            font-size: 9pt;
            margin: 3px 0;
            color: #555;
        }

        .notes {
            margin-top: 20px;
            padding: 15px;
            background-color: #fff8dc;
            border-radius: 5px;
            border-left: 4px solid #bba016;
        }

        .notes h4 {
            color: #bba016;
            font-size: 11pt;
            margin-bottom: 8px;
        }

        .notes p {
            font-size: 9pt;
            color: #555;
            font-style: italic;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            text-align: center;
            font-size: 9pt;
            color: #999;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            background-color: #28a745;
            color: white;
            border-radius: 20px;
            font-size: 10pt;
            font-weight: bold;
            margin-top: 5px;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80pt;
            color: rgba(17, 109, 110, 0.05);
            font-weight: bold;
            z-index: -1;
        }
    </style>
</head>

<body>
    <div class="watermark">SWEET MOMENTS</div>

    <div class="container">
        <!-- Header -->
        <div class="header clearfix">
            <div class="company-info">
                <div class="company-name">{{ $company_name }}</div>
                <div class="company-tagline">Your Perfect Wedding Partner</div>
                <div class="company-details">
                    {{ $company_address }}<br>
                    Phone: {{ $company_phone }}<br>
                    Email: {{ $company_email }}
                </div>
            </div>
            <div class="invoice-info">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-details">
                    <strong>Invoice Number:</strong> {{ $invoice_number }}<br>
                    <strong>Invoice Date:</strong> {{ $invoice_date }}<br>
                    <strong>Order ID:</strong> #{{ $order->id }}<br>
                    <span class="status-badge">PAID</span>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="section">
            <div class="section-title">Bill To</div>
            <div class="customer-info">
                <table>
                    <tr>
                        <td>Customer Name:</td>
                        <td>{{ $order->name }}</td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td>{{ $order->user->email }}</td>
                    </tr>
                    <tr>
                        <td>Phone:</td>
                        <td>{{ $order->phone }}</td>
                    </tr>
                    <tr>
                        <td>Address:</td>
                        <td>{{ $order->address }}</td>
                    </tr>
                    <tr>
                        <td>Event Date:</td>
                        <td>{{ \Carbon\Carbon::parse($order->event_date)->format('d F Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Order Details -->
        <div class="section">
            <div class="section-title">Order Details</div>
            <table class="items" style="width: 100%">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Vendor</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-right">Unit Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>{{ $order->vendorPackage->name }}</strong><br>
                            <small style="color: #666;">{{ $order->vendorPackage->description }}</small>
                        </td>
                        <td>
                            {{ $order->vendorPackage->vendorProfile->business_name }}<br>
                            <small
                                style="color: #666;">{{ $order->vendorPackage->vendorProfile->category->name ?? 'Wedding Service' }}</small>
                        </td>
                        <td class="text-center">{{ $order->qty }}</td>
                        <td class="text-right">Rp {{ number_format($order->vendorPackage->price, 0, ',', '.') }}</td>
                        <td class="text-right"><strong>Rp
                                {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="summary">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Tax (0%):</td>
                    <td>Rp 0</td>
                </tr>
                <tr class="total-row">
                    <td>TOTAL:</td>
                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div style="clear: both;"></div>

        <!-- Payment Information -->
        <div class="payment-info">
            <h4><strong>✓ Payment Confirmed</strong></h4>
            <p><strong>Transaction ID:</strong> {{ $order->transaction_id ?? 'N/A' }}</p>
            <p><strong>Payment Date:</strong> {{ $order->updated_at->format('d F Y, H:i') }} WIB</p>
            <p><strong>Payment Status:</strong> Successfully Paid</p>
        </div>

        <!-- Notes -->
        @if ($order->notes)
            <div class="notes">
                <h4>Special Notes</h4>
                <p>{{ $order->notes }}</p>
            </div>
        @endif

        <!-- Terms and Conditions -->
        <div class="section" style="margin-top: 30px;">
            <div class="section-title">Terms & Conditions</div>
            <div style="font-size: 9pt; color: #666; line-height: 1.8;">
                <p>1. This invoice serves as official payment confirmation for your wedding service order.</p>
                <p>2. Please contact the vendor directly for any service-related inquiries or changes.</p>
                <p>3. Cancellation policy is subject to the vendor's terms and conditions.</p>
                <p>4. Keep this invoice for your records and present it to the vendor on the event date.</p>
                <p>5. For any billing questions, please contact our support at {{ $company_email }}.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for choosing Sweet Moments for your special day!</p>
            <p>This is a computer-generated invoice and does not require a signature.</p>
            <p style="margin-top: 10px;">
                © {{ date('Y') }} Sweet Moments. All rights reserved.<br>
                Generated on {{ now()->format('d F Y, H:i') }} WIB
            </p>
        </div>
    </div>
</body>

</html>
