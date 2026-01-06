<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $customers = Customer::from('customer_master as cm')
        ->leftJoin('customer_login_log as cl', 'cl.customer_id', '=', 'cm.customer_id')
        ->select(
            'cm.customer_id',
            'cm.customer_name',
            'cm.customer_mobile',
            'cm.customer_email',
            DB::raw('MAX(cl.login_date_time) as last_login'),
            DB::raw('COUNT(cl.id) as login_count')
        )
        ->groupBy('cm.customer_id', 'cm.customer_name', 'cm.customer_mobile', 'cm.customer_email')
        ->orderByDesc('cm.customer_id')
        ->paginate(20);

        return view('admin.report.customer_login', compact('customers'));
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

}
