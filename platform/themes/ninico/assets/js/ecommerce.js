class Ecommerce {
    $body = $(document.body)
    $productsFilter = this.$body.find('#products-filter')

    init() {
        this.$body
            .on('click', '.add-to-cart:not(.cart-form button[type=submit])', (event) => {
                this.addToCart(event)
            })
            .on('click', '.remove-cart-item', (event) => {
                this.removeFromCart(event)
            })
            .on('click', '.btn-apply-coupon-code', (event) => {
                this.applyCouponCode(event)
            })
            .on('click', '.btn-remove-coupon-code', (event) => {
                this.removeCouponCode(event)
            })
            .on('click', '.product-quantity span', (event) => {
                this.changeCartQuantity(event)
            })
            .on('keyup', '.product-quantity input', (event) => {
                this.onChangeQuantityInput(event)
            })
            .on('click', '.add-to-compare', (event) => {
                this.addToCompare(event)
            })
            .on('click', '.js-sale-popup-quick-view-button', (event) => {
                this.quickView(event)
            })
            .on('click', '.tpproduct .quickview', (event) => {
                this.quickView(event)
            })
            .on('click', '.tpproduct .button-quick-shop', (event) => {
                this.quickShop(event)
            })
            .on('click', '.remove-compare-item', (event) => {
                this.removeFromCompare(event)
            })
            .on('click', '.add-to-wishlist', (event) => {
                this.addToWishlist(event)
            })
            .on('click', '.remove-wishlist-item', (event) => {
                this.removeFromWishlist(event)
            })
            .on('submit', '#products-filter', (event) => {
                event.preventDefault()

                this.filterProducts($(event.currentTarget), 1)
            })
            .on('click', '.product-area .basic-pagination ul li a', (event) => {
                this.handleProductsPagination(event)
            })
            .on('change', '.product-area .tp-shop-selector select[name="sort-by"]', (event) => {
                this.handleProductsSorting(event)
            })
            .on('change', '.product-area .tp-shop-selector select[name="per-page"]', (event) => {
                this.handleProductsPerPage(event)
            })
            .on('click', '.product-area .product-filter-nav button', (event) => {
                this.handleProductsLayout(event)
            })
            .on('change', '#products-filter select, input', (event) => {
                if ($(event.currentTarget).closest('#quick-shop-popup').length) {
                    return
                }

                this.$productsFilter.trigger('submit')
            })
            .on('click', '.product-filter-button', () => {
                this.$body.find('.product-filter-mobile').addClass('active')
            })
            .on('click', '.product-filter-mobile .backdrop, .close-product-filter-mobile', () => {
                this.$body.find('.product-filter-mobile').removeClass('active')
            })
            .on('click', 'form.cart-form button[type=submit]', (event) => {
                this.addProductToCart(event)
            })
            .on('click', '.tpproduct-details__reviewers', () => {
                this.$body.find('.tpproduct-details__nav #reviews-tab').trigger('click')
                $('html, body').animate({
                    scrollTop: $('.tpproduct-details__navtab').offset().top - 100,
                })
            })
            .on('click', '.product-sidebar__list .f-right', (event) => {
                event.preventDefault()

                $(event.currentTarget).closest('.category-filter').find('.product-sidebar__list').slideToggle()
            })

        this.reviewSection()
        this.priceFilter()
        this.productGallery($('.product-gallery'))
        this.quickSearchProducts()

        const _this = this

        window.onBeforeChangeSwatches = function (data, $attrs) {
            const $product = $attrs.closest('.tpproduct-details__content')
            const $form = $product.find('.cart-form')

            $product.find('.error-message').hide()
            $product.find('.success-message').hide()
            $product.find('.number-items-available').html('').hide()
            const $submit = $form.find('button[type=submit]')

            if (data) {
                $submit.prop('disabled', true)
            }
        }

        window.onChangeSwatchesSuccess = function (response, $attrs) {
            const $product = $attrs.closest('.tpproduct-details__content')
            const $form = $product.find('.cart-form')
            const $footerCartForm = $('.footer-cart-form')

            if (!response) {
                return
            }

            const $submit = $form.find('button[type=submit]')

            if (response.error) {
                $submit.prop('disabled', true)
                $product
                    .find('.number-items-available')
                    .html(`<span class='text-danger'>(${response.message})</span>`)
                    .show()
                $form.find('.hidden-product-id').val('')
                $footerCartForm.find('.hidden-product-id').val('')
            } else {
                const data = response.data
                const $price = $product.find('.tpproduct-details__price')
                const $salePrice = $price.find('.product-price-sale')
                const $originalPrice = $price.find('.product-price-original')

                if (data.sale_price !== data.price) {
                    $salePrice.removeClass('d-none')
                    $originalPrice.addClass('d-none')
                } else {
                    $salePrice.addClass('d-none')
                    $originalPrice.removeClass('d-none')
                }

                $salePrice.find('span.amount').text(data.display_sale_price)
                $salePrice.find('del.amount').text(data.display_price)
                $originalPrice.find('span.amount').text(data.display_sale_price)

                if (data.sku) {
                    $product.find('.meta-sku .meta-value').text(data.sku)
                    $product.find('.meta-sku').removeClass('d-none')
                } else {
                    $product.find('.meta-sku').addClass('d-none')
                }

                $form.find('.hidden-product-id').val(data.id)
                $footerCartForm.find('.hidden-product-id').val(data.id)
                $submit.prop('disabled', false)

                if (data.error_message) {
                    $submit.prop('disabled', true)
                    $product
                        .find('.number-items-available')
                        .html(`<span class='text-danger'>(${data.error_message})</span>`)
                        .show()
                } else if (data.success_message) {
                    $product.find('.number-items-available').html(response.data.stock_status_html).show()
                } else {
                    $product.find('.number-items-available').html('').hide()
                }

                const unavailableAttributeIds = data.unavailable_attribute_ids || []
                $product.find('.attribute-swatch-item').removeClass('pe-none')
                $product.find('.product-filter-item option').prop('disabled', false)

                if (unavailableAttributeIds && unavailableAttributeIds.length) {
                    unavailableAttributeIds.map(function (id) {
                        let $item = $product.find(`.attribute-swatch-item[data-id="${id}"]`)
                        if ($item.length) {
                            $item.addClass('pe-none')
                            $item.find('input').prop('checked', false)
                        } else {
                            $item = $product.find(`.product-filter-item option[data-id="${id}"]`)
                            if ($item.length) {
                                $item.prop('disabled', 'disabled').prop('selected', false)
                            }
                        }
                    })
                }

                const $gallery = $product.closest('.product-area').find('.product-gallery')

                let imageHtml = ''

                data.image_with_sizes.origin.forEach(function (item) {
                    imageHtml += `<a href='${item}'>
                        <img title='${data.name}' title='${data.name}' src='${
                            siteConfig.img_placeholder ? siteConfig.img_placeholder : item
                        }' data-lazy='${item}'>
                    </a>`
                })

                $gallery.find('.product-gallery__wrapper').slick('unslick').html(imageHtml)

                let thumbHtml = ''

                data.image_with_sizes.thumb.forEach(function (item) {
                    thumbHtml += `<img alt='${data.name}' title='${data.name}' src='${
                        siteConfig.img_placeholder ? siteConfig.img_placeholder : item
                    }' data-src='${item}' data-lazy='${item}'>`
                })

                $gallery.find('.product-thumbnails').slick('unslick').html(thumbHtml)

                _this.productGallery($gallery)
            }
        }
    }

    productGallery($gallery) {
        if (!$gallery.length) {
            return
        }

        const first = $gallery.find('.product-gallery__wrapper')
        const thumbnails = $gallery.find('.product-thumbnails')

        if (first.length) {
            if (first.hasClass('slick-initialized')) {
                first.slick('unslick')
            }

            first.slick({
                rtl: NinicoApp.isRtl(),
                slidesToShow: 1,
                slidesToScroll: 1,
                infinite: false,
                dots: false,
                arrows: false,
                lazyLoad: 'ondemand',
            })
        }

        if (thumbnails.length) {
            if (thumbnails.hasClass('slick-initialized')) {
                thumbnails.slick('unslick')
            }

            thumbnails.slick({
                rtl: NinicoApp.isRtl(),
                slidesToShow: 6,
                slidesToScroll: 1,
                infinite: false,
                focusOnSelect: true,
                asNavFor: first,
                vertical: thumbnails.data('vertical') === 1,
                nextArrow: '<button class="slick-next slick-arrow"><i class="fas fa-chevron-down"></i></button>',
                prevArrow: '<button class="slick-prev slick-arrow"><i class="fas fa-chevron-up"></i></button>',
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 4,
                            vertical: false,
                        },
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 3,
                            vertical: false,
                        },
                    },
                ],
            })
        }

        this.lightGallery($gallery)
    }

    quickSearchProducts() {
        const quickSearch = '.form--quick-search'
        const $quickSearch = $('.form--quick-search')
        $('body').on('click', function (e) {
            if (!$(e.target).closest(quickSearch).length) {
                $('.panel--search-result').removeClass('active')
            }
        })

        let currentRequest = null
        $quickSearch.on('keyup', '.input-search-product', function () {
            const $form = $(this).closest('form')
            ajaxSearchProduct($form)
        })

        $quickSearch.on('change', '.product-category-select', function () {
            const $form = $(this).closest('form')
            ajaxSearchProduct($form)
        })

        $quickSearch.on('click', '.loadmore', function (e) {
            e.preventDefault()
            const $form = $(this).closest('form')
            $(this).addClass('loading')
            ajaxSearchProduct($form, $(this).attr('href'))
        })

        function ajaxSearchProduct($form, url = null) {
            const $panel = $form.find('.panel--search-result')
            const k = $form.find('.input-search-product').val()
            if (!k) {
                $panel.html('').removeClass('active')
                return
            }

            $quickSearch.find('.input-search-product').val(k)
            const $button = $form.find('button[type=submit]')

            currentRequest = $.ajax({
                type: 'GET',
                url: url || $form.data('url'),
                dataType: 'json',
                data: url ? [] : $form.serialize(),
                beforeSend: function () {
                    $button.addClass('loading')

                    if (currentRequest !== null) {
                        currentRequest.abort()
                    }
                },
                success: ({ error, data }) => {
                    if (!error) {
                        if (url) {
                            const $content = $(`<div>${data}</div>`)
                            $panel.find('.panel__content').find('.loadmore-container').remove()
                            $panel.find('.panel__content').append($content.find('.panel__content p-3').contents())
                        } else {
                            $panel.html(data).addClass('active')
                        }

                        return
                    }

                    $panel.html('').removeClass('active')
                },
                complete: () => {
                    $button.removeClass('loading')
                },
            })
        }
    }

    addToCart(event) {
        event.preventDefault()

        const $currentTarget = $(event.currentTarget)

        $.ajax({
            url: $currentTarget.prop('href'),
            method: 'POST',
            data: {
                id: $currentTarget.data('id'),
            },
            dataType: 'json',
            beforeSend: () => {
                $currentTarget.addClass('loading')
            },
            success: ({ error, message }) => {
                if (error) {
                    NinicoApp.showError(message)
                    return
                }

                this.loadAjaxCart()
                this.$body.find('.tp-cart-toggle').trigger('click')
            },
            error: (error) => NinicoApp.handleError(error),
            complete: () => {
                $currentTarget.removeClass('loading')
            },
        })
    }

    addProductToCart(event) {
        event.preventDefault()

        const $button = $(event.currentTarget)
        const $form = $button.closest('form.cart-form')

        const data = $form.serializeArray()
        data.push({ name: 'checkout', value: $button.prop('name') === 'checkout' ? 1 : 0 })

        $.ajax({
            type: 'POST',
            url: $form.prop('action'),
            data: $.param(data),
            beforeSend: () => {
                $button.addClass('button-loading')
            },
            success: ({ error, message, data }) => {
                if (error) {
                    NinicoApp.showError(message)
                    if (data?.next_url !== undefined) {
                        window.location.href = data.next_url
                    }

                    return
                }

                if (data?.next_url !== undefined) {
                    window.location.href = data.next_url

                    return
                }

                this.$body.find('.tp-cart-toggle').trigger('click')

                this.loadAjaxCart()
            },
            error: (error) => {
                NinicoApp.handleError(error)
            },
            complete: () => {
                $button.removeClass('button-loading')
            },
        })
    }

    addToCompare(event) {
        event.preventDefault()

        const $currentTarget = $(event.currentTarget)

        $.ajax({
            url: $currentTarget.prop('href'),
            method: 'POST',
            beforeSend: () => {
                $currentTarget.addClass('loading')
            },
            success: (response) => {
                const { error, data, message } = response

                if (error) {
                    NinicoApp.showError(message)
                } else {
                    NinicoApp.showSuccess(message)
                    $('.header-cart .tp-product-compare-count').text(data.count)
                }
            },
            error: (error) => {
                NinicoApp.handleError(error)
            },
            complete: () => {
                $currentTarget.removeClass('loading')
            },
        })
    }

    removeFromCompare(event) {
        event.preventDefault()

        const $currentTarget = $(event.currentTarget)

        $.ajax({
            url: $currentTarget.prop('href'),
            method: 'POST',
            data: {
                _method: 'DELETE',
            },
            success: (response) => {
                const { error, data, message } = response

                if (error) {
                    NinicoApp.showError(message)
                } else {
                    NinicoApp.showSuccess(message)
                    $('.header-cart .tp-product-compare-count').text(data.count)
                    $('.compare-area').load(window.location.href + ' .compare-area > *')
                }
            },
            error: (error) => {
                NinicoApp.handleError(error)
            },
        })
    }

    removeFromCart(event) {
        event.preventDefault()

        const $currentTarget = $(event.currentTarget)

        $.ajax({
            url: $currentTarget.data('url'),
            method: 'GET',
            beforeSend: () => {
                $currentTarget.addClass('loading')
            },
            success: (response) => {
                if (response.error) {
                    NinicoApp.showError(response.message)
                    return
                }

                const $cartArea = $('.cart-area')

                if ($cartArea.length && window.siteConfig?.cartUrl) {
                    $cartArea.load(window.siteConfig.cartUrl + ' .cart-area > *')
                }

                this.loadAjaxCart()
            },
            error: (res) => {
                NinicoApp.showError(res.message)
            },
            complete: () => {
                $currentTarget.removeClass('loading')
            },
        })
    }

    addToWishlist(event) {
        event.preventDefault()

        const $currentTarget = $(event.currentTarget)

        $.ajax({
            url: $currentTarget.prop('href'),
            method: 'POST',
            beforeSend: () => {
                $currentTarget.addClass('loading')
            },
            success: (response) => {
                const { error, message, data } = response

                if (error) {
                    NinicoApp.showError(message)
                } else {
                    NinicoApp.showSuccess(message)
                    $('.header-cart .tp-product-wishlist-count').text(data.count)
                    if (data.added) {
                        $currentTarget.find('i').removeClass('fal').addClass('fas')
                    } else {
                        $currentTarget.find('i').removeClass('fas').addClass('fal')
                    }
                }
            },
            error: (error) => {
                NinicoApp.handleError(error)
            },
            complete: () => {
                $currentTarget.removeClass('loading')
            },
        })
    }

    removeFromWishlist(event) {
        event.preventDefault()

        const $currentTarget = $(event.currentTarget)

        $.ajax({
            url: $currentTarget.prop('href'),
            method: 'POST',
            data: {
                _method: 'DELETE',
            },
            success: (response) => {
                if (response.error) {
                    NinicoApp.showError(response.message)
                } else {
                    NinicoApp.showSuccess(response.message)
                    $('.header-cart .tp-product-wishlist-count').text(response.data.count)
                    $('.wishlist-area').load(window.location.href + ' .wishlist-area > *')
                }
            },
            error: (error) => {
                NinicoApp.handleError(error)
            },
        })
    }

    loadAjaxCart() {
        if (window.siteConfig?.ajaxCart) {
            $.ajax({
                url: window.siteConfig.ajaxCart,
                method: 'GET',
                success: (response) => {
                    const { data, error } = response
                    if (!error) {
                        this.$body.find('.tpcartinfo .tpcart__product').html(data.html)
                        this.$body.find('.header-cart .tp-product-count').text(data.count)
                    }
                },
            })
        }
    }

    applyCouponCode(event) {
        event.preventDefault()

        const $currentTarget = $(event.currentTarget)

        const couponCode = $currentTarget.closest('.coupon').find('#coupon_code').val()

        $.ajax({
            url: $currentTarget.data('url'),
            type: 'POST',
            data: {
                coupon_code: couponCode,
            },
            beforeSend: () => {
                $currentTarget.prop('disabled', true).addClass('loading')
            },
            success: (response) => {
                if (!response.error) {
                    $('.cart-area').load(window.location.href + '?applied_coupon=1 .cart-area > *', function () {
                        $currentTarget.prop('disabled', false).removeClass('loading')
                        NinicoApp.showSuccess(response.message)
                    })
                } else {
                    NinicoApp.showError(response.message)
                }
            },
            error: (error) => {
                NinicoApp.handleError(error)
            },
            complete: (response) => {
                if (!(response.status === 200 && !response?.responseJSON?.error)) {
                    $currentTarget.prop('disabled', false).removeClass('loading')
                }
            },
        })
    }

    removeCouponCode(event) {
        event.preventDefault()

        const $currentTarget = $(event.currentTarget)
        const buttonText = $currentTarget.text()

        $currentTarget.text($currentTarget.data('loading-text'))

        $.ajax({
            url: $currentTarget.prop('href'),
            type: 'POST',
            success: (response) => {
                if (!response.error) {
                    $('.cart-area').load(window.location.href + ' .cart-area > *', function () {
                        $currentTarget.text(buttonText)
                    })
                } else {
                    NinicoApp.showError(response.message)
                }
            },
            error: (error) => {
                NinicoApp.handleError(error)
            },
            complete: (response) => {
                if (!(response.status === 200 && !response?.responseJSON?.error)) {
                    $currentTarget.text(buttonText)
                }
            },
        })
    }

    changeCartQuantity(event) {
        const $target = $(event.target)

        const $quantity = $target.parent().find('input')
        const step = parseInt($quantity.attr('step'), 10)
        const min = parseInt($quantity.attr('min'), 10)
        const max = parseInt($quantity.attr('max'), 10)
        const current = parseInt($quantity.val(), 10)

        if ($target.hasClass('cart-minus') && current > min) {
            $quantity.val(current - step)
            $quantity.trigger('change')
        }

        if ($target.hasClass('cart-plus') && current < max) {
            $quantity.val(current + step)
            $quantity.trigger('change')
        }

        this.updateCart(event)
    }

    onChangeQuantityInput(event) {
        const $target = $(event.target)

        const min = parseInt($target.attr('min'), 10)
        const max = parseInt($target.attr('max'), 10)
        const current = parseInt($target.val(), 10)

        if (current < min) {
            $target.val(min)
        }

        if (current > max) {
            $target.val(max)
        }

        this.updateCart(event)
    }

    updateCart(event) {
        event.preventDefault()

        const $form = this.$body.find('.cart-form')

        if (!$form.length) {
            return
        }

        $.ajax({
            type: 'POST',
            cache: false,
            url: $form.prop('action'),
            data: new FormData($form[0]),
            contentType: false,
            processData: false,
            success: (response) => {
                const { error, message } = response

                if (error) {
                    NinicoApp.showError(message)
                } else {
                    $('.cart-area').load(window.siteConfig.cartUrl + ' .cart-area > *')

                    this.loadAjaxCart()

                    NinicoApp.showSuccess(message)
                }
            },
            error: (error) => {
                NinicoApp.handleError(error)
            },
        })
    }

    handleProductsPagination(event) {
        event.preventDefault()

        const url = new URL($(event.currentTarget).attr('href'))
        const page = url.searchParams.get('page')

        this.$productsFilter.find('input[name="page"]').val(page)
        this.filterProducts(this.$productsFilter, page)
    }

    handleProductsSorting(event) {
        const $currentTarget = $(event.currentTarget)

        this.$productsFilter.find('input[name="sort-by"]').val($currentTarget.val()).trigger('change')
    }

    handleProductsPerPage(event) {
        const $currentTarget = $(event.currentTarget)

        this.$productsFilter.find('input[name="per-page"]').val($currentTarget.val()).trigger('change')
    }

    handleProductsLayout(event) {
        const $currentTarget = $(event.currentTarget)

        $currentTarget.addClass('active')
        $currentTarget.siblings().removeClass('active')

        this.$productsFilter.find('input[name="layout"]').val($currentTarget.data('type')).trigger('change')
    }

    filterProducts($form, page = null) {
        if (page) {
            $form.find('input[name=page]').val(page)
        }

        $.ajax({
            url: `${$form.prop('action')}?${$form.serialize()}`,
            type: 'GET',
            beforeSend: () => {
                this.$body.find('.product-filter-mobile').removeClass('active')
                this.$body.find('.loading-spinner').removeClass('d-none')
                $('html, body').animate({
                    scrollTop: $('.product-area').offset().top - 100,
                })
            },
            success: ({ error, message, data, additional }) => {
                this.$body.find('.product-list').html(data)
                this.$body.find('.product-item-count span').text(message)

                if (additional?.breadcrumb) {
                    $('.page-breadcrumbs div').html(additional.breadcrumb)
                }

                if (additional?.filters_html) {
                    const $categoriesFilter = $form
                        .find('.product-categories-filter-widget .product-sidebar__widget')
                        .clone()
                    $form.html(additional.filters_html)

                    $form
                        .find('.product-categories-filter-widget .product-sidebar__widget')
                        .replaceWith($categoriesFilter)

                    this.priceFilter()
                }

                if (!error) {
                    window.history.pushState({}, '', `${window.location.pathname}?${$form.serialize()}`)
                } else {
                    NinicoApp.showError(message || 'Opp!')
                }
            },
            error: (error) => {
                NinicoApp.handleError(error)
            },
            complete: () => {
                this.$body.find('.loading-spinner').addClass('d-none')
            },
        })
    }

    priceFilter() {
        const $sliderRange = $(document).find('#slider-range')

        if ($sliderRange.length) {
            const min = $sliderRange.data('min')
            const max = $sliderRange.data('max')
            const $priceFilter = $(document).find('.price-filter')
            $sliderRange.slider({
                range: true,
                min: min,
                max: max,
                values: [
                    $priceFilter.find('input[name="min_price"]').val(),
                    $priceFilter.find('input[name="max_price"]').val(),
                ],
                slide: function (event, ui) {
                    $priceFilter.find('#amount').text(`${ui.values[0].format_price()} - ${ui.values[1].format_price()}`)
                },
                change: (event, ui) => {
                    $priceFilter.find('input[name="min_price"]').val(ui.values[0])
                    $priceFilter.find('input[name="max_price"]').val(ui.values[1]).trigger('change')
                },
            })

            $priceFilter
                .find('#amount')
                .text(
                    `${$sliderRange.slider('values', 0).format_price()} - ${$sliderRange
                        .slider('values', 1)
                        .format_price()}`
                )
        }
    }

    /**
     @param {jQuery} element
     */
    lightGallery(element) {
        if (element.data('lightGallery')) {
            element.data('lightGallery').destroy(true)
        }

        element.lightGallery({
            selector: 'a',
            thumbnail: true,
            share: false,
            fullScreen: false,
            autoplay: false,
            autoplayControls: false,
            actualSize: false,
        })
    }

    reviewSection() {
        const $reviewListWrapper = this.$body.find('.comment-list')
        const $loadingSpinner = this.$body.find('.loading-spinner')

        if (window.location.hash === '#reviews') {
            $(document).find('.tpproduct-details__reviewers').trigger('click')
        }

        $loadingSpinner.addClass('d-none')

        const $productReviewImages = $('.product-review-images')

        if ($productReviewImages.length > 0) {
            this.lightGallery($productReviewImages)
        }

        const _this = this

        const fetchData = (url, hasAnimation = false) => {
            $.ajax({
                url: url,
                type: 'GET',
                beforeSend: function () {
                    $loadingSpinner.removeClass('d-none')

                    if (hasAnimation) {
                        $('html, body').animate(
                            {
                                scrollTop: `${$('.product-reviews-container').offset().top}px`,
                            },
                            1500
                        )
                    }
                },
                success: function ({ data }) {
                    $reviewListWrapper.html(data)

                    let $galleries = $('.product-reviews-container .review-images')

                    if ($galleries.length > 0) {
                        _this.lightGallery($galleries)
                    }
                },
                complete: function () {
                    $loadingSpinner.addClass('d-none')
                },
            })
        }

        if ($reviewListWrapper.length < 1) {
            return
        }

        fetchData($reviewListWrapper.data('url'))

        $reviewListWrapper.on('click', '.basic-pagination li a', function (e) {
            e.preventDefault()

            const href = $(this).prop('href')

            if (href === '#') {
                return
            }

            fetchData(href, true)
        })

        const imagesReviewBuffer = []
        const setImagesFormReview = function (input) {
            const dT = new ClipboardEvent('').clipboardData || new DataTransfer()
            for (let file of imagesReviewBuffer) {
                dT.items.add(file)
            }
            input.files = dT.files
            loadPreviewImage(input)
        }

        const loadPreviewImage = function (input) {
            let $uploadText = $('.image-upload__text')
            const maxFiles = $(input).data('max-files')
            let filesAmount = input.files.length

            if (maxFiles) {
                if (filesAmount >= maxFiles) {
                    $uploadText.closest('.image-upload__uploader-container').addClass('d-none')
                } else {
                    $uploadText.closest('.image-upload__uploader-container').removeClass('d-none')
                }
                $uploadText.text(filesAmount + '/' + maxFiles)
            } else {
                $uploadText.text(filesAmount)
            }
            const viewerList = $('.image-viewer__list')
            const $template = $('#review-image-template').html()

            viewerList.addClass('is-loading')
            viewerList.find('.image-viewer__item').remove()

            if (filesAmount) {
                for (let i = filesAmount - 1; i >= 0; i--) {
                    viewerList.prepend($template.replace('__id__', i))
                }
                for (let j = filesAmount - 1; j >= 0; j--) {
                    let reader = new FileReader()
                    reader.onload = function (event) {
                        viewerList
                            .find('.image-viewer__item[data-id=' + j + ']')
                            .find('img')
                            .attr('src', event.target.result)
                    }
                    reader.readAsDataURL(input.files[j])
                }
            }
            viewerList.removeClass('is-loading')
        }

        $(document).on('change', '.form-review-product input[type=file]', function (event) {
            event.preventDefault()
            let input = this
            let $input = $(input)
            let maxSize = $input.data('max-size')
            Object.keys(input.files).map(function (i) {
                if (maxSize && input.files[i].size / 1024 > maxSize) {
                    let message = $input
                        .data('max-size-message')
                        .replace('__attribute__', input.files[i].name)
                        .replace('__max__', maxSize)
                    NinicoApp.showError(message)
                } else {
                    imagesReviewBuffer.push(input.files[i])
                }
            })

            let filesAmount = imagesReviewBuffer.length
            const maxFiles = $input.data('max-files')
            if (maxFiles && filesAmount > maxFiles) {
                imagesReviewBuffer.splice(filesAmount - maxFiles - 1, filesAmount - maxFiles)
            }

            setImagesFormReview(input)
        })

        $(document).on('click', '.form-review-product .image-viewer__icon-remove', function (event) {
            event.preventDefault()
            const $this = $(event.currentTarget)
            let id = $this.closest('.image-viewer__item').data('id')
            imagesReviewBuffer.splice(id, 1)

            let input = $('.form-review-product input[type=file]')[0]
            setImagesFormReview(input)
        })

        $(document).on('submit', '.form-review-product', function (e) {
            e.preventDefault()
            e.stopPropagation()

            const $form = $(e.currentTarget)
            const $button = $form.find('button[type=submit]')

            $.ajax({
                type: 'POST',
                cache: false,
                url: $form.prop('action'),
                data: new FormData($form[0]),
                contentType: false,
                processData: false,
                beforeSend: () => {
                    $button.prop('disabled', true).addClass('button-loading')
                },
                success: ({ error, message }) => {
                    if (!error) {
                        $form.find('input[type=file]').val('')
                        $form.find('textarea').val('')
                        imagesReviewBuffer.splice(0, imagesReviewBuffer.length)
                        loadPreviewImage($form.find('input[type=file]')[0])
                        NinicoApp.showSuccess(message)
                        fetchData($reviewListWrapper.data('url'))

                        return
                    }

                    NinicoApp.showError(message)
                },
                error: function (error) {
                    NinicoApp.handleError(error)
                },
                complete: () => {
                    $button.prop('disabled', false).removeClass('button-loading')
                },
            })
        })
    }

    quickView(event) {
        event.preventDefault()

        const $this = $(event.currentTarget)

        $.ajax({
            url: $this.prop('href'),
            type: 'GET',
            beforeSend: () => {
                $this.addClass('loading')
            },
            success: ({ data }) => {
                $('#quick-view-popup').html(data)

                $.magnificPopup.open({
                    items: {
                        src: '#quick-view-popup',
                    },
                    type: 'inline',
                })

                $('.thumbnails .images').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: true,
                    arrows: true,
                    adaptiveHeight: false,
                    rtl: NinicoApp.isRtl(),
                })
            },
            error: function (error) {
                NinicoApp.handleError(error)
            },
            complete: () => {
                $this.removeClass('loading')
            },
        })
    }

    quickShop(event) {
        event.preventDefault()

        const $this = $(event.currentTarget)

        $.ajax({
            url: $this.prop('href'),
            type: 'GET',
            beforeSend: () => {
                $this.addClass('loading')
            },
            success: ({ data }) => {
                $('#quick-shop-popup').html(data)

                $.magnificPopup.open({
                    items: {
                        src: '#quick-shop-popup',
                    },
                    type: 'inline',
                })
            },
            error: function (error) {
                NinicoApp.handleError(error)
            },
            complete: () => {
                $this.removeClass('loading')
            },
        })
    }
}

$(() => {
    const AppEcommerce = new Ecommerce()

    AppEcommerce.init()

    setTimeout(function () {
        const $productGalleryWrapper = $('.product-gallery__wrapper')

        if ($productGalleryWrapper.length && !$productGalleryWrapper.width()) {
            AppEcommerce.productGallery($('.product-gallery'))
        }
    }, 1500)
})
