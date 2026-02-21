<?php

namespace App\Services\Payments\Contracts;

use App\Models\Order;

interface PaymentGateway
{
    public function initiatePayment(Order $order, array $payload): array;
}
