<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\CustomerLoginRequest;
use App\Http\Requests\Customer\CustomerRegisterRequest;
use App\Repositories\CustomerRepository;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public $customerRepository;

    public function __construct(CustomerRepository $customerRepository){
        $this->customerRepository = $customerRepository;
    }

    public function registerPage(Request $request)
    {
        $inputs = array_merge_recursive(
            $request->all(),
            $request->header(),
            $request->route()->parameters()
        );
        return $this->customerRepository->registerPage($inputs);
    }

    public function register(CustomerRegisterRequest $request)
    {
        $inputs = array_merge_recursive(
            $request->all(),
            $request->header(),
            $request->route()->parameters()
        );
        return $this->customerRepository->register($inputs);
    }

    public function loginPage(Request $request)
    {
        $inputs = array_merge_recursive(
            $request->all(),
            $request->header(),
            $request->route()->parameters()
        );
        return $this->customerRepository->loginPage($inputs);
    }


    public function login(CustomerLoginRequest $request)
    {
        $inputs = array_merge_recursive(
            $request->all(),
            $request->header(),
            $request->route()->parameters()
        );
        return $this->customerRepository->login($inputs);
    }
}
