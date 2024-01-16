<?php

namespace Botble\SalePopup\Http\Requests\Settings;

use Botble\SalePopup\Support\SalePopupHelper;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class SalePopupSettingRequest extends Request
{
    public function rules(): array
    {
        $salePopupHelper = app(SalePopupHelper::class);

        return [
            'enabled' => ['sometimes', 'required', 'boolean'],
            'collection_id' => ['sometimes', 'required', 'string'],
            'purchased_text' => ['required', 'string'],
            'verified_text' => ['required', 'string'],
            'quick_view_text' => ['required', 'string'],
            'list_users_purchased' => ['required', 'string'],
            'show_time_ago_suggest' => ['sometimes', 'required', 'boolean'],
            'list_sale_time' => ['required', 'string'],
            'limit_products' => ['sometimes', 'required', 'numeric'],
            'show_verified' => ['sometimes', 'required', 'boolean'],
            'show_close_button' => ['sometimes', 'required', 'boolean'],
            'show_quick_view_button' => ['sometimes', 'required', 'boolean'],
            'display_pages' => ['sometimes', 'required', 'array', Rule::in(array_keys($salePopupHelper->displayPages()))],
        ];
    }
}
