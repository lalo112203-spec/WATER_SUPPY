<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get(route('register'));

        $response->assertOk();
    }

    public function test_new_users_can_register(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $customer = \App\Models\Customer::create([
            'customer_id' => '12345',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'address' => '123 Main St',
            'type' => 'Regular',
            'status' => 'Active',
            'admin_id' => $admin->id,
        ]);

        $response = $this->withoutMiddleware()->post(route('register.store'), [
            'name' => 'John Doe',
            'email' => '12345', // As per system logic
            'account_number' => '12345',
            'address' => '123 Main St',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
    }
}
