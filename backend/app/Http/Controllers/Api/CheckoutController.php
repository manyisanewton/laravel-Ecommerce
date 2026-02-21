<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\Payments\PaymentManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct(private readonly PaymentManager $paymentManager)
    {
    }

    public function checkout(CheckoutRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $result = DB::transaction(function () use ($payload, $request): array {
            $total = 0;
            $rows = [];

            foreach ($payload['items'] as $item) {
                $product = Product::query()->findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    abort(422, "Insufficient stock for {$product->name}");
                }

                $subtotal = (float) $product->price * $item['quantity'];
                $total += $subtotal;

                $rows[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ];
            }

            $order = Order::query()->create([
                'user_id' => $request->user()->id,
                'reference' => 'ORD-'.Str::upper(Str::random(10)),
                'status' => OrderStatus::Pending,
                'payment_provider' => PaymentProvider::from($payload['provider']),
                'payment_status' => PaymentStatus::Initiated,
                'total_amount' => $total,
                'meta' => ['phone_number' => $payload['phone_number'] ?? null],
            ]);

            foreach ($rows as $row) {
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $row['product']->id,
                    'quantity' => $row['quantity'],
                    'unit_price' => $row['product']->price,
                    'subtotal' => $row['subtotal'],
                ]);

                $row['product']->decrement('stock', $row['quantity']);
            }

            $payment = $this->paymentManager
                ->forProvider($payload['provider'])
                ->initiatePayment($order, $payload);

            $order->update([
                'payment_status' => PaymentStatus::Processing,
                'payment_reference' => $payment['payment_reference'] ?? null,
                'meta' => array_merge($order->meta ?? [], ['payment' => $payment]),
            ]);

            return [$order->load('items.product'), $payment];
        });

        return response()->json([
            'order' => $result[0],
            'payment' => $result[1],
        ], 201);
    }

    public function myOrders(): JsonResponse
    {
        return response()->json(
            Order::query()
                ->where('user_id', auth('api')->id())
                ->with('items.product')
                ->latest()
                ->paginate(20)
        );
    }
}
