<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\CustomerMagazineLog;
use App\Models\Customer;

class ReportController extends Controller
{
    public function index(Request $request)
    {
         $q = $request->q;

        $customers = Customer::from('customer_master as cm')
        ->leftJoin('customer_login_log as cl', 'cl.customer_id', '=', 'cm.customer_id')
        ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('cm.customer_name', 'like', "%{$q}%")
                        ->orWhere('cm.customer_mobile', 'like', "%{$q}%")
                        ->orWhere('cm.customer_email', 'like', "%{$q}%");
                });
            })

        ->select(
            'cm.customer_id',
            'cm.customer_name',
            'cm.customer_mobile',
            'cm.customer_email',
            'cm.login_count',
            DB::raw('MAX(cl.login_date_time) as last_login')
        )
        ->groupBy('cm.customer_id', 'cm.customer_name', 'cm.customer_mobile', 'cm.customer_email')
        ->orderByDesc('cm.customer_id')
        ->paginate(20);

        return view('admin.report.customer_login', compact('customers','q'));
    }

    public function loginHistory($customer_id)
    {
        $customer = Customer::where('customer_id', $customer_id)->firstOrFail();

        $logs = $customer->loginLogs()
            ->orderByDesc('login_date_time')
            ->paginate(50);

        return view('admin.report.login_history', compact('customer', 'logs'));
    }
    public function loginHistoryAjax($customer_id)
    {
        $customer = Customer::where('customer_id', $customer_id)->firstOrFail();

        $logs = $customer->loginLogs()
            ->orderByDesc('login_date_time')
            ->limit(50)
            ->get(['id', 'login_date_time']);

        // return formatted values for modal
        $data = $logs->map(function ($log) {
            return [
                'id' => $log->id,
                'login_date_time' => $log->login_date_time
                    ? $log->login_date_time->timezone('Asia/Kolkata')->format('d-m-Y h:i A')
                    : null,
            ];
        });

        return response()->json([
            'success' => true,
            'customer_id' => $customer->customer_id,
            'customer_name' => $customer->customer_name,
            'logs' => $data
        ]);
    }
    public function userWisePdfViews(Request $request)
    {
        $q = $request->q;

        $customers = Customer::from('customer_master as cm')
            ->leftJoin('customer_magazine_log as cml', 'cml.customer_id', '=', 'cm.customer_id')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('cm.customer_name', 'like', "%{$q}%")
                        ->orWhere('cm.customer_mobile', 'like', "%{$q}%")
                        ->orWhere('cm.customer_email', 'like', "%{$q}%");
                });
            })
            ->select(
                'cm.customer_id',
                'cm.customer_name',
                'cm.customer_mobile',
                'cm.customer_email',
                'cm.magazine_count',
                DB::raw('MAX(cml.date_time) as last_view_time')
            )
            ->groupBy('cm.customer_id', 'cm.customer_name', 'cm.customer_mobile', 'cm.customer_email')
            ->orderByDesc('magazine_count')
            ->paginate(20)
            ->appends(['q' => $q]);

        return view('admin.report.user_wise_pdf_views', compact('customers', 'q'));
    }
     public function userPdfViewsDetail($customer_id)
    {
        $customer = Customer::from('customer_master as cm')
            ->where('cm.customer_id', $customer_id)
            ->select('cm.customer_id', 'cm.customer_name', 'cm.customer_mobile', 'cm.customer_email')
            ->firstOrFail();

        $rows = CustomerMagazineLog::from('customer_magazine_log as cml')
            ->join('magazine_master as mm', function ($join) {
                $join->on('mm.id', '=', 'cml.magazine_id')
                     ->where('mm.isDelete', 0)
                     ->where('mm.iStatus', 1);
            })
            ->where('cml.customer_id', $customer_id)
            ->select(
                'cml.logid',
                'cml.magazine_id',
                'cml.date_time',
                'mm.title',
                'mm.month',
                'mm.year',
                'mm.image',
                'mm.pdf'
            )
            ->orderByDesc('cml.date_time')
            ->paginate(20);

        return view('admin.report.user_pdf_views_detail', compact('customer', 'rows'));
    }
    // ✅ Customer wise: Article view counts
