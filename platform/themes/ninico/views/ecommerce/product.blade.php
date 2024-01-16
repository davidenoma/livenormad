@php
    Theme::asset()
        ->usePath()
        ->add('jquery-bar-rating-css', 'plugins/jquery-bar-rating/fontawesome-stars.css');
    Theme::asset()
        ->container('footer')
        ->usePath()
        ->add('jquery-bar-rating-js', 'plugins/jquery-bar-rating/jquery.barrating.min.js');

    Theme::asset()
        ->usePath()
        ->add('lightgallery-css', 'plugins/lightgallery/css/lightgallery.min.css');
    Theme::asset()
        ->container('footer')
        ->usePath()
        ->add('lightgallery-js', 'plugins/lightgallery/js/lightgallery.min.js');

    $flashSale = $product->latestFlashSales()->first();

    if ($flashSale) {
         Theme::asset()
            ->container('footer')
            ->usePath()
            ->add('countdown-js', 'js/countdown.js');
    }

    Theme::set('pageTitle', '');
@endphp

<div class="product-area row pb-25">
    <div class="col-lg-5 col-md-12">
        @include(Theme::getThemeNamespace('views.ecommerce.includes.product-gallery'))
    </div>
    <div class="col-lg-4 col-md-8">
        <div class="tpproduct-details__content">
            <div class="tpproduct-details__tag-area d-flex align-items-center mb-5">
                @if ($product->isOutOfStock())
                    <span class="tpproduct-details__tag">{{ __('Out of stock') }}</span>
                @else
                    @if ($product->isOnSale() && $product->sale_percent)
                        <span class="tpproduct-details__tag" style="background-color: #328f0a; color: #fff">
                            {{ __(':percent% off', ['percent' => $product->sale_percent]) }}
                        </span>
                    @endif
                    @foreach ($product->productLabels as $label)
                        <span
                            class="tpproduct-details__tag"
                            style="background-color: {{ $label->color }}; color: #fff"
                        >{{ $label->name }}</span>
                    @endforeach
                @endif
                @if (EcommerceHelper::isReviewEnabled())
                    <div class="tpproduct-details__rating">
                        <div class="product-rating-wrapper">
                            <div
                                class="product-rating"
                                style="width: {{ $product->reviews_avg * 20 }}%"
                            ></div>
                        </div>
                    </div>
                    <a
                        class="tpproduct-details__reviewers">{{ __(':count Reviews', ['count' => $product->reviews_count]) }}</a>
                @endif
            </div>
            <div class="tpproduct-details__title-area d-flex align-items-center flex-wrap mb-5">
                <h3 class="tpproduct-details__title">{!! BaseHelper::clean($product->name) !!}</h3>
                @if (!$product->isOutOfStock())
                    <span class="tpproduct-details__stock">{{ __('In Stock') }}</span>
                @endif
            </div>
            <div class="tpproduct-details__price mb-30">
                <span @class(['product-price-sale', 'd-none' => !$product->isOnSale()])>
                    <span class="amount">{{ format_price($product->front_sale_price_with_taxes) }}</span>
                    <del class="amount">{{ format_price($product->price_with_taxes) }}</del>
                </span>
                <span @class([
                    'product-price-original',
                    'd-none' => $product->isOnSale(),
                    'ms-0' => !$product->isOnSale(),
                ])>
                    <span class="amount">{{ format_price($product->front_sale_price_with_taxes) }}</span>
                </span>
            </div>

            {!! apply_filters('ecommerce_before_product_description', null, $product) !!}
            <div class="tpproduct-details__pera">
                <p>{!! BaseHelper::clean($product->description) !!}</p>
            </div>
            {!! apply_filters('ecommerce_after_product_description', null, $product) !!}

            @if ($flashSale)
                <div class="theme-bg p-3 mb-4">
                    <div class="mb-3">
                        <div class="small deal-expire-text mb-2">
                            <p>{!! __('Hurry up! Sale end in') !!}</p>
                        </div>
                        <div class="tpdealcontact__count">
                            <div
                                class="tpdealcontact__countdown"
                                data-countdown="{{ $flashSale->end_date }}"
                            ></div>
                        </div>
                    </div>
                    <div>
                        <div class="small text-muted mb-2">
                            <span>{{ __('Sold: :count', ['count' => $flashSale->pivot->sold . '/' . $flashSale->pivot->quantity]) }}</span>
                        </div>
                        <div class="tpdealcontact__progress">
                            <div class="progress">
                                <div
                                    class="progress-bar"
                                    style="width: {{ $flashSale->pivot->quantity > 0 ? ($flashSale->pivot->sold / $flashSale->pivot->quantity) * 100 : 0 }}%"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form
                class="cart-form"
                method="POST"
                action="{{ route('public.cart.add-to-cart') }}"
            >
                <input
                    id="hidden-product-id"
                    name="id"
                    type="hidden"
                    value="{{ $product->is_variation || !$product->defaultVariation->product_id ? $product->id : $product->defaultVariation->product_id }}"
                />

                <div class="tpproductdot mb-30">
                    @if ($product->variations->isNotEmpty())
                        {!! render_product_swatches($product, [
                            'selected' => $selectedAttrs,
                            'view' => Theme::getThemeNamespace('views.ecommerce.attributes.swatches-renderer'),
                        ]) !!}

                        <div
                            class="number-items-available"
                            style="display: none; margin-bottom: 10px;"
                        ></div>
                    @endif

                    {!! render_product_options($product) !!}

                    <input
                        class="hidden-product-id"
                        name="id"
                        type="hidden"
                        value="{{ $product->is_variation || !$product->defaultVariation->product_id ? $product->id : $product->defaultVariation->product_id }}"
                    />

                    {!! apply_filters(ECOMMERCE_PRODUCT_DETAIL_EXTRA_HTML, null, $product) !!}
                </div>

                <div class="tpproduct-details__count d-flex align-items-center flex-wrap gap-2 mb-3">
                    @if (EcommerceHelper::isCartEnabled())
                        <div class="tpproduct-details__quantity">
                            <span class="cart-minus"><i class="far fa-minus"></i></span>
                            <input
                                class="tp-cart-input"
                                name="qty"
                                type="text"
                                value="1"
                            >
                            <span class="cart-plus"><i class="far fa-plus"></i></span>
                        </div>
                        <div class="d-flex gap-2 tpproduct-details__cart">
                            <button
                                class="btn add-to-cart"
                                name="add_to_cart"
                                type="submit"
                                value="1"
                                @disabled($product->isOutOfStock())
                            >
                                <i class="fal fa-shopping-cart"></i>
                                {{ __('Add To Cart') }}
                            </button>

                            @if (EcommerceHelper::isQuickBuyButtonEnabled())
                                <button
                                    class="btn bg-dark buy-now"
                                    name="checkout"
                                    type="submit"
                                    value="1"
                                    @disabled($product->isOutOfStock())
                                >
                                    {{ __('Buy Now') }}
                                </button>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="d-flex align-items-center gap-4 mb-4">
                    @if (EcommerceHelper::isWishlistEnabled())
                        <div>
                            <a
                                class="wishlist add-to-wishlist text-muted small"
                                href="{{ route('public.wishlist.add', $product->getKey()) }}"
                            >
                                <i class="fal fa-heart"></i>
                                {{ __('Wishlist') }}
                            </a>
                        </div>
                    @endif
                    @if (EcommerceHelper::isCompareEnabled())
                        <div>
                            <a
                                class="add-to-compare text-muted small"
                                href="{{ route('public.compare.add', $product->id) }}"
                            >
                                <i class="fal fa-exchange"></i>
                                {{ __('Compare') }}
                            </a>
                        </div>
                    @endif
                </div>
            </form>

            @if ($product->sku)
                <div class="tpproduct-details__information tpproduct-details__code meta-sku">
                    <p>{{ __('SKU:') }}</p><span class="meta-value">{{ $product->sku }}</span>
                </div>
            @endif
            @if ($product->categories->isNotEmpty())
                <div class="tpproduct-details__information tpproduct-details__categories">
                    <p>{{ __('Categories:') }}</p>
                    @foreach ($product->categories as $category)
                        <span>
                            <a href="{{ $category->url }}">{{ $category->name . (!$loop->last ? ',' : null) }}</a>
                        </span>
                    @endforeach
                </div>
            @endif
            @if ($product->tags->isNotEmpty())
                <div class="tpproduct-details__information tpproduct-details__tags">
                    <p>{{ __('Tags:') }}</p>
                    @foreach ($product->tags as $tag)
                        <span>
                            <a href="{{ $tag->url }}">{{ $tag->name . (!$loop->last ? ',' : null) }}</a>
                        </span>
                    @endforeach
                </div>
            @endif
            <div class="tpproduct-details__information tpproduct-details__social">
                <p>{{ __('Share:') }}</p>
                <a
                    href="https://www.facebook.com/sharer/sharer.php?u={{ $product->url }}"
                    target="_blank"
                ><i class="fab fa-facebook-f"></i></a>
                <a
                    href="https://twitter.com/intent/tweet?text={{ $product->name }}&url={{ $product->url }}"
                    target="_blank"
                ><i class="fab fa-twitter"></i></a>
                <a
                    href="https://www.linkedin.com/sharing/share-offsite/?url={{ $product->url }}"
                    target="_blank"
                ><i class="fab fa-linkedin-in"></i></a>
                <a
                    href="https://www.pinterest.com/pin/create/button/?url={{ $product->url }}&media={{ $product->image }}&description={{ $product->name }}"
                    target="_blank"
                ><i class="fab fa-pinterest-p"></i></a>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-4">
        {!! dynamic_sidebar('product_detail_sidebar') !!}
    </div>
