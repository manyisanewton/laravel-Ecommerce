<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Services\Payments\Contracts\PaymentGateway;
use Illuminate\Support\Str;

class PesapalGateway implements PaymentGateway
{
    public function initiatePayment(Order $order, array $payload): array
    {
        // Replace this stub with real PesaPal/DPO token/order flow.
        return [
            'provider' => 'pesapal',
            'payment_reference' => 'PES-'.Str::upper(Str::random(10)),
            'redirect_url' => 'https://pay.pesapal.com/iframe/'.Str::random(20),
            'status' => 'processing',
        ];
    }
}
