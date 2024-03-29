<div
    class="form-group mb-3 variant-radio product-option product-option-{{ Str::slug($option->name) }} product-option-{{ $option->id }}"
    style="margin-bottom: 10px"
>
    <div class="product-option-item-wrapper">
        <div class="product-option-item-label">
            <label class="{{ $option->required ? 'required' : '' }} form-label">
                {{ $option->name }}
            </label>
        </div>
        <div class="product-option-item-values">
            <input
                name="options[{{ $option->id }}][option_type]"
                type="hidden"
                value="radio"
            />
            @foreach ($option->values as $value)
                @php
                    $price = 0;
                    if (!empty($value->affect_price) && doubleval($value->affect_price) > 0) {
                        $price = $value->affect_type == 0 ? $value->affect_price : (floatval($value->affect_price) * $product->front_sale_price_with_taxes) / 100;
                    }
                @endphp
                <div class="form-radio">
                    <input
                        id="option-{{ $option->id }}-value-{{ Str::slug($value->option_value) }}"
                        name="options[{{ $option->id }}][values]"
                        data-extra-price="{{ $price }}"
                        type="radio"
                        value="{{ $value->option_value }}"
                        @if ($option->required && $loop->first) checked @endif
                        class="form-check-input"
                    >
                    <label class="form-label" for="option-{{ $option->id }}-value-{{ Str::slug($value->option_value) }}" class="form-check-label">
                        &nbsp;{{ $value->option_value }}
                        @if ($price > 0)
                            <strong class="extra-price">+ {{ format_price($price) }}</strong>
                        @endif
                    </label>
                </div>
            @endforeach
        </div>
    </div>
</div>
