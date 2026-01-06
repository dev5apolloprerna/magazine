<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MagazineMaster;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Plan;

class SubscriptionApiController extends Controller
{


    public function index(Request $request)
    {
        $user = auth()->guard('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorised'
            ], 401);
        }

        $customerId = $user->customer_id ?? $user->id;

        $sub = $this->latestSubscription($customerId);
        $subInfo = $this->subscriptionInfo($sub);

        return response()->json([
            'success' => true,
            'subscription' => $subInfo,
        ]);
    }
    private function latestSubscription($customerId): ?Subscription
    {
        return Subscription::with('Plan')->where('customer_id', $customerId)
            ->orderByDesc('isActive',1)
            ->first();

        /*return Subscription::where('customer_id', $customerId)
            ->orderByDesc('end_date')
            ->first();*/
    }
    private function subscriptionInfo(?Subscription $sub): array
    {
        if (!$sub) {
            return [
                'status' => 'none',
                'start_date' => null,
                'end_date' => null,
                'days_left' => null,
                'plan_id' => null,
                'plan_name' => null,
                'amount' => null,
            ];
        }

        $today = Carbon::today();
        $end = Carbon::parse($sub->end_date);

        return [
            'status' => $today->lte($end) ? 'active' : 'expired',
            'start_date' => $sub->start_date,
            'end_date' => $sub->end_date,
            'days_left' => $today->lte($end) ? $today->diffInDays($end) : 0,
            'plan_id' => $sub->plan_id,
            'plan_name' => $sub->Plan->plan_name,
            'amount' => $sub->amount,
        ];
    }
}