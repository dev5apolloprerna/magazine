<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerLoginLog;
use App\Models\Subscription;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Carbon\Carbon;

class CustomerAuthController extends Controller
{
    public function login(Request $request)
    {
        $rules = [
            'customer_mobile' => 'required|digits:10',
            'password' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $customer = Customer::where('customer_mobile', $request->customer_mobile)
            ->where('isDelete', 0)
            ->where('iStatus', 1)
            ->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json(['status' => false, 'message' => 'Invalid login credentials'], 401);
        }

        $token = JWTAuth::fromUser($customer);

        // Log login
        CustomerLoginLog::create([
            'customer_id' => $customer->customer_id,
            'login_date_time' => Carbon::now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
            'customer' => $customer
        ]);
    }
    public function register(Request $request)
    {
        $request->validate([
            'customer_name'   => 'required|string|max:255',
            'customer_mobile' => 'required|digits:10|unique:customer_master,customer_mobile',
            'customer_email'  => 'required|email|unique:customer_master,customer_email',
            'password'        => 'required|min:6',
        ]);

        $customer = Customer::create([
            'customer_name'   => $request->customer_name,
            'customer_mobile' => $request->customer_mobile,
            'customer_email'  => $request->customer_email,
            'password'        => Hash::make($request->password),
            'iStatus'         => 1,
        ]);

        // Insert dummy subscription (start + end = yesterday)
        $yesterday = Carbon::yesterday()->toDateString();

        Subscription::create([
            'customer_id' => $customer->customer_id,
            'plan_id'     => 0, // or default if applicable
            'start_date'  => $yesterday,
            'end_date'    => $yesterday,
            'amount'      => 0,
            'days'      => 0,
            'iStatus'     => 1,
            'isDelete'    => 0,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Create token
        $token = JWTAuth::fromUser($customer);

        return response()->json([
            'status'   => true,
            'message'  => 'Registration successful',
            // 'token'    => $token,
            'customer_list' => $customer,
        ]);
    }

}
