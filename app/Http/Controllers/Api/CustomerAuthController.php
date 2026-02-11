<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerLoginLog;
use App\Models\Subscription;
use App\Models\FreeArticle;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use JWTAuth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        $customerId=$customer->customer_id;
        $this->refreshActiveSubscription($customerId);

         Customer::where('customer_id', $customer->customer_id)->increment('login_count');

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
        $FreeArticle=FreeArticle::first();
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
            'free_article'    => $FreeArticle->free_article,
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
    
    public function profile(Request $request)
    {
        $customer = auth()->guard('api')->user();
    
        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorised'
            ], 401);
        }
    
        return response()->json([
            'status' => true,
            'message' => 'Customer profile fetched successfully',
            'data' => [
                'customer_id'     => $customer->customer_id,
                'customer_name'   => $customer->customer_name,
                'customer_mobile' => $customer->customer_mobile,
                'customer_email'  => $customer->customer_email,
                'created_at'      => $customer->created_at,
                'profile_image_url' => !empty($customer->profile_image)
                ? asset('/profile/' . $customer->profile_image)
                : null
            ]
        ]);
    }
    

    public function updateProfile(Request $request)
    {
        $customer = auth()->guard('api')->user();

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorised'
            ], 401);
        }

        $request->validate([
            'customer_name'   => 'required|string|max:255',
            'customer_mobile' => 'required|digits:10|unique:customer_master,customer_mobile,' . $customer->customer_id . ',customer_id',
            'customer_email'  => 'required|email|unique:customer_master,customer_email,' . $customer->customer_id . ',customer_id',

            // ✅ image validation
            'profile_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $customer->customer_name   = $request->customer_name;
        $customer->customer_mobile = $request->customer_mobile;
        $customer->customer_email  = $request->customer_email;

        // ✅ Upload profile image if provided
        if ($request->hasFile('profile_image')) {

            $folderPath = base_path('../public_html/magazine/profile'); // public_html/magazine/profile
            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0755, true);
            }

            // ✅ Delete old image (if exists)
            if (!empty($customer->profile_image)) {
                $oldPath = $folderPath . '/' . $customer->profile_image;
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $image = $request->file('profile_image');
            $filename = 'cust_' . $customer->customer_id . '_' . time() . '.' . $image->getClientOriginalExtension();

            $image->move($folderPath, $filename);

            // ✅ Save filename in DB column
            $customer->profile_image = $filename; // change column name if different
        }

        $customer->save();

        return response()->json([
            'status'  => true,
            'message' => 'Profile updated successfully',
            'data'    => $customer,
            'profile_image_url' => !empty($customer->profile_image)
                ? asset('/profile/' . $customer->profile_image)
                : null
        ]);
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
