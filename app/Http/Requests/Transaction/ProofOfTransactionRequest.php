<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class ProofOfTransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'proof_of_transaction' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ];
    }
}
