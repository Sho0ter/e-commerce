<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\ProductCreateRequest;
use App\Http\Requests\API\ProductUpdateRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $store_id = Auth::user()->store->id;
        $products = Product::where('store_id', $store_id)->paginate(10);
        return $this->sendResponse($products, __('product.success_show'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductCreateRequest $request)
    {
        $data = $request->all();
        $store_id = Auth::user()->store->id;
        $data['store_id'] =  $store_id;
        $product = Product::create($data);
        $product->refresh();
        return $this->sendResponse($product, __('product.success_add'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $store_id = Auth::user()->store->id;
        $product->where('store_id', $store_id);
        return $this->sendResponse($product, __('product.success_show'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $store_id = Auth::user()->store->id;
        $product->where('store_id', $store_id);
        $product->update($request->all());
        return $this->sendResponse($product, __('product.success_update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $store_id = Auth::user()->store->id;
        $product->where('store_id', $store_id);
        $product->delete();
        return $this->sendResponse($product, __('product.success_delete'));
    }
}
