<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Customer;

use App\Models\Scent_Category;

use App\Models\Cart;

use App\Models\Product;

use App\Models\QuizQuestion;

use App\Models\QuizChoices;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    function home()
    {
        $customer = session('customer');
        $scentcategory = Scent_Category::all();
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

        return view('home', compact('customer', 'scentcategory', 'carts', 'cartproducts'));
    }

    public function aboutus()
    {
        $customer = session('customer');
        $carts = session('carts');
        return view('aboutus', compact('carts', 'customer'));
    }


    // //updateCartQty - ORIGINAL
    //     public function updateQty(Request $request, $customerID)
    //     {

    //         info($request->all());
    //         info($customerID);
    //         info($request->selectedProductID);

    //         Cart::where('customerID', $customerID)->where('productID', $request->selectedProductID)->update([
    //             'qty' => $request->productqty,
    //         ]);

    //         return back();
    //     }

    // //UPDATE-CartQty-WORKING
    // public function updateQty(Request $request, $customerID)
    // {
    //     Cart::where('customerID', $customerID)->where('productID', $request->selectedProductID)->update([
    //         'qty' => $request->productqty,
    //     ]);

    //     return response()->json(['success' => true]);
    // }

    //UPDATECART
    public function updateQty(Request $request, $customerID)
    {
        $productID = $request->selectedProductID;
        $newQty = $request->productqty;

        if ($newQty <= 0) {
            // If the new quantity is zero or less, delete the record from the database
            Cart::where('customerID', $customerID)->where('productID', $productID)->delete();
        } else {
            // Update the quantity in the database
            Cart::where('customerID', $customerID)->where('productID', $productID)->update([
                'qty' => $newQty,
            ]);
        }

        return response()->json(['success' => true]);
    }
}
