<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Customer;

class CustomerPasswordController extends Controller
{
    private string $emailColumn = 'customer_email';

    // Temp password expiry (optional)
    private int $tempExpireMinutes = 60;

    public function forgot(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;

        $customer = Customer::where($this->emailColumn, $email)->first();
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Email not found.',
            ], 404);
        }

        // ✅ Generate a temporary password
        $tempPassword = $this->generateTempPassword(10);

        // ✅ Update password in DB (hashed)
        $customer->password = Hash::make($tempPassword);

        // Optional fields (recommended)
        // Add these columns via migration (shown below) if you want:
        $customer->must_reset_password = 1;
        $customer->temp_password_set_at = now();

        $customer->save();

        // ✅ Email data
        $data = [
            'customer'     => $customer,
            'tempPassword' => $tempPassword,
            'minutes'      => $this->tempExpireMinutes,
        ];

        $msg = [
            'FromMail' => config('mail.from.address'),
            'Title'    => config('mail.from.name'),
            'ToEmail'  => $email,
            'Subject'  => 'Your Temporary Password',
        ];

        try {
            Mail::send('emails.customer_reset_password', $data, function ($message) use ($msg) {
                $message->from($msg['FromMail'], $msg['Title']);
                $message->to($msg['ToEmail'])->subject($msg['Subject']);
            });
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Mail failed',
                'error'   => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Temporary password sent successfully.',
        ]);
    }

    /**
     * ✅ Reset password using temp password
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email'         => 'required|email',
            'temp_password' => 'required|string',
            'password'      => 'required|string|min:6|confirmed',
        ]);

        $email = $request->email;

        $customer = Customer::where($this->emailColumn, $email)->first();
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        // Optional expiry check (recommended)
        if (!empty($customer->temp_password_set_at)) {
            $created = Carbon::parse($customer->temp_password_set_at);
            if ($created->diffInMinutes(now()) > $this->tempExpireMinutes) {
                return response()->json([
                    'success' => false,
                    'message' => 'Temporary password expired. Please request again.',
                ], 422);
            }
        }

        // ✅ Verify temp password
        if (!Hash::check($request->temp_password, $customer->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid temporary password.',
            ], 422);
        }

        // ✅ Set new password
        $customer->password = Hash::make($request->password);

        // Optional fields reset
        $customer->must_reset_password = 0;
        $customer->temp_password_set_at = null;

        $customer->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
    }

    /**
     * Strong-ish temp password generator
     */
    private function generateTempPassword(int $len = 10): string
    {
        // Example: Ab7#kP2!xQ (mix)
        $upper = Str::upper(Str::random(2));
        $lower = Str::lower(Str::random(4));
        $digits = (string) random_int(10 ** 2, (10 ** 3) - 1); // 3 digits
        $symbols = '!@#$%&*';
        $sym = $symbols[random_int(0, strlen($symbols) - 1)];

        $raw = $upper . $lower . $digits . $sym . Str::random(max(0, $len - (2 + 4 + 3 + 1)));
        return substr(str_shuffle($raw), 0, $len);
    }

    public function changePassword(Request $request)
    {
        $customer = auth()->guard('api')->user();

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorised'
            ], 401);
        }

        $request->validate([
            'current_password' => 'required|string|min:6',
            'new_password'     => 'required|string|min:6|different:current_password|confirmed',
            // requires: new_password_confirmation
        ]);

        // ✅ Check current password
        if (!Hash::check($request->current_password, $customer->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }

        // ✅ Update password
        $customer->password = Hash::make($request->new_password);
        $customer->save();

        return response()->json([
            'status'  => true,
            'message' => 'Password changed successfully'
        ]);
    }

}
