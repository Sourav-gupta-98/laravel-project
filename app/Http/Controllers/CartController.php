<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Repositories\CartRepository;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function get(Request $request)
    {
        $inputs = array_merge_recursive(
            $request->all(),
            $request->header(),
            $request->route()->parameters()
        );
        return $this->cartRepository->get($inputs);
    }

    public function update(Request $request)
    {
        $inputs = array_merge_recursive(
            $request->all(),
            $request->header(),
            $request->route()->parameters()
        );
        return $this->cartRepository->update($inputs);
    }

    public function delete(Request $request)
    {
        $inputs = array_merge_recursive(
            $request->all(),
            $request->header(),
            $request->route()->parameters()
        );
        return $this->cartRepository->delete($inputs);
    }


}
