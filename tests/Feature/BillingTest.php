<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use App\Models\CustomerType;
use App\Models\Bill;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BillingTest extends TestCase
{
    use RefreshDatabase;
    public function test_admin_can_generate_bill(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $type = CustomerType::firstOrCreate(
            ['name' => 'Residential'],
            [
                'base_charge' => 150,
                'usage_rate' => 15,
                'base_limit' => 10,
            ]
        );

        $customer = Customer::create([
            'admin_id' => $admin->id,
            'customer_id' => '1001',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'address' => 'Test Address',
            'type' => 'Residential',
            'customer_type_id' => $type->id,
            'meter_reading' => 50,
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->post(route('billing.store'), [
            'customer_id' => $customer->id,
            'billing_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
            'new_reading' => 65,
            'consumption' => 15,
            'base_charge' => 150,
            'usage_charge' => 75,
            'force_billing' => 0,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        
        $this->assertDatabaseHas('bills', [
            'customer_id' => $customer->id,
            'previous_reading' => 50,
            'new_reading' => 65,
            'consumption' => 15,
        ]);

        $this->assertEquals(65, $customer->fresh()->meter_reading);
    }

    public function test_duplicate_bill_warning_returned_when_force_billing_not_set(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $type = CustomerType::firstOrCreate(
            ['name' => 'Commercial'],
            [
                'base_charge' => 200,
                'usage_rate' => 20,
                'base_limit' => 10,
            ]
        );

        $customer = Customer::create([
            'admin_id' => $admin->id,
            'customer_id' => '1002',
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'address' => 'Test Street',
            'type' => 'Commercial',
            'customer_type_id' => $type->id,
            'meter_reading' => 20,
            'status' => 'active',
        ]);

        // Create an existing bill for this month
        Bill::create([
            'customer_id' => $customer->id,
            'billing_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
            'previous_reading' => 10,
            'new_reading' => 20,
            'usage_units' => 10,
            'consumption' => 10,
            'base_charge' => 200,
            'usage_charge' => 0,
            'total_amount' => 200,
            'status' => 'Pending',
        ]);

        // Try generating another bill without force_billing
        $response = $this->actingAs($admin)->post(route('billing.store'), [
            'customer_id' => $customer->id,
            'billing_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
            'new_reading' => 30,
            'consumption' => 10,
            'base_charge' => 200,
            'usage_charge' => 0,
            'force_billing' => 0,
        ]);

        $response->assertSessionHas('billing_warning');
        $warning = session('billing_warning');
        $this->assertEquals($customer->id, $warning['customer_id'] ?? null);
        $this->assertEquals(30, $warning['new_reading'] ?? null);
    }

    public function test_admin_can_force_generate_duplicate_bill(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $type = CustomerType::firstOrCreate(
            ['name' => 'Industrial'],
            [
                'base_charge' => 300,
                'usage_rate' => 25,
                'base_limit' => 10,
            ]
        );

        $customer = Customer::create([
            'admin_id' => $admin->id,
            'customer_id' => '1003',
            'name' => 'Bob Builder',
            'email' => 'bob@example.com',
            'address' => 'Build Site',
            'type' => 'Industrial',
            'customer_type_id' => $type->id,
            'meter_reading' => 100,
            'status' => 'active',
        ]);

        // Existing bill this month
        Bill::create([
            'customer_id' => $customer->id,
            'billing_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
            'previous_reading' => 80,
            'new_reading' => 100,
            'usage_units' => 20,
            'consumption' => 20,
            'base_charge' => 300,
            'usage_charge' => 250,
            'total_amount' => 550,
            'status' => 'Pending',
        ]);

        // Force generate second bill
        $response = $this->actingAs($admin)->post(route('billing.store'), [
            'customer_id' => $customer->id,
            'billing_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
            'new_reading' => 110,
            'consumption' => 10,
            'base_charge' => 300,
            'usage_charge' => 0,
            'force_billing' => 1,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionMissing('billing_warning');
        $this->assertDatabaseHas('bills', [
            'customer_id' => $customer->id,
            'previous_reading' => 100,
            'new_reading' => 110,
            'consumption' => 10,
        ]);
        $this->assertEquals(110, $customer->fresh()->meter_reading);
    }

    public function test_billing_index_with_consumer_asc_sorting(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $type = CustomerType::firstOrCreate(
            ['name' => 'Residential'],
            [
                'base_charge' => 150,
                'usage_rate' => 15,
                'base_limit' => 10,
            ]
        );

        $customer = Customer::create([
            'admin_id' => $admin->id,
            'customer_id' => '1001',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'address' => 'Test Address',
            'type' => 'Residential',
            'customer_type_id' => $type->id,
            'meter_reading' => 50,
            'status' => 'active',
        ]);

        Bill::create([
            'customer_id' => $customer->id,
            'billing_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
            'previous_reading' => 50,
            'new_reading' => 65,
            'consumption' => 15,
            'usage_units' => 15,
            'base_charge' => 150,
            'usage_charge' => 75,
            'total_amount' => 225,
            'status' => 'Pending',
        ]);

        $response = $this->actingAs($admin)->get(route('billing.index', [
            'sort' => 'consumer_asc',
        ]));

        $response->assertStatus(200);
        $response->assertSee('John Doe');
    }

    public function test_billing_index_filters_by_all_months_and_years(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $type = CustomerType::firstOrCreate(
            ['name' => 'Residential'],
            [
                'base_charge' => 150,
                'usage_rate' => 15,
                'base_limit' => 10,
            ]
        );

        $customer = Customer::create([
            'admin_id' => $admin->id,
            'customer_id' => '1002',
            'name' => 'Alice Month Test',
            'email' => 'alice@example.com',
            'address' => 'Test Address',
            'type' => 'Residential',
            'customer_type_id' => $type->id,
            'meter_reading' => 50,
            'status' => 'active',
        ]);

        Bill::create([
            'customer_id' => $customer->id,
            'billing_date' => '2025-05-15',
            'due_date' => '2025-06-15',
            'previous_reading' => 50,
            'new_reading' => 60,
            'consumption' => 10,
            'usage_units' => 10,
            'base_charge' => 150,
            'usage_charge' => 0,
            'total_amount' => 150,
            'status' => 'Pending',
        ]);

        // Filter by month only (May = 05)
        $resMonth = $this->actingAs($admin)->get(route('billing.index', ['month' => '05']));
        $resMonth->assertStatus(200);
        $resMonth->assertSee('All Years');
        $resMonth->assertSee('All Months');
        $this->assertEquals(1, $resMonth->viewData('monthlyBillingRecords')->flatten()->count());

        // Filter by year only (2025)
        $resYear = $this->actingAs($admin)->get(route('billing.index', ['year' => '2025']));
        $resYear->assertStatus(200);
        $this->assertEquals(1, $resYear->viewData('monthlyBillingRecords')->flatten()->count());

        // Filter by both month and year (05 & 2025)
        $resBoth = $this->actingAs($admin)->get(route('billing.index', ['month' => '05', 'year' => '2025']));
        $resBoth->assertStatus(200);
        $this->assertEquals(1, $resBoth->viewData('monthlyBillingRecords')->flatten()->count());

        // Non-matching filter (different year)
        $resNone = $this->actingAs($admin)->get(route('billing.index', ['month' => '05', 'year' => '2024']));
        $resNone->assertStatus(200);
        $this->assertEquals(0, $resNone->viewData('monthlyBillingRecords')->flatten()->count());

        // Backward compatibility (month="2025-05")
        $resLegacy = $this->actingAs($admin)->get(route('billing.index', ['month' => '2025-05']));
        $resLegacy->assertStatus(200);
        $this->assertEquals(1, $resLegacy->viewData('monthlyBillingRecords')->flatten()->count());
    }
}
