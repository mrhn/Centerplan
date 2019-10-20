<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class TransactionService extends ModelService
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): Collection
    {
        /** @var \Illuminate\Database\Eloquent\Collection $transactions */
        $transactions = Transaction::all();

        return $transactions;
    }

    /**
     * @param array               $parameters
     * @param \App\Models\Account $account
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $parameters, Account $account): Model
    {
        $transaction = new Transaction($parameters);

        $transaction->account()->associate($account);
        $transaction->save();

        return $transaction;
    }

    /**
     * @param \App\Models\Transaction $transaction
     * @param array                   $parameters
     */
    public function update(Transaction $transaction, array $parameters): void
    {
        $transaction->fill($parameters);
        $transaction->save();
    }

    /**
     * @param \App\Models\Transaction $transaction
     *
     * @throws \Exception
     */
    public function delete(Transaction $transaction): void
    {
        $transaction->delete();
    }
}
