<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    public function __construct(private readonly array $filters)
    {
    }

    public function collection(): Collection
    {
        return User::query()
            ->whereBetween('created_at', [
                $this->filters['from'].' 00:00:00',
                $this->filters['to'].' 23:59:59',
            ])
            ->get(['id', 'name', 'email', 'role', 'status', 'created_at']);
    }
}
