<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EditProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'price' => ['required', 'numeric', 'min:1'],
            'stock' => ['required', 'numeric', 'min:1'],
            'description' => 'required',
            'category' => 'required',
        ];
    }
}
