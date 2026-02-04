<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MagazineMaster;
use App\Models\Subscription;
use App\Models\Customer;
use App\Models\ArticleMaster;
use App\Models\CustomerArticleLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ArticleApiController extends Controller
{
    /**
     * ✅ Article List
     * Rules:
     * - If subscribed for that magazine issue => can view FREE + PAID
     * - If NOT subscribed:
     *    - PAID locked
     *    - FREE allowed only if customer.free_article > 0
     * NOTE: List does NOT decrement free count.
     */
    public function article_list(Request $request)
    {
        $user = auth()->guard('api')->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorised'], 401);
        }

        $customerId = (int) ($user->customer_id ?? $user->id);

        // optional: keep active flag updated
        $this->refreshActiveSubscription($customerId);

        $customer = Customer::where('customer_id', $customerId)->first();
        $freeRemaining = (int) ($customer->free_article ?? 0);

        $q = ArticleMaster::query()
            ->where('isDelete', 0)
            ->where('iStatus', 1);

        if ($request->filled('magazine_id')) {
            $q->where('magazine_id', (int) $request->magazine_id);
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $q->where('article_title', 'like', "%{$search}%");
        }

        $articles = $q->orderByDesc('article_id')->limit(50)->get();

        if ($articles->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No articles found', 'data' => []]);
        }

        // preload magazines (avoid N+1)
        $magazineIds = $articles->pluck('magazine_id')->unique()->values();
        $magazines = MagazineMaster::whereIn('id', $magazineIds)->get()->keyBy('id');

        $data = $articles->map(function ($a) use ($customerId, $magazines, $freeRemaining) {
            $mag = $magazines->get($a->magazine_id);

            $access = $this->articleAccessForList_v3($a, $mag, $customerId, $freeRemaining);

            return [
                'article_id'      => (int) $a->article_id,
                'magazine_id'     => (int) $a->magazine_id,
                'article_title'   => $a->article_title,
                'isPaid'          => (int) ($a->isPaid ?? 0),
                'image_url' => $a->article_image
                ? magazine_base_url($a->article_image)
                : asset('assets/images/noimage.png'),

                'can_view'        => (bool) $access['can_view'],
                'lock_reason'     => $access['reason'],
                'unlock_type'     => $access['unlock_type'], // subscription | free_count | locked

                'free_remaining'  => $freeRemaining,

                'pdf_url'         => $access['can_view'] ? magazine_base_url($a->article_pdf) : null,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'message' => 'Article list fetched successfully',
            'data'    => $data
        ]);
    }

    /**
     * ✅ Article Show / View
     * Change you asked:
     * - If FREE article viewed multiple times => decrease free_article ONLY ON FIRST TIME
     *
     * Logic:
     * - If subscribed => allow FREE + PAID (no decrement)
     * - If NOT subscribed:
     *    - PAID locked
     *    - FREE:
     *        - if already unlocked (log exists) => allow (no decrement)
     *        - else decrement free count once and create log
     */
    public function article_show(Request $request)
    {
        $id = (int) $request->id;

        $user = auth()->guard('api')->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorised'], 401);
        }

        $customerId = (int) ($user->customer_id ?? $user->id);

        Customer::where('customer_id', $user->id)->increment('article_count');
        ArticleMaster::where('article_id', $id)->increment('view_count');


        $article = ArticleMaster::where('isDelete', 0)
            ->where('iStatus', 1)
            ->where('article_id', $id)
            ->firstOrFail();

        $mag = MagazineMaster::where('isDelete', 0)
            ->where('iStatus', 1)
            ->where('id', $article->magazine_id)
            ->first();

        if (!$mag) {
            return response()->json(['success' => false, 'message' => 'Magazine not found for this article'], 404);
        }

        $isPaid = (int) ($article->isPaid ?? 0);

        // ✅ subscription check
        $hasSub = $this->hasSubscriptionForIssue($mag, $customerId);

        // ✅ Paid requires subscription
        if (!$hasSub && $isPaid === 1) {
            return response()->json([
                'success'     => false,
                'message'     => 'This is a paid article. Subscription required.',
                'can_view'    => false,
                'unlock_type' => 'locked',
                'lock_reason' => 'paid_requires_subscription',
                'data'        => [
                    'article_id'    => (int) $article->article_id,
                    'magazine_id'   => (int) $article->magazine_id,
                    'article_title' => $article->article_title,
                    'isPaid'        => $isPaid,
                    'image_url' => $a->article_image
                        ? magazine_base_url($a->article_image)
                        : asset('assets/images/noimage.png'),

                    'pdf_url'       => null,
                ],
            ], 403);
        }

        // ✅ If subscribed => allow directly (no decrement)
        if ($hasSub) {
            $this->logArticleView($customerId, (int) $article->magazine_id, (int) $article->article_id);

            return response()->json([
                'success'     => true,
                'can_view'    => true,
                'unlock_type' => 'subscription',
                'data'        => [
                    'article_id'    => (int) $article->article_id,
                    'magazine_id'   => (int) $article->magazine_id,
                    'article_title' => $article->article_title,
                    'isPaid'        => $isPaid,
                    'image_url' => $article->article_image
                                ? magazine_base_url($article->article_image)
                                : asset('assets/images/noimage.png'),

                'pdf_url'       => magazine_base_url($article->article_pdf),
                ],
            ]);
        }

        // ✅ Not subscribed + FREE article
        // If already unlocked once => allow without decrement
        $alreadyUnlocked = CustomerArticleLog::where('customer_id', $customerId)
            ->where('article_id', (int) $article->article_id)
            ->exists();

        if ($alreadyUnlocked) {
            // optional: you can still log "repeat view" if you want another table
            // For now: no decrement, no extra log row (keeps it "single-time unlock")
            return response()->json([
                'success'     => true,
                'can_view'    => true,
                'unlock_type' => 'free_count',
                'data'        => [
                    'article_id'    => (int) $article->article_id,
                    'magazine_id'   => (int) $article->magazine_id,
                    'article_title' => $article->article_title,
                    'isPaid'        => $isPaid,
                    'image_url' => $article->article_image                                 ? magazine_base_url($article->article_image)                                 : asset('assets/images/noimage.png'),
                    'pdf_url'       => magazine_base_url($article->article_pdf),
                ],
            ]);
        }

        // ✅ First time unlock => decrement once (transaction safe) + create log
        $allowed = false;
        $freeAfter = 0;

        DB::transaction(function () use ($customerId, $article, &$allowed, &$freeAfter) {
            // Re-check inside transaction to avoid double decrement due to concurrent requests
            $already = CustomerArticleLog::where('customer_id', $customerId)
                ->where('article_id', (int) $article->article_id)
                ->lockForUpdate()
                ->exists();

            if ($already) {
                $allowed = true;
                // we can't know freeAfter accurately without reloading customer; return later if needed
                $freeAfter = (int) (Customer::where('customer_id', $customerId)->value('free_article') ?? 0);
                return;
            }

            $cust = Customer::where('customer_id', $customerId)
                ->lockForUpdate()
                ->first();

            $free = (int) ($cust->free_article ?? 0);

            if ($free > 0) {
                $cust->free_article = $free - 1;
                $cust->save();

                // create unlock log (this marks "used once")
                CustomerArticleLog::create([
                    'magazine_id' => (int) $article->magazine_id,
                    'article_id'  => (int) $article->article_id,
                    'customer_id' => (int) $customerId,
                    'date_time'   => now(),
                ]);

                $allowed = true;
                $freeAfter = $free - 1;
            } else {
                $allowed = false;
                $freeAfter = 0;
            }
        });

        if (!$allowed) {
            return response()->json([
                'success'     => false,
                'message'     => 'Free article view limit finished.',
                'can_view'    => false,
                'unlock_type' => 'locked',
                'lock_reason' => 'free_limit_finished',
                'data'        => [
                    'article_id'    => (int) $article->article_id,
                    'magazine_id'   => (int) $article->magazine_id,
                    'article_title' => $article->article_title,
                    'isPaid'        => $isPaid,
                    'image_url' => $article->article_image                                 ? magazine_base_url($article->article_image)                                 : asset('assets/images/noimage.png'),
                    'pdf_url'       => null,
                ],
            ], 403);
        }

        return response()->json([
            'success'         => true,
            'can_view'        => true,
            'unlock_type'     => 'free_count',
            'free_remaining'  => $freeAfter,
            'data'            => [
                'article_id'    => (int) $article->article_id,
                'magazine_id'   => (int) $article->magazine_id,
                'article_title' => $article->article_title,
                'isPaid'        => $isPaid,
                'image_url' => $article->article_image                                 ? magazine_base_url($article->article_image)                                 : asset('assets/images/noimage.png'),
                'pdf_url'       => magazine_base_url($article->article_pdf),
            ],
        ]);
    }

    // -------------------------
    // ✅ PRIVATE HELPERS
    // -------------------------

    private function articleAccessForList_v3($article, $mag, int $customerId, int $freeRemaining): array
    {
        if (!$mag) {
            return ['can_view' => false, 'reason' => 'magazine_missing', 'unlock_type' => 'locked'];
        }

        $hasSub = $this->hasSubscriptionForIssue($mag, $customerId);

        if ($hasSub) {
            return ['can_view' => true, 'reason' => null, 'unlock_type' => 'subscription'];
        }

        if ((int) ($article->isPaid ?? 0) === 1) {
            return ['can_view' => false, 'reason' => 'paid_requires_subscription', 'unlock_type' => 'locked'];
        }

        if ($freeRemaining > 0) {
            return ['can_view' => true, 'reason' => null, 'unlock_type' => 'free_count'];
        }

        return ['can_view' => false, 'reason' => 'free_limit_finished', 'unlock_type' => 'locked'];
    }

    private function hasSubscriptionForIssue($mag, int $customerId): bool
    {
        $issueDate = Carbon::parse($mag->publish_date)->startOfDay();
        if (!$issueDate) return false;

        $sub = Subscription::where('customer_id', $customerId)
            ->where('iStatus', 1)
            ->where('isDelete', 0)
            ->whereDate('end_date', '>=', $issueDate->toDateString())
            ->orderByDesc('end_date')
            ->first();

        return (bool) $sub;
    }

    private function refreshActiveSubscription(int $customerId): void
    {
        $today = Carbon::today()->toDateString();

        DB::table('subscription_master')
            ->where('customer_id', $customerId)
            ->update(['isActive' => 0]);

        DB::table('subscription_master')
            ->where('customer_id', $customerId)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->orderByDesc('end_date')
            ->limit(1)
            ->update(['isActive' => 1]);
    }

    /**
     * Optional: used for subscription views only (doesn't affect free count)
     * For free-unlock, we create log inside the transaction.
     */
    private function logArticleView(int $customerId, int $magazineId, int $articleId): void
    {
        try {
            CustomerArticleLog::create([
                'magazine_id' => $magazineId,
                'article_id'  => $articleId,
                'customer_id' => $customerId,
                'date_time'   => now(),
            ]);
        } catch (\Throwable $e) {
            // ignore
        }
    }
}