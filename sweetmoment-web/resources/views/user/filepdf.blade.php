<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wedding Offer</title>
    <style>
        /* Reset basic styles */
        body, h1, p, table, th, td {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        /* Page Setup */
        body {
            background-color: #f4f4f4;
            padding: 50px;
            font-size: 16px;
        }

        /* Container for the content */
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
        }

        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 36px;
            color: #4caf50;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 18px;
            color: #777;
        }

        /* Body Section */
        .body-content {
            margin-top: 20px;
        }

        /* Table for the wedding details */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
            color: #333;
            font-weight: bold;
        }

        td {
            background-color: #fafafa;
        }

        .table-label {
            font-weight: bold;
        }

        /* Signature Section */
        .signature {
            text-align: right;
            margin-top: 40px;
            font-size: 18px;
            color: #333;
            font-style: italic;
        }

        .signature p {
            margin-bottom: 5px;
        }

        /* Footer Section */
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #888;
        }

        .footer p {
            margin: 0;
        }

        /* Price Formatting */
        .price {
            font-weight: bold;
            font-size: 20px;
            color: #e91e63;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <h1>Vendor Offer</h1>
            <p>by SweetMoments</p>
        </div>

        <!-- Body Content Section -->
        <div class="body-content">
            <table>
                <tr>
                    <th>Type of Offering</th>
                    <td>{{ $vendor_offer->jenispenawaran }}</td>
                </tr>
                <tr>
                    <th>Estimated Budget</th>
                    <td><span class="price">{{ number_format($vendor_offer->budget, 0, ',', '.') }}</span></td>
                </tr>
                <tr>
                    <th>Catatan</th>
                    <td>{{ $vendor_offer->catatan }}</td>
                </tr>
                <tr>
                    <th>Diajukan oleh</th>
                    <td>{{ $vendor_offer->user->name }}</td> <!-- Menggunakan $wedding->user->name untuk mendapatkan nama user yang membuat wedding -->
                </tr>
            </table>
        </div>

        <!-- Signature Section -->
        <div class="signature">
            <p>Salam Hangat,</p>
            <p><strong>{{ $vendor_offer->user->name }}</strong></p> <!-- Menampilkan nama user yang membuat wedding -->
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p>&copy; 2024 SweetMoments Organizers. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
