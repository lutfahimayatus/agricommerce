<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'address' => 'required|string|max:100',
            'shipping_cost_id' => 'required|exists:shipping_costs,id,deleted_at,NULL',
        ];
    }
}
