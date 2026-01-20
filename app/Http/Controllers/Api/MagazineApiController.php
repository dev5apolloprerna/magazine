<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MagazineMaster;
use App\Models\Subscription;
use App\Models\Customer;
use App\Models\CustomerMagazineLog;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Plan;

class MagazineApiController extends Controller
{
    /**
     * GET /api/magazines
     * LIST: show all magazines, but mark can_view based on subscription end_date
     */
     
    public function all_magazines(Request $request)
    {
        $user = auth()->guard('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorised'
            ], 401);
        }

        $customerId = $user->customer_id ?? $user->id;
        // dd($customerId); 
        $sub = $this->latestSubscription($customerId);
        $subInfo = $this->subscriptionInfo($sub);

        // $magazines = MagazineMaster::where('isDelete', 0)
        //     ->where('iStatus', 1)
        //     ->orderByDesc('year')
        //     ->orderByRaw("FIELD(month,'December','November','October','September','August','July','June','May','April','March','February','January')") // optional
        //     ->orderByDesc('id')
        //     ->get();
        
        $query = MagazineMaster::where('isDelete', 0)
        ->where('iStatus', 1);

        // ✅ Filter by month
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }
    
        // ✅ Filter by year
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
    
        $magazines = $query
            ->orderByDesc('year')
            ->orderByRaw("FIELD(month,'December','November','October','September','August','July','June','May','April','March','February','January')")
            ->orderByDesc('id')
            ->get();

        $data = $magazines->map(function ($m) use ($customerId) 
        {
        
            $access = $this->magazineAccess($m, (int) $customerId);

            return [
                'id'          => $m->id,
                'title'       => $m->title,
                'month'       => $m->month,
                'year'        => $m->year,
                'created_at'  => $m->publish_date,
                'image_url'   => magazine_base_url($m->image),
                'can_view'    => $access['can_view'],
                'lock_reason' => $access['reason'],
                'pdf_url'     => $access['can_view'] ? magazine_base_url($m->pdf) : null,
            ];

        })->values();

        if ($data->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No magazines found',
                'data' => []
            ]);
        }

        return response()->json([
            'success' => true,
            //'subscription' => $subInfo,
            'data' => $data
        ]);
    }
    
   public function plan_list(Request $request)
    {
        $user = auth()->guard('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorised'
            ], 401);
        }
    
        $customerId = $user->customer_id ?? $user->id;
    
        // ✅ get current active plan_id for this customer
        $activePlanId = Subscription::where('customer_id', $customerId)
            ->where('isActive', 1)
            ->orderByDesc('subscription_id')
            ->value('plan_id'); // returns plan_id or null
    
        $plans = Plan::where(['iStatus' => 1, 'isDelete' => 0])
            ->select('plan_id', 'plan_name', 'plan_amount', 'days')
            ->get()
            ->map(function ($p) use ($activePlanId) {
                $p->is_active_plan = ((int)$p->plan_id === (int)$activePlanId) ? 1 : 0;
                return $p;
            });
    
        return response()->json([
            'success' => true,
            'message' => $plans->isEmpty() ? 'No plans available' : 'Plan list fetched successfully',
            'data'    => $plans
        ]);
    }

    
    
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


        $data = $magazines->map(function ($m) use ($customerId) {
        
            $access = $this->magazineAccess($m, (int) $customerId);
        
            return [
                'id'          => $m->id,
                'title'       => $m->title,
                'month'       => $m->month,
                'year'        => $m->year,
                'image_url'   => magazine_base_url($m->image),
                'can_view'    => $access['can_view'],
                'lock_reason' => $access['reason'],
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
        $id = $request->id;

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

        //$access = $this->magazineAccess($mag, $sub);
        $access = $this->magazineAccess($mag, (int) $customerId);

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

        /*$log = CustomerMagazineLog::firstOrCreate(
                ['customer_id' => $customerId, 'magazine_id' => $mag->id],
                ['clicked_count' => 0]
            );

        $log->increment('clicked_count');*/

        Customer::where('customer_id', $customerId)->increment('magazine_count');

        // ✅ Magazine total views
        MagazineMaster::where('id', $mag->id)->increment('magazine_count');

        // ✅ Insert log row (history)
        CustomerMagazineLog::create([
            'magazine_id' => $mag->id,
            'customer_id' => $customerId,
            'date_time'   => now(),
        ]);

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
    private function magazineAccess($mag, int $customerId): array
{
        $issueDate = \Carbon\Carbon::parse($mag->publish_date)->startOfDay();

    //$issueDate = $this->issueDate($mag);
    if (!$issueDate) {
        return ['can_view' => false, 'reason' => 'invalid_magazine_date'];
    }


    // ✅ check subscription that has *not yet expired* and covers old magazines
    $sub = Subscription::where('customer_id', $customerId)
        ->where('iStatus', 1)
        ->where('isDelete', 0)
        ->whereDate('end_date', '>=', $issueDate->toDateString()) // <= This is the key change
        ->orderByDesc('end_date')
        ->first();

    if (!$sub) {
        return ['can_view' => false, 'reason' => 'no_subscription_for_issue'];
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
