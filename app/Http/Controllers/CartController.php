<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cart;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    //UPDATE CART-ITEM
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
    public function addToCart(Request $request)
    {
        $customerID = session('customer')->customerID;
        $productID = $request->input('productID');
        $quantity = $request->input('quantity');

        // Check if the product is already in the cart
        $existingCartItem = Cart::where('customerID', $customerID)
            ->where('productID', $productID)
            ->first();

        if ($existingCartItem) {
            // If the product is already in the cart, update the quantity
            $existingCartItem->update(['qty' => $existingCartItem->qty + $quantity]);
        } else {
            // If the product is not in the cart, create a new cart item
            Cart::create([
                'customerID' => $customerID,
                'productID' => $productID,
                'qty' => $quantity,
            ]);
        }

        // Retrieve the updated cart data with base64-encoded image
        $updatedCartData = DB::table('carts')
            ->where('customerID', $customerID)
            ->join('products', 'carts.productID', '=', 'products.productID')
            ->select('carts.*', 'products.productName', 'products.productPrice', DB::raw('TO_BASE64(products.png) as png'))
            ->get();

        return response()->json(['success' => true, 'cartData' => $updatedCartData]);
    }
}
