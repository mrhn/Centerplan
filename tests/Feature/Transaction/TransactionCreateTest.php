<?php

namespace Tests\Feature\Transaction;

use App\Enums\TransactionTypes;
use App\Models\Account;
use App\Models\User;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

/**
 * @internal
 */
final class TransactionCreateTest extends TestCase
{
    public function test_create_transaction(): void
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();
        $user->accounts()->save($account);

        /** @var \Faker\Generator $faker */
        $faker = app(Faker::class);

        $dateTime = Carbon::now();
        $dateTime->micros(0);

        $data = [
            'executed_at' => $dateTime->toDateTimeString(),
            'description' => $faker->text(50),
            'type' => $faker->randomElement([TransactionTypes::DEBIT, TransactionTypes::CREDIT]),
            'amount' => $faker->randomFloat(2, 1, 500000),
        ];

        $response = $this->actingAs($user)
            ->json(
                'POST',
                route('transactions.store', ['account' => $account->id]),
                $data
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_CREATED);

        $data['executed_at'] = $dateTime->toJSON();

        $response->assertJson([
            'data' => $data,
        ]);

        $data['account_id'] = $account->id;
        $data['executed_at'] = $dateTime->toDateTimeString();

        $this->assertDatabaseHas(
            'transactions',
            $data
        );
    }

    public function test_create_transaction_validation_fails(): void
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();
        $user->accounts()->save($account);

        /** @var \Faker\Generator $faker */
        $faker = app(Faker::class);
        $data = [
            'executed_at' => 'not real',
            'type' => 'creditor',
            'amount' => 'a number',
        ];

        $response = $this->actingAs($user)
            ->json(
                'POST',
                route('transactions.store', ['account' => $account->id]),
                $data
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJson([
            'status' => 'fail',
            'message' => 'The given data was invalid.',
            'errors' => [
                'executed_at' => ['The executed at is not a valid date.'],
                'description' => ['The description field is required.'],
                'type' => ['The selected type is invalid.'],
                'amount' => ['The amount must be a number.'],
            ],
        ]);
    }

    public function test_create_transaction_account_not_found(): void
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->json(
                'POST',
                route('transactions.store', ['account' => 42])
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

    public function test_create_transaction_unauthenticated(): void
    {
        $response = $this->json(
            'POST',
            route('transactions.store', ['account' => 42])
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
