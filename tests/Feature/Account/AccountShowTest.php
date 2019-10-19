<?php

namespace Tests\Feature\Account;

use App\Models\Account;
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
