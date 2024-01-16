<section class="contact-area mb-40">
    <div class="row">
        <div class="col-lg-4 col-12">
            <div class="tpcontact__right mb-40">
                <div class="tpcontact__shop mb-30">
                    <h4 class="tpshop__title mb-25">{!! BaseHelper::clean($shortcode->title) !!}</h4>
                    <div class="tpshop__info">
                        <ul>
                            @if($address = $shortcode->address)
                                <li>
                                    <i class="fal fa-map-marker-alt"></i>
                                    <a href="https://maps.google.com/maps?q={{ addslashes($address) }}" target="_blank">{{ $address }}</a>
                                </li>
                            @endif
                            @if($phone = $shortcode->phone)
                                <li>
                                    <i class="fal fa-phone-alt"></i>
                                    <a href="tel:{{ $phone }}">{{ $phone }}</a>
                                </li>
                            @endif
                            @if($email = $shortcode->email)
                                <li>
                                    <i class="fal fa-envelope"></i>
                                    <a href="mailto:{{ $email }}">{{ $email }}</a>
                                </li>
                            @endif
                            @if($hours = $shortcode->hours)
                                <li>
                                    <i class="fal fa-clock"></i>
                                    <span>{{ __('Store Hours:') }}</span>
                                    <span>{{ $hours }}</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="tpcontact__support">
                    @foreach(range(1, 2) as $i)
                        @if($label = $shortcode->{'button_label_' . $i})
                            <a href="{{ $shortcode->{'button_url_' . $i} }}">
                                {{ $label }}
                                @if($icon = $shortcode->{'button_icon_' . $i})
                                    <i class="{{ $icon }}"></i>
                                @endif
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        @if ($shortcode->show_contact_form && is_plugin_active('contact'))
            <div class="col-lg-8 col-12">
                <div class="tpcontact__form">
                    <div class="tpcontact__info mb-35">
                        <h4 class="tpcontact__title">{{ __('Make Custom Request') }}</h4>
                        <p>{{ __('Must-have pieces selected every month want style Ideas and Treats?') }}</p>
                    </div>
                    <form action="{{ route('public.send.contact') }}" id="contact-form" method="POST">
                        @csrf

                        {!! apply_filters('pre_contact_form', null) !!}

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="tpcontact__input mb-20">
                                    <input name="name" type="text" placeholder="{{ __('Full name') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="tpcontact__input mb-20">
                                    <input name="email" type="email" placeholder="{{ __('Email address') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="tpcontact__input mb-20">
                                    <input name="phone" type="text" placeholder="{{ __('Phone number') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="tpcontact__input mb-20">
                                    <input name="subject" type="text" placeholder="{{ __('Subject') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="tpcontact__input mb-20">
                                    <textarea name="content" placeholder="{{ __('Enter message') }}" required></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                @if (is_plugin_active('captcha'))
                                    @if (setting('enable_captcha'))
                                        <div class="tpcontact__input mb-30">
                                            {!! Captcha::display() !!}
                                        </div>
                                    @endif

                                    @if (setting('enable_math_captcha_for_contact_form', false))
                                        <div class="tpcontact__input mb-30">
                                            <label class="form-label" for="math-group">{{ app('math-captcha')->label() }}</label>
                                            {!! app('math-captcha')->input(['id' => 'math-group', 'placeholder' => app('math-captcha')->getMathLabelOnly() . ' = ?']) !!}
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>

                        {!! apply_filters('after_contact_form', null) !!}

                        <div class="tpcontact__submit">
                            <button type="submit" class="tp-btn tp-color-btn tp-wish-cart">
                                {{ __('Get A Quote') }}
                                <i class="fal fa-long-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</section>
