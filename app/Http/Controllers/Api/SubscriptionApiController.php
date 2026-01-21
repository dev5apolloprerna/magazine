<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        $subs = $this->allSubscriptions($customerId);

        return response()->json([
            'success' => true,
            'subscriptions' => $subs,
        ]);
    }

    private function allSubscriptions($customerId): array
    {
        $today = Carbon::today()->startOfDay();

        $subscriptions = Subscription::with('Plan')
            ->where('customer_id', $customerId)
            ->orderByDesc('start_date')   // or end_date
            ->get();

        $result = [];
        $activeFound = false;

        foreach ($subscriptions as $sub) {
            $start = Carbon::parse($sub->start_date)->startOfDay();
            $end   = Carbon::parse($sub->end_date)->endOfDay();

            // status
            if ($today->lt($start)) {
                $status = 'upcoming';
                $daysLeft = 0;
            } elseif ($today->gt($end)) {
                $status = 'expired';
                $daysLeft = 0;
            } else {
                $status = 'active';
                $daysLeft = $today->diffInDays($end) + 1; // inclusive
            }

            // active flag: only one subscription should be active
            $activeFlag = 0;
            if (!$activeFound && $status === 'active') {
                $activeFlag = 1;
                $activeFound = true;
            }

            $result[] = [
                'subscription_id' => $sub->subscription_id ?? $sub->id,
                'customer_id'     => $sub->customer_id,
                'plan_id'         => $sub->plan_id,
                'plan_name'       => $sub->Plan->plan_name ?? '',
                'start_date'      => $sub->start_date,
                'end_date'        => $sub->end_date,
                'days'            => $sub->days,
                'amount'          => $sub->amount,
                'isActive'        => $sub->isActive,      // from DB
                'status'          => $status,             // computed
                'days_left'       => $daysLeft,
                'active_flag'     => $activeFlag,         // computed (single active)
            ];
        }

        return $result;
    }
}
