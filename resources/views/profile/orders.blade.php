<style>
    /*table.table {*/
    /*    width: 100%;*/
    /*}*/
    table.table td, table.table th {
        text-align: left;
    }
    table.table tbody:nth-child(odd) {
        background-color: #efefef;
        /*padding-bottom: 1rem;*/
    }
    ul {
        list-style: inherit;
        padding-left: 2rem;
    }
</style>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="">
                    <section class="space-y-6">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Your Orders') }}
                            </h2>
                        </header>
                        <table class="table w-full text-left table-auto min-w-max">
                            <thead>
                            <tr class="text-gray-900">
                                <th class="">Created</th>
                                <th class="">Amount</th>
                                <th class="">Status</th>
                                <th class="">Actions</th>
                            </tr>
                            </thead>
                            @foreach(\Illuminate\Support\Facades\Auth::user()->orders()->get() as $order)
                                <tbody>
                                    <tr>
                                        <td>{{ $order->created_at }}</td>
                                        <td>${{ $order->price }}</td>
                                        <td>{{ $order->orderStatus()->get()->first()->label }}</td>
                                        <td></td>
                                    </tr>
                                    <tr class="pb-4">
                                        <td colspan="4">
                                            <ul>
                                                @foreach ($order->products()->get() as $product)
                                                    <li><a href="{{ $product->getUri() }}">{{ $product->name }}</a></li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
