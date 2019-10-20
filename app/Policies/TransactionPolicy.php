<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\User        $user
     * @param \App\Models\Transaction $transaction
     * @param \App\Models\Account     $account
     *
     * @return bool
     */
    public function show(User $user, Transaction $transaction, Account $account): bool
    {
        return $transaction->account->is($account);
    }

    /**
     * @param \App\Models\User        $user
     * @param \App\Models\Transaction $transaction
     * @param \App\Models\Account     $account
     *
     * @return bool
     */
    public function update(User $user, Transaction $transaction, Account $account): bool
    {
        return $transaction->account->is($account);
    }

    /**
     * @param \App\Models\User        $user
     * @param \App\Models\Transaction $transaction
     * @param \App\Models\Account     $account
     *
     * @return mixed
     */
    public function delete(User $user, Transaction $transaction, Account $account)
    {
        return $transaction->account->is($account);
    }
}
