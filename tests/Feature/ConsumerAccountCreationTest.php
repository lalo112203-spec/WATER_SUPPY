<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ConsumerAccountCreationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected CustomerType $customerType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin_test@water.system',
            'username' => 'admintest',
        ]);

        $this->customerType = CustomerType::create([
            'name' => 'Residential',
            'description' => 'Residential type',
        ]);
    }

    public function test_admin_can_create_consumer_account_with_custom_username_and_password()
    {
        $customer = Customer::create([
            'admin_id' => $this->admin->id,
            'customer_id' => '2001',
            'name' => 'DELA CRUZ, JUAN',
            'type' => 'Residential',
            'customer_type_id' => $this->customerType->id,
            'email' => '2001@system.local',
            'meter_post' => 'Post 1',
            'barangay' => 'BARANGAY 1',
            'address' => 'BARANGAY 1 DOLORES EASTERN SAMAR',
        ]);

        $this->assertNull($customer->user);

        $response = $this->actingAs($this->admin)->post(route('customers.create-account', $customer), [
            'username' => 'juandelacruz',
            'password' => 'secretPass123',
        ]);

        $response->assertSessionHas('success');
        $customer->refresh();

        $this->assertNotNull($customer->user);
        $this->assertEquals('juandelacruz', $customer->user->username);
        $this->assertEquals('secretPass123', $customer->user->plain_password);
        $this->assertTrue(Hash::check('secretPass123', $customer->user->password));

        // Flush session before login as consumer
        auth()->logout();
        $this->flushSession();

        // Consumer can log in using custom username
        $loginRes = $this->post(route('login.store'), [
            'email' => 'juandelacruz',
            'password' => 'secretPass123',
        ]);
        $loginRes->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($customer->user);

        // Consumer can also log in using account number
        auth()->logout();
        $this->flushSession();
        $loginWithAcctRes = $this->post(route('login.store'), [
            'email' => '2001',
            'password' => 'secretPass123',
        ]);
        $loginWithAcctRes->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($customer->user);
    }

    public function test_username_conflict_is_prevented()
    {
        // Existing user with username 'existing_user'
        User::factory()->create([
            'username' => 'existing_user',
            'email' => 'existing@system.local',
        ]);

        $customer = Customer::create([
            'admin_id' => $this->admin->id,
            'customer_id' => '2002',
            'name' => 'REYES, PEDRO',
            'type' => 'Residential',
            'customer_type_id' => $this->customerType->id,
            'email' => '2002@system.local',
            'meter_post' => 'Post 2',
            'barangay' => 'BARANGAY 2',
            'address' => 'BARANGAY 2 DOLORES EASTERN SAMAR',
        ]);

        $response = $this->actingAs($this->admin)->post(route('customers.create-account', $customer), [
            'username' => 'existing_user',
            'password' => 'newPassword99',
        ]);

        $response->assertSessionHas('error');
        $customer->refresh();
        $this->assertNull($customer->user);
    }

    public function test_registering_new_consumer_with_custom_username_and_password()
    {
        $response = $this->actingAs($this->admin)->post(route('customers.store'), [
            'customer_id' => '3001',
            'last_name' => 'Rizal',
            'first_name' => 'Jose',
            'middle_name' => 'P',
            'customer_type_id' => $this->customerType->id,
            'meter_post' => 'Post 3',
            'barangay' => 'BARANGAY 3',
            'create_account' => '1',
            'username' => 'joserizal',
            'password' => 'freedom123',
        ]);

        $response->assertRedirect(route('customers.index'));

        $customer = Customer::where('customer_id', '3001')->first();
        $this->assertNotNull($customer);
        $this->assertNotNull($customer->user);
        $this->assertEquals('joserizal', $customer->user->username);
        $this->assertEquals('freedom123', $customer->user->plain_password);

        // Can log in with username
        auth()->logout();
        $this->flushSession();
        $loginRes = $this->post(route('login.store'), [
            'email' => 'joserizal',
            'password' => 'freedom123',
        ]);
        $loginRes->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($customer->user);
    }
}
