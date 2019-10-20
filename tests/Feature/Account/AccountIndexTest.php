<?php

namespace Tests\Feature\Account;

use App\Models\Account;
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

        $response = $this->actingAs($user)
            ->json(
                'GET',
                route('accounts.index')
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_OK);

        $response->assertJson([
            'data' => $accounts->map(function (Account $account): array {
                return [
                    'id' => $account->id,
                    'name' => $account->name,
                ];
            })->all(),
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
