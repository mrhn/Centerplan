<?php

namespace Tests\Feature\Transaction;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

/**
 * @internal
 */
final class TransactionIndexTest extends TestCase
{
    public function test_index_transactions(): void
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();
        $user->accounts()->save($account);

        $transactions = factory(Transaction::class, 3)->create(['account_id' => $account->id]);

        $randomAccount = factory(Account::class)->create();
        $transactionsNotOwned = factory(Transaction::class, 3)->create(['account_id' => $randomAccount->id]);

        $response = $this->actingAs($user)
            ->json(
                'GET',
                route('transactions.index', ['account' => $account->id])
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_OK);

        $response->assertJsonCount(3, 'data');

        $response->assertJson([
            'data' => $transactions->map(function (Transaction $transaction): array {
                return [
                    'id' => $transaction->id,
                    'executed_at' => $transaction->executed_at->toJSON(),
                    'description' => $transaction->description,
                    'type' => $transaction->type,
                    'amount' => $transaction->amount,
                ];
            })->all(),
        ]);
    }

    public function test_index_transactions_empty(): void
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();
        $user->accounts()->save($account);

        $response = $this->actingAs($user)
            ->json(
                'GET',
                route('transactions.index', ['account' => $account->id])
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_OK);

        $response->assertJson([
            'data' => [],
        ]);
    }

    public function test_index_transactions_account_not_found(): void
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->json(
                'GET',
                route('transactions.index', ['account' => 42])
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

    public function test_index_transaction_unauthenticated(): void
    {
        $response = $this->json(
            'GET',
            route('transactions.index', ['account' => 42])
        )
        ;

        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);

        $response->assertJson(
            [
                'status' => 'fail',
                'message' => 'Unauthenticated.',
            ]
        );
    }
}
