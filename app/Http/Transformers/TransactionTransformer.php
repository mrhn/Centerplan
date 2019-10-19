<?php

namespace App\Http\Transformers;

use App\Models\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * @param \App\Models\Transaction $account
     *
     * @return array
     */
    public function transform(Transaction $account)
    {
        return [
            'id' => $account->id,
        ];
    }
}
