<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\CheckoutRequest;
use App\Http\Resources\Cart\CartCollection;
use App\Http\Resources\Transaction\TransactionResource;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ShippingCost;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function add(Product $product)
    {
        $cart = Cart::where([['user_id', auth()->user()->id], ['product_id', $product->id]])->first();

        if ($cart) {
            $cart->qty = ++$cart->qty;
            $cart->save();
        } else {
            auth()->user()->cart()->create([
                'product_id' => $product->id,
                'qty' => 1,
            ]);
        }

        return $this->index();
    }

    public function index()
    {
        return new CartCollection(auth()->user()->cart);
    }

    public function checkout(CheckoutRequest $request)
    {
        if (auth()->user()->cart->count() == 0) {
            throw ValidationException::withMessages(['Your cart is empty']);
        }

        $data = $request->validated();
        $user = auth()->user();
        $cart = $user->cart;
        $total = 0;
        $shippingCost = ShippingCost::find($data['shipping_cost_id']);

        foreach ($cart as $item) {
            $productPrice = $item->product->price;
            $price = $productPrice * $item->qty;
            $total += $price;
        }

        $transaction = $user->transactions()->create([
            'shipping_cost_id' => $shippingCost->id,
            'total_pay' => $total + $shippingCost->cost,
            'address' => $data['address'],
        ]);

        foreach ($cart as $item) {
            $transaction->details()->create([
                'product_id' => $item->product->id,
                'qty' => $item->qty,
                'total_price' => $item->product->price * $item->qty,
            ]);

            $item->delete();
        }

        return new TransactionResource($transaction);
    }
}
