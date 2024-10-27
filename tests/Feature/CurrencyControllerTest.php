<?php

namespace Tests\Feature;

use App\Models\CurrencyHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CurrencyControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a user for authentication
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_validates_the_request()
    {
        $this->actingAs($this->user) // Act as the authenticated user
            ->post('/convert', [
                'from' => 'USD',
                'to' => 'USD', // Same currency
                'amount' => 100,
            ])
            ->assertSessionHasErrors('to'); // Check for validation error
    }

    /** @test */
    public function it_requires_different_currencies()
    {
        $this->actingAs($this->user)
            ->post('/convert', [
                'from' => 'USD',
                'to' => 'USD', // Same currency
                'amount' => 100,
            ])
            ->assertSessionHasErrors('to');
    }

    /** @test */
    public function it_converts_currency_successfully()
    {
        // Mock the currency service response
        $this->mockCurrencyService();

        $response = $this->actingAs($this->user)
            ->post('/convert', [
                'from' => 'USD',
                'to' => 'EUR',
                'amount' => 100,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('currency_histories', [
            'user_id' => $this->user->id,
            'from_currency' => 'USD',
            'to_currency' => 'EUR',
            'amount' => 100,
            // Adjust this value based on your mock service
            'result' => 200,
        ]);
    }

    /** @test */
    // public function it_handles_api_errors()
    // {
    //     // Mock an API error
    //     $this->mockCurrencyService(true);

    //     $response = $this->actingAs($this->user)
    //         ->post('/convert', [
    //             'from' => 'USD',
    //             'to' => 'EUR',
    //             'amount' => 100,
    //         ]);

    //     $response->assertStatus(500);
    //     $response->assertSessionHas('error', 'Invalid currency');
    // }

    /** @test */
    public function it_rolls_back_on_exception()
    {
        // Force an exception to occur
        $this->mockCurrencyService(false, true);

        $response = $this->actingAs($this->user)
            ->post('/convert', [
                'from' => 'USD',
                'to' => 'EUR',
                'amount' => 100,
            ]);

        $response->assertStatus(500);
        $this->assertDatabaseMissing('currency_histories', [
            'user_id' => $this->user->id,
            'from_currency' => 'USD',
            'to_currency' => 'EUR',
            'amount' => 100,
        ]);
    }

    protected function mockCurrencyService($shouldFail = false, $forceException = false)
    {
        $mock = \Mockery::mock('App\Services\CurrencyService');

        if ($forceException) {
            $mock->shouldReceive('convert')
                ->andThrow(new \Exception('Database error'));
        } elseif ($shouldFail) {
            $mock->shouldReceive('convert')
                ->andReturn(['error' => 'Invalid currency']);
        } else {
            $mock->shouldReceive('convert')
                ->andReturn(['conversion_result' => 200, 'conversion_rate' => 1.5]);
        }

        $this->app->instance('App\Services\CurrencyService', $mock);
    }
}
