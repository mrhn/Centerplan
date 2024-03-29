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
final class TransactionShowTest extends TestCase
{
    public function test_show_transaction(): void
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();
        $user->accounts()->save($account);

        /** @var Transaction $transaction */
        $transaction = factory(Transaction::class)->create(['account_id' => $account->id]);

        $response = $this->actingAs($user)
            ->json(
                'GET',
                route(
                    'transactions.show',
                    [
                        'account' => $account->id,
                        'transaction' => $transaction->id,
                    ]
                )
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_OK);

        $response->assertJson([
            'data' => [
                'id' => $transaction->id,
                'executed_at' => $transaction->executed_at->toJSON(),
                'description' => $transaction->description,
                'type' => $transaction->type,
                'amount' => $transaction->amount,
            ],
        ]);
    }

    public function test_show_transaction_not_owned_by_account(): void
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();
        $user->accounts()->save($account);

        $randomAccount = factory(Account::class)->create();

        /** @var Transaction $transaction */
        $transaction = factory(Transaction::class)->create(['account_id' => $randomAccount->id]);

        $response = $this->actingAs($user)
            ->json(
                'GET',
                route(
                    'transactions.show',
                    [
                        'account' => $account->id,
                        'transaction' => $transaction->id,
                    ]
                )
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

    public function test_show_transaction_not_linked_by_account(): void
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();
        $user->accounts()->save($account);

        $randomAccount = factory(Account::class)->create();
        $user->accounts()->save($randomAccount);

        /** @var Transaction $transaction */
        $transaction = factory(Transaction::class)->create(['account_id' => $randomAccount->id]);

        $response = $this->actingAs($user)
            ->json(
                'GET',
                route(
                    'transactions.show',
                    [
                        'account' => $account->id,
                        'transaction' => $transaction->id,
                    ]
                )
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_FORBIDDEN);

        $response->assertJson(
            [
                'status' => 'fail',
                'message' => 'This action is unauthorized.',
            ]
        );
    }

    public function test_show_transaction_not_found(): void
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();
        $user->accounts()->save($account);

        $response = $this->actingAs($user)
            ->json(
                'GET',
                route(
                    'transactions.show',
                    [
                        'account' => $account->id,
                        'transaction' => 42,
                    ]
                )
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

    public function test_show_transaction_account_not_found(): void
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();
        $user->accounts()->save($account);

        $transaction = factory(Transaction::class)->create(['account_id' => $account->id]);

        $response = $this->actingAs($user)
            ->json(
                'GET',
                route(
                    'transactions.show',
                    [
                        'account' => 42,
                        'transaction' => $transaction->id,
                    ]
                )
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

    public function test_show_transaction_unauthenticated(): void
    {
        $response = $this->json(
            'GET',
            route(
                'transactions.show',
                [
                    'account' => 42,
                    'transaction' => 42,
                ]
            )
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
