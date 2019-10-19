<?php

namespace Tests\Feature\Account;

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

/**
 * @internal
 */
final class AccountCreateTest extends TestCase
{
    public function test_create_account()
    {
        $user = factory(User::class)->create();

        /** @var \Faker\Generator $faker */
        $faker = app(Faker::class);
        $data = ['name' => $faker->name];

        $response = $this->actingAs($user)
            ->json(
                'POST',
                route('accounts.store'),
                $data
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_CREATED);

        $response->assertJson([
            'data' => $data,
        ]);

        $this->assertDatabaseHas(
            'accounts',
            $data
        );

        $this->assertDatabaseHas(
            'account_user',
            [
                'user_id' => $user->id,
                'account_id' => $response->getOriginalContent()->data->id,
            ]
        );
    }

    public function test_create_account_validation_fails()
    {
        $user = factory(User::class)->create();

        /** @var \Faker\Generator $faker */
        $faker = app(Faker::class);
        $data = [];

        $response = $this->actingAs($user)
            ->json(
                'POST',
                route('accounts.store'),
                $data
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

    public function test_create_account_unauthenticated()
    {
        $response = $this->json(
            'POST',
            route('accounts.store')
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