public function userWiseArticleViews(Request $request)
{
    $q = $request->q;

    $customers = Customer::from('customer_master as cm')
        ->leftJoin('customer_article_log as cal', 'cal.customer_id', '=', 'cm.customer_id')
        ->when($q, function ($query) use ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('cm.customer_name', 'like', "%{$q}%")
                    ->orWhere('cm.customer_mobile', 'like', "%{$q}%")
                    ->orWhere('cm.customer_email', 'like', "%{$q}%");
            });
        })
        ->select(
            'cm.customer_id',
            'cm.customer_name',
            'cm.customer_mobile',
            'cm.customer_email',
            'cm.article_count',
            DB::raw('MAX(cal.date_time) as last_view_time')
        )
        ->groupBy('cm.customer_id', 'cm.customer_name', 'cm.customer_mobile', 'cm.customer_email', 'cm.article_count')
        ->orderByDesc('cm.article_count')
        ->paginate(20)
        ->appends(['q' => $q]);

    return view('admin.report.user_wise_article_views', compact('customers', 'q'));
}

// ✅ Customer detail: Article wise history
public function userArticleViewsDetail($customer_id)
{
    $customer = Customer::from('customer_master as cm')
        ->where('cm.customer_id', $customer_id)
        ->select('cm.customer_id', 'cm.customer_name', 'cm.customer_mobile', 'cm.customer_email')
        ->firstOrFail();

    $rows = DB::table('customer_article_log as cal')
        ->join('article_master as am', function ($join) {
            $join->on('am.article_id', '=', 'cal.article_id')
                ->where('am.isDelete', 0)
                ->where('am.iStatus', 1);
        })
        ->leftJoin('magazine_master as mm', function ($join) {
            $join->on('mm.id', '=', 'am.magazine_id')
                ->where('mm.isDelete', 0)
                ->where('mm.iStatus', 1);
        })
        ->where('cal.customer_id', $customer_id)
        ->select(
            'cal.logid',
            'cal.date_time',
            'am.article_id',
            'am.article_title',
            'am.article_pdf',
            'am.magazine_id',
            'mm.title as magazine_title',
            'mm.month',
            'mm.year'
        )
        ->orderByDesc('cal.date_time')
        ->paginate(20);

    return view('admin.report.user_article_views_detail', compact('customer', 'rows'));
}

// ✅ Article wise report
public function articleWisePdfViews(Request $request)
{
    $q = $request->q;

    $articles = DB::table('article_master as am')
        ->leftJoin('customer_article_log as cal', 'cal.article_id', '=', 'am.article_id')
        ->leftJoin('magazine_master as mm', function ($join) {
            $join->on('mm.id', '=', 'am.magazine_id')
                ->where('mm.isDelete', 0)
                ->where('mm.iStatus', 1);
        })
        ->where('am.isDelete', 0)
        ->where('am.iStatus', 1)
        ->when($q, function ($query) use ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('am.article_title', 'like', "%{$q}%")
                    ->orWhere('am.article_id', 'like', "%{$q}%")
                    ->orWhere('mm.title', 'like', "%{$q}%");
            });
        })
        ->select(
            'am.article_id',
            'am.article_title',
            'am.magazine_id',
            'mm.title as magazine_title',
            'mm.month',
            'mm.year',
            DB::raw('COUNT(cal.logid) as total_views'),
            DB::raw('COUNT(DISTINCT cal.customer_id) as unique_users'),
            DB::raw('MAX(cal.date_time) as last_view_time')
        )
        ->groupBy(
            'am.article_id',
            'am.article_title',
            'am.magazine_id',
            'mm.title',
            'mm.month',
            'mm.year'
        )
        ->orderByDesc('total_views')
        ->paginate(20)
        ->appends(['q' => $q]);

    return view('admin.report.article_wise_pdf_views', compact('articles', 'q'));
}

// ✅ Article detail: who viewed + time
public function articlePdfViewsDetail(Request $request, $article_id)
{
    $article = DB::table('article_master as am')
        ->leftJoin('magazine_master as mm', 'mm.id', '=', 'am.magazine_id')
        ->where('am.article_id', $article_id)
        ->where('am.isDelete', 0)
        ->where('am.iStatus', 1)
        ->select(
            'am.article_id',
            'am.article_title',
            'am.magazine_id',
            'mm.title as magazine_title',
            'mm.month',
            'mm.year'
        )
        ->first();

    if (!$article) {
        abort(404);
    }

    $rows = DB::table('customer_article_log as cal')
        ->join('customer_master as cm', 'cm.customer_id', '=', 'cal.customer_id')
        ->where('cal.article_id', $article_id)
        ->select(
            'cal.logid',
            'cal.date_time',
            'cm.customer_id',
            'cm.customer_name',
            'cm.customer_mobile',
            'cm.customer_email'
        )
        ->orderByDesc('cal.date_time')
        ->paginate(30)
        ->appends($request->query());

    return view('admin.report.article_pdf_views_detail', compact('article', 'rows'));
}



}
