<?php

namespace Tests\Feature\Account;

use App\Models\Account;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

/**
 * @internal
 */
final class AccountUpdateTest extends TestCase
{
    public function test_update_account()
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();

        /** @var \Faker\Generator $faker */
        $faker = app(Faker::class);
        $data = ['name' => $faker->name];

        $response = $this->actingAs($user)
            ->json(
                'PUT',
                route('accounts.update', ['account' => $account->id]),
                $data
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_OK);

        $response->assertJson([
            'data' => $data,
        ]);

        $this->assertDatabaseHas(
            'accounts',
            $data
        );
    }

    public function test_update_account_no_data()
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();

        $response = $this->actingAs($user)
            ->json(
                'PUT',
                route('accounts.update', ['account' => $account->id]),
                []
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJson([
            'status' => 'fail',
            'message' => 'The given data was invalid.',
            'errors' => [
                'name' => ['The name field is required.'],
            ],
        ]);
    }

    public function test_update_account_validation_error()
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();

        $data = ['name' => 42];

        $response = $this->actingAs($user)
            ->json(
                'PUT',
                route('accounts.update', ['account' => $account->id]),
                $data
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJson([
            'status' => 'fail',
            'message' => 'The given data was invalid.',
            'errors' => [
                'name' => ['The name must be a string.'],
            ],
        ]);
    }

    public function test_update_account_not_found()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json(
            'PUT',
            route('accounts.update', ['account' => 42]),
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

    public function test_put_account_unauthenticated()
    {
        $account = factory(Account::class)->create();

        $response = $this->json(
            'PUT',
            route('accounts.update', ['account' => $account->id]),
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
