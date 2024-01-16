<div class="container">
    <div class="row align-items-center">
        <div class="col-xl-2 col-lg-3">
            @if($logo = theme_option('logo'))
                <div class="logo">
                    <a href="{{ route('public.index') }}">
                        <img src="{{ RvMedia::getImageUrl($logo) }}" alt="{{ theme_option('site_title') }}">
                    </a>
                </div>
            @endif
        </div>
        @if(is_plugin_active('ecommerce'))
            <div class="col-xl-10 col-lg-9">
                <div class="header-meta-info d-flex align-items-center justify-content-between">
                    {!! Theme::get('headerSearchBar') !!}
                    {!! Theme::partial('header-meta') !!}
                </div>
            </div>
        @endif
    </div>
</div>
