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
                            <div class="shopping-cart row">
                                <div class="mr-2">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div>
                                    Cart is Empty!
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
