<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use App\Models\DeliveryService;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{   
    public function productsview()
    {
        // Assuming you want to retrieve all products
        $products = Product::all();
        $customer = session('customer');
        // $carts = session('carts');
        // $cartproducts = session('cartproducts');
        $carts = Cart::where('customerID', $customer->customerID)->get();
        session(['carts' => $carts]);

        // Retrieve product information for each cart item
        $cartproducts = [];
        foreach ($carts as $cart) {
            $product = Product::find($cart->productID);
            if ($product) {
                $cartproducts[] = $product;
            }
        }
        session(['cartproducts' => $cartproducts]);

        return view('productsview', compact('products', 'customer', 'carts', 'cartproducts'));
    }

    public function testproduct()
    {
        // Assuming you want to retrieve all products
        $products = Product::all();

        return view('testproduct', compact('products'));
    }

    public function showImage($productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            abort(404); // Product not found
        }

        $imageData = $product->picture;
        $base64Image = base64_encode($imageData);

        $image = Product::make($product->picture);
        dd($image->width(), $image->height());

        return view('testproduct', compact('base64Image'));
    }

    public function checkout1()
    {
        $customer = session('customer');
        $carts = session('carts');
        $cartproducts = session('cartproducts');

        $deliveryservices = DeliveryService::all();
        $paymentmethods = PaymentMethod::all();
        return view('checkout1', compact('cartproducts', 'carts', 'customer', 'deliveryservices', 'paymentmethods'));
    }

    
    public function productdetail($productId) {

        $product = Product::find($productId);

        $customer = session('customer');
        // $carts = session('carts');
        // $cartproducts = session('cartproducts');
        $carts = Cart::where('customerID', $customer->customerID)->get();
        session(['carts' => $carts]);

        // Retrieve product information for each cart item
        $cartproducts = [];
        foreach ($carts as $cart) {
            $cartproduct = Product::find($cart->productID);
            if ($cartproduct) {
                $cartproducts[] = $cartproduct;
            }
        }
        session(['cartproducts' => $cartproducts]);
        
        info("this is the id of product /");
        info($productId);
        info($product->all());

        return view('productDetail', compact('product','customer', 'carts', 'cartproducts'));
    }

    // public function productdetailview()
    // {
    //     return view('productdetail');
    // }
}
