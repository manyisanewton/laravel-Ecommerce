<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Services\Payments\Contracts\PaymentGateway;
use Illuminate\Support\Str;

class MpesaGateway implements PaymentGateway
{
    public function initiatePayment(Order $order, array $payload): array
    {
        // Replace this stub with real STK Push call to Safaricom sandbox endpoint.
        return [
            'provider' => 'mpesa',
            'payment_reference' => 'MPESA-'.Str::upper(Str::random(10)),
            'checkout_request_id' => 'ws_CO_'.Str::random(12),
            'status' => 'processing',
            'phone_number' => $payload['phone_number'] ?? null,
        ];
    }
}
