<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\AddProductRequest;
use App\Http\Requests\Admin\EditProductRequest;
use App\Repositories\ProductRepository;
use App\Services\UtilityService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function add(AddProductRequest $request)
    {
        $inputs = array_merge_recursive(
            $request->all(),
            $request->header(),
            $request->route()->parameters()
        );
        return $this->productRepository->add($inputs);
    }

    public function get(Request $request)
    {
        $inputs = array_merge_recursive(
            $request->all(),
            $request->header(),
            $request->route()->parameters()
        );
        $inputs['perpage'] = $request->per_page !== null ? $request->per_page : 15;
        $inputs['page'] = $request->page !== null ? $request->page : -1;
        $inputs = UtilityService::trimBlankKeys($inputs);
        $searchData = UtilityService::trimKeys($inputs, ['category']);
        return $this->productRepository->get($inputs, $searchData);
    }

    public function detail(Request $request)
    {
        $inputs = array_merge_recursive(
            $request->all(),
            $request->header(),
            $request->route()->parameters()
        );
        return $this->productRepository->detail($inputs);
    }

    public function update(EditProductRequest $request)
    {
        $inputs = array_merge_recursive(
            $request->all(),
            $request->header(),
            $request->route()->parameters()
        );
        $reqData = UtilityService::trimKeys($inputs, ['name', 'description', 'price', 'image', 'category', 'stock']);
        return $this->productRepository->update($inputs,$reqData);
    }

    public function delete(Request $request)
    {
        $inputs = array_merge_recursive(
            $request->all(),
            $request->header(),
            $request->route()->parameters()
        );
        return $this->productRepository->delete($inputs);
    }

}
