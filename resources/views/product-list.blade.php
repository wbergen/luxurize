<x-main-layout>
    <div class="product-list">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <h3 class="mb-3">Categories</h3>
                    <ul class="mb-4">
                        @foreach($categories as $category)
                            <li>
                                <a href="{{ $category->getUri() }}">{{ $category->label }}</a>
                            </li>
                        @endforeach
                    </ul>
                    <h3 class="mb-3">Tags</h3>
                    <ul class="mb-4">
                        @foreach($tags as $tag)
                            <li>
                                <a href="{{ $tag->getUri() }}">{{ $tag->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <div class="breadcrumbs card mx-3">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="/"><i class="fas fa-home"></i></a>
                        </div>
                        <div class="col-auto px-1"><span><i class="fas fa-caret-right"></i></span></div>
                        <div class="col-auto">
                            <a href="/">All Products</a>
                        </div>
                        @if(!empty($breadcrumbs))
                            @foreach($breadcrumbs as $bc)
                                <div class="col-auto px-1"><span><i class="fas fa-caret-right"></i></span></div>
                                <div class="col-auto">
                                    <a href="{{ $bc->link }}">{{ $bc->label }}</a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-md-4 p-3 h-100">
                            <a class="product card d-block h-100" href="{{ $product->getUri() }}">
                                <h4 class="title mb-2 text-center">{{ $product->name }}</h4>
                                <div class="p-2 mb-2">
                                    <img class="mw-100" src="{{ $product->imageUri() }}">
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
</x-main-layout>
