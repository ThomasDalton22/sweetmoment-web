<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification as MidtransNotification;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function payOrder($orderId)
    {
        try {
            $order = Order::with(['vendorPackage.vendorProfile.user', 'user'])
                ->where('id', $orderId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if ($order->status === 'Paid') {
                return redirect()->route('home')
                    ->with('error', 'This order has already been paid');
            }

            // Create payment page
            return view('payment.page', compact('order'));
        } catch (\Exception $e) {
            Log::error('Payment page error: ' . $e->getMessage());
            return redirect()->route('home')
                ->with('error', 'Order not found or access denied');
        }
    }

    public function createPayment(Request $request, $orderId)
    {
        try {
            $order = Order::with(['vendorPackage.vendorProfile', 'user'])
                ->where('id', $orderId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if ($order->status === 'Paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Order already paid'
                ], 400);
            }

            // Create unique transaction ID
            $transactionId = 'SM-' . $orderId . '-' . time();

            // Prepare transaction details
            $transactionDetails = [
                'order_id' => $transactionId,
                'gross_amount' => (int) $order->total_price,
            ];

            // Prepare item details
            $itemDetails = [
                [
                    'id' => $order->vendor_package_id,
                    'price' => (int) $order->vendorPackage->price,
                    'quantity' => $order->qty,
                    'name' => $order->vendorPackage->name,
                    'brand' => $order->vendorPackage->vendorProfile->business_name,
                    'category' => $order->vendorPackage->vendorProfile->category->name ?? 'Wedding Service',
                ]
            ];

            // Prepare customer details
            $customerDetails = [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->phone,
                'billing_address' => [
                    'first_name' => $order->user->name,
                    'address' => $order->address,
                    'phone' => $order->phone,
                ],
                'shipping_address' => [
                    'first_name' => $order->user->name,
                    'address' => $order->address,
                    'phone' => $order->phone,
                ]
            ];

            // Prepare transaction data
            $transactionData = [
                'transaction_details' => $transactionDetails,
                'item_details' => $itemDetails,
                'customer_details' => $customerDetails,
                'enabled_payments' => [
                    'credit_card',
                    'mandiri_clickpay',
                    'cimb_clicks',
                    'bca_klikbca',
                    'bca_klikpay',
                    'bri_epay',
                    'echannel',
                    'permata_va',
                    'bca_va',
                    'bni_va',
                    'other_va',
                    'gopay',
                    'indomaret',
                    'danamon_online',
                    'akulaku'
                ],
                'vtweb' => [],
                'callbacks' => [
                    'finish' => route('payment.finish'),
                    'unfinish' => route('payment.unfinish'),
                    'error' => route('payment.error'),
                ]
            ];

            // Save transaction ID to order
            $order->update([
                'transaction_id' => $transactionId,
                'payment_status' => 'pending'
            ]);

            // Get Snap Token
            $snapToken = Snap::getSnapToken($transactionData);

            // Log payment creation
            Log::info('Payment created', [
                'order_id' => $orderId,
                'transaction_id' => $transactionId,
                'amount' => $order->total_price,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'transaction_id' => $transactionId
            ]);
        } catch (\Exception $e) {
            Log::error('Payment creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment'
            ], 500);
        }
    }

    public function handleNotification(Request $request)
    {
        try {
            $notification = new MidtransNotification();

            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? '';
            $orderId = $notification->order_id;
            $transactionId = $notification->transaction_id;

            // Find order by transaction ID
            $order = Order::where('transaction_id', $orderId)->first();

            if (!$order) {
                Log::error('Order not found for transaction: ' . $orderId);
                return response('Order not found', 404);
            }

            Log::info('Payment notification received', [
                'order_id' => $order->id,
                'transaction_id' => $orderId,
                'status' => $transactionStatus,
                'fraud_status' => $fraudStatus
            ]);

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $this->updateOrderStatus($order, 'Paid', 'success');
                }
            } elseif ($transactionStatus == 'settlement') {
                $this->updateOrderStatus($order, 'Paid', 'success');
            } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                $this->updateOrderStatus($order, 'Cancelled', 'failed');
            } elseif ($transactionStatus == 'pending') {
                $this->updateOrderStatus($order, 'Pending Payment', 'pending');
            }

            return response('OK', 200);
        } catch (\Exception $e) {
            Log::error('Payment notification error: ' . $e->getMessage());
            return response('Error', 500);
        }
    }

    private function updateOrderStatus($order, $status, $paymentStatus)
    {
        DB::transaction(function () use ($order, $status, $paymentStatus) {
            $order->update([
                'status' => $status,
                'payment_status' => $paymentStatus,
                // 'paid_at' => $status === 'Paid' ? now() : null
            ]);

            // Create notification for user
            Notification::createForUser(
                $order->user_id,
                'payment_update',
                'Payment Update',
                "Your payment for order #{$order->id} is {$status}",
                [
                    'order_id' => $order->id,
                    'status' => $status,
                    'amount' => $order->total_price
                ]
            );

            // Create notification for vendor if paid
            if ($status === 'Paid') {
                Notification::createForUser(
                    $order->vendorPackage->vendorProfile->user_id,
                    'new_order',
                    'New Paid Order',
                    "You have received a new paid order from {$order->user->name}",
                    [
                        'order_id' => $order->id,
                        'customer_name' => $order->user->name,
                        'package_name' => $order->vendorPackage->name,
                        'amount' => $order->total_price
                    ]
                );
            }

            Log::info('Order status updated', [
                'order_id' => $order->id,
                'new_status' => $status,
                'payment_status' => $paymentStatus
            ]);
        });
    }

    public function paymentFinish(Request $request)
    {
        $orderId = $request->get('order_id');
        $statusCode = $request->get('status_code');
        $transactionStatus = $request->get('transaction_status');

        return view('payment.finish', compact('orderId', 'statusCode', 'transactionStatus'));
    }

    public function paymentUnfinish(Request $request)
    {
        return view('payment.unfinish');
    }

    public function paymentError(Request $request)
    {
        return view('payment.error');
    }

    public function checkPaymentStatus($orderId)
    {
        try {
            $order = Order::where('id', $orderId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'status' => $order->status,
                'payment_status' => $order->payment_status ?? 'pending'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }
    }
}
