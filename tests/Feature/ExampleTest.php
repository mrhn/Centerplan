<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_basic_test()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
