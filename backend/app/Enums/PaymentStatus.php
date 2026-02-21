<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Initiated = 'initiated';
    case Processing = 'processing';
    case Success = 'success';
    case Failed = 'failed';
}
