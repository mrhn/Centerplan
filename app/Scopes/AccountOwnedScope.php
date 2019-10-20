<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class AccountOwnedScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereHas('users', function (Builder $builder) {
            /** @var \App\Models\User $user */
            $user = \Auth::user();

            $builder->where('id', $user->id);
        });
    }
}
