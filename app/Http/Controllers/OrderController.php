<?php

namespace App\Http\Controllers;

use App\Jobs\OrderMailJob;
use App\Mail\OrderMail;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
        OrderMailJob::dispatch($request, $id)->onConnection('sqs');
    }
}
