<!doctype html>
<html lang="en">
    @include('components.layout.head')
    <body class="">
        @php

        @endphp
        @include('components.layout.header')
        <div class="page-content">
            {{ $slot }}
        </div>
        @include('components.layout.footer')
    </body>
</html>
