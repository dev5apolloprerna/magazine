<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $query = Plan::where('isDelete', 0);
        if ($request->search) {
            $query->where('plan_name', 'like', '%' . $request->search . '%');
        }

        $plans = $query->latest()->paginate(10);
        return view('admin.plan.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plan_name'    => 'required',
            'plan_amount'  => 'required',
            'days'         => 'required',
        ]);

        Plan::create([
            'plan_name'   => $request->plan_name,
            'plan_amount' => $request->plan_amount,
            'days'        => $request->days,
            'iStatus'     => $request->has('iStatus') ? 1 : 0,
        ]);

        return redirect()->route('plan.index')->with('success', 'Plan added successfully.');
    }

    public function edit($id)
    {
        $plan = Plan::findOrFail($id);
        return view('admin.plan.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);

        $request->validate([
            'plan_name'    => 'required',
            'plan_amount'  => 'required',
            'days'         => 'required',
        ]);

        $plan->update([
            'plan_name'   => $request->plan_name,
            'plan_amount' => $request->plan_amount,
            'days'        => $request->days,
            'iStatus'     => $request->has('iStatus') ? 1 : 0,
        ]);

        return redirect()->route('plan.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();

        return response()->json(['success' => 'Deleted successfully.']);
    }

    public function bulkDelete(Request $request)
    {
        foreach ($request->ids as $id) {
            $plan = Plan::find($id);
            if ($plan) {
                $plan->delete();
            }
        }

        return redirect()->route('plan.index')->with('success', 'Selected plans deleted.');
    }

    public function toggleStatus(Request $request)
    {
        $plan = Plan::find($request->id);
        $plan->iStatus = !$plan->iStatus;
        $plan->save();

        return response()->json(['success' => 'Status updated']);
    }
}
