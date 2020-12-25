<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function showCustomers()
    {
        $customers = Customer::all();
        return response()->json(['customers' => $customers]);
    }

    public function saveCustomer(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'email' => 'required|string',
            'address' => 'required|string'
        ]);

        $customer = new Customer();
        $customer->title = $request->title;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->save();
        return response()->json(['customer' => $customer]);
    }
}
