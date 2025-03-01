<x-layout>
    <div class="product-list">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <h3 class="mb-3">Categories</h3>
                    <ul class="mb-4">
                        <li>Category 1</li>
                        <li>Category 2</li>
                        <li>Category 3</li>
                    </ul>
                    <h3 class="mb-3">Tags</h3>
                    <ul class="mb-4">
                        <li>Tag 1</li>
                        <li>Tag 2</li>
                        <li>Tag 3</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-md-4 p-3 h-100">
                            <a class="product card d-block h-100" href="{{ $product->getUri() }}">
                                <h4 class="title mb-2 text-center">{{ $product->name }}</h4>
                                <div class="p-2 mb-2">
                                    <img class="mw-100" src="{{ $product->image }}">
                                </div>
                                <div class="row justify-content-between">
                                    <div>
{{--                                        <span class="badge">New!</span>--}}
                                    </div>
                                    <div>${{ $product->price }}</div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layout>
