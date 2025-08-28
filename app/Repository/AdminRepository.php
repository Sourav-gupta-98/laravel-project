<?php

namespace app\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            $request['unique_id'] = \UtilityService::generateUniqueCode();
            $user = $this->user::create($request);
            if ($user) {
                DB::commit();
                return view('admin/login')->with('message', 'Registered Successfully!');
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
        return view('admin/login');
    }

    public function login(array $request)
    {
        try {
            if (Auth::guard('web')->attempt($request, $request->filled('remember'))) {
                $request->session()->regenerate();
                return redirect()->intended('admin/dashboard');
            } else {
                return back()->with('error', 'Something went wrong');
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

}
