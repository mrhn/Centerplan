<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class TransactionService implements ModelServiceContract
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
     * @param array                    $parameters
     * @param null|\App\Models\Account $account
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $parameters, Account $account = null): Model
    {
        if (!$account) {
            throw new \InvalidArgumentException('Account is required for transaction.');
        }

        $transaction = new Transaction($parameters);

        $transaction->account()->associate($account);
        $transaction->save();

        return $transaction;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array                               $parameters
     */
    public function update(Model $model, array $parameters): void
    {
        $model->fill($parameters);
        $model->save();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $transaction
     *
     * @throws \Exception
     */
    public function delete(Model $transaction): void
    {
        $transaction->delete();
    }
}
