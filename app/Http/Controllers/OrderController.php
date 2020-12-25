<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function showOrderOfCustomer($id)
    {
        $customer = Customer::findOrFail($id);
        $orders = Order::where('customer_id', $id)->get();
        return response()->json(['customer' => $customer, 'orders' => $orders]);
    }

    public function saveOrderOfCustomer(Request $request, $id)
    {
        $order = new Order();
        $order->name = $request->name;
        $order->description = $request->description;
        $order->total_price = $request->total_price;
        $order->customer_id = $id;
        $order->save();
        return response()->json(['order' => $order]);
    }
}
