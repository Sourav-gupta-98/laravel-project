<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\AddOrderRequest;
use App\Http\Requests\Product\UpdateOrderRequest;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function add(AddOrderRequest $request)
    {
        $inputs = array_merge_recursive(
            $request->all(),
            $request->header(),
            $request->route()->parameters()
        );
        return $this->orderRepository->add($inputs);
    }

    public function get(Request $request)
    {
        $inputs = array_merge_recursive(
            $request->all(),
            $request->header(),
            $request->route()->parameters()
        );
        return $this->orderRepository->get($inputs);
    }

    public function update(UpdateOrderRequest $request)
    {
        $inputs = array_merge_recursive(
            $request->all(),
            $request->header(),
            $request->route()->parameters()
        );
        return $this->orderRepository->update($inputs);
    }
}
