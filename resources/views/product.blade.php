<x-main-layout>
    <div class="product">
        <div class="card row mx-3 mb-2">
            <a href="/"><i class="fas pr-1 fa-caret-left"></i> Back to Products</a>
        </div>
        <div class="row mb-3">
            <div class="col-md-7">
                <div class="card mx-auto">
                    <img class="mw-100" src="{{ $product->imageUri() }}" alt="{{ $product->name }}">
                </div>
            </div>
            <div class="col-md-5 flex-grow">
                <div class="card">
                    <h1 class="title mb-3">{{ $product->name }}</h1>
                    <div class="description mb-3">
                        {{ $product->description }}
                    </div>
                    <div id="add-to-product-button">
                        <button class="w-100" @click="addProductToCart">Add To Cart!</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mx-3">
            <h2>Reviews</h2>
            <div>
                No Reviews Yet!
            </div>
        </div>
    </div>
</x-main-layout>

<script>
    document.addEventListener('app-loaded', () => {
        const addToProductButton = createApp({
            data() {
                return {
                    product: @json($product),
                }
            },
            methods: {
                addProductToCart() {
                    axios.post(`/ajax/products/add-to-cart`, {id: this.product.id, quantity: 1})
                        .then(resp => {
                            window.location.href = window.location.href;
                        });
                }
            },
        }).mount("#add-to-product-button");
    });
</script>
