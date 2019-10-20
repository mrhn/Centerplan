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
final class AccountShowTest extends TestCase
{
    public function test_show_account()
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();

        $response = $this->actingAs($user)
            ->json(
                'GET',
                route('accounts.show', ['account' => $account->id])
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_OK);

        $response->assertJson([
            'data' => [
                'id' => $account->id,
                'name' => $account->name,
            ],
        ]);
    }

    public function test_show_account_account_balance(): void
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();

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
                route('accounts.show', ['account' => $account->id])
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_OK);

        $response->assertJson([
            'data' => [
                'balance' => 4500,
            ],
        ]);
    }

    public function test_show_account_not_found()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->json(
                'GET',
                route('accounts.show', ['account' => 42])
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND);

        $response->assertJson(
            [
                'status' => 'fail',
                'message' => 'Model not found.',
            ]
        );
    }

    public function test_show_account_unauthenticated()
    {
        $response = $this->json(
            'GET',
            route('accounts.show', ['account' => 42])
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
