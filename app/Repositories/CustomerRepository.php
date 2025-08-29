<?php

namespace app\Repositories;

use App\Models\Customer;
use App\Services\UtilityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerRepository
{
    public $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function registerPage($request)
    {
        return view('customer/register');
    }

    public function register(array $request)
    {
        DB::beginTransaction();
        try {
            $request['password'] = Hash::make($request['password']);
            $request['unique_id'] = UtilityService::generateUniqueCode();
            $customer = $this->customer::create($request);
            if ($customer) {
                DB::commit();
                return view('customer/login')->with('message', 'Registered Successfully!');
            } else {
                return back()->with('error', 'Something went wrong');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function loginPage($request)
    {
        return view('customer/login');
    }

    public function login($request)
    {
        try {
            if (Auth::guard('customer')->attempt($request->only('email', 'password'))) {
                return redirect()->intended('customer/dashboard');
            } else {
                return back()->withErrors(['Invalid email or password']);
            }
        } catch (\Exception $e) {
            return back()->withErrors([$e->getMessage()]);
        }
    }

    public function logout($request)
    {
        try {
            Auth::guard('customer')->logout();
            return view('customer/login');
        } catch (\Exception $e) {
            return back()->withErrors([$e->getMessage()]);
        }
    }

    public function dashboard($request)
    {
        try {
            return view('customer/dashboard', [
                'productsCount' => 1000,
                'pendingOrders' => 2,
                'shippedOrders' => 3,
                'deliveredOrders' => 1,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors([$e->getMessage()]);
        }
    }


}
