<?php

namespace App\Services;

use App\Models\Account;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AccountService implements ModelServiceContract
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): Collection
    {
        /** @var \Illuminate\Database\Eloquent\Collection $accounts */
        $accounts = Account::all();

        return $accounts;
    }

    /**
     * @param array $parameters
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $parameters): Model
    {
        $account = new Account($parameters);
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $account->save();
        $account->user()->save($user);

        return $account;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $account
     * @param array                               $parameters
     */
    public function update(Model $account, array $parameters): void
    {
        $account->fill($parameters);
        $account->save();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $account
     *
     * @throws \Exception
     */
    public function delete(Model $account): void
    {
        $account->delete();
    }
}
