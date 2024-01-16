<?php

use Botble\Ads\Forms\AdsForm;
use Botble\Base\Facades\Assets;
use Botble\Base\Facades\BaseHelper;
use Botble\Media\Facades\RvMedia;
use Botble\Page\Forms\PageForm;
use Botble\SimpleSlider\Forms\SimpleSliderItemForm;
use Botble\Slug\Facades\SlugHelper;
use Botble\Team\Models\Team;
use Botble\Theme\Facades\Theme;

register_page_template([
    'default' => __('Default'),
    'full-width' => __('Full width'),
    'blank' => __('Blank (without header and footer)'),
]);

register_sidebar([
    'id' => 'footer_sidebar',
    'name' => __('Footer sidebar'),
    'description' => __('Widgets in footer of page'),
]);

register_sidebar([
    'id' => 'footer_middle_sidebar',
    'name' => __('Footer middle sidebar'),
    'description' => __('Widgets in middle footer of page'),
]);

register_sidebar([
    'id' => 'footer_bottom_sidebar',
    'name' => __('Footer bottom sidebar'),
    'description' => __('Widgets in bottom footer of page'),
]);

app()->booted(fn () => remove_sidebar('primary_sidebar'));

if (is_plugin_active('blog')) {
    register_sidebar([
        'id' => 'blog_sidebar',
        'name' => __('Blog sidebar'),
        'description' => __('Widgets in blog page'),
    ]);
}

if (is_plugin_active('ecommerce')) {
    register_sidebar([
        'id' => 'ecommerce_products_sidebar',
        'name' => __('Products listing sidebar'),
        'description' => __('Widgets in products listing page'),
    ]);

    register_sidebar([
        'id' => 'product_detail_sidebar',
        'name' => __('Product detail sidebar'),
        'description' => __('Widgets in the product detail page'),
    ]);
}

if (is_plugin_active('simple-slider')) {
    SimpleSliderItemForm::extend(function (SimpleSliderItemForm $form) {
        $model = $form->getModel();
        $model->loadMissing('metadata');

        return $form
            ->addAfter('title', 'subtitle', 'text', [
                'label' => __('Subtitle'),
                'value' => $model->getMetaData('subtitle', true),
                'metadata' => true,
            ])
            ->addAfter('subtitle', 'action_label', 'text', [
                'label' => __('Button label'),
                'value' => $model->getMetaData('action_label', true),
                'attr' => [
                    'placeholder' => __('Ex: Shop Now'),
                ],
                'metadata' => true,
            ])
            ->addAfter('image', 'tablet_image', 'mediaImage', [
                'label' => __('Tablet Image'),
                'value' => $model->getMetaData('tablet_image', true),
                'help_block' => [
                    'text' => __('For devices with width from 768px to 1200px, if empty, will use the image from the desktop.'),
                ],
                'metadata' => true,
            ])
            ->addAfter('tablet_image', 'mobile_image', 'mediaImage', [
                'label' => __('Mobile Image'),
                'value' => $model->getMetaData('mobile_image', true),
                'help_block' => [
                    'text' => __('For devices with width less than 768px, if empty, will use the image from the tablet.'),
                ],
                'metadata' => true,
            ]);
    }, 127);
}

if (is_plugin_active('ads')) {
    AdsForm::extend(function (AdsForm $form) {
        $model = $form->getModel();
        $model->loadMissing('metadata');

        return $form
            ->addAfter('name', 'subtitle', 'textarea', [
                'label' => __('Subtitle'),
                'value' => $model->getMetaData('subtitle', true),
                'attr' => [
                    'rows' => 2,
                ],
                'metadata' => true,
            ]);
    }, 127);
}

