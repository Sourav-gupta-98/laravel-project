<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\products;
use App\Services\UtilityService;
use Illuminate\Support\Facades\DB;

class CartRepository
{

    public $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function get($request)
    {
        try {
            $carts = Cart::with(['product'])->where('customer_id', auth()->guard('customer')->user()->id)
                ->orderBy('id', 'DESC')
                ->get();
            return view('products/cart', ['carts' => $carts]);

        } catch (\Exception $exception) {
            return back()->withErrors([$exception->getMessage()]);
        }
    }

    public function update($request)
    {
        DB::beginTransaction();
        try {
            $product = products::where('unique_id', $request['unique_id'])->first();
            if ($product && $product->id) {
                $productExistInCart = $this->cart::where('product_id', $product->id)
                    ->where('customer_id', auth()->guard('customer')->user()->id)->first();
                if ($productExistInCart && $productExistInCart->id) {
                    $productExistInCart->quantity = $productExistInCart->quantity + 1;
                    $productExistInCart->save();
                } else {
                    $productExistInCart =  $this->cart::create([
                        'customer_id' => auth()->guard('customer')->user()->id,
                        'product_id' => $product->id,
                        'quantity' => 1,
                        'unique_id' => UtilityService::generateUniqueCode()
                    ]);
                }
                DB::commit();
                return redirect('customer/cart');
            } else {
                return back()->withErrors(['Product not found!']);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return back()->withErrors([$exception->getMessage()]);
        }

    }

    public function delete($request)
    {
        DB::beginTransaction();
        try {
            $product = products::where('unique_id', $request['unique_id'])->first();
            if ($product && $product->id) {
                $productExistInCart = $this->cart::where('product_id', $product->id)
                    ->where('customer_id', auth()->guard('customer')->user()->id)->first();
                if ($productExistInCart && $productExistInCart->id && $productExistInCart->quantity > 1) {
                    $productExistInCart->quantity = $productExistInCart->quantity - 1;
                    $productExistInCart->save();
                } else {
                    $productExistInCart->forceDelete();
                }
                DB::commit();
                return redirect('customer/cart');
            } else {
                return back()->withErrors(['Product not found!']);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return back()->withErrors([$exception->getMessage()]);
        }
    }
}
