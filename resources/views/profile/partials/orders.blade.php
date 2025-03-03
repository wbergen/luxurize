<style>
    table.table {
        width: 100%;
    }
    table.table td, table.table th {
        text-align: center;
    }
</style>
<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Your Orders') }}
        </h2>
    </header>
        <table class="table">
            <thead>
               <tr>
                   <th>Created</th>
                   <th>Amount</th>
                   <th>Status</th>
               </tr>
            </thead>
            <tbody>
                @foreach(\Illuminate\Support\Facades\Auth::user()->orders()->get() as $order)
                    <tr>
                        <td>{{ $order->created_at }}</td>
                        <td>${{ $order->price }}</td>
                        <td>{{ $order->orderStatus()->get()->first()->label }}</td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <ul>
                                @foreach ($order->products()->get() as $product)
                                    <li><a href="{{ $product->getUri() }}">{{ $product->name }}</a></li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</section>
