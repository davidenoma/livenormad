class NinicoApp {
    static handleError(data) {
        if (typeof data.errors !== 'undefined' && data.errors.length) {
            this.handleValidationError(data.errors)
        } else if (typeof data.responseJSON !== 'undefined') {
            if (typeof data.responseJSON.errors !== 'undefined') {
                if (data.status === 422) {
                    this.handleValidationError(data.responseJSON.errors)
                }
            } else if (typeof data.responseJSON.message !== 'undefined') {
                this.showError(data.responseJSON.message)
            } else {
                $.each(data.responseJSON, (index, el) => {
                    $.each(el, (key, item) => {
                        this.showError(item)
                    })
                })
            }
        } else {
            this.showError(data.statusText)
        }
    }

    static handleValidationError(errors) {
        let message = ''

        $.each(errors, (index, item) => {
            if (message !== '') {
                message += '<br />'
            }
            message += item
        })
        this.showError(message)
    }

    static showError(message) {
        toastr.error(message)
    }

    static showSuccess(message) {
        toastr.success(message)
    }

    static isRtl() {
        return document.body.dir === 'rtl'
    }
}

$(document).ready(() => {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').prop('content'),
        },
    })

    toastr.options = {
        positionClass: 'toast-bottom-right',
    }

    window.NinicoApp = NinicoApp

    let $categoryLabel = $('.product-category-label .text')

    $(document).on('change', '.product-category-select', function () {
        $categoryLabel.text($.trim($(this).find('option:selected').text()))
    })
})
