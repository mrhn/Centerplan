<?php

namespace Tests\Feature\Transaction;

use App\Enums\TransactionTypes;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

/**
 * @internal
 */
final class TransactionPatchTest extends TestCase
{
    public function test_patch_transaction(): void
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();
        $user->accounts()->save($account);

        $transaction = factory(Transaction::class)->create(['account_id' => $account->id]);

        /** @var \Faker\Generator $faker */
        $faker = app(Faker::class);

        $data = [
            'type' => $faker->randomElement([TransactionTypes::DEBIT, TransactionTypes::CREDIT]),
            'amount' => $faker->randomFloat(2, 1, 500000),
        ];

        $response = $this->actingAs($user)
            ->json(
                'PATCH',
                route('transactions.update', ['account' => $account->id, 'transaction' => $transaction->id]),
                $data
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_OK);

        // check we dont change already set fields
        $data['description'] = $transaction->description;

        $response->assertJson([
            'data' => $data,
        ]);

        $this->assertDatabaseHas(
            'transactions',
            $data
        );
    }

    /**
     * To secure patch spec is correct.
     */
    public function test_patch_transaction_no_data(): void
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();
        $user->accounts()->save($account);

        $transaction = factory(Transaction::class)->create(['account_id' => $account->id]);

        $response = $this->actingAs($user)
            ->json(
                'PATCH',
                route('transactions.update', ['account' => $account->id, 'transaction' => $transaction->id]),
                []
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_OK);
    }

    public function test_patch_transaction_validation_error(): void
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();
        $user->accounts()->save($account);

        $transaction = factory(Transaction::class)->create(['account_id' => $account->id]);

        $data = [
            'executed_at' => 'not real',
            'type' => 'creditor',
            'amount' => 'a number',
        ];

        $response = $this->actingAs($user)
            ->json(
                'PATCH',
                route('transactions.update', ['account' => $account->id, 'transaction' => $transaction->id]),
                $data
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJson([
            'status' => 'fail',
            'message' => 'The given data was invalid.',
            'errors' => [
                'executed_at' => ['The executed at is not a valid date.'],
                'type' => ['The selected type is invalid.'],
                'amount' => ['The amount must be a number.'],
            ],
        ]);
    }

    public function test_patch_transaction_not_found(): void
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();
        $user->accounts()->save($account);

        $response = $this->actingAs($user)->json(
            'PATCH',
            route('transactions.update', ['account' => $account->id, 'transaction' => 42]),
            []
        );

        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND);

        $response->assertJson(
            [
                'status' => 'fail',
                'message' => 'Model not found.',
            ]
        );
    }

    public function test_patch_transaction_account_not_found(): void
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();
        $user->accounts()->save($account);

        $transaction = factory(Transaction::class)->create(['account_id' => $account->id]);

        $response = $this->actingAs($user)->json(
            'PATCH',
            route('transactions.update', ['account' => 42, 'transaction' => $transaction->id]),
            []
        );

        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND);

        $response->assertJson(
            [
                'status' => 'fail',
                'message' => 'Model not found.',
            ]
        );
    }

    public function test_patch_transaction_account_not_linked_to_transaction(): void
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();
        $user->accounts()->save($account);

        $randomAccount = factory(Account::class)->create();
        $user->accounts()->save($randomAccount);

        $transaction = factory(Transaction::class)->create(['account_id' => $randomAccount->id]);

        $response = $this->actingAs($user)->json(
            'PATCH',
            route('transactions.update', ['account' => $account->id, 'transaction' => $transaction->id]),
            []
        );

        $response->assertStatus(JsonResponse::HTTP_FORBIDDEN);

        $response->assertJson(
            [
                'status' => 'fail',
                'message' => 'This action is unauthorized.',
            ]
        );
    }

    public function test_patch_transaction_unauthenticated(): void
    {
        $account = factory(Account::class)->create();

        $transaction = factory(Transaction::class)->create(['account_id' => $account->id]);

        $response = $this->json(
            'PATCH',
            route('transactions.update', ['account' => $account->id, 'transaction' => $transaction->id]),
            []
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
