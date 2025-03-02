<header>
    <nav class="position-fixed w-100 bg-light shadow">
        <div class="row align-items-center">
            <a class="navbar-brand" href="/">
                <img height="40" src="{{config('site.logos.png')}}" alt="{{ config('site.name') }}"/>
            </a>
            <h1 class="page-title mx-auto">
                Luxurize It!
            </h1>
            <div class="mr-2">
                @auth
                    <div class="row align-items-center">
                        <div class="mx-2">
                            <div class="shopping-cart dropdown row" href="/checkout">
                                <div class="mr-2">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div>
                                    @if(empty(app('cart')->products()->exists()))
                                        <div>Cart is Empty!</div>
                                    @else
                                        <div>({{ app('cart')->products()->count() }})</div>
                                        <div class="dropdown-content left p-3 text-black">
                                            <h4 class="mb-4">Currently in your cart:</h4>
                                            <div class="row">
                                                @foreach(app('cart')->products()->get() as $index => $product)
                                                    <div class="col-md-9 mb-4">
                                                        {{ $index + 1 }}. <a href="{{ $product->getUri() }}">{{ $product->name }}</a>
                                                    </div>
                                                    <div class="col-md-3 mb-4 text-right">${{ $product->price }}</div>
                                                @endforeach
                                            </div>
                                            <a class="d-block text-right" href="/checkout">Checkout Now!</a>
                                        </div>

                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mx-2">
                            <a href="/dashboard">
                                {{ \Illuminate\Support\Facades\Auth::user()->email }}
                            </a>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <a href="/register" class="mx-2">REGISTER NOW</a>
                        <a href="/login" class="mx-2">LOGIN</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>
</header>
