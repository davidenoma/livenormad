<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="footer-widget footer-col-1 mb-40">
        <div class="footer-logo mb-30">
            @if($logo = (Theme::get('footerLogo', $config['logo']) ?: theme_option('logo')))
                <a href="{{ route('public.index') }}">
                    {{ RvMedia::image($logo, theme_option('site_title')) }}
                </a>
            @endif
        </div>
        @if ($description = $config['description'])
            <div class="footer-content">
                <div>{!! BaseHelper::clean($description) !!}</div>
            </div>
        @endif
    </div>
</div>
