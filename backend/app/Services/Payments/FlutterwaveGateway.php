<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Services\Payments\Contracts\PaymentGateway;
use Illuminate\Support\Str;

class FlutterwaveGateway implements PaymentGateway
{
    public function initiatePayment(Order $order, array $payload): array
    {
        // Replace this stub with real Flutterwave charge endpoint.
        return [
            'provider' => 'flutterwave',
            'payment_reference' => 'FLW-'.Str::upper(Str::random(10)),
            'payment_link' => 'https://checkout.flutterwave.com/v3/hosted/pay/'.Str::random(18),
            'status' => 'processing',
        ];
    }
}
