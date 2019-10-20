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
final class TransactionDeleteTest extends TestCase
{
    public function test_delete_transaction(): void
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();
        $user->accounts()->save($account);

        $transaction = factory(Transaction::class)->create(['account_id' => $account->id]);

        $response = $this->actingAs($user)
            ->json(
                'DELETE',
                route('transactions.destroy', ['account' => $account->id, 'transaction' => $transaction->id])
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing(
            'transactions',
            [
                'id' => $transaction->id,
            ]
        );
    }

    public function test_delete_transaction_not_found(): void
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();
        $user->accounts()->save($account);

        $response = $this->actingAs($user)
            ->json(
                'DELETE',
                route('transactions.destroy', ['account' => $account->id, 'transaction' => 42])
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND);

        $response->assertJson([
            'status' => 'fail',
            'message' => 'Model not found.',
        ]);
    }

    public function test_delete_transaction_account_not_found(): void
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();
        $user->accounts()->save($account);

        $transaction = factory(Transaction::class)->create(['account_id' => $account->id]);

        $response = $this->actingAs($user)
            ->json(
                'DELETE',
                route('transactions.destroy', ['account' => 42, 'transaction' => $transaction->id])
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND);

        $response->assertJson([
            'status' => 'fail',
            'message' => 'Model not found.',
        ]);
    }

    public function test_delete_transaction_unauthenticated(): void
    {
        $account = factory(Account::class)->create();
        $transaction = factory(Transaction::class)->create(['account_id' => $account->id]);

        $response = $this->json(
            'DELETE',
            route('transactions.destroy', ['account' => 42, 'transaction' => $transaction->id])
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
