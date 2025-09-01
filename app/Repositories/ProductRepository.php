<?php

namespace App\Repositories;

use App\Imports\ProductsImport;
use App\Models\products;
use Maatwebsite\Excel\Facades\Excel;

class ProductRepository
{
    public $product;

    public function __construct(products $product)
    {
        $this->product = $product;
    }

    public function add($request)
    {
        Excel::import(new ProductsImport(auth()->guard('admin')->user()->id), $request['file']);
        return back()->with('message', 'Products import started! Data will be processed in background.');
    }

    public function get($request, $searchData)
    {
        try {
            if (auth()->guard('admin')->check()) {
                $products = $this->product::with(['added_by'])->where([[$searchData]])
                    ->when(array_key_exists('name', $request), function ($query) use ($request) {
                        $query->where('name', 'like', '%' . $request['name'] . '%');
                    })
                    ->where('added_by', auth()->guard('admin')->user()->id)
                    ->orderBy('id', 'desc')
                    ->paginate(10)
                    ->withQueryString();
            } else if (auth()->guard('customer')->check()) {
                $products = $this->product::with(['added_by'])->where([[$searchData]])
                    ->when(array_key_exists('name', $request), function ($query) use ($request) {
                        $query->where('name', 'like', '%' . $request['name'] . '%');
                    })
                    ->orderBy('id', 'desc')
                    ->paginate(10)
                    ->withQueryString();
            }
            return view('products/product', ['products' => $products]);
        } catch (\Exception $exception) {
            return back()->withErrors([$exception->getMessage()]);
        }
    }

    public function detail($request)
    {
        try {
            $product = $this->product::with(['added_by'])->where('unique_id', $request['unique_id'])->first();
            if ($product) {
                return view('products/product_detail', ['product' => $product]);
            } else {
                return redirect('admin/product')->with(['Product not found!']);
            }
        } catch (\Exception $exception) {
            return back()->withErrors([$exception->getMessage()]);
        }
        return view('admin.product');
    }

    public function update($request, $reqData)
    {
        try {
            $product = $this->product::where('unique_id', $request['unique_id'])->first();
            if ($product) {
                $product->update($reqData);
                return redirect('admin/product')->with('message', 'Product updated successfully!');
            } else {
                return back()->withErrors(['Product not found!']);
            }
        } catch (\Exception $exception) {
            return back()->withErrors([$exception->getMessage()]);
        }
    }

    public function delete($request)
    {
        try {
            $product = $this->product::where('unique_id', $request['unique_id'])->delete();
            return redirect('admin/product')->with('message', 'Product has been deleted');
        } catch (\Exception $exception) {
            return back()->withErrors([$exception->getMessage()]);
        }
        return view('admin.product');
    }


}
