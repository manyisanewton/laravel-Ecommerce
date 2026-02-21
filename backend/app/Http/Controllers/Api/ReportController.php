<?php

namespace App\Http\Controllers\Api;

use App\Exports\ActivityLogsExport;
use App\Exports\OrdersExport;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReportFilterRequest;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function users(ReportFilterRequest $request): JsonResponse
    {
        [$from, $to] = $this->period($request->validated());

        return response()->json(
            User::query()->whereBetween('created_at', [$from, $to])->paginate(50)
        );
    }

    public function orders(ReportFilterRequest $request): JsonResponse
    {
        [$from, $to] = $this->period($request->validated());

        return response()->json(
            Order::query()->with('user')->whereBetween('created_at', [$from, $to])->paginate(50)
        );
    }

    public function activity(ReportFilterRequest $request): JsonResponse
    {
        [$from, $to] = $this->period($request->validated());

        return response()->json(
            ActivityLog::query()->with('user')->whereBetween('created_at', [$from, $to])->paginate(100)
        );
    }

    public function export(string $type, ReportFilterRequest $request)
    {
        $validated = $request->validated();
        $format = $validated['format'] ?? 'excel';

        return match ([$type, $format]) {
            ['users', 'excel'] => Excel::download(new UsersExport($validated), 'users-report.xlsx'),
            ['orders', 'excel'] => Excel::download(new OrdersExport($validated), 'orders-report.xlsx'),
            ['activity', 'excel'] => Excel::download(new ActivityLogsExport($validated), 'activity-report.xlsx'),
            ['users', 'pdf'] => Pdf::loadView('reports.users', ['data' => (new UsersExport($validated))->collection()])->download('users-report.pdf'),
            ['orders', 'pdf'] => Pdf::loadView('reports.orders', ['data' => (new OrdersExport($validated))->collection()])->download('orders-report.pdf'),
            ['activity', 'pdf'] => Pdf::loadView('reports.activity', ['data' => (new ActivityLogsExport($validated))->collection()])->download('activity-report.pdf'),
            default => response()->json(['message' => 'Unsupported export type/format'], 422),
        };
    }

    private function period(array $validated): array
    {
        return [
            $validated['from'].' 00:00:00',
            $validated['to'].' 23:59:59',
        ];
    }
}
