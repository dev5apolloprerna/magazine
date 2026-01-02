<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MagazineMaster;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MagazineApiController extends Controller
{
    /**
     * GET /api/magazines
     * LIST: show all magazines, but mark can_view based on subscription end_date
     */
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

        $magazines = MagazineMaster::where('isDelete', 0)
            ->where('iStatus', 1)
            ->orderByDesc('year')
            ->orderByRaw("FIELD(month,'December','November','October','September','August','July','June','May','April','March','February','January')") // optional
            ->orderByDesc('id')
            ->get();

        $data = $magazines->map(function ($m) use ($sub) 
        {
            $access = $this->magazineAccess($m, $sub);

            return [
                'id'          => $m->id,
                'title'       => $m->title,
                'month'       => $m->month,
                'year'        => $m->year,
                'image_url'   => magazine_base_url($m->image),
                'can_view'    => $access['can_view'],
                'lock_reason' => $access['reason'],
                // ✅ optional: hide pdf_url if locked
                'pdf_url'     => $access['can_view'] ? magazine_base_url($m->pdf) : null,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'subscription' => $subInfo,
            'data' => $data
        ]);
    }

    /**
     * GET /api/magazines/{id}
     * DETAIL: if locked then do not send pdf url
     */
    public function show(Request $request)
    {
        $id=$request->id;
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

        $mag = MagazineMaster::where('isDelete', 0)
            ->where('iStatus', 1)
            ->findOrFail($id);

        $access = $this->magazineAccess($mag, $sub);

        if (!$access['can_view']) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription expired / not allowed to view this magazine.',
                'can_view' => false,
                'lock_reason' => $access['reason'],
                'subscription' => $subInfo,
                'magazine_preview' => [
                    'id' => $mag->id,
                    'title' => $mag->title,
                    'month' => $mag->month,
                    'year' => $mag->year,
                    'image_url' => magazine_base_url($mag->image),
                ],
            ], 403);
        }

        return response()->json([
            'success' => true,
            'can_view' => true,
            'subscription' => $subInfo,
            'data' => [
                'id'        => $mag->id,
                'title'     => $mag->title,
                'month'     => $mag->month,
                'year'      => $mag->year,
                'image_url' => magazine_base_url($mag->image),
                'pdf_url'   => magazine_base_url($mag->pdf),
            ],
        ]);
    }

    // -----------------------------
    // ✅ REQUIRED PRIVATE METHODS
    // -----------------------------

    /**
     * latest subscription for customer
     */
    private function latestSubscription($customerId): ?Subscription
    {
        return Subscription::where('customer_id', $customerId)
            ->orderByDesc('end_date')
            ->first();
    }

    /**
     * Subscription info for response
     */
    private function subscriptionInfo(?Subscription $sub): array
    {
        if (!$sub) {
            return [
                'status' => 'none',
                'start_date' => null,
                'end_date' => null,
                'days_left' => null,
                'plan_id' => null,
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
            'amount' => $sub->amount,
        ];
    }

    /**
     * Main access logic:
     * LIST: show all but can_view false for locked
     * DETAIL: block if can_view false
     */
    private function magazineAccess($mag, ?Subscription $sub): array
    {
        if (!$sub) {
            return ['can_view' => false, 'reason' => 'no_subscription'];
        }

        $issueDate = $this->issueDate($mag);
        if (!$issueDate) {
            return ['can_view' => false, 'reason' => 'invalid_magazine_date'];
        }

        $start = Carbon::parse($sub->start_date)->startOfDay();
        $end   = Carbon::parse($sub->end_date)->endOfDay(); // ✅ include end date fully

        // ✅ allow only between start_date and end_date (inclusive)
        if ($issueDate->lt($start)) {
            return ['can_view' => false, 'reason' => 'before_subscription'];
        }

        if ($issueDate->gt($end)) {
            return ['can_view' => false, 'reason' => 'after_end_date'];
        }

        return ['can_view' => true, 'reason' => null];
    }

    private function issueDate($m): ?Carbon
    {
        $month = trim((string) $m->month);
        $year  = (int) $m->year;

        if ($year <= 0) return null;

        // numeric month
        if (is_numeric($month)) {
            $mm = (int) $month;
            if ($mm < 1 || $mm > 12) return null;
            return Carbon::create($year, $mm, 1)->startOfDay();
        }

        // month name
        try {
            return Carbon::parse("01 {$month} {$year}")->startOfDay();
        } catch (\Throwable $e) {
            return null;
        }
    }
}
