<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=5, user-scalable=1" name="viewport"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {!! BaseHelper::googleFonts(sprintf('https://fonts.googleapis.com/css2?family=%s:wght@400;500;600', urlencode($primaryFont = theme_option('primary_font', 'Jost')))) !!}

        <style>
            :root {
                --primary-color: {{ $primaryColor = theme_option('primary_color', '#ff775e') }};
                --primary-color-rgb: {{ implode(',', BaseHelper::hexToRgb($primaryColor)) }};
                --primary-font: '{{ $primaryFont }}', sans-serif;
            }
        </style>

        {!! Theme::header() !!}
    </head>
    <body @if (BaseHelper::siteLanguageDirection() === 'rtl') dir="rtl" @endif>
        {!! apply_filters(THEME_FRONT_BODY, null) !!}

        @if(theme_option('preloader_enabled', 'yes') === 'yes')
            {!! Theme::partial('preloader') !!}
        @endif

        {!! Theme::partial('scroll-top') !!}

        @yield('content')

        @if(is_plugin_active('ecommerce') && theme_option('bottom_mobile_menu_enabled', 'yes') === 'yes')
            @include(Theme::getThemeNamespace('partials.navigation-bar'))
        @endif

        <script>
            'use strict';

            window.trans = {};
            window.siteConfig = {};
            @if (is_plugin_active('ecommerce'))
                window.currencies = @json(get_currencies_json());
                @if(EcommerceHelper::isCartEnabled())
                    siteConfig.ajaxCart = '{{ route('public.ajax.cart') }}';
                    siteConfig.cartUrl = '{{ route('public.cart') }}';
                @endif
            @endif
        </script>

        {!! Theme::footer() !!}

        @if (
            session()->has('success_msg')
            || session()->has('error_msg')
            || (isset($errors) && $errors->count() > 0)
            || isset($error_msg)
        )
            <script type="text/javascript">
                window.onload = function() {
                    @if (session()->has('success_msg'))
                        NinicoApp.showSuccess('{{ session('success_msg') }}');
                    @endif

                    @if (session()->has('error_msg'))
                        NinicoApp.showError('{{ session('error_msg') }}');
                    @endif

                    @if (isset($error_msg))
                        NinicoApp.showError('{{ $error_msg }}');
                    @endif

                    @if (isset($errors))
                        @foreach ($errors->all() as $error)
                            NinicoApp.showError('{!! BaseHelper::clean($error) !!}');
                        @endforeach
                    @endif
                };
            </script>
        @endif
    </body>
</html>
