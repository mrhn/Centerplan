<?php

namespace App\Http\Transformers;

use App\Models\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * @param \App\Models\Transaction $transaction
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'id' => $transaction->id,
            'executed_at' => $transaction->executed_at,
            'description' => $transaction->description,
            'type' => $transaction->type,
            'amount' => $transaction->amount,
        ];
    }
}
