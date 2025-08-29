<?php

namespace app\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\UtilityService;

class AdminRepository
{

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function registerPage($request)
    {
        return view('admin/register');
    }

    public function register(array $request)
    {
        DB::beginTransaction();
        try {

            $request['password'] = Hash::make($request['password']);
            $request['unique_id'] = UtilityService::generateUniqueCode();
            $user = $this->user::create($request);
            if ($user) {
                DB::commit();
                return view('admin/login')->with('message', 'Registered Successfully!');
            } else {
                return back()->withErrors(['Something went wrong']);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors([$e->getMessage()]);
        }
    }

    public function loginPage($request)
    {
        return view('admin/login');
    }

    public function login($request)
    {
        try {
            if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {
                return redirect()->intended('admin/dashboard');
            } else {
                return back()->withErrors(['Invalid Email or Password']);
            }
        } catch (\Exception $e) {
            return back()->withErrors([$e->getMessage()]);
        }
    }

    public function logout($request)
    {
        try {
            Auth::guard('admin')->logout();
            return view('admin/login');
        } catch (\Exception $e) {
            return back()->withErrors([$e->getMessage()]);
        }
    }

    public function dashboard($request)
    {
        try {
            return view('admin/dashboard', [
                'productsCount' => 10,
                'customersCount' => 1000,
                'ordersPending' => 12,
                'ordersShipped' => 15,
                'ordersDelivered' => 20,
            ]);

        } catch (\Exception $e) {
            return back()->withErrors([$e->getMessage()]);
        }
    }

}
