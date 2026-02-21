<?php

namespace App\Services\Payments;

use App\Services\Payments\Contracts\PaymentGateway;
use InvalidArgumentException;

class PaymentManager
{
    public function forProvider(string $provider): PaymentGateway
    {
        return match ($provider) {
            'mpesa' => app(MpesaGateway::class),
            'flutterwave' => app(FlutterwaveGateway::class),
            'pesapal' => app(PesapalGateway::class),
            default => throw new InvalidArgumentException('Unsupported provider: '.$provider),
        };
    }
}
