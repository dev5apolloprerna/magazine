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
use App\Models\Plan;

class ArticleApiController extends Controller
{
    
       public function article_list(Request $request)
    {
        $user = auth()->guard('api')->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorised'], 401);
        }
    
        $customerId = (int) ($user->customer_id ?? $user->id);
    
        $q = ArticleMaster::query()
            ->where('isDelete', 0)
            ->where('iStatus', 1);
    
        if ($request->filled('magazine_id')) {
            $q->where('magazine_id', (int) $request->magazine_id);
        }
    
        if ($request->filled('search')) {
            $search = trim($request->search);
            $q->where('article_title', 'like', "%{$search}%");
        }
    
        // Optional filters:
        // if ($request->filled('isPaid')) $q->where('isPaid', (int)$request->isPaid);
    
        $articles = $q->orderByDesc('article_id')->limit(50)->get();
    
        if ($articles->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No articles found', 'data' => []]);
        }
    
        // preload magazines
        $magazineIds = $articles->pluck('magazine_id')->unique()->values();
        $magazines = MagazineMaster::whereIn('id', $magazineIds)->get()->keyBy('id');
    
        $data = $articles->map(function ($a) use ($customerId, $magazines) {
            $mag = $magazines->get($a->magazine_id);
    
            $access = $this->articleAccessForList_v2($a, $mag, $customerId);
    
            return [
                'article_id'    => $a->article_id,
                'magazine_id'   => $a->magazine_id,
                'article_title' => $a->article_title,
                'isPaid'        => (int) $a->isPaid,
    
                'image_url'     => magazine_base_url($a->article_image),
    
                'can_view'      => $access['can_view'],
                'lock_reason'   => $access['reason'],
                'unlock_type'   => $access['unlock_type'], // subscription | free | locked
    
                'pdf_url'       => $access['can_view'] ? magazine_base_url($a->article_pdf) : null,
            ];
        })->values();
    
        return response()->json([
            'success' => true,
            'message' => 'Article list fetched successfully',
            'data' => $data
        ]);
    }
        
        // ✅ DETAIL/VIEW: /api/articles/{id}
        public function article_show(Request $request)
        {
            $id = (int) $request->id;
        
            $user = auth()->guard('api')->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorised'], 401);
            }
        
            $customerId = (int) ($user->customer_id ?? $user->id);
        
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
        
                 CustomerArticleLog::create([
                    'magazine_id' => $article->magazine_id,
                    'article_id' => $article->article_id,
                    'customer_id' => $customerId,
                    'date_time'   => now(),
                ]);
        

            // ✅ subscription check (same rule as magazine)
            $hasSub = $this->hasSubscriptionForIssue($mag, $customerId);
        
            // ✅ If NOT subscribed -> allow ONLY FREE articles
            if (!$hasSub && (int)$article->isPaid === 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'This is a paid article. Subscription required.',
                    'can_view' => false,
                    'unlock_type' => 'locked',
                    'lock_reason' => 'paid_requires_subscription',
                    'data' => [
                        'article_id'    => $article->article_id,
                        'magazine_id'   => $article->magazine_id,
                        'article_title' => $article->article_title,
                        'isPaid'        => (int) $article->isPaid,
                        'image_url'     => magazine_base_url($article->article_image),
                        'pdf_url'       => null,
                    ],
                ], 403);
            }
        
            // ✅ Allowed (subscribed OR free article)
            return response()->json([
                'success' => true,
                'can_view' => true,
                'unlock_type' => $hasSub ? 'subscription' : 'free',
                'data' => [
                    'article_id'    => $article->article_id,
                    'magazine_id'   => $article->magazine_id,
                    'article_title' => $article->article_title,
                    'isPaid'        => (int) $article->isPaid,
                    'image_url'     => magazine_base_url($article->article_image),
                    'pdf_url'       => magazine_base_url($article->article_pdf),
                ],
            ]);
        }
        
        private function articleAccessForList_v2($article, $mag, int $customerId): array
        {
            if (!$mag) {
                return ['can_view' => false, 'reason' => 'magazine_missing', 'unlock_type' => 'locked'];
            }
        
            $hasSub = $this->hasSubscriptionForIssue($mag, $customerId);
        
            // subscribed -> can view free + paid
            if ($hasSub) {
                return ['can_view' => true, 'reason' => null, 'unlock_type' => 'subscription'];
            }
        
            // not subscribed -> only free
            if ((int)$article->isPaid === 0) {
                return ['can_view' => true, 'reason' => null, 'unlock_type' => 'free'];
            }
        
            return ['can_view' => false, 'reason' => 'paid_requires_subscription', 'unlock_type' => 'locked'];
        }
        // ✅ For LIST response (does not decrement free count)
        private function articleAccessForList($mag, int $customerId, int $freeRemaining): array
        {
            if (!$mag) {
                return ['can_view' => false, 'reason' => 'magazine_missing', 'unlock_type' => 'locked'];
            }
        
            if ($this->hasSubscriptionForIssue($mag, $customerId)) {
                return ['can_view' => true, 'reason' => null, 'unlock_type' => 'subscription'];
            }
        
            if ($freeRemaining > 0) {
                // In list we just show it can be opened using free (real decrement happens in detail)
                return ['can_view' => true, 'reason' => null, 'unlock_type' => 'free'];
            }
        
            return ['can_view' => false, 'reason' => 'subscription_expired_and_free_zero', 'unlock_type' => 'locked'];
        }
        
        
        // ✅ Subscription check same as your magazineAccess rule (end_date >= issue publish_date)
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
}