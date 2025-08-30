<?php

namespace App\Http\Requests\Product;

use App\Constants\AppConstant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::in(AppConstant::order_status())],
        ];
    }
}
