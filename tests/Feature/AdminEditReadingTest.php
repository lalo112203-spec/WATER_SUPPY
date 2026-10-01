<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminEditReadingTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected CustomerType $customerType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->customerType = CustomerType::firstOrCreate(
            ['name' => 'Regular'],
            [
                'base_charge' => 100,
                'base_limit' => 10,
                'usage_rate' => 15,
            ]
        );
    }

    public function test_customer_directory_contains_reading_edit_feature(): void
    {
        $customer = Customer::create([
            'admin_id' => $this->admin->id,
            'customer_id' => '1001',
            'name' => 'SABANDEJA, LALA HOBA',
            'type' => 'Regular',
            'customer_type_id' => $this->customerType->id,
            'email' => '1001@system.local',
            'address' => 'BRGY 11',
            'barangay' => 'BRGY 11',
            'meter_reading' => 20,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->get(route('customers.index'));

        $response->assertStatus(200);
        $response->assertSee('Edit Meter Reading');
        $response->assertSee('edit-reading-btn-' . $customer->id);
        $response->assertSee('editCustomerReadingModalAdmin');
        $response->assertSee('triggerEditReadingFromRow');
    }

    public function test_admin_can_update_bill_reading(): void
    {
        $customer = Customer::create([
            'admin_id' => $this->admin->id,
            'customer_id' => '1001',
            'name' => 'SABANDEJA, LALA HOBA',
            'type' => 'Regular',
            'customer_type_id' => $this->customerType->id,
            'email' => '1001@system.local',
            'address' => 'BRGY 11',
            'barangay' => 'BRGY 11',
            'meter_reading' => 20,
            'status' => 'active',
        ]);

        $bill = Bill::create([
            'customer_id' => $customer->id,
            'billing_date' => now(),
            'due_date' => now()->addDays(30),
            'previous_reading' => 0,
            'new_reading' => 20,
            'usage_units' => 20,
            'consumption' => 20,
            'base_charge' => 100,
            'usage_charge' => 150,
            'total_amount' => 250,
            'status' => 'Pending',
        ]);

        $response = $this->actingAs($this->admin)->putJson(route('reader.updateBill', $bill), [
            'new_reading' => 30,
            'previous_reading' => 0,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'bill' => [
                'id' => $bill->id,
                'new_reading' => 30,
                'consumption' => 30,
                'total_amount' => 400, // 100 + (30-10)*15 = 400
            ],
            'customer_meter_reading' => 30,
        ]);

        $this->assertEquals(30, $customer->fresh()->meter_reading);
        $this->assertEquals(30, $bill->fresh()->new_reading);
    }

    public function test_admin_can_update_customer_initial_reading(): void
    {
        $customer = Customer::create([
            'admin_id' => $this->admin->id,
            'customer_id' => '1002',
            'name' => 'DELA CRUZ, JUAN',
            'type' => 'Regular',
            'customer_type_id' => $this->customerType->id,
            'email' => '1002@system.local',
            'address' => 'BRGY 4',
            'barangay' => 'BRGY 4',
            'meter_reading' => 0,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->putJson(route('reader.updateCustomerReading', $customer), [
            'reading' => 15,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'customer_meter_reading' => 15,
        ]);

        $this->assertEquals(15, $customer->fresh()->meter_reading);
    }
}