</div>

@include(Theme::getThemeNamespace('views.ecommerce.includes.cross-sale-products'), ['products' => $product->crossSaleProducts])

<div class="product-details-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="tpproduct-details__navtab mb-60">
                    <div class="tpproduct-details__nav mb-30">
                        <ul
                            class="nav nav-tabs pro-details-nav-btn"
                            id="myTabs"
                            role="tablist"
                        >
                            <li
                                class="nav-item"
                                role="presentation"
                            >
                                <button
                                    class="nav-links active"
                                    id="description-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#description"
                                    type="button"
                                    role="tab"
                                    aria-controls="description"
                                    aria-selected="true"
                                >
                                    {{ __('Description') }}
                                </button>
                            </li>
                            @if (EcommerceHelper::isReviewEnabled())
                                <li
                                    class="nav-item"
                                    role="presentation"
                                >
                                    <button
                                        class="nav-links"
                                        id="reviews-tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#reviews"
                                        type="button"
                                        role="tab"
                                        aria-controls="reviews"
                                        aria-selected="false"
                                    >
                                        {{ __('Reviews (:count)', ['count' => number_format($product->reviews_count)]) }}
                                    </button>
                                </li>
                            @endif
                            @if (is_plugin_active('faq') && count($product->faq_items) > 0)
                                <li
                                    class="nav-item"
                                    role="presentation"
                                >
                                    <button
                                        class="nav-links"
                                        id="faq-tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#faq"
                                        type="button"
                                        role="tab"
                                        aria-controls="faq"
                                        aria-selected="true"
                                    >
                                        {{ __('Questions & Answers') }}
                                    </button>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div
                        class="tab-content tp-content-tab"
                        id="myTabContent-2"
                    >
                        <div
                            class="tab-para tab-pane fade show active"
                            id="description"
                            role="tabpanel"
                            aria-labelledby="description-tab"
                        >
                            <div class="ck-content">
                                {!! BaseHelper::clean($product->content) !!}
                            </div>
                            @if (theme_option('facebook_comment_enabled_in_product', 'no') === 'yes')
                                <div class="postbox__comment mb-65">
                                    <h3 class="postbox__comment-title">{{ __('Comment') }}</h3>
                                    {!! apply_filters(BASE_FILTER_PUBLIC_COMMENT_AREA, null, $product) !!}
                                </div>
                            @endif
                        </div>
                        @if (EcommerceHelper::isReviewEnabled())
                            <div
                                class="tab-pane fade"
                                id="reviews"
                                role="tabpanel"
                                aria-labelledby="reviews-tab"
                            >
                                <div class="comments-area">
                                    @if ($product->review_images)
                                        <div class="mb-50">
                                            <h5 class="mb-20">
                                                {{ __('Images from customer (:count)', ['count' => count($product->review_images)]) }}
                                            </h5>
                                            <div class="row g-2 product-review-images">
                                                @foreach ($product->review_images as $img)
                                                    <a
                                                        href="{{ RvMedia::getImageUrl($img) }}"
                                                        @class(['col-lg-1 col-sm-2 col-3', 'd-none' => $loop->iteration > 12])
                                                    >
                                                        <div class="border position-relative rounded">
                                                            <img
                                                                class="img-fluid rounded h-100"
                                                                src="{{ RvMedia::getImageUrl($img, 'thumb') }}"
                                                                alt="{{ $product->name }}"
                                                            >
                                                            @if ($loop->iteration === 12 && count($product->review_images) - $loop->iteration > 0)
                                                                <div
                                                                    class="d-flex justify-content-center align-items-center position-absolute top-0 bottom-0 left-0 right-0 bg-black w-100"
                                                                    style="--bs-bg-opacity: .5;"
                                                                >
                                                                    <span
                                                                        class="text-white fs-6">+{{ count($product->review_images) - $loop->iteration }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-8">
                                            <h5 class="tp-comments-title mb-35">
                                                {{ __(':count review(s) for ":name"', ['count' => $product->reviews_count, 'name' => $product->name]) }}
                                            </h5>

                                            <div class="position-relative product-reviews-container">
                                                <div
                                                    class="comment-list"
                                                    data-url="{{ route('public.ajax.product-reviews', $product->id) }}"
                                                ></div>
                                                <div class="loading-spinner"></div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-20">
                                                <h5>{{ __('Add your review') }}</h5>
                                                <p class="small text-muted mt-2">
                                                    {{ __('Your email address will not be published. Required fields are marked.') }}
                                                </p>
                                            </div>

                                            <form
                                                class="form-review-product"
                                                action="{{ route('public.reviews.create') }}"
                                                method="post"
                                            >
                                                @csrf

                                                <input
                                                    name="product_id"
                                                    type="hidden"
                                                    value="{{ $product->getKey() }}"
                                                >

                                                @guest('customer')
                                                    <p class="text-danger mb-3">{!! BaseHelper::clean(
                                                        __('Please <a href=":link">login</a> to write review!', ['link' => route('customer.login')]),
                                                    ) !!}</p>
                                                @endguest

                                                <div class="d-flex align-items-center mb-3">
                                                    <label
                                                        class="form-label"
                                                        for="rating"
                                                    >{{ __('Your rating:') }}</label>
                                                    <div class="form-rating-stars ms-2 mb-1">
                                                        @foreach (array_reverse(range(1, 5)) as $i)
                                                            <input
                                                                class="btn-check"
                                                                id="rating-star-{{ $i }}"
                                                                name="star"
                                                                type="radio"
                                                                value="{{ $i }}"
                                                                @checked($i === 5)
                                                            >
                                                            <label
                                                                for="rating-star-{{ $i }}"
                                                                title="{{ $i }} stars"
                                                            >
                                                                <i class="fas fa-star"></i>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label
                                                        class="form-label"
                                                        for="comment"
                                                    >{{ __('Review:') }}</label>
                                                    <textarea
                                                        class="form-control"
                                                        id="comment"
                                                        name="comment"
                                                        style="height: auto"
                                                        rows="3"
                                                        required
                                                        placeholder="{{ __('Write your review') }}"
                                                        @disabled(!auth('customer')->check())
                                                    ></textarea>
                                                </div>

                                                <div class="mb-3">
                                                    <script type="text/x-custom-template" id="review-image-template">
                                                        <span class="image-viewer__item" data-id="__id__">
                                                            <img src="{{ RvMedia::getDefaultImage() }}" alt="{{ __('Preview') }}" class="img-responsive d-block">
                                                            <span class="image-viewer__icon-remove">
                                                                <i class="fas fa-times-circle"></i>
                                                            </span>
                                                        </span>
                                                    </script>
                                                    <div class="image-upload__viewer d-flex">
                                                        <div class="image-viewer__list position-relative">
                                                            <div class="image-upload__uploader-container">
                                                                <div class="d-table">
                                                                    <div class="image-upload__uploader">
                                                                        <i class="fi fi-rr-file-add"></i>
                                                                        <div class="image-upload__text">
                                                                            {{ __('Upload photos') }}</div>
                                                                        <input
                                                                            class="image-upload__file-input"
                                                                            name="images[]"
                                                                            data-max-files="{{ EcommerceHelper::reviewMaxFileNumber() }}"
                                                                            data-max-size="{{ EcommerceHelper::reviewMaxFileSize(true) }}"
                                                                            data-max-size-message="{{ trans('validation.max.file', ['attribute' => '__attribute__', 'max' => '__max__']) }}"
                                                                            type="file"
                                                                            accept="image/png,image/jpeg,image/jpg"
                                                                            multiple="multiple"
                                                                        >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="alert alert-info p-2 pb-0 small muted">
                                                        {{ __('You can upload up to :total photos, each photo maximum size is :max kilobytes.', [
                                                            'total' => EcommerceHelper::reviewMaxFileNumber(),
                                                            'max' => EcommerceHelper::reviewMaxFileSize(true),
                                                        ]) }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <button
                                                    class="d-flex tp-btn pro-submit"
                                                    type="submit"
                                                    @disabled(!auth('customer')->check())
                                                >
                                                    <i
                                                        class="fal fa-paper-plane me-2 ms-0"
                                                        style="color: unset"
                                                    ></i>
                                                    <span>{{ __('Submit Review') }}</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (is_plugin_active('faq') && count($product->faq_items) > 0)
                            <div
                                class="tab-para tab-pane fade"
                                id="faq"
                                role="tabpanel"
                                aria-labelledby="faq-tab"
                            >
                                <div
                                    class="accordion"
                                    id="accordionFaqs"
                                >
                                    @foreach ($product->faq_items as $faq)
                                        <div class="accordion-item tp-accordion-item">
                                            <h2
                                                class="accordion-header tp-accordion-header"
                                                id="heading-{{ $loop->index }}"
                                            >
                                                <button
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapse-{{ $loop->index }}"
                                                    type="button"
                                                    aria-expanded="true"
                                                    aria-controls="collapse-{{ $loop->index }}"
                                                    @class(['accordion-button', 'collapsed' => !$loop->first])
                                                >
                                                    {!! BaseHelper::clean($faq[0]['value']) !!}
                                                </button>
                                            </h2>
                                            <div
                                                id="collapse-{{ $loop->index }}"
                                                data-bs-parent="#accordionFaqs"
                                                aria-labelledby="heading-{{ $loop->index }}"
                                                @class(['accordion-collapse collapse', 'show' => $loop->first])
                                            >
                                                <div class="accordion-body tp-accordion-body">
                                                    {!! BaseHelper::clean($faq[1]['value']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php($relatedProducts = get_related_products($product, 5))

@if ($relatedProducts->isNotEmpty())
    <div class="related-product-area pt-65 pb-50 related-product-border">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <div class="tpsection mb-40">
                        <h4 class="tpsection__title">{{ __('Related Products') }}</h4>
                    </div>
                </div>
                @if ($relatedProducts->count() > 1)
                    <div class="col-sm-6">
                        <div class="tprelated__arrow d-flex align-items-center justify-content-end mb-40">
                            <div class="tprelated__prv"><i class="far fa-long-arrow-left"></i></div>
                            <div class="tprelated__nxt"><i class="far fa-long-arrow-right"></i></div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="swiper-container related-product-active">
                <div class="swiper-wrapper">
                    @foreach ($relatedProducts as $product)
                        <div class="swiper-slide">
                            @include(Theme::getThemeNamespace('views.ecommerce.includes.product-item'))
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @include(Theme::getThemeNamespace('views.ecommerce.includes.quick-view-modal'))
@endif
