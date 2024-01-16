<section class="track-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-sm-12">
                <div class="tptrack__product mb-40">
                    <div class="tptrack__content grey-bg-3">
                        <div class="tptrack__item d-flex mb-20">
                            <div class="tptrack__item-icon">
                                <i class="fal fa-lock"></i>
                            </div>
                            <div class="tptrack__item-content">
                                <h4 class="tptrack__item-title">{{ __('Forgot password') }}</h4>
                                <p>{{ __('Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.') }}</p>
                            </div>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (session('success_msg'))
                            <div class="alert alert-success">
                                {{ session('success_msg') }}
                            </div>
                        @endif

                        @if (session('error_msg'))
                            <div class="alert alert-danger">
                                {{ session('error_msg') }}
                            </div>
                        @endif

                        <form action="{{ route('customer.password.request') }}" method="post">
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
                            <div class="tptrack__btn">
                                <button type="submit" class="tptrack__submition">{{ __('Send Password Reset Link') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
