<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\AdminLoginRequest;
use App\Http\Requests\Admin\AdminRegisterRequest;
use App\Repositories\AdminRepository;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public $userRepository;

    public function __construct(AdminRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerPage(Request $request)
    {
        $inputs = array_merge_recursive(
            $request->all(),
            $request->header(),
            $request->route()->parameters()
        );
        return $this->userRepository->registerPage($inputs);
    }

    public function register(AdminRegisterRequest $request)
    {
        $inputs = array_merge_recursive(
            $request->all(),
            $request->header(),
            $request->route()->parameters()
        );
        return $this->userRepository->register($inputs);
    }

    public function loginPage(Request $request)
    {
        $inputs = array_merge_recursive(
            $request->all(),
            $request->header(),
            $request->route()->parameters()
        );
        return $this->userRepository->loginPage($inputs);
    }


    public function login(AdminLoginRequest $request)
    {
        return $this->userRepository->login($request);
    }

    public function logout(Request $request)
    {
        $inputs = array_merge_recursive(
            $request->all(),
            $request->header(),
            $request->route()->parameters()
        );
        return $this->userRepository->logout($inputs);
    }

    public function dashboard(Request $request)
    {
        $inputs = array_merge_recursive(
            $request->all(),
            $request->header(),
            $request->route()->parameters()
        );
        return $this->userRepository->dashboard($inputs);
    }
}
