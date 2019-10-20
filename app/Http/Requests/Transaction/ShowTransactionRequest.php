<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ShowTransactionRequest.
 *
 * @property \App\Models\Transaction $transaction
 * @property \App\Models\Account     $account
 */
class ShowTransactionRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        /** @var \App\Models\User $user */
        $user = $this->user();

        return $user->can('show', [$this->transaction, $this->account]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
