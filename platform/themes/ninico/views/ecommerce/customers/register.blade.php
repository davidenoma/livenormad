<section class="track-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-sm-12">
                <div class="tptrack__product mb-40">
                    <div class="tptrack__thumb">
                        <img src="{{ RvMedia::getImageUrl(theme_option('register_background') ?: RvMedia::getDefaultImage()) }}" alt="{{ __('Sign Up') }}">
                    </div>
                    <div class="tptrack__content grey-bg-3">
                        <div class="tptrack__item d-flex mb-20">
                            <div class="tptrack__item-icon">
                                <i class="fal fa-user-plus"></i>
                            </div>
                            <div class="tptrack__item-content">
                                <h4 class="tptrack__item-title">{{ __('Sign Up') }}</h4>
                                <p>{{ __('Your personal data will be used to support your experience throughout this website, to manage access to your account.') }}</p>
                            </div>
                        </div>
                        <form action="{{ route('customer.register.post') }}" method="post">
                            @csrf
                            <div class="tptrack__id mb-10">
                                <div>
                                    <span><i class="fal fa-user"></i></span>
                                    <input type="text" name="name" placeholder="{{ __('Name') }}" value="{{ old('name') }}">
                                </div>
                                @error('name')
                                <div class="tp-invalid">{{ $errors->first('name') }}</div>
                                @enderror
                            </div>
                            <div class="tptrack__id mb-10">
                                <div>
                                    <span><i class="fal fa-envelope"></i></span>
                                    <input type="email" name="email" placeholder="{{ __('Email address') }}" value="{{ old('email') }}">
                                </div>
                                @error('email')
                                <div class="tp-invalid">{{ $errors->first('email') }}</div>
                                @enderror
                            </div>
                            <div class="tptrack__email mb-10">
                                <div>
                                    <span><i class="fal fa-key"></i></span>
                                    <input type="password" name="password" placeholder="{{ __('Password') }}">
                                </div>
                                @error('password')
                                <div class="tp-invalid">{{ $errors->first('password') }}</div>
                                @enderror
                            </div>
                            <div class="tptrack__id mb-10">
                                <div>
                                    <span><i class="fal fa-key"></i></span>
                                    <input type="password" name="password_confirmation" placeholder="{{ __('Password confirmation') }}">
                                </div>
                                @error('password_confirmation')
                                    <div class="tp-invalid">{{ $errors->first('password_confirmation') }}</div>
                                @enderror
                            </div>
                            @if (is_plugin_active('captcha'))
                                @if(Captcha::isEnabled() && get_ecommerce_setting('enable_recaptcha_in_register_page', 0))
                                    <div class="tptrack__id mb-10">
                                        {!! Captcha::display() !!}

                                        @error('captcha')
                                            <div class="tp-invalid">{{ $errors->first('captcha') }}</div>
                                        @enderror
                                    </div>
                                @endif

                                @if (Captcha::mathCaptchaEnabled() && get_ecommerce_setting('enable_math_captcha_in_register_page', 0))
                                    <div class="tptrack__id mb-10">
                                        <div>
                                            <span><i class="fal fa-calculator"></i></span>
                                            {!! app('math-captcha')->input(['class' => 'form-control', 'id' => 'math-group', 'placeholder' => app('math-captcha')->label()]) !!}
                                        </div>
                                        @error('math-captcha')
                                            <div class="tp-invalid">{{ $errors->first('math-captcha') }}</div>
                                        @enderror
                                    </div>
                                @endif
                            @endif

                            <p class="small">{{ __('Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our privacy policy.') }}</p>

                            <div class="tpsign__remember d-flex align-items-center justify-content-between mb-15">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" @checked(old('agree_terms_and_policy', true)) id="agree_terms_and_policy" name="agree_terms_and_policy">
                                    <label class="form-check-label" for="agree_terms_and_policy">{{ __('I agree to terms & Policy.') }}</label>
                                    @error('agree_terms_and_policy')
                                    <div class="tp-invalid">{{ $errors->first('agree_terms_and_policy') }}</div>
                                    @enderror
                                </div>

                                <div class="tpsign__pass">
                                    <a href="{{ route('customer.login') }}">{{ __('Already Have Account?') }}</a>
                                </div>
                            </div>

                            <div class="tptrack__btn">
                                <button type="submit" class="tptrack__submition">
                                    {{ __('Register Now') }} <i class="fal fa-long-arrow-right"></i>
                                </button>
                            </div>
                        </form>

                        {!! apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, \Botble\Ecommerce\Models\Customer::class) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
