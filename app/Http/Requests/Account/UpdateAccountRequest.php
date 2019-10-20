<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['string'],
        ];

        if ('PUT' === $this->getMethod()) {
            $rules['name'][] = 'required';
        }

        return $rules;
    }
}
