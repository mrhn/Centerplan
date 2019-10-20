<?php

namespace Tests\Feature\Account;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

/**
 * @internal
 */
final class AccountDeleteTest extends TestCase
{
    public function test_delete_account()
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();

        $response = $this->actingAs($user)
            ->json(
                'DELETE',
                route('accounts.destroy', ['account' => $account->id])
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing(
            'accounts',
            $account->toArray()
        );
    }

    public function test_delete_account_not_found()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->json(
                'DELETE',
                route('accounts.destroy', ['account' => 42])
            )
        ;

        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND);

        $response->assertJson([
            'status' => 'fail',
            'message' => 'Model not found.',
        ]);
    }

    public function test_delete_account_unauthenticated()
    {
        $account = factory(Account::class)->create();

        $response = $this->json(
            'PUT',
            route('accounts.destroy', ['account' => $account->id]),
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
