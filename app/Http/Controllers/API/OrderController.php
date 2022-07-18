<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\StoreOrderRequest;
use App\Http\Requests\API\UpdateOrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseController
{
    public function __construct()
    {
        $this->authorizeResource(Order::class, 'order');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::user()->id)->get();
        return $this->sendResponse($orders, __('order.success_show'));
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
    public function store(StoreOrderRequest $request)
    {
        DB::beginTransaction();

        $user_id = Auth::user()->id;

        $product_prices = 0;
        $vat_value = 0;

        // get cart items 
        $cart_ids = Cart::where('user_id', $user_id)->where('status', 'pending')->pluck('id');
        if (count($cart_ids) == 0) {
            return $this->sendResponse([], __('order.cart_is_empty'));
        }

        $shipping_cost = Self::calculate_shiiping_from_cart_ids($cart_ids);

        foreach ($cart_ids as $cart_id) {
            $data = Self::calculate_product_price_and_vat_from_cart_id($cart_id);
            $product_prices += $data['price_without_vat'];
            $vat_value += $data['vat_value'];
        }


        try {

            //make Order
            $order = new Order();
            $order->user_id = $user_id;
            $order->cart_ids = $cart_ids;
            $order->product_prices = $product_prices;
            $order->vat =  $vat_value;
            $order->shipping = $shipping_cost;
            $order->total = $product_prices + $vat_value + $shipping_cost;
            $order->save();

            // change the cart_ids status 
            Cart::whereIn('id', $cart_ids)->update(['status' => 'processed']);

            DB::commit();
            $message = __('order.success_add');
        } catch (\Throwable $e) {
            DB::rollback();
            $message = __('order_check_Cart_items');
        }

        return $this->sendResponse($order, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return $this->sendResponse($order, __('order.success_show'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return $this->sendResponse($order, __('order.success_delete'));
    }

    public static function calculate_product_price_and_vat_from_cart_id($cart_id)
    {
        $product = Cart::find($cart_id)->product;
        $data = array();
        $vat_value = 0;

        $product_price = $product->price;
        $include_vat = $product->include_vat;

        $store = $product->store;
        $vat_percentage = $store->vat_percentage;

        if ($include_vat) {
            $price_without_vat = $product_price / (($vat_percentage / 100) + 1);
            $vat_value =  $product_price - $price_without_vat;
        } else {
            $price_without_vat = $product_price;
            $vat_value = ($vat_percentage / 100) * $product_price;
        }
        $data['vat_value'] = round($vat_value, 3);
        $data['price_without_vat'] = round($price_without_vat, 3);
        return $data;
    }

    public static function calculate_shiiping_from_cart_ids($cart_ids)
    {
        $carts = Cart::whereIn('id', $cart_ids)->get();
        $store_ids = array();
        foreach ($carts as $cart) {
            $store_id = $cart->product->store->id;
            $store_ids[] = $store_id;
        }
        $store_ids = array_unique($store_ids, SORT_REGULAR);
        $shipping_cost = 0;
        foreach ($store_ids as $store_id) {
            $store = Store::find($store_id);
            $shipping_cost += $store->shipping_cost;
        }
        return  round($shipping_cost, 3);
    }
}
