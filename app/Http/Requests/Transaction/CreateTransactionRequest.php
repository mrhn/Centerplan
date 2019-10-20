<?php

namespace App\Http\Requests\Transaction;

use App\Enums\TransactionTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\In;

class CreateTransactionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'executed_at' => ['date', 'nullable'],
            'description' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', new In([TransactionTypes::CREDIT, TransactionTypes::DEBIT])],
            'amount' => ['required', 'numeric',  'min:0'],
        ];
    }
}
