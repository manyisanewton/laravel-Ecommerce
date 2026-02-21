<?php

namespace App\Enums;

enum PaymentProvider: string
{
    case Mpesa = 'mpesa';
    case Flutterwave = 'flutterwave';
    case Pesapal = 'pesapal';
}
