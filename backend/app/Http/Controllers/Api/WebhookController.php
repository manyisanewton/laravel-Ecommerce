<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function mpesa(Request $request): JsonResponse
    {
        return $this->handleWebhook($request, 'mpesa_receipt');
    }

    public function flutterwave(Request $request): JsonResponse
    {
        return $this->handleWebhook($request, 'tx_ref');
    }

    public function pesapal(Request $request): JsonResponse
    {
        return $this->handleWebhook($request, 'merchant_reference');
    }

    private function handleWebhook(Request $request, string $referenceKey): JsonResponse
    {
        $paymentReference = data_get($request->all(), $referenceKey);

        $order = Order::query()->where('payment_reference', $paymentReference)->first();

        if (! $order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $success = (bool) data_get($request->all(), 'success', true);

        $order->update([
            'status' => $success ? OrderStatus::Paid : OrderStatus::Failed,
            'payment_status' => $success ? PaymentStatus::Success : PaymentStatus::Failed,
            'meta' => array_merge($order->meta ?? [], ['callback' => $request->all()]),
        ]);

        return response()->json(['message' => 'Webhook processed']);
    }
}
