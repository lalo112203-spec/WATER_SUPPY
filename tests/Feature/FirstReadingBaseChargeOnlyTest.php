<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FirstReadingBaseChargeOnlyTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $reader;
    protected $customerType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->reader = User::factory()->create(['role' => 'reader']);

        $this->customerType = CustomerType::create([
            'name' => 'Residential',
            'base_charge' => 150,
            'usage_rate' => 20,
            'base_limit' => 10,
        ]);
    }

    public function test_customer_model_is_first_reading(): void
    {
        $customer = Customer::create([
            'admin_id' => $this->admin->id,
            'customer_id' => '2001',
            'name' => 'First User',
            'email' => 'first@example.com',
            'address' => 'Barangay 1',
            'type' => 'Residential',
            'customer_type_id' => $this->customerType->id,
            'meter_reading' => 0,
            'status' => 'active',
        ]);

        $this->assertTrue($customer->isFirstReading());

        $bill = Bill::create([
            'customer_id' => $customer->id,
            'billing_date' => now(),
            'due_date' => now()->addDays(30),
            'previous_reading' => 0,
            'new_reading' => 25,
            'usage_units' => 25,
            'consumption' => 25,
            'base_charge' => 150,
            'usage_charge' => 0,
            'total_amount' => 150,
            'status' => 'Pending',
        ]);

        $this->assertFalse($customer->fresh()->isFirstReading());
        $this->assertTrue($bill->isFirstBill());

        $secondBill = Bill::create([
            'customer_id' => $customer->id,
            'billing_date' => now()->addMonth(),
            'due_date' => now()->addMonth()->addDays(30),
            'previous_reading' => 25,
            'new_reading' => 50,
            'usage_units' => 25,
            'consumption' => 25,
            'base_charge' => 150,
            'usage_charge' => 300,
            'total_amount' => 450,
            'status' => 'Pending',
        ]);

        $this->assertFalse($secondBill->isFirstBill());
    }

    public function test_reader_recording_first_reading_waives_usage_charge(): void
    {
        $customer = Customer::create([
            'admin_id' => $this->admin->id,
            'customer_id' => '2002',
            'name' => 'Maria Santos',
            'email' => 'maria@example.com',
            'address' => 'Barangay 2',
            'type' => 'Residential',
            'customer_type_id' => $this->customerType->id,
            'meter_reading' => 0,
            'status' => 'active',
        ]);

        // First reading input by reader (consumption = 30 m³ > base limit 10)
        $response = $this->actingAs($this->reader)->post(route('reader.storeReading'), [
            'customer_id' => $customer->id,
            'reading' => 30,
        ]);

        $response->assertRedirect(route('reader.dashboard'));

        $firstBill = Bill::where('customer_id', $customer->id)->first();
        $this->assertNotNull($firstBill);
        $this->assertEquals(30, $firstBill->consumption);
        $this->assertEquals(150, $firstBill->base_charge);
        // Usage charge must be waived (0.00)
        $this->assertEquals(0, $firstBill->usage_charge);
        // Total must only include base charge
        $this->assertEquals(150, $firstBill->total_amount);

        // Now test second reading in the next month: usage charge should apply normally!
        $this->travel(35)->days();

        $secondResponse = $this->actingAs($this->reader)->post(route('reader.storeReading'), [
            'customer_id' => $customer->id,
            'reading' => 55, // 25 m³ consumption -> 15 billable * 20 = 300
        ]);

        $secondResponse->assertRedirect(route('reader.dashboard'));

        $secondBill = Bill::where('customer_id', $customer->id)->latest('id')->first();
        $this->assertNotNull($secondBill);
        $this->assertEquals(25, $secondBill->consumption);
        $this->assertEquals(150, $secondBill->base_charge);
        $this->assertEquals(300, $secondBill->usage_charge); // (25 - 10) * 20 = 300
        $this->assertEquals(450, $secondBill->total_amount);
    }

    public function test_admin_generating_first_bill_waives_usage_charge(): void
    {
        $customer = Customer::create([
            'admin_id' => $this->admin->id,
            'customer_id' => '2003',
            'name' => 'Pedro Penduko',
            'email' => 'pedro@example.com',
            'address' => 'Barangay 3',
            'type' => 'Residential',
            'customer_type_id' => $this->customerType->id,
            'meter_reading' => 0,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->post(route('billing.store'), [
            'customer_id' => $customer->id,
            'billing_date' => now()->format('Y-m-d'),
            'new_reading' => 40,
            'consumption' => 40,
            'base_charge' => 150,
            'usage_charge' => 600, // Even if passed, first reading must waive usage charge
            'force_billing' => 0,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $bill = Bill::where('customer_id', $customer->id)->first();
        $this->assertNotNull($bill);
        $this->assertEquals(40, $bill->consumption);
        $this->assertEquals(150, $bill->base_charge);
        $this->assertEquals(0, $bill->usage_charge);
        $this->assertEquals(150, $bill->total_amount);
    }

    public function test_consumer_submitting_first_reading_waives_usage_charge(): void
    {
        $customer = Customer::create([
            'admin_id' => $this->admin->id,
            'customer_id' => '2004',
            'name' => 'Consumer Juan',
            'email' => 'juan.consumer@example.com',
            'address' => 'Barangay 4',
            'type' => 'Residential',
            'customer_type_id' => $this->customerType->id,
            'meter_reading' => 0,
            'status' => 'active',
        ]);

        $consumerUser = User::factory()->create([
            'role' => 'consumer',
            'customer_id' => $customer->id,
        ]);

        $response = $this->actingAs($consumerUser)->post(route('consumer.storeReading'), [
            'reading' => 35,
        ]);

        $response->assertRedirect(route('dashboard'));

        $bill = Bill::where('customer_id', $customer->id)->first();
        $this->assertNotNull($bill);
        $this->assertEquals(35, $bill->consumption);
        $this->assertEquals(150, $bill->base_charge);
        $this->assertEquals(0, $bill->usage_charge);
        $this->assertEquals(150, $bill->total_amount);
    }

    public function test_admin_cannot_generate_bill_when_reading_is_same_as_previous(): void
    {
        $customer = Customer::create([
            'admin_id' => $this->admin->id,
            'customer_id' => '2005',
            'name' => 'Same Reading User',
            'email' => 'same@example.com',
            'address' => 'Barangay 5',
            'type' => 'Residential',
            'customer_type_id' => $this->customerType->id,
            'meter_reading' => 50,
            'status' => 'active',
        ]);

        // Prior bill exists so it's not a first bill
        Bill::create([
            'customer_id' => $customer->id,
            'billing_date' => now()->subMonth(),
            'due_date' => now()->subMonth()->addDays(30),
            'previous_reading' => 20,
            'new_reading' => 50,
            'usage_units' => 30,
            'consumption' => 30,
            'base_charge' => 150,
            'usage_charge' => 400,
            'total_amount' => 550,
            'status' => 'Paid',
        ]);

        $response = $this->actingAs($this->admin)->post(route('billing.store'), [
            'customer_id' => $customer->id,
            'billing_date' => now()->format('Y-m-d'),
            'new_reading' => 50, // Same reading as previous
            'consumption' => 0,
            'base_charge' => 150,
            'usage_charge' => 0,
            'force_billing' => 0,
        ]);

        $response->assertSessionHasErrors(['new_reading']);
        $this->assertEquals(1, Bill::where('customer_id', $customer->id)->count());
    }

    public function test_admin_cannot_generate_bill_when_reading_is_less_than_previous(): void
    {
        $customer = Customer::create([
            'admin_id' => $this->admin->id,
            'customer_id' => '2006',
            'name' => 'Lower Reading User',
            'email' => 'lower@example.com',
            'address' => 'Barangay 6',
            'type' => 'Residential',
            'customer_type_id' => $this->customerType->id,
            'meter_reading' => 100,
            'status' => 'active',
        ]);

        // Prior bill exists
        Bill::create([
            'customer_id' => $customer->id,
            'billing_date' => now()->subMonth(),
            'due_date' => now()->subMonth()->addDays(30),
            'previous_reading' => 50,
            'new_reading' => 100,
            'usage_units' => 50,
            'consumption' => 50,
            'base_charge' => 150,
            'usage_charge' => 800,
            'total_amount' => 950,
            'status' => 'Paid',
        ]);

        // Reading is 10 (less than 100)
        $response = $this->actingAs($this->admin)->post(route('billing.store'), [
            'customer_id' => $customer->id,
            'billing_date' => now()->format('Y-m-d'),
            'new_reading' => 10, // Less than previous 100
            'consumption' => 0,
            'base_charge' => 150,
            'usage_charge' => 0,
            'force_billing' => 0,
        ]);

        $response->assertSessionHasErrors(['new_reading']);
        $this->assertEquals(1, Bill::where('customer_id', $customer->id)->count());
    }

    public function test_reader_can_record_reading_when_same_or_lower(): void
    {
        $customer = Customer::create([
            'admin_id' => $this->admin->id,
            'customer_id' => '2007',
            'name' => 'Reader Test User',
            'email' => 'reader.test@example.com',
            'address' => 'Barangay 7',
            'type' => 'Residential',
            'customer_type_id' => $this->customerType->id,
            'meter_reading' => 80,
            'status' => 'active',
        ]);

        // Prior bill
        Bill::create([
            'customer_id' => $customer->id,
            'billing_date' => now()->subMonth(),
            'due_date' => now()->subMonth()->addDays(30),
            'previous_reading' => 40,
            'new_reading' => 80,
            'usage_units' => 40,
            'consumption' => 40,
            'base_charge' => 150,
            'usage_charge' => 600,
            'total_amount' => 750,
            'status' => 'Paid',
        ]);

        // Reader enters 75 (lower than 80)
        $response = $this->actingAs($this->reader)->post(route('reader.storeReading'), [
            'customer_id' => $customer->id,
            'reading' => 75,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('reader.dashboard'));

        $latestBill = Bill::where('customer_id', $customer->id)->latest('id')->first();
        $this->assertNotNull($latestBill);
        $this->assertEquals(80, $latestBill->previous_reading);
        $this->assertEquals(75, $latestBill->new_reading);
        $this->assertEquals(0, $latestBill->consumption);
        $this->assertEquals(150, $latestBill->base_charge);
        $this->assertEquals(0, $latestBill->usage_charge);
        $this->assertEquals(150, $latestBill->total_amount);
    }
}
