<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\MagazineMaster;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Subscription;

use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        /*try
        {*/
            $mag_total=MagazineMaster::where('isDelete', 0)->where('iStatus', 1)->count();
            $customerCount = Customer::where('iStatus', 1)->where('isDelete', 0)->count();
            $planCount = Plan::where('iStatus', 1)->where('isDelete', 0)->count();


    $today = Carbon::today();
    $next7 = Carbon::today()->addDays(7);

    // Subscribed = end_date >= today
    $subscribedCount = DB::table('subscription_master')
        ->where('isDelete', 0)
        ->whereDate('end_date', '>=', $today)
        ->distinct('customer_id')
        ->count('customer_id');

    // Renewal = end_date between today and next 7 days
    $renewalCount = DB::table('subscription_master')
        ->where('isDelete', 0)
        ->whereBetween('end_date', [$today, $next7])
        ->distinct('customer_id')
        ->count('customer_id');

    // Unsubscribed = not present in subscription_master (isDelete=0)
    $unsubscribedCount = Customer::where('isDelete', 0)
        ->whereNotIn('customer_id', function ($q) {
            $q->select('customer_id')
              ->from('subscription_master')
              ->where('isDelete', 0);
        })
        ->count();

    $counts = [
        'subscribed'   => $subscribedCount,
        'renewal'      => $renewalCount,
        'unsubscribed' => $unsubscribedCount,
    ];




            return view('home', compact('customerCount','mag_total','planCount','counts'));

        /*} catch (\Exception $e) {
        report($e);
        return false;
        }*/
    }

    /**
     * User Profile
     * @param Nill
     * @return View Profile
     * @author Shani Singh
     */
    public function getProfile()
    {
        try{
        $session = Auth::user()->id;
        // dd($session);
        $users = User::where('users.id',  $session)
            ->first();
        // dd($users);

        return view('profile', compact('users'));
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }


    public function EditProfile()
    {
        try{
        $roles = Role::where('id', '!=', '1')->get();

        return view('Editprofile', compact('roles'));
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

    /**
     * Update Profile
     * @param $profileData
     * @return Boolean With Success Message
     * @author Shani Singh
     */
   public function updateProfile(Request $request)
    {
    
        #Validations
        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'mobile_number' => 'required|numeric|digits:10',
        ]);

        try {
            DB::beginTransaction();

            #Update Profile Data
            User::whereId(auth()->user()->id)->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile_number' => $request->mobile_number,
            ]);

            #Commit Transaction
            DB::commit();

            #Return To Profile page with success
            return back()->with('success', 'Profile Updated Successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Change Password
     * @param Old Password, New Password, Confirm New Password
     * @return Boolean With Success Message
     * @author Shani Singh
     */
    public function changePassword(Request $request)
    {
        try{
        $session = Auth::user()->id;

        $user = User::where('id', '=', $session)->where(['status' => 1])->first();

        if (Hash::check($request->current_password, $user->password)) 
        {
            $newpassword = $request->new_password;
            $confirmpassword = $request->new_confirm_password;

            if ($newpassword == $confirmpassword) {
                $Student = DB::table('users')
                    ->where(['status' => 1, 'id' => $session])
                    ->update([
                        'password' => Hash::make($confirmpassword),
                    ]);
                Auth::logout();
                return redirect()->route('login')->with('success', 'User Password Updated Successfully.');
            } else {
                return back()->with('error', 'password and confirm password does not match');
            }
        } else {
            return back()->with('error', 'Current Password does not match');
        }
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }
}