PageForm::extend(function (PageForm $form) {
    Assets::addScriptsDirectly(Theme::asset()->url('js/page.js'));

    $model = $form->getModel();
    $model->loadMissing('metadata');

    return $form
        ->addAfter('template', 'header_style', 'customSelect', [
            'label' => __('Header style'),
            'choices' => [
                'inherit' => __('Inherit (use header style from theme options)'),
                'default' => __('Default'),
                'centered-logo' => __('Centered logo'),
                'collapsed' => __('Collapsed'),
            ],
            'selected' => $model->getMetaData('header_style', true) ?: 'inherit',
            'metadata' => true,
        ])
        ->addAfter('image', 'banner_image', is_in_admin(true) ? 'mediaImage' : 'customImage', [
            'label' => __('Banner image (1920x200px)'),
            'label_attr' => ['class' => 'control-label'],
            'value' => $model->getMetaData('banner_image', true),
            'metadata' => true,
        ])
        ->addAfter('banner_image', 'customize_footer', 'customSelect', [
            'label' => __('Customize footer'),
            'label_attr' => ['class' => 'control-label'],
            'choices' => [
                'inherit' => __('Inherit (use footer style from theme options)'),
                'custom' => __('Custom'),
            ],
            'selected' => $model->getMetaData('customize_footer', true) ?: 'inherit',
            'metadata' => true,
        ])
        ->addAfter('customize_footer', 'footer_background_color', 'customColor', [
            'label' => __('Footer background color'),
            'label_attr' => ['class' => 'control-label'],
            'value' => $model->getMetaData('footer_background_color', true) ?: theme_option('footer_background_color', '#040404'),
            'metadata' => true,
        ])
        ->addAfter('footer_background_color', 'footer_text_color', 'customColor', [
            'label' => __('Footer text color'),
            'label_attr' => ['class' => 'control-label'],
            'value' => $model->getMetaData('footer_text_color', true) ?: theme_option('footer_text_color', '#fff'),
            'metadata' => true,
        ])
        ->addAfter('footer_text_color', 'footer_text_muted_color', 'customColor', [
            'label' => __('Footer text muted color'),
            'label_attr' => ['class' => 'control-label'],
            'value' => $model->getMetaData('footer_text_muted_color', true) ?: theme_option('footer_text_muted_color', '#a0a0a0'),
            'metadata' => true,
        ])
        ->addAfter('footer_text_muted_color', 'footer_logo', 'mediaImage', [
            'label' => __('Footer logo'),
            'label_attr' => ['class' => 'control-label'],
            'value' => $model->getMetaData('footer_logo', true) ?: theme_option('logo_light'),
            'metadata' => true,
        ])
        ->addAfter('footer_logo', 'footer_border_color', 'customColor', [
            'label' => __('Footer border color'),
            'label_attr' => ['class' => 'control-label'],
            'value' => $model->getMetaData('footer_border_color', true) ?: theme_option('footer_border_color', '#282828'),
            'metadata' => true,
        ])
        ->addAfter('footer_border_color', 'footer_bottom_background_color', 'customColor', [
            'label' => __('Footer bottom background color'),
            'label_attr' => ['class' => 'control-label'],
            'value' => $model->getMetaData('footer_bottom_background_color', true) ?: theme_option('footer_bottom_background_color', '#040404'),
            'metadata' => true,
        ]);
}, 127);

RvMedia::setUploadPathAndURLToPublic()
    ->addSize('small', 300, 300)
    ->addSize('medium', 720, 720);

if (is_plugin_active('team')) {
    SlugHelper::removeModule(Team::class);
}

if (! function_exists('get_currencies_json')) {
    function get_currencies_json(): array
    {
        $currency = get_application_currency();
        $numberAfterDot = $currency->decimals ?: 0;

        return [
            'display_big_money' => config('plugins.ecommerce.general.display_big_money_in_million_billion'),
            'billion' => __('billion'),
            'million' => __('million'),
            'is_prefix_symbol' => $currency->is_prefix_symbol,
            'symbol' => $currency->symbol,
            'title' => $currency->title,
            'decimal_separator' => get_ecommerce_setting('decimal_separator', '.'),
            'thousands_separator' => get_ecommerce_setting('thousands_separator', ','),
            'number_after_dot' => $numberAfterDot,
            'show_symbol_or_title' => true,
        ];
    }
}

app()->booted(function () {
    add_filter('ecommerce_number_of_products_display_options', function (): array {
        return [
            20 => 20,
            30 => 30,
            40 => 40,
            60 => 60,
        ];
    }, 120);

    if (is_plugin_active('announcement')) {
        add_filter('announcement_display', fn () => null, 120);
    }
});

if (! function_exists('get_product_layouts')) {
    function get_product_layouts(): array
    {
        return ['list' => __('List'), 'grid' => __('Grid')];
    }
}

if (! function_exists('get_default_product_layout')) {
    function get_default_product_layout(): string
    {
        $defaultLayout = 'grid';

        $layout = theme_option('default_product_list_layout', $defaultLayout);

        return ($layout !== $defaultLayout && array_key_exists($layout, get_product_layouts())) ? $layout : $defaultLayout;
    }
}

if (! function_exists('get_current_product_layout')) {
    function get_current_product_layout(): string
    {
        $defaultLayout = get_default_product_layout();

        $layout = BaseHelper::clean(request()->input('layout', $defaultLayout));

        return ($layout !== $defaultLayout && array_key_exists($layout, get_product_layouts())) ? $layout : $defaultLayout;
    }
}
