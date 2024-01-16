@extends(EcommerceHelper::viewPath('customers.master'))

@section('content')
    <h2 class="customer-page-title">{{ __('Account information') }}</h2>

    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <x-core::icon name="ti ti-user" /> {{ __('Name') }}: {{ $customer->name }}
            </div>

            @if ($customer->dob)
                <div class="mb-3">
                    <x-core::icon name="ti ti-calendar" /> {{ __('Date of birth') }}: {{ $customer->dob }}
                </div>
            @endif

            <div class="mb-3">
                <x-core::icon name="ti ti-mail" /> {{ __('Email') }}: {{ $customer->email }}
            </div>

            @if ($customer->phone)
                <div class="mb-3">
                    <x-core::icon name="ti ti-phone" /> {{ __('Phone') }}: {{ $customer->phone }}
                </div>
            @endif
        </div>
    </div>
@endsection
