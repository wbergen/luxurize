<x-layout>
    <div class="checkout">
        <div class="card mb-4">
            <h2>Your Cart:</h2>
            <table class="table striped">
                <thead>
                <tr>
                    <th class="text-left">Product</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Price</th>
                </tr>
                </thead>
                <tbody>
                    @foreach(app('cart')->products()->get() as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td class="text-right">{{ 1 }}</td>
                            <td class="text-right">{{ $p->price }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td><b>Total</b></td>
                        <td colspan="2" class="text-right font-weight-bold">
                            {{ app('cart')->products()->sum('price') }}
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
        <div class="card">
            <div id="dropin-wrapper">
                <div id="checkout-message"></div>
                <div id="dropin-container"></div>
                <button id="pp-submit-button">Submit Payment</button>
            </div>
        </div>
        <div class="card">
            <input id="shipping-input" name="shipping">
        </div>
    </div>
</x-layout>
<script async src="https://maps.googleapis.com/maps/api/js?key={{ config('payments.places.api_key') }}&loading=async&libraries=places&callback=initMap"></script>
<script src="https://js.braintreegateway.com/web/dropin/1.44.1/js/dropin.min.js"></script>
<script>


    const ppButton = document.querySelector('#pp-submit-button');
    braintree.dropin.create({
        // Insert your tokenization key here
        authorization: '{{ $nonce }}',
        container: '#dropin-container'
    }, function (createErr, instance) {
        ppButton.addEventListener('click', function () {
            instance.requestPaymentMethod(function (requestPaymentMethodErr, payload) {
                // When the user clicks on the 'Submit payment' button this code will send the
                // encrypted payment information in a variable called a payment method nonce
                axios.post("/payments/process", {'paymentMethodNonce': payload.nonce, cartId: @json(app('cart')->id)})
                    .then(resp => {
                        // Tear down the Drop-in UI
                        instance.teardown(function (teardownErr) {
                            if (teardownErr) {
                                console.error('Could not tear down Drop-in UI!');
                            } else {
                                console.info('Drop-in UI has been torn down!');
                                // Remove the 'Submit payment' button
                                document.querySelector('#pp-submit-button').remove();
                            }
                        });

                        if (resp.success) {
                            window.location.href = '/thankyou';
                        } else {
                            console.error("something happened on the server");
                        }
                    })
            });
        });
    });

    function initMap() {
        const input = document.getElementById("shipping-input");
        const autocomplete = new google.maps.places.Autocomplete(input);
    }

    document.addEventListener('app-loaded', () => {
        const ppCheckout = createApp({
            data() {
                return {
                    {{--product: @json($product),--}}
                }
            },
            methods: {
                addProductToCart() {
                    axios.post(`/ajax/products/add-to-cart`, {id: this.product.id, quantity: 1})
                        .then(resp => {
                            console.log(resp);
                        });
                }
            },
        }).mount("#add-to-product-button");


    });
</script>
