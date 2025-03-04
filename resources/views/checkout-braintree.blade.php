<x-layout>
    <div id="checkout" class="checkout">
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
            <div class="card mb-4">
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

            <div class="card">
                <h4 class="">Card Information:</h4>
                <div id="dropin-wrapper">
                    <div id="checkout-message"></div>
                    <div id="dropin-container"></div>
                    <button :disabled="processing" id="pp-submit-button" class="w-100 btn btn-primary">
                        <span v-if="processing"><i class="fas fa-circle-notch fa-spin fa-2x"></i></span>
                        <span v-else>Complete Checkout!</span>
                    </button>
                </div>
            </div>
        </div>
        <div v-else>
            <div class="card">
                <h3 class="text-center">There's nothing in your cart to checkout!</h3>
            </div>
        </div>
    </div>
</x-layout>
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
                document.addEventListener('shipping-entered', evt => {
                    this.updateShippingFromPlaces(evt.detail.place);
                });
                const that = this;
                const ppButton = document.querySelector('#pp-submit-button');
                braintree.dropin.create({
                    // Insert your tokenization key here
                    authorization: '{{ $nonce }}',
                    container: '#dropin-container'
                }, function (createErr, instance) {
                    ppButton.addEventListener('click', function () {
                        if (that.valid()) {
                            instance.requestPaymentMethod(function (requestPaymentMethodErr, payload) {
                                // When the user clicks on the 'Submit payment' button this code will send the
                                // encrypted payment information in a variable called a payment method nonce
                                if (payload) {
                                    that.processing = true;
                                    axios.post("/payments/bt/process", {
                                        'paymentMethodNonce': payload.nonce,
                                        cartId: that.cart.id,
                                        shipping: that.shipping,
                                    })
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

                                            if (resp.data.success) {
                                                // window.location.href = '/thankyou';
                                            } else {
                                                console.error("something happened on the server");
                                            }
                                        })
                                }
                            });
                        }
                    });
                });

            },
            methods: {
                valid() {
                    let valid = true;
                    this.v = {};
                    for (let [key, val] of Object.entries(this.shipping)) {
                        switch (key) {
                            case "country":
                            case "addr2":
                                break;
                            default:
                                if (!val) {
                                    valid = this.v[key] = false;
                                }
                        }
                    }
                    console.log(this.v);
                    return valid;
                },
                getPlaceComponentByName(place, compName, longName = true) {
                    console.log(place.address_components);
                    const c = place.address_components.find(c => c.types.includes(compName));
                    if (longName) {
                        return c?.long_name ?? "";
                    }
                    return c?.short_name ?? "";
                },
                updateShippingFromPlaces(place) {
                    this.shipping.addr = `${this.getPlaceComponentByName(place, "street_number")} ${this.getPlaceComponentByName(place, "route")}`;
                    this.shipping.addr2 = `${this.getPlaceComponentByName(place, "subpremise")}`;
                    this.shipping.city = `${this.getPlaceComponentByName(place, "locality")}`;
                    this.shipping.state = `${this.getPlaceComponentByName(place, "administrative_area_level_1", false)}`;
                    this.shipping.zip = `${this.getPlaceComponentByName(place, "postal_code")}`;
                    this.shipping.country = `${this.getPlaceComponentByName(place, "country")}`;

                    console.log(this.shipping);
                    // place.address_components.forEach(c => {
                    //
                    // });
                },
            },
        }).mount("#checkout");

    });

</script>
<script async src="https://maps.googleapis.com/maps/api/js?key={{ config('payments.places.api_key') }}&loading=async&libraries=places&callback=initMap"></script>
<script src="https://js.braintreegateway.com/web/dropin/1.44.1/js/dropin.min.js"></script>
