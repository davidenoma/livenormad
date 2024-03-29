@extends(EcommerceHelper::viewPath('customers.master'))

@section('content')
    <h2 class="customer-page-title">{{ __('Orders') }}</h2>

    <div class="customer-list-order">
        <table class="table table-bordered table-striped">
            <thead>
                <tr class="success">
                    <th>{{ __('Order number') }}</th>
                    <th>{{ __('Created at') }}</th>
                    <th>{{ __('Payment method') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>

                        <td>{{ $order->code }}</td>
                        <td>{{ $order->created_at->format('d M Y H:i:s') }}</td>
                        <td>
                            @if(is_plugin_active('payment') && $order->payment->id && $order->payment->payment_channel->label())
                                {{ $order->payment->payment_channel->label() }}
                            @else
                                &mdash;
                            @endif
                        </td>
                        <td>{!! BaseHelper::clean($order->status->toHtml()) !!}</td>

                        <td>
                            <a
                                class="btn btn-primary btn-sm"
                                href="{{ route('customer.orders.view', $order->id) }}"
                            >{{ __('View') }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-center">
            {!! $orders->links() !!}
        </div>

    </div>
@endsection
