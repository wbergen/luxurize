<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Shipping;
use App\Models\ShoppingCart;
use Braintree\Gateway;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class BrainTreeController extends Controller
{
    public function process(Request $request): array
    {
        $gateway = new Gateway([
            'environment' => config('payments.braintree.env'),
            'merchantId' => config('payments.braintree.id'),
            'publicKey' => config('payments.braintree.pub_key'),
            'privateKey' => config('payments.braintree.private_key')
        ]);

        $validated = $request->validate([
            'paymentMethodNonce' => 'required',
            'cartId' => 'required|numeric',
            'shipping' => 'required|array'
        ]);

        $cart = ShoppingCart::find($validated['cartId']);

        $shipping = new Shipping([
            'address' => $validated['shipping']['addr'],
            'address_two' => $validated['shipping']['addr2'],
            'city' => $validated['shipping']['city'],
            'state' => $validated['shipping']['state'],
            'zip' => $validated['shipping']['zip'],
            'country' => $validated['shipping']['country'],
            'user_id' => Auth::user()->id
        ]);
        $shipping->save();

        // Then, create a transaction:
        $result = $gateway->transaction()->sale([
            'amount' => '10.00',
            'paymentMethodNonce' => $validated['paymentMethodNonce'],
            'deviceData' => [
//                    'ua' => $request->userAgent()
            ],
            'options' => [ 'submitForSettlement' => True ]
        ]);

        if ($result->success) {
            $order = new Order([
                'user_id' => Auth::user()->id,
                'order_status_id' => 1,
                'price' => $cart->products()->sum('price')
            ]);
            $order->save();
            $order->shipping()->associate($shipping);
            $order->products()->saveMany($cart->products()->get());
            $order->save();
            $cart->delete();
            return ['success' => $result->success, 'orderId' => $order->id, 'result' => $result];
        }
        return ['success' => 'false'];
    }
}
