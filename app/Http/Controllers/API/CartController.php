<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\StoreCartRequest;
use App\Http\Requests\API\UpdateCartRequest;
use App\Models\Cart;
use App\Models\Product;
use App\Policies\CartPolicy;
use Illuminate\Support\Facades\Auth;

class CartController extends BaseController
{

    public function __construct()
    {
        $this->authorizeResource(Cart::class, 'cart');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cart = Cart::where('user_id', Auth::user()->id)->where('status', 'pending')->with(['product'])->get();
        return $this->sendResponse($cart, __('cart.success_show'));
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
     * @param  \App\Http\Requests\StoreCartRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCartRequest $request)
    {
        $product = Product::find($request->product_id);

        $data = $request->all();
        $data['price'] = $product->price;
        $data['total_price'] = $product->price * $request->quantity;
        $data['user_id']    = Auth::user()->id;
        $cart = Cart::create($data);
        return $this->sendResponse($cart, __('cart.success_add'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCartRequest  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCartRequest $request, Cart $cart)
    {
        $product = Product::find($cart->product_id);
        $data = $request->all();
        $data['price'] = $product->price;
        $data['total_price'] = $product->price * $request->quantity;
        $cart->update($data);
        return $this->sendResponse($cart, __('cart.success_update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        $cart->delete();
        return $this->sendResponse($cart, __('cart.success_delete'));
    }
}
