<x-layout>
    <div id="checkout" class="checkout" v-cloak>
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

        <div v-if="canCheckout">
            <div v-if="false" class="card mb-4">
                <h4 class="mb-4">Shipping Information</h4>
                <h4 class="alert danger mb-4" v-show="hasShippingErrors">Please Enter a Valid Shipping Address below to continue</h4>
                <form class="grid-form">
                    <input id="shipping-input" name="autocomplete" placeholder="Type to autocomplete you address here" class="form-control col-md-12">
                    <input id="shipping-input-addr" :value="shipping.addr" name="addr" placeholder="Address" class="form-control col-md-12">
                    <input id="shipping-input-addr2" :value="shipping.addr2" name="add2" placeholder="Apt, Suite, etc" class="form-control col-md-12">
                    <input id="shipping-input-city" :value="shipping.city" name="city" placeholder="City" class="form-control col-md mx-auto">
                    <input id="shipping-input-state" :value="shipping.state" name="state" placeholder="State" class="form-control col-md mx-auto">
                    <input id="shipping-input-zip" :value="shipping.zip" name="zip" placeholder="Zip Code" class="form-control col-md mx-auto">
                    <input id="shipping-input-country" :value="shipping.country" name="zip" placeholder="Country" class="form-control col-md mx-auto">

                </form>
            </div>
        </div>
        <div v-else>
            <div class="card">
                <h3 class="text-center">There's nothing in your cart to checkout!</h3>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="row">
            <div class="col mx-auto" id="paypal-button-container"></div>
        </div>
    </div>
