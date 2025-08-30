<?php

namespace App\Http\Requests\Product;

use App\Constants\AppConstant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'payment_method' => ['required', 'string', Rule::in(AppConstant::payment_status())],
            'shipping_address' => ['required', 'string'],
            'billing_address' => ['required', 'string'],
        ];
    }
}
