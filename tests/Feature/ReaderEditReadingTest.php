<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReaderEditReadingTest extends TestCase
{
    use RefreshDatabase;

    protected User $reader;
    protected User $admin;
    protected CustomerType $customerType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reader = User::factory()->create([
            'role' => 'reader',
            'email_verified_at' => now(),
        ]);

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

    public function test_reader_can_edit_existing_bill_reading(): void
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

        $this->actingAs($this->reader);

        // Edit reading from 20 to 25
        $response = $this->putJson(route('reader.updateBill', $bill), [
            'new_reading' => 25,
            'previous_reading' => 0,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'bill' => [
                'id' => $bill->id,
                'new_reading' => 25,
                'consumption' => 25,
                'total_amount' => 325, // 100 base + (25 - 10) * 15 = 100 + 225 = 325
            ],
            'customer_meter_reading' => 25,
        ]);

        $this->assertEquals(25, $customer->fresh()->meter_reading);
        $this->assertEquals(25, $bill->fresh()->new_reading);
        $this->assertEquals(325, $bill->fresh()->total_amount);
    }

    public function test_reader_cannot_set_reading_lower_than_previous(): void
    {
        $customer = Customer::create([
            'admin_id' => $this->admin->id,
            'customer_id' => '1002',
            'name' => 'TEST CUSTOMER',
            'type' => 'Regular',
            'customer_type_id' => $this->customerType->id,
            'email' => '1002@system.local',
            'address' => 'BRGY 11',
            'barangay' => 'BRGY 11',
            'meter_reading' => 30,
            'status' => 'active',
        ]);

        $bill = Bill::create([
            'customer_id' => $customer->id,
            'billing_date' => now(),
            'due_date' => now()->addDays(30),
            'previous_reading' => 20,
            'new_reading' => 30,
            'usage_units' => 10,
            'consumption' => 10,
            'base_charge' => 100,
            'usage_charge' => 0,
            'total_amount' => 100,
            'status' => 'Pending',
        ]);

        $this->actingAs($this->reader);

        $response = $this->putJson(route('reader.updateBill', $bill), [
            'new_reading' => 15, // lower than previous 20
            'previous_reading' => 20,
        ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }

    public function test_reader_can_update_customer_initial_reading_when_no_bills(): void
    {
        $customer = Customer::create([
            'admin_id' => $this->admin->id,
            'customer_id' => '1003',
            'name' => 'NEW CONSUMER',
            'type' => 'Regular',
            'customer_type_id' => $this->customerType->id,
            'email' => '1003@system.local',
            'address' => 'BRGY 11',
            'barangay' => 'BRGY 11',
            'meter_reading' => 0,
            'status' => 'active',
        ]);

        $this->actingAs($this->reader);

        $response = $this->putJson(route('reader.updateCustomerReading', $customer), [
            'reading' => 12,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'customer_meter_reading' => 12,
        ]);

        $this->assertEquals(12, $customer->fresh()->meter_reading);
    }
}
