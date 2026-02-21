<?php

namespace App\Exports;

use App\Models\ActivityLog;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class ActivityLogsExport implements FromCollection
{
    public function __construct(private readonly array $filters)
    {
    }

    public function collection(): Collection
    {
        return ActivityLog::query()
            ->with('user:id,name,email')
            ->whereBetween('created_at', [
                $this->filters['from'].' 00:00:00',
                $this->filters['to'].' 23:59:59',
            ])
            ->get(['id', 'user_id', 'action', 'method', 'endpoint', 'ip_address', 'created_at']);
    }
}
