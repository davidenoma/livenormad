@php
    $hasAds = $shortcode->ads_1 || $shortcode->ads_2;
    $style = ! in_array($shortcode->style, ['wooden', 'fashion', 'furniture', 'cosmetics', 'grocery']) ? 'wooden' : $shortcode->style;
@endphp

@if($sliders->isNotEmpty())
    @php $sliders->loadMissing('metadata'); @endphp
    <section @class([
        'slider-area',
        'pb-25' => $style === 'wooden',
        'slider-bg slider-bg-height' => $style === 'fashion',
    ])>
        @include(Theme::getThemeNamespace("partials.shortcodes.simple-slider.styles.$style"))
    </section>
@endif
