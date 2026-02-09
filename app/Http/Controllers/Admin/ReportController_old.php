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


    // ✅ Customer detail: Magazine wise view counts
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


        public function magazineWisePdfViews(Request $request)
        {
            $q = $request->q;

            $magazines = DB::table('magazine_master as mm')
                ->leftJoin('customer_magazine_log as cml', 'cml.magazine_id', '=', 'mm.id')
                ->where('mm.isDelete', 0)
                ->where('mm.iStatus', 1)
                ->when($q, function ($query) use ($q) {
                    $query->where(function ($sub) use ($q) {
                        $sub->where('mm.title', 'like', "%{$q}%")
                            ->orWhere('mm.month', 'like', "%{$q}%")
                            ->orWhere('mm.year', "%{$q}%");
                    });
                })
                ->select(
                    'mm.id',
                    'mm.title',
                    'mm.month',
                    'mm.year',
                    DB::raw('COUNT(cml.logid) as total_views'),
                    DB::raw('COUNT(DISTINCT cml.customer_id) as unique_users'),
                    DB::raw('MAX(cml.date_time) as last_view_time')
                )
                ->groupBy('mm.id', 'mm.title', 'mm.month', 'mm.year')
                ->orderByDesc('total_views')
                ->paginate(20)
                ->appends(['q' => $q]);

            return view('admin.report.magazine_wise_pdf_views', compact('magazines', 'q'));
        }

        public function magazinePdfViewsDetail(Request $request, $magazine_id)
        {
            $magazine = DB::table('magazine_master')
                ->where('id', $magazine_id)
                ->where('isDelete', 0)
                ->where('iStatus', 1)
                ->first();

            if (!$magazine) {
                abort(404);
            }

            $rows = DB::table('customer_magazine_log as cml')
                ->join('customer_master as cm', 'cm.customer_id', '=', 'cml.customer_id')
                ->where('cml.magazine_id', $magazine_id)
                ->select(
                    'cml.logid',
                    'cml.date_time',
                    'cm.customer_id',
                    'cm.customer_name',
                    'cm.customer_mobile',
                    'cm.customer_email'
                )
                ->orderByDesc('cml.date_time')
                ->paginate(30)
                ->appends($request->query()); // keep search/page in back link

            return view('admin.report.magazine_pdf_views_detail', compact('magazine', 'rows'));
        }



}
