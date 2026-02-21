<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrdersExport implements FromCollection
{
    public function __construct(private readonly array $filters)
    {
    }

    public function collection(): Collection
    {
        return Order::query()
            ->with('user:id,name,email')
            ->whereBetween('created_at', [
                $this->filters['from'].' 00:00:00',
                $this->filters['to'].' 23:59:59',
            ])
            ->get(['id', 'user_id', 'reference', 'status', 'payment_provider', 'payment_status', 'total_amount', 'created_at']);
    }
}
