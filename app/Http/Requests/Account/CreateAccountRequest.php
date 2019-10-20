<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class CreateAccountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
        ];
    }
}
