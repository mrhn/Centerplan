<?php

namespace App\Http\Requests;

use App\Enums\TransactionTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\In;

class UpdateTransactionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'executed_at' => ['date', 'nullable'],
            'description' => ['string', 'max:255'],
            'type' => ['string', new In([TransactionTypes::CREDIT, TransactionTypes::DEBIT])],
            'amount' => ['numeric'],
        ];

        if ('PUT' === $this->getMethod()) {
            $rules['description'][] = 'required';
            $rules['type'][] = 'required';
            $rules['amount'][] = 'required';
        }

        return $rules;
    }
}