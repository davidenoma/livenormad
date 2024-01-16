<section class="track-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-sm-12">
                <div class="tptrack__product mb-40">
                    <div class="tptrack__thumb">
                        <img src="{{ RvMedia::getImageUrl(theme_option('login_background') ?: RvMedia::getDefaultImage()) }}" alt="{{ __('Login') }}">
                    </div>
                    <div class="tptrack__content grey-bg-3">
                        <div class="tptrack__item d-flex mb-20">
                            <div class="tptrack__item-icon">
                                <i class="fal fa-lock"></i>
                            </div>
                            <div class="tptrack__item-content">
                                <h4 class="tptrack__item-title">{{ __('Login Here') }}</h4>
                                <p>{{ __('Your personal data will be used to support your experience throughout this website, to manage access to your account.') }}</p>
                            </div>
                        </div>
                        <form action="{{ route('customer.login.post') }}" method="post">
                            @csrf
                            <div class="tptrack__id mb-15">
                                <div>
                                    <span><i class="fal fa-user"></i></span>
                                    <input type="email" name="email" placeholder="{{ __('Email address') }}" value="{{ old('email') }}">
                                </div>
                                @error('email')
                                <div class="tp-invalid">{{ $errors->first('email') }}</div>
                                @enderror
                            </div>
                            <div class="tptrack__email mb-15">
                                <div>
                                    <span><i class="fal fa-key"></i></span>
                                    <input type="password" name="password" placeholder="{{ __('Password') }}">
                                </div>
                                @error('password')
                                <div class="tp-invalid">{{ $errors->first('password') }}</div>
                                @enderror
                            </div>
                            <div class="tpsign__remember d-flex align-items-center justify-content-between mb-15">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
                                </div>
                                <div class="tpsign__pass">
                                    <a href="{{ route('customer.password.reset') }}">{{ __('Forgot Password?') }}</a>
                                </div>
                            </div>
                            <div class="tptrack__btn">
                                <button type="submit" class="tptrack__submition">{{ __('Login Now') }} <i class="fal fa-long-arrow-right"></i></button>
                            </div>
                        </form>

                        <div class="text-center tpsign__account mt-3">
                            <a href="{{ route('customer.register') }}">{{ __("Don't have an account?") }}</a>
                        </div>

                        {!! apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, \Botble\Ecommerce\Models\Customer::class) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
