<?php

namespace App\Http\Transformers;

use App\Models\Account;
use League\Fractal\TransformerAbstract;

class AccountTransformer extends TransformerAbstract
{
    /**
     * @var bool
     */
    private $showAccountBalance = false;

    /**
     * @param bool $show
     */
    public function showAccountBalance(bool $show = true): void
    {
        $this->showAccountBalance = $show;
    }

    /**
     * @param \App\Models\Account $account
     *
     * @return array
     */
    public function transform(Account $account): array
    {
        $transform = [
            'id' => $account->id,
            'name' => $account->name,
        ];

        if ($this->showAccountBalance) {
            $transform['balance'] = $account->balance;
        }

        return $transform;
    }
}
