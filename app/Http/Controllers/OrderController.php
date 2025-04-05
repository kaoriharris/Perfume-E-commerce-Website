<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderDelivery;
use App\Models\Payment;
use App\Models\VirtualAccount;
use App\Models\DeliveryService;
use App\Models\PaymentMethod;
use App\Models\Customer;
use App\Models\transactionHistory;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;


use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Exists;

class OrderController extends Controller
{

    public function checkout2(Request $request)
    {
        $customer = session('customer');
        $carts = session('carts');
        $cartproducts = session('cartproducts');

        $request->validate([
            'address' => 'required'
        ]);

        $data['dob'] = $request->name;
        $data['address'] = $request->address;
        Customer::where('customerID', $customer->customerID)->update([
            'address' => $request->address
        ]);
        $data2['customerID'] = $customer->customerID;
        $currentDateTime = date('Y-m-d H:i:s');
        $data2['orderTime'] = $currentDateTime;
        $order = Order::create($data2);
        info("line 44");
        $order1 = Order::where('orderTime', $currentDateTime)->first();
        session(['order' => $order1]);
        info($order1);
        $subtotal = 0;
        foreach ($cartproducts as $cartproduct) {
            $cart = $carts->where('productID', $cartproduct->productID)->first();
            info($order1);
            $data3['orderID'] = $order1->orderID;
            $data3['productID'] = $cartproduct->productID;
            $data3['productQty'] = $cart->qty;
            $a = $cart->qty;
            $b = $cartproduct->productPrice;
            $data3['pricePerItem'] = $b;
            $pricexqty = $a * $b;
            $data3['subTotal'] = $pricexqty;
            $subtotal += $pricexqty;
            OrderDetail::create($data3);
        }
        Order::where('orderID', $order->orderID)->update(['totalPrice' => $subtotal]);
        $order2 = Order::where('orderID', $order1->orderID)->first();
        session(['order2' => $order2]);
        info("ini yg dicari");
        info($order2);
        $deliveryservices = DeliveryService::all();
        $paymentmethods = PaymentMethod::all();
        return view('checkout2', compact('cartproducts', 'carts', 'customer', 'deliveryservices', 'paymentmethods'));
    }

    public function checkout3(Request $request)
    {
        $customer = session('customer');
        $carts = session('carts');
        $cartproducts = session('cartproducts');
        $order = session('order2');
        $choice = $request->input('chosen');
        info($choice);
        info('above choice');
        $deliveryservicesbeforearray = DeliveryService::where('deliveryServiceID', $choice)->first();
        $deliveryservices = $deliveryservicesbeforearray->toArray();
        info($deliveryservices['pricePerKm']);
        info('above ideli');
        $price = $deliveryservices['pricePerKm'];
        $totalprice = $request->total;
        session(['total' => $totalprice]);
        $data['orderID'] = $order->orderID;
        $data['deliveryServiceID'] = $choice;
        $data['distance'] = 40;
        $data['pricePerKm'] = $price;
        $data['deliveryTotalPrice'] = 40 * $price;
        $orderdelivery = OrderDelivery::create($data);
        session(['orderdelivery' => $orderdelivery]);
        $ongkir = $orderdelivery->deliveryTotalPrice;
        $totalamount = $ongkir + $totalprice;
        Order::where('orderID', $order->orderID)->update([
            'totalPrice' => $totalamount
        ]);
        $paymentmethods = PaymentMethod::all();
        return view('checkout3', compact('cartproducts', 'carts', 'customer', 'paymentmethods', 'orderdelivery', 'totalprice', 'totalamount'));
    }

