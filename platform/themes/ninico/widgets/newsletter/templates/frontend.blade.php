@if (is_plugin_active('newsletter'))
    <div class="col-lg-3 col-md-4">
        <div class="footer-widget footer-col-5 mb-40">
            @if($title = Arr::get($config, 'title'))
                <h4 class="footer-widget__title mb-30">{!! BaseHelper::clean($title) !!}</h4>
            @endif
            @if($subtitle = Arr::get($config, 'subtitle'))
                <p>{!! BaseHelper::clean($subtitle) !!}</p>
            @endif
            <div class="footer-widget__newsletter">
                <form action="{{ route('public.newsletter.subscribe') }}" method="post" class="newsletter-form">
                    @csrf
                    <input type="email" name="email" placeholder="{{ __('Enter email address') }}" required>

                    @if (setting('enable_captcha') && is_plugin_active('captcha'))
                        <div class="mb-3">
                            {!! Captcha::display() !!}
                        </div>
                    @endif

                    <button type="submit" class="footer-widget__fw-news-btn tpsecondary-btn">
                        {{ __('Subscribe Now') }}
                        <i class="fal fa-long-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endif
