<?php

namespace App\Jobs;

use App\Mail\OrderMail;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class OrderMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Request
     */
    private $request;
    private $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Request $request, $id)
    {
        $this->request = $request;
        $this->id = $id;
    }


    public function handle($request, $id)
    {
        $order = new Order();
        $order->name = $request->name;
        $order->description = $request->description;
        $order->total_price = $request->total_price;
        $order->customer_id = $id;

        if(!$order->save()){
            return response()->json('Order can not save', 400);
        }

        Http::post('https://webhook.site/2a652ac3-da00-4c6a-941e-20ab9d0aea94', [
            'order' => $order
        ]);

        $customer = Customer::findOrFail($order->customer_id);
        Mail::to($customer->email)
            ->send(new OrderMail());

        return response()->json(['order' => $order]);
    }
}