    public function checkout4(Request $request)
    {
        $customer = session('customer');
        $carts = session('carts');
        $cartproducts = session('cartproducts');
        $order = session('order');
        $orderdelivery = session('orderdelivery');

        $total = session('total');
        $choice = $request->input('chosen');
        $choosenmethod = PaymentMethod::where('methodID', $choice)->first();
        $ongkir = $orderdelivery->deliveryTotalPrice;
        $totalamount = $ongkir + $total;

        $frontva = $choosenmethod->bankCode;
        $backva = $customer->customerID;

        $va = $frontva + $backva;



        $data['orderID'] = $order->orderID;
        $data['methodID'] = $choice;
        $data['paymentAmount'] = $totalamount;
        $payment = Payment::create($data);
        session(['payment' => $payment]);


        if (VirtualAccount::where('virtualAccount', $va)->exists()) {
            $virtualaccount = VirtualAccount::find($va);
        } else {
            $virtualaccount = new VirtualAccount;
            $virtualaccount->customerID = $customer->customerID;
            $virtualaccount->methodID = $choice;
            $virtualaccount->virtualAccount = $va;
            $virtualaccount->bill = $totalamount;
            $virtualaccount->save();
        }

        $paymentmethods = PaymentMethod::all();
        return view('checkout4', compact('cartproducts', 'carts', 'customer', 'orderdelivery', 'totalamount', 'virtualaccount'));
    }

    public function checkout5()
    {
        $customer = session('customer');
        $order = session('order');

        Order::where('orderID', $order->orderID)->update([
            'orderStatus' => "delivered"
        ]);
        Payment::where('orderID', $order->orderID)->update([
            'paymentStatus' => "paid"
        ]);
        OrderDelivery::where('orderID', $order->orderID)->update([
            'deliveryStatus' => "delivered"
        ]);
        Cart::where('customerID', $customer->customerID)->delete();

        return view('checkout5');
    }

    public function goBackfrom2()
    {
        $order = session('order');
        Order::where('orderID', $order->orderID)->update([
            'orderStatus' => "cancelled"
        ]);
    }

    public function goBackfrom3()
    {
        $order = session('order');
        OrderDelivery::where('orderID', $order->orderID)->update([
            'deliveryStatus' => "cancelled"
        ]);
    }

    public function goBackfrom4()
    {
        $order = session('order');
        Payment::where('orderID', $order->orderID)->update([
            'paymentStatus' => "cancelled"
        ]);
    }

    public function history()
    {
        $products = Product::all();
        $customer = session('customer');
        $transactions = TransactionHistory::where('customerID', $customer->customerID)->get();

        $transactionDetails = [];
        foreach ($transactions as $transaction) {
            $orderDetails = OrderDetail::where('orderID', $transaction->orderID)->get();

            $productDetails = [];
            foreach ($orderDetails as $orderDetail) {
                $orderedProduct = Product::find($orderDetail->productID);
                $productDetails[] = [
                    'productID' => $orderedProduct->productID,
                    'productName' => $orderedProduct->productName,
                    'pricePerItem' => $orderedProduct->productPrice,
                    'quantity' => $orderDetail->productQty,
                    'subtotal' => $orderDetail->subTotal,
                ];
            }

            info('this is $productDetails');
            Log::info($productDetails);

            info('this is $transaction');
            Log::info($transaction);
            // $deliveryService = DeliveryService::where('deliveryServiceID', $transaction->deliveryServiceID)->get();
            // info('this is $deliveryService');
            // info($deliveryService);
            // // info('this is $deliveryService');
            // // Log::info($deliveryService);
            // $paymentMethod = PaymentMethod::find($transaction->methodID)->get();
            // info('this is $paymentmethod');
            // info($paymentMethod);
            // info('this is $paymentMethod');
            // Log::info($paymentMethod);

            $transactionDetails[] = [
                'orderID' => $transaction->orderID,
                'orderTime' => $transaction->orderTime,
                'totalPrice' => $transaction->totalPrice,
                'distance' => $transaction->distance,
                'pricePerKm' => $transaction->pricePerKm,
                'deliveryTotalPrice' => $transaction->deliveryTotalPrice,
                'deliveryStatus' => $transaction->deliveryStatus,
                'bankName' => $transaction->bankName,
                'paymentAmount' => $transaction->paymentAmount,
                'paymentStatus' => $transaction->paymentStatus,
                'productDetails' => $productDetails,
            ];
        }

        return view('history', compact('transactionDetails', 'products'));
    }
}
