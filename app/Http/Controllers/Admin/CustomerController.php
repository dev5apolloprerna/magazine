<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::where('isDelete', 0);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_mobile', 'like', '%' . $request->search . '%');
            });
        }

        $customers = $query->latest()->paginate(10);
        return view('admin.customer.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customer.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'   => 'required',
            'customer_mobile' => 'required',
            'customer_email'  => 'required|email|unique:customer_master,customer_email',
            'password'        => 'required|min:6',
        ]);

        Customer::create([
            'customer_name'   => $request->customer_name,
            'customer_mobile' => $request->customer_mobile,
            'customer_email'  => $request->customer_email,
            'password'        => Hash::make($request->password),
            'iStatus'         => $request->has('iStatus') ? 1 : 0,
        ]);

        return redirect()->route('customer.index')->with('success', 'Customer added successfully.');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.customer.form', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'customer_name'   => 'required',
            'customer_mobile' => 'required',
            'customer_email'  => 'required|email|unique:customer_master,customer_email,' . $id . ',customer_id',
            'password'        => 'nullable|min:6',
        ]);

        $customer->customer_name   = $request->customer_name;
        $customer->customer_mobile = $request->customer_mobile;
        $customer->customer_email  = $request->customer_email;

        if ($request->password) {
            $customer->password = Hash::make($request->password);
        }

        $customer->iStatus = $request->has('iStatus') ? 1 : 0;
        $customer->save();

        return redirect()->route('customer.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json(['success' => 'Record deleted successfully.']);
    }

    public function bulkDelete(Request $request)
    {
        foreach ($request->ids as $id) {
            $customer = Customer::find($id);
            if ($customer) {
                $customer->delete();
            }
        }

        return redirect()->route('customer.index')->with('success', 'Selected records deleted successfully.');
    }

    public function toggleStatus(Request $request)
    {
        $customer = Customer::find($request->id);
        $customer->iStatus = !$customer->iStatus;
        $customer->save();

        return response()->json(['success' => 'Status updated']);
    }

    public function subscriptionTabs()
    {
        $today = Carbon::today();
        $next7 = Carbon::today()->addDays(7);

        // Subscribed = active (end_date >= today)
        $subscribed = Customer::select('customer_master.*', 'subscription_master.start_date', 'subscription_master.end_date','subscription_master.amount','plan_master.plan_name','subscription_master.days')
            ->join('subscription_master', 'subscription_master.customer_id', '=', 'customer_master.customer_id')
            ->leftJoin('plan_master', 'plan_master.plan_id', '=', 'subscription_master.plan_id')
            ->where('subscription_master.end_date', '>=', $today)
            ->where('subscription_master.isDelete', 0)
            ->where('customer_master.isDelete', 0)
            ->get();

        // Renewal = end_date within next 7 days
        $renewal = Customer::select('customer_master.*', 'subscription_master.start_date', 'subscription_master.end_date','subscription_master.amount','plan_master.plan_name','subscription_master.days')
            ->join('subscription_master', 'subscription_master.customer_id', '=', 'customer_master.customer_id')
            ->leftJoin('plan_master', 'plan_master.plan_id', '=', 'subscription_master.plan_id')
            ->whereBetween('subscription_master.end_date', [$today, $next7])
            ->where('subscription_master.isDelete', 0)
            ->where('customer_master.isDelete', 0)
            ->get();

        // Unsubscribed = no entry in subscription_master
        $unsubscribed = Customer::whereNotIn('customer_id', function ($query) {
                $query->select('customer_id')->from('subscription_master')->where('isDelete', 0);
            })
            ->where('isDelete', 0)
            ->get();

        return view('admin.customer.subscription-tabs', compact('subscribed', 'renewal', 'unsubscribed'));
    }

}
