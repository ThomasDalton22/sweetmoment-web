<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Http\Request;


class OrderController extends Controller
{
    public function riwayat(){

        
        return view('vendor.riwayat.index');
    }

    public function riwayat_user()
    {

        
        return view('user.riwayat.index');
    }


    public function add_riwayat(){

        return view('vendor.riwayat.add');
    }

    public function store_riwayat(Request $request)
    {
        $request->request->add([
            'total_price' => $request->qty * 5, 
            'status' => 'Unpaid'
        ]);
        $order = Order::create($request->all());


        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => $order->id,
                'gross_amount' => $order->total_price,
            ),
            'customer_details' => array(
                'name' => $request->name,
                'phone' => $request->phone,
            ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);


        return view('vendor.riwayat.store', compact('snapToken', 'order'));
    }
}
