<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\ProviderOrder;
use App\Models\Shipping;
use App\Models\ShoppingCart;
use Braintree\Gateway;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use PaypalServerSdkLib\Authentication\ClientCredentialsAuthCredentialsBuilder;
use PaypalServerSdkLib\Environment;
use PaypalServerSdkLib\Models\Builders\AmountWithBreakdownBuilder;
use PaypalServerSdkLib\Models\Builders\OrderRequestBuilder;
use PaypalServerSdkLib\Models\Builders\PurchaseUnitRequestBuilder;
use PaypalServerSdkLib\PaypalServerSdkClient;
use PaypalServerSdkLib\PaypalServerSdkClientBuilder;


class PayPalController extends Controller
{

    private ?PaypalServerSdkClient $_client = null;

    private function client(): PaypalServerSdkClient
    {
        if (empty($this->_client)) {
            $this->_client = PaypalServerSdkClientBuilder::init()
                ->clientCredentialsAuthCredentials(
                    ClientCredentialsAuthCredentialsBuilder::init(
                        config('payments.paypal.api_key'),
                        config("payments.paypal.api_secret")
                    )
                )
                ->environment(Environment::SANDBOX)
                ->build();
        }
        return $this->_client;
    }

    public function create(Request $request): array
    {

        $validated = $request->validate([
            'cartId' => 'required|numeric',
            'shipping' => 'array'
        ]);
        $cart = ShoppingCart::find($validated['cartId']);
        $price = $cart->products()->sum('price');

        $orderBody = [
            "body" => OrderRequestBuilder::init("CAPTURE", [
                PurchaseUnitRequestBuilder::init(
                    AmountWithBreakdownBuilder::init("USD", $price)->build()
                )->build(),
            ])->build(),
        ];

        $apiResponse = $this->client()->getOrdersController()->ordersCreate($orderBody);
        $jsonResponse = json_decode($apiResponse->getBody(), true);

        $providerOrder = new ProviderOrder(['provider_id' => $jsonResponse['id'], 'provider_status' => $jsonResponse['status']]);
        $providerOrder->save();

        $order = new Order([
            'user_id' => Auth::user()->id,
            'order_status_id' => 1,
            'price' => $cart->products()->sum('price')
        ]);
        $order->save();
        $order->providerOrder()->associate($providerOrder);
        $order->products()->saveMany($cart->products()->get());
        $order->save();

        return [
            "data" => $jsonResponse,
            "httpStatusCode" => $apiResponse->getStatusCode(),
        ];
    }

    public function capture(Request $request): array
    {

        $validated = $request->validate([
            'orderId' => 'required'
        ]);

        $providerOrder = ProviderOrder::where('provider_id', $validated['orderId'])->first();
        $order = $providerOrder->order()->first();

        $captureBody = [
            "id" => $validated['orderId'],
        ];
        $apiResponse = $this->client()->getOrdersController()->ordersCapture($captureBody);
        $jsonResponse = json_decode($apiResponse->getBody(), true);

        $providerOrder->provider_status = $jsonResponse['status'] ?? null;
        $providerOrder->provider_cut = $jsonResponse['purchase_units'][0]['payments']['captures'][0]['seller_receivable_breakdown']['paypal_fee']['value'] ?? null;
        $providerOrder->provider_gross = $jsonResponse['purchase_units'][0]['payments']['captures'][0]['seller_receivable_breakdown']['gross_amount']['value'] ?? null;
        $providerOrder->provider_net = $jsonResponse['purchase_units'][0]['payments']['captures'][0]['seller_receivable_breakdown']['net_amount']['value'] ?? null;
        $providerOrder->provider_payment_id = $jsonResponse['purchase_units'][0]['payments']['captures'][0]['id'] ?? null;
        $providerOrder->payer_id = $jsonResponse['payer']['payer_id'] ?? null;
        $providerOrder->payer_email = $jsonResponse['payer']['email_address'] ?? null;
        $providerOrder->payer_name = $jsonResponse['payer']['name']['given_name'] ?? null;
        $providerOrder->payer_last_name = $jsonResponse['payer']['name']['surname'] ?? null;
        $providerOrder->save();

        if ($apiResponse->getStatusCode() == 201) {
            $shipping = new Shipping([
                'address' => $jsonResponse['purchase_units'][0]['shipping']['address']['address_line_1'],
                'address_two' => $jsonResponse['purchase_units'][0]['shipping']['address']['address_line_2'] ?? null,
                'city' => $jsonResponse['purchase_units'][0]['shipping']['address']['admin_area_2'],
                'state' => $jsonResponse['purchase_units'][0]['shipping']['address']['admin_area_1'],
                'zip' => $jsonResponse['purchase_units'][0]['shipping']['address']['postal_code'],
                'country' => $jsonResponse['purchase_units'][0]['shipping']['address']['country_code'],
//                'source' => 'paypal', // TODO
                'user_id' => Auth::user()->id
            ]);
            $shipping->save();

            $order->order_status_id = 2;
            $order->shipping()->associate($shipping);
            $order->save();

            if (app('cart')->products()->exists()) {
                app('cart')->delete();
            }

            $orderId = $order->id;
        }

        return [
            'orderId' => $orderId ?? null,
            "data" => $jsonResponse,
            "httpStatusCode" => $apiResponse->getStatusCode(),
        ];
    }
}