</x-layout>
<!-- Initialize the JS-SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=Ae-WJPXj3A7GbB9Aq3P5v2bJEcpQzEGNpunLAhdR5j3yp3gYpDbB25Ks1yCub9jWiRCii9yJjaebsdTO&buyer-country=US&currency=USD&components=buttons&enable-funding=venmo,paylater,card" data-sdk-integration-source="developer-studio"></script>
<script>
    function initMap() {
        const input = document.getElementById("shipping-input");
        const autocomplete = new google.maps.places.Autocomplete(input, {
            fields: ['address_components', 'geometry', 'name'],
            types: ['address'],
        });

        autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace();
            if (!place.geometry) {
                // User entered the name of a Place that was not suggested and
                // pressed the Enter key, or the Place Details request failed.
                // window.alert(`No details available for input: '${place.name}'`);
                return;
            }
            document.dispatchEvent(new CustomEvent('shipping-entered', {detail: {place}}));
        });
    }

    const setupPaypalCheckout = () => {
        window.paypal
            .Buttons({
                style: {
                    shape: "rect",
                    layout: "vertical",
                    color: "gold",
                    label: "paypal",
                    disableMaxWidth: true,
                },
                message: {
                    amount: @json(app('cart')->products()->get()->sum('price')),
                } ,

                async createOrder() {
                    try {
                        const cart = @json(app('cart'));
                        const response = await fetch("/payments/pp/create", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            // use the "body" param to optionally pass additional order information
                            // like product ids and quantities
                            body: JSON.stringify({
                                cartId: cart?.id,
                            })
                        });

                        const result = await response.json();
                        const orderData = result.data;

                        if (orderData.id) {
                            return orderData.id;
                        }
                        const errorDetail = orderData?.details?.[0];
                        const errorMessage = errorDetail
                            ? `${errorDetail.issue} ${errorDetail.description} (${orderData.debug_id})`
                            : JSON.stringify(orderData);

                        throw new Error(errorMessage);
                    } catch (error) {
                        console.error(error);
                        // resultMessage(`Could not initiate PayPal Checkout...<br><br>${error}`);
                    }
                } ,

                async onApprove(data, actions) {
                    try {
                        console.log(data, actions);
                        const cart = @json(app('cart'));
                        const response = await fetch(
                            `/payments/pp/capture`,
                            {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    orderId: data.orderID
                                })
                            }
                        );

                        const result = await response.json();
                        const orderData = result.data;
                        // Three cases to handle:
                        //   (1) Recoverable INSTRUMENT_DECLINED -> call actions.restart()
                        //   (2) Other non-recoverable errors -> Show a failure message
                        //   (3) Successful transaction -> Show confirmation or thank you message

                        const errorDetail = orderData?.details?.[0];

                        if (errorDetail?.issue === "INSTRUMENT_DECLINED") {
                            // (1) Recoverable INSTRUMENT_DECLINED -> call actions.restart()
                            // recoverable state, per
                            // https://developer.paypal.com/docs/checkout/standard/customize/handle-funding-failures/
                            return actions.restart();
                        } else if (errorDetail) {
                            // (2) Other non-recoverable errors -> Show a failure message
                            throw new Error(
                                `${errorDetail.description} (${orderData.debug_id})`
                            );
                        } else if (!orderData.purchase_units) {
                            throw new Error(JSON.stringify(orderData));
                        } else {
                            // (3) Successful transaction -> Show confirmation or thank you message
                            // Or go to another URL:  actions.redirect('thank_you.html');
                            const transaction =
                                orderData?.purchase_units?.[0]?.payments
                                    ?.captures?.[0] ||
                                orderData?.purchase_units?.[0]?.payments
                                    ?.authorizations?.[0];
                            //               resultMessage(
                            //                   `Transaction ${transaction.status}: ${transaction.id}<br>
                            // <br>See console for all available details`
                            //               );

                            window.location.href = '/thankyou';

                            // console.log(
                            //     "Capture result",
                            //     orderData,
                            //     JSON.stringify(orderData, null, 2)
                            // );
                            // TODO Done, go to thankyou
                        }
                    } catch (error) {
                        // TODO Better handle errors?
                        console.error(error);
                        // resultMessage(
                        //     `Sorry, your transaction could not be processed...<br><br>${error}`
                        // );
                    }
                } ,
            })
            .render("#paypal-button-container");
    };


    document.addEventListener('app-loaded', () => {
        const ppCheckout = createApp({
            data() {
                return {
                    shipping: {
                        addr: "",
                        addr2: "",
                        city: "",
                        state: "",
                        zip: "",
                        country: "",
                    },
                    v: {},
                    processing: false,
                    cart: @json(app('cart')),
                    products: @json(app('cart')->products()->get())
                }
            },
            computed: {
                canCheckout() {
                    return this.products.length;
                },
                hasShippingErrors() {
                    return Object.values(this.v).filter(v => v === false).length;
                }
            },
            mounted() {
                // document.addEventListener('shipping-entered', evt => {
                //     this.updateShippingFromPlaces(evt.detail.place);
                // });
                if (this.canCheckout) {
                    setupPaypalCheckout();
                }
            },
            methods: {
                // valid() {
                //     let valid = true;
                //     this.v = {};
                //     for (let [key, val] of Object.entries(this.shipping)) {
                //         switch (key) {
                //             case "country":
                //             case "addr2":
                //                 break;
                //             default:
                //                 if (!val) {
                //                     valid = this.v[key] = false;
                //                 }
                //         }
                //     }
                //     console.log(this.v);
                //     return valid;
                // },
                // getPlaceComponentByName(place, compName, longName = true) {
                //     console.log(place.address_components);
                //     const c = place.address_components.find(c => c.types.includes(compName));
                //     if (longName) {
                //         return c?.long_name ?? "";
                //     }
                //     return c?.short_name ?? "";
                // },
                // updateShippingFromPlaces(place) {
                //     this.shipping.addr = `${this.getPlaceComponentByName(place, "street_number")} ${this.getPlaceComponentByName(place, "route")}`;
                //     this.shipping.addr2 = `${this.getPlaceComponentByName(place, "subpremise")}`;
                //     this.shipping.city = `${this.getPlaceComponentByName(place, "locality")}`;
                //     this.shipping.state = `${this.getPlaceComponentByName(place, "administrative_area_level_1", false)}`;
                //     this.shipping.zip = `${this.getPlaceComponentByName(place, "postal_code")}`;
                //     this.shipping.country = `${this.getPlaceComponentByName(place, "country")}`;
                //
                //     console.log(this.shipping);
                //     // place.address_components.forEach(c => {
                //     //
                //     // });
                // },
            },
        }).mount("#checkout");

    });

</script>
<script async src="https://maps.googleapis.com/maps/api/js?key={{ config('payments.places.api_key') }}&loading=async&libraries=places&callback=initMap"></script>
<script src="https://js.braintreegateway.com/web/dropin/1.44.1/js/dropin.min.js"></script>
