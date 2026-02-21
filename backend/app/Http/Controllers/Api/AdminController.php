<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    public function users(): JsonResponse
    {
        return response()->json(User::query()->latest()->paginate(20));
    }

    public function toggleUserStatus(User $user): JsonResponse
    {
        $user->update([
            'status' => $user->status === UserStatus::Active ? UserStatus::Inactive : UserStatus::Active,
        ]);

        return response()->json($user);
    }

    public function orders(): JsonResponse
    {
        return response()->json(
            Order::query()->with(['user', 'items.product'])->latest()->paginate(20)
        );
    }
}
