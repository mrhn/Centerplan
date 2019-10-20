<?php

namespace Tests\Feature\Account;

use App\Enums\TransactionTypes;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

/**
 * @internal
 */
final class AccountIndexTest extends TestCase
{
    public function test_index_accounts(): void
    {
        $user = factory(User::class)->create();
        /** @var \Illuminate\Support\Collection $accounts */
        $accounts = factory(Account::class, 3)->create();

        $accounts->each(function (Account $account) use ($user): void {
            $account->users()->save($user);
        });

        $accountsNotOwned = factory(Account::class, 3)->create();

        $response = $this->actingAs($user)
            ->json(
                'GET',
                route('accounts.index')
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_OK);

        $response->assertJsonCount(3, 'data');

        $response->assertJson([
            'data' => $accounts->map(function (Account $account): array {
                return [
                    'id' => $account->id,
                    'name' => $account->name,
                ];
            })->all(),
        ]);
    }

    public function test_index_account_account_balance(): void
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();
        $user->accounts()->save($account);

        // Credit is 7200 and debit is 2700, account balance should be 7200 - 2700 = 4500
        $creditTransactions = factory(Transaction::class, 2)
            ->create([
                'type' => TransactionTypes::CREDIT,
                'account_id' => $account->id,
                'amount' => 3600,
            ])
        ;

        $debitTransactions = factory(Transaction::class, 3)
            ->create([
                'type' => TransactionTypes::DEBIT,
                'account_id' => $account->id,
                'amount' => 900,
            ])
        ;

        $response = $this->actingAs($user)
            ->json(
                'GET',
                route('accounts.index').'?balance=1'
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_OK);

        $response->assertJson([
            'data' => [
                [
                    'balance' => 4500,
                ],
            ],
        ]);
    }

    public function test_index_accounts_empty(): void
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->json(
                'GET',
                route('accounts.index')
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_OK);

        $response->assertJson([
            'data' => [],
        ]);
    }

    public function test_index_account_unauthenticated(): void
    {
        $response = $this->json(
            'GET',
            route('accounts.index')
        );

        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);

        $response->assertJson(
            [
                'status' => 'fail',
                'message' => 'Unauthenticated.',
            ]
        );
    }
}
