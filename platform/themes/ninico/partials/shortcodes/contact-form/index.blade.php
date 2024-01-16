<x-core::form :url="route('public.send.contact')" method="post" class="contact-form">
    {!! apply_filters('pre_contact_form', null) !!}

    <div class="row">
        <div class="col-md-6">
            <div class="tpcontact__input mb-20">
                <input name="name" id="contact_name" type="text" placeholder="{{ __('Name') }}" required value="{{ old('name') }}" placeholder="{{ __('Name') }}">
            </div>
        </div>

        <div class="col-md-6">
            <div class="tpcontact__input mb-20">
                <input name="email" id="contact_email" type="email" placeholder="{{ __('Email') }}" required value="{{ old('email') }}" placeholder="{{ __('Email') }}">
            </div>
        </div>

        <div class="col-md-6">
            <div class="tpcontact__input mb-20">
                <input name="address" id="contact_address" type="text" placeholder="{{ __('Address') }}" required value="{{ old('address') }}" placeholder="{{ __('Address') }}">
            </div>
        </div>

        <div class="col-md-6">
            <div class="tpcontact__input mb-20">
                <input name="phone" id="contact_phone" type="text" placeholder="{{ __('Phone') }}" required value="{{ old('phone') }}" placeholder="{{ __('Phone') }}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="tpcontact__input mb-20">
                <input name="subject" id="contact_subject" type="text" placeholder="{{ __('Subject') }}" required value="{{ old('subject') }}" placeholder="{{ __('Subject') }}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="tpcontact__input mb-20">
                <textarea name="content" id="contact_content" rows="5" placeholder="{{ __('Message') }}" required value="{{ old('content') }}" placeholder="{{ __('Message') }}"></textarea>
            </div>
        </div>
    </div>

    @if (is_plugin_active('captcha'))
        @if (Captcha::reCaptchaEnabled())
            {!! Captcha::display() !!}
        @endif

        @if (Captcha::mathCaptchaEnabled() && setting('enable_math_captcha_for_contact_form', 0))
            <div class="tpcontact__input mb-20">
                <label class="form-label required" for="math-group">{{ app('math-captcha')->label() }}</label>
                {!! app('math-captcha')->input(['id' => 'math-group']) !!}
            </div>
        @endif
    @endif

    {!! apply_filters('after_contact_form', null) !!}

    <div class="tpcontact__submit">
        <button type="submit" class="tp-btn tp-color-btn tp-wish-cart">
            {{ __('Send') }}
            <i class="fal fa-long-arrow-right"></i>
        </button>
    </div>

    <div class="mt-5">
        <div
            class="contact-message contact-success-message"
            style="display: none"
        ></div>
        <div
            class="contact-message contact-error-message"
            style="display: none"
        ></div>
    </div>
</x-core::form>
