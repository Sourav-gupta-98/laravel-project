<?php

namespace app\Repositories;

use App\Models\Customer;
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
            $request['unique_id'] = \UtilityService::generateUniqueCode();
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

    public function login(array $request)
    {
        try {
            if (Auth::guard('customer')->attempt($request, $request->filled('remember'))) {
                $request->session()->regenerate();
                return redirect()->intended('customer/dashboard');
            } else {
                return back()->with('error', 'Something went wrong');
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


}
