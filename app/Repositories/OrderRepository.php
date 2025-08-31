<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\OrderDetail;
use App\Models\Orders;
use App\Models\products;
use App\Services\UtilityService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderRepository
{
    public $order;
    public $orderDetails;

    public function __construct(Orders $order, OrderDetail $orderDetail)
    {
        $this->order = $order;
        $this->orderDetails = $orderDetail;
    }

    public function add($request)
    {
        DB::beginTransaction();
        try {
            $cartProduct = Cart::with(['product'])->where('customer_id', auth()->guard('customer')->user()->id)->get();
            if (count($cartProduct) > 0) {
                $order = $this->order::create([
                    'unique_id' => UtilityService::generateUniqueCode(),
                    'order_number' => $this->order::where('customer_id', auth()->guard('customer')->user()->id)->max('order_number') + 1,
                    'customer_id' => auth()->guard('customer')->id(),
                    'payment_method' => $request['payment_method'],
                    'transaction_id' => rand(10000, 999999),
                    'shipping_address' => $request['shipping_address'],
                    'billing_address' => $request['billing_address'],
                ]);
                if ($order) {
                    foreach ($cartProduct as $product) {
                        $this->orderDetails::create([
                            'unique_id' => UtilityService::generateUniqueCode(),
                            'order_id' => $order->id,
                            'product_id' => $product->product_id,
                            'product_added_by' => products::where('id', $product->product_id)->pluck('added_by')->first(),
                            'quantity' => $product->quantity,
                            'price' => $product['product']->price,
                            'total' => round($product['product']->price * $product->quantity, 2),
                        ]);
                    }
                    Cart::with(['product'])->where('customer_id', auth()->guard('customer')->user()->id)->forceDelete();
                    DB::commit();
                    return redirect('customer/orders');
                } else {
                    return back()->withErrors(['message', 'somthing went wrong']);
                }
            } else {
                return back()->withErrors(['message', 'Product not found!']);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return back()->withErrors(['message', $exception->getMessage()]);
        }
    }

    public function get($request)
    {
        try {
            if (auth()->guard('admin')->check()) {
                $orderIds = $this->orderDetails::where('product_added_by', auth()->guard('admin')->user()->id)->distinct()->pluck('order_id')->toArray();
                $orders = $this->order::with(['order_details', 'customer'])->whereIn('id', $orderIds)->orderBy('id', 'DESC')->get();
                return view('products/orders', ['orders' => $orders]);
            } else if (auth()->guard('customer')->check()) {
                $orders = $this->order::with(['order_details'])->where('customer_id', auth()->guard('customer')->user()->id)
                    ->orderBy('id', 'DESC')
                    ->get();
                return view('products/orders', ['orders' => $orders]);
            }
        } catch (\Exception $exception) {
            return $exception->getMessage();
            return back()->withErrors([$exception->getMessage()]);
        }
    }

    public function update($request)
    {
        try {
            $order = $this->order::where('unique_id', $request['unique_id'])->update([
                'status' => $request['status'],
            ]);
            if ($order) {
                return redirect('admin/orders');
            } else {
                return back()->withErrors(['message', 'something went wrong']);
            }

        } catch (\Exception $exception) {
            return back()->withErrors([$exception->getMessage()]);
        }
    }
}
