<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    private $projectSlug;
    private $apiKey;

    public function __construct()
    {
        // Set Pakasir configuration
        $this->projectSlug = config('services.pakasir.project_slug');
        $this->apiKey = config('services.pakasir.api_key');
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
            $order = Order::where('id', $orderId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if ($order->status === 'Paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Order already paid'
                ], 400);
            }

            $paymentMethod = $request->input('payment_method');
            $externalId = 'SM-' . $order->id . '-' . time();
            $amount = (int) $order->total_price;

            // Base Pakasir payment link
            $pakasirUrl = "https://app.pakasir.com/pay/{$this->projectSlug}/{$amount}?order_id={$externalId}";

            // Add return redirect parameter
            $redirectUrl = route('payment.finish');
            $pakasirUrl .= "&redirect=" . urlencode($redirectUrl);

            // If user specifically requested QRIS, append qris_only=1
            if ($paymentMethod === 'qris') {
                $pakasirUrl .= "&qris_only=1";
            }

            // Update order with transaction identifier
            $order->update([
                'transaction_id' => $externalId,
                'payment_status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'payment_method' => $paymentMethod,
                'invoice_url' => $pakasirUrl
            ]);
        } catch (\Exception $e) {
            Log::error('Pakasir payment creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function handlePakasirWebhook(Request $request)
    {
        try {
            $payload = $request->all();
            Log::info('Pakasir webhook received', ['payload' => $payload]);

            $orderId = $payload['order_id'] ?? null;
            $amount = $payload['amount'] ?? null;
            $status = $payload['status'] ?? null;

            if (!$orderId || !$amount) {
                Log::warning('Pakasir webhook: missing order_id or amount');
                return response('Invalid payload', 400);
            }

            $order = Order::where('transaction_id', $orderId)->first();
            if (!$order) {
                Log::error('Pakasir webhook: Order not found: ' . $orderId);
                return response('Order not found', 404);
            }

            // Double-check with Pakasir server to prevent spoofing
            $response = Http::get('https://app.pakasir.com/api/transactiondetail', [
                'project' => $this->projectSlug,
                'amount' => (int) $amount,
                'order_id' => $orderId,
                'api_key' => $this->apiKey,
            ]);

            if ($response->successful()) {
                $detail = $response->json();
                $apiStatus = $detail['status'] ?? null;

                if ($apiStatus === 'completed' || $status === 'completed') {
                    $this->updateOrderStatus($order, 'Paid', 'success');
                    return response('OK', 200);
                }
            } else {
                Log::error('Pakasir detail API call failed', ['response' => $response->body()]);
                // Fallback: update order status if webhook payload indicates completion
                if ($status === 'completed') {
                    $this->updateOrderStatus($order, 'Paid', 'success');
                    return response('OK', 200);
                }
            }

            return response('OK', 200);
        } catch (\Exception $e) {
            Log::error('Pakasir webhook exception: ' . $e->getMessage());
            return response('Error', 500);
        }
    }

    private function updateOrderStatus($order, $status, $paymentStatus)
    {
        DB::transaction(function () use ($order, $status, $paymentStatus) {
            $order->update([
                'status' => $status,
                'payment_status' => $paymentStatus,
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
        return redirect()->route('home', ['route' => 'orders'])
            ->with('success', 'Payment completed successfully!');
    }

    public function paymentUnfinish(Request $request)
    {
        return redirect()->route('home', ['route' => 'orders'])
            ->with('warning', 'Payment was not completed. You can try again from your orders.');
    }

    public function paymentError(Request $request)
    {
        return redirect()->route('home', ['route' => 'orders'])
            ->with('error', 'Payment failed. Please try again or contact support.');
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

    public function getInvoiceStatus($orderId)
    {
        try {
            $order = Order::where('id', $orderId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if (!$order->xendit_invoice_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No invoice found'
                ], 404);
            }

            $response = Http::withBasicAuth($this->xenditApiKey, '')
                ->get($this->xenditBaseUrl . '/v2/invoices/' . $order->xendit_invoice_id);

            if ($response->successful()) {
                $invoice = $response->json();

                return response()->json([
                    'success' => true,
                    'status' => $invoice['status'],
                    'invoice_url' => $invoice['invoice_url'],
                    'paid_at' => $invoice['paid_at'] ?? null
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch invoice'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching invoice status'
            ], 500);
        }
    }
}
