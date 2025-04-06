<?php

namespace App\Http\Controllers;

use App\Models\Obligation;
use App\Models\Order;
use App\Models\Payments\PaymentProviders\Paypal\PaypalPaymentRecord;
use App\Models\Payments\PaymentRecord;
use App\Models\Payments\PaymentRecordType;
use App\Models\Products\Obligables\Meeting;
use App\Models\Products\Obligables\Shippable;
use App\Models\Products\Obligables\Subscription;
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
        $price = $cart->products()->sum('price'); // TODO Improve to handle quantity, optional tax, etc

        $orderBody = [
            "body" => OrderRequestBuilder::init("CAPTURE", [
                PurchaseUnitRequestBuilder::init(
                    AmountWithBreakdownBuilder::init("USD", $price)->build()
                )->build(),
            ])->build(),
        ];

        $apiResponse = $this->client()->getOrdersController()->ordersCreate($orderBody);
        $jsonResponse = json_decode($apiResponse->getBody(), true);

        $paypalPaymentRecord = new PaypalPaymentRecord([
            'provider_id' => $jsonResponse['id'],
            'provider_status' => $jsonResponse['status'],
        ]);
        $paypalPaymentRecord->save();
        $paymentRecord = new PaymentRecord();
        $paypalPaymentRecord->paymentRecord()->save($paymentRecord);

        $order = new Order([
            'user_id' => Auth::user()->id,
            'price' => $price
        ]);
        $order->save();
        $order->paymentRecord()->associate($paymentRecord);
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

        $paymentRecord = PaymentRecord::findByProvider(PaypalPaymentRecord::class, $validated['orderId']);
        $providerPaymentRecord = $paymentRecord->providerRecord();
        $order = $paymentRecord->order()->first();



//        $providerPaymentRecord = PaypalPaymentRecord::where('provider_id', $validated['orderId'])->first();
//        $providerOrder = PaymentRecord::where('provider_id', $validated['orderId'])->first();
//        $order = $providerOrder->order()->first();
//        $providerPaymentRecord = $providerOrder->paymentRecord();

        $captureBody = [
            "id" => $validated['orderId'],
        ];
        $apiResponse = $this->client()->getOrdersController()->ordersCapture($captureBody);
        $jsonResponse = json_decode($apiResponse->getBody(), true);

        $providerPaymentRecord->provider_status = $jsonResponse['status'] ?? null;
        $providerPaymentRecord->provider_cut = $jsonResponse['purchase_units'][0]['payments']['captures'][0]['seller_receivable_breakdown']['paypal_fee']['value'] ?? null;
        $providerPaymentRecord->provider_gross = $jsonResponse['purchase_units'][0]['payments']['captures'][0]['seller_receivable_breakdown']['gross_amount']['value'] ?? null;
        $providerPaymentRecord->provider_net = $jsonResponse['purchase_units'][0]['payments']['captures'][0]['seller_receivable_breakdown']['net_amount']['value'] ?? null;
        $providerPaymentRecord->provider_payment_id = $jsonResponse['purchase_units'][0]['payments']['captures'][0]['id'] ?? null;
        $providerPaymentRecord->payer_id = $jsonResponse['payer']['payer_id'] ?? null;
        $providerPaymentRecord->payer_email = $jsonResponse['payer']['email_address'] ?? null;
        $providerPaymentRecord->payer_name = $jsonResponse['payer']['name']['given_name'] ?? null;
        $providerPaymentRecord->payer_last_name = $jsonResponse['payer']['name']['surname'] ?? null;
        $providerPaymentRecord->save();

        if ($apiResponse->getStatusCode() == 201) {

            // TODO the big change, need to create an obligation, and create each of the obligation_<type> pivot records
            $obligation = new Obligation([
                'obligation_status_id' => 2, // UNFULLFILLED
                'user_id' => Auth::user()->id,
                'order_id' => $order->id,
            ]);
            $obligation->save();

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

            foreach ($order->products()->get() as $product) {
                $obligable = $product->makeAndPersistObligable($obligation, ['shipping' => $shipping ?? null]);
            }

//            $obligation->order_status_id = 2;
//            $order->shipping()->associate($shipping);
//            $order->save();

            if (app('cart')->products()->exists()) {
                app('cart')->delete();
            }

            $orderId = $order->id;
            $order->obligation()->associate($obligation);
            $order->save();
        }

        return [
            'orderId' => $orderId ?? null,
            "data" => $jsonResponse,
            "httpStatusCode" => $apiResponse->getStatusCode(),
        ];
    }
}
