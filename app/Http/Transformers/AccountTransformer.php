<?php

namespace App\Http\Transformers;

use App\Models\Account;
use League\Fractal\TransformerAbstract;

class AccountTransformer extends TransformerAbstract
{
    /**
     * @param \App\Models\Account $account
     *
     * @return array
     */
    public function transform(Account $account): array
    {
        return [
            'id' => $account->id,
            'name' => $account->name,
        ];
    }
}
