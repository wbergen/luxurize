<!doctype html>
<html lang="en">
    @include('layouts.main.head')
    <body class="">
        @php

        @endphp
        @include('layouts.main.header')
        <div class="page-content">
            {{ $slot }}
        </div>
        @include('layouts.main.footer')
    </body>
</html>
