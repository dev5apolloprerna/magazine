<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Razorpay\Api\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RazorpayOrder;
use Razorpay\Api\Errors\SignatureVerificationError;
use Carbon\Carbon;


class PaymentController extends Controller
{
        public function createOrder(Request $request)
        {
            $user = auth()->guard('api')->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorised'], 401);
            }

            $request->validate([
                'plan_id' => 'required|integer',
            ]);

            $customerId = $user->customer_id ?? $user->id;

            $plan = DB::table('plan_master')->where('plan_id', $request->plan_id)->first();
            if (!$plan) {
                return response()->json(['success' => false, 'message' => 'Plan not found'], 404);
            }

            $amount = (float) $plan->plan_amount;
            $days   = (int) $plan->days;

            try {
                $api = new \Razorpay\Api\Api(config('services.razorpay.key'), config('services.razorpay.secret'));

                $receipt = 'rcpt_' . $customerId . '_' . time();

                $order = $api->order->create([
                    'receipt'  => $receipt,
                    'amount'   => (int) round($amount * 100),
                    'currency' => 'INR',
                ]);

                $orderdata=RazorpayOrder::create([
                    'customer_id' => $customerId,
                    'plan_id'     => $request->plan_id,
                    'order_id'    => $order['id'],
                    'receipt'     => $receipt,
                    'amount'      => $amount,
                    'currency'    => 'INR',
                    'status'      => 'created',
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Order created',
                    'data' => [
                        'razorpay_key' => config('services.razorpay.key'),
                        'razorpay_order_id'     => $order['id'],
                        'order_id'     => $orderdata->id,
                        'customer_id'     => $orderdata->customer_id,
                        'amount'       => $amount,
                        'currency'     => 'INR',
                        'plan_id'      => $request->plan_id,
                        'days'         => $days,
                    ]
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }
        }

        public function paymentStatusUpdate(Request $request)
        {
            // accept both Customer_id and customer_id
            $customerId = $request->input('customer_id', $request->input('Customer_id'));
            $status     = $request->input('status'); // "Success" / "Failed" etc.

            $rzpOrderId   = $request->input('razorpay_order_id');
            $rzpPaymentId = $request->input('razorpay_payment_id');
            $rzpSignature = $request->input('razorpay_signature');

            // local order id from app (optional)
            $localOrderId = $request->input('order_id'); // e.g. 69

            $request->validate([
                'status'             => 'required|string',
                'razorpay_order_id'  => 'required|string',
                'razorpay_payment_id'=> 'required|string',
                'razorpay_signature' => 'required|string',
            ]);

            if (empty($customerId)) {
                return response()->json(['success' => false, 'message' => 'customer_id is required'], 422);
            }

            // ✅ Find order row from DB (prefer razorpay order id)
            $orderRow = RazorpayOrder::where('order_id', $rzpOrderId)->first();

            // fallback: if app sends your internal id in order_id
            if (!$orderRow && !empty($localOrderId)) {
                $orderRow = RazorpayOrder::where('id', $localOrderId)->first();
                // if found but order_id mismatch, reject
                if ($orderRow && $orderRow->order_id !== $rzpOrderId) {
                    return response()->json(['success' => false, 'message' => 'Order mismatch'], 422);
                }
            }

            if (!$orderRow) {
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            // ✅ Customer match
            if ((string)$orderRow->customer_id !== (string)$customerId) {
                return response()->json(['success' => false, 'message' => 'Customer mismatch'], 422);
            }

            // ✅ If already paid, return success (idempotent)
            if ($orderRow->status === 'paid') {
                return response()->json([
                    'success' => true,
                    'message' => 'Already updated',
                ]);
            }

            // If app says failed, mark failed and stop
            if (strtolower($status) !== 'success') {
                $orderRow->status = 'failed';
                $orderRow->payment_id = $rzpPaymentId;
                $orderRow->signature  = $rzpSignature;
                $orderRow->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Payment marked as failed',
                ]);
            }

            // ✅ Verify Razorpay signature
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            try {
                $api->utility->verifyPaymentSignature([
                    'razorpay_order_id'   => $rzpOrderId,
                    'razorpay_payment_id' => $rzpPaymentId,
                    'razorpay_signature'  => $rzpSignature,
                ]);
            } catch (SignatureVerificationError $e) {
                $orderRow->status = 'failed';
                $orderRow->save();

                return response()->json([
                    'success' => false,
                    'message' => 'Signature verification failed',
                ], 422);
            }

            // ✅ Recommended: Check payment status from Razorpay
            $payment = $api->payment->fetch($rzpPaymentId);
            if (($payment['status'] ?? '') !== 'captured') {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not captured yet',
                ], 422);
            }

            // ✅ Renew subscription using plan_id from razorpay_orders
            $planId = $orderRow->plan_id;

            $plan = DB::table('plan_master')->where('plan_id', $planId)->first();
            if (!$plan) {
                return response()->json(['success' => false, 'message' => 'Plan not found'], 404);
            }

            $days   = (int) $plan->days;
            $amount = (float) $plan->amount;

            DB::transaction(function () use ($orderRow, $rzpPaymentId, $rzpSignature, $customerId, $planId, $days, $amount) {

                // update order
                $orderRow->payment_id = $rzpPaymentId;
                $orderRow->signature  = $rzpSignature;
                $orderRow->status     = 'paid';
                $orderRow->save();

                // active subscription (if any)
                $activeSub = DB::table('subscription_master')
                    ->where('customer_id', $customerId)
                    ->where('isActive', 1)
                    ->orderByDesc('subscription_id')
                    ->first();

                $today = Carbon::today();

                // if active and not expired => start after end_date else start today
                if ($activeSub && !empty($activeSub->end_date) && Carbon::parse($activeSub->end_date)->gte($today)) {
                    $startDate = Carbon::parse($activeSub->end_date)->addDay();
                } else {
                    $startDate = $today;
                }

                // end_date inclusive
                $endDate = (clone $startDate)->addDays(max(0, $days - 1));

                // deactivate old
                DB::table('subscription_master')
                    ->where('customer_id', $customerId)
                    ->where('isActive', 1)
                    ->update(['isActive' => 0]);

                // insert new subscription
                DB::table('subscription_master')->insert([
                    'customer_id' => $customerId,
                    'plan_id'     => $planId,
                    'start_date'  => $startDate->toDateString(),
                    'end_date'    => $endDate->toDateString(),
                    'days'        => $days,
                    'amount'      => $amount,
                    'isActive'    => 1,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Payment updated and subscription renewed',
                'data' => [
                    'customer_id' => (int)$customerId,
                    'plan_id'     => (int)$planId,
                    'razorpay_order_id'   => $rzpOrderId,
                    'razorpay_payment_id' => $rzpPaymentId,
                    'status'      => 'paid',
                ]
            ]);
        }




        public function verifyAndRenew(Request $request)
        {
            $user = auth()->guard('api')->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorised'], 401);
            }

            $request->validate([
                'plan_id'             => 'required|integer',
                'razorpay_order_id'   => 'required|string',
                'razorpay_payment_id' => 'required|string',
                'razorpay_signature'  => 'required|string',
            ]);

            $customerId = $user->customer_id ?? $user->id;

            // ✅ Find order record
            $orderRow = RazorpayOrder::where('order_id', $request->razorpay_order_id)
                ->where('customer_id', $customerId)
                ->first();

            if (!$orderRow) {
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            // ✅ If already paid, return success (idempotent)
            if ($orderRow->status === 'paid') {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment already verified & subscription renewed.',
                ]);
            }

            // ✅ Fetch plan
            $plan = DB::table('plan_master')->where('plan_id', $request->plan_id)->first();
            if (!$plan) {
                return response()->json(['success' => false, 'message' => 'Plan not found'], 404);
            }

            $amount = (float) $plan->plan_amount;
            $days   = (int) $plan->days;

            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

            // ✅ Verify signature
            try {
                $api->utility->verifyPaymentSignature([
                    'razorpay_order_id'   => $request->razorpay_order_id,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature'  => $request->razorpay_signature,
                ]);
            } catch (SignatureVerificationError $e) {
                // mark failed (optional)
                $orderRow->status = 'failed';
                $orderRow->save();

                return response()->json([
                    'success' => false,
                    'message' => 'Payment signature verification failed',
                ], 422);
            }

            // ✅ Check payment captured
            $payment = $api->payment->fetch($request->razorpay_payment_id);
            if (($payment['status'] ?? '') !== 'captured') {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not captured yet',
                ], 422);
            }

            DB::transaction(function () use ($customerId, $request, $days, $amount, $orderRow) {

                // ✅ update order row
                $orderRow->payment_id = $request->razorpay_payment_id;
                $orderRow->signature  = $request->razorpay_signature;
                $orderRow->status     = 'paid';
                $orderRow->save();

                // ✅ current active subscription
                $activeSub = DB::table('subscription_master')
                    ->where('customer_id', $customerId)
                    ->where('isActive', 1)
                    ->orderByDesc('subscription_id')
                    ->first();

                $today = Carbon::today();

                // if active and not expired -> start after end_date else start today
                if ($activeSub && !empty($activeSub->end_date) && Carbon::parse($activeSub->end_date)->gte($today)) {
                    $startDate = Carbon::parse($activeSub->end_date)->addDay();
                } else {
                    $startDate = $today;
                }

                // end_date inclusive
                $endDate = (clone $startDate)->addDays(max(0, $days - 1));

                // deactivate old
                DB::table('subscription_master')
                    ->where('customer_id', $customerId)
                    ->where('isActive', 1)
                    ->update(['isActive' => 0]);

                // insert new subscription
                DB::table('subscription_master')->insert([
                    'customer_id' => $customerId,
                    'plan_id'     => $request->plan_id,
                    'start_date'  => $startDate->toDateString(),
                    'end_date'    => $endDate->toDateString(),
                    'days'        => $days,
                    'amount'      => $amount,
                    'isActive'    => 1,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Payment verified & subscription renewed successfully',
            ]);
        }

}