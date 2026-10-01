<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GaloWordDocumentRequirementsTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $reader;
    protected $consumerUser;
    protected $customer;
    protected $customerType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customerType = CustomerType::create([
            'name' => 'Residential',
            'base_charge' => 150,
            'usage_rate' => 20,
            'green_max' => 10,
            'orange_max' => 20,
            'red_max' => 30,
            'base_limit' => 10,
        ]);

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->customer = Customer::create([
            'admin_id' => $this->admin->id,
            'customer_id' => '1001',
            'name' => 'Juan Dela Cruz',
            'email' => 'juan@example.com',
            'type' => 'Residential',
            'customer_type_id' => $this->customerType->id,
            'address' => 'Poblacion',
            'barangay' => 'Barangay 1',
            'meter_post' => 'POST-12',
            'meter_reading' => 50,
            'status' => 'active',
        ]);

        $this->consumerUser = User::factory()->create([
            'role' => 'consumer',
            'customer_id' => $this->customer->id,
        ]);

        $this->reader = User::factory()->create([
            'role' => 'reader',
        ]);
    }

    protected function createBill(array $attributes = []): Bill
    {
        return Bill::create(array_merge([
            'customer_id' => $this->customer->id,
            'billing_date' => now(),
            'due_date' => now()->addDays(15),
            'period' => 'October 2026',
            'previous_reading' => 30,
            'usage_units' => 50,
            'consumption' => 20,
            'base_charge' => 150,
            'usage_charge' => 200,
            'total_amount' => 350,
            'status' => 'Pending',
        ], $attributes));
    }

    /**
     * Requirement 1: Arrears should always be displayed on receipt even if 0 or paid.
     */
    public function test_requirement_1_arrears_always_displayed_on_receipt(): void
    {
        $bill = $this->createBill([
            'total_amount' => 350,
            'status' => 'Pending',
        ]);

        $this->actingAs($this->admin);
        $response = $this->get(route('billing.receipt', $bill));
        $response->assertStatus(200);
        $response->assertSee('Unpaid Bill');
        $response->assertSee('₱0');
    }

    public function test_arrears_adds_all_unpaid_bills_of_customer(): void
    {
        $bill1 = $this->createBill([
            'billing_date' => '2026-09-01',
            'total_amount' => 100,
            'status' => 'Pending',
        ]);

        $bill2 = $this->createBill([
            'billing_date' => '2026-09-15',
            'total_amount' => 175,
            'status' => 'Pending',
        ]);

        $bill3 = $this->createBill([
            'billing_date' => '2026-09-30',
            'total_amount' => 145,
            'status' => 'Pending',
        ]);

        // Bill 1 arrears must include bill 2 and bill 3 (175 + 145 = 320)
        $this->assertEquals(320.0, $bill1->arrears);
        $this->assertEquals(420.0, $bill1->total_amount + $bill1->arrears);

        // Bill 2 arrears must include bill 1 and bill 3 (100 + 145 = 245)
        $this->assertEquals(245.0, $bill2->arrears);
        $this->assertEquals(420.0, $bill2->total_amount + $bill2->arrears);

        // Bill 3 arrears must include bill 1 and bill 2 (100 + 175 = 275)
        $this->assertEquals(275.0, $bill3->arrears);
        $this->assertEquals(420.0, $bill3->total_amount + $bill3->arrears);

        // Customer total unpaid
        $this->assertEquals(420.0, $this->customer->unpaid_bills_total);
        $this->assertEquals(3, $this->customer->unpaid_bills_count);

        // Receipt for Bill 1 displays arrears of 320 and total due of 420
        $this->actingAs($this->admin);
        $response = $this->get(route('billing.receipt', $bill1));
        $response->assertStatus(200);
        $response->assertSee('₱320');
        $response->assertSee('₱420');

        // Mark Bill 2 as Paid
        $bill2->update(['status' => 'Paid']);
        $bill1->refresh();

        // Bill 1 arrears now only includes bill 3 (145)
        $this->assertEquals(145.0, $bill1->arrears);
        $this->assertEquals(245.0, $bill1->total_amount + $bill1->arrears);
    }

    /**
     * Requirement 2: Meter reading module Edit option allows editing reading and recalculates.
     */
    public function test_requirement_2_reader_can_edit_reading(): void
    {
        $bill = $this->createBill([
            'previous_reading' => 40,
            'usage_units' => 50,
            'consumption' => 10,
            'base_charge' => 150,
            'usage_charge' => 0,
            'total_amount' => 150,
            'status' => 'Pending',
        ]);

        $this->actingAs($this->reader);
        $response = $this->put(route('reader.updateBill', $bill), [
            'reading' => 65,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $bill->refresh();
        $this->assertEquals(65, $bill->new_reading);
        $this->assertEquals(25, $bill->consumption);
        $this->assertEquals(450, $bill->total_amount); // 150 base + 15 * 20
    }

    /**
     * Requirement 3 & 4: Official Receipt (OR) Number and Payment Verification Process.
     */
    public function test_requirement_3_and_4_payment_verification_and_or_number(): void
    {
        $bill = $this->createBill([
            'total_amount' => 200,
            'status' => 'Pending',
        ]);

        $this->actingAs($this->admin);

        // Incorrect payment amount rejected
        $badResponse = $this->patch(route('billing.mark-paid', $bill), [
            'payment_amount' => 150,
            'or_number' => 'OR-999001',
        ]);
        $badResponse->assertSessionHas('error');
        $this->assertEquals('Pending', $bill->fresh()->status);

        // Exact payment amount accepted with OR number
        $goodResponse = $this->patch(route('billing.mark-paid', $bill), [
            'payment_amount' => 200,
            'or_number' => 'OR-999001',
        ]);
        $goodResponse->assertSessionHas('success');

        $bill->refresh();
        $this->assertEquals('Paid', $bill->status);
        $this->assertEquals('OR-999001', $bill->or_number);
    }

    /**
     * Requirement 5: List of consumers for each month displayed when sorting/filtering.
     */
    public function test_requirement_5_monthly_consumer_list_displayed(): void
    {
        $this->createBill([
            'billing_date' => now(),
            'period' => 'October 2026',
        ]);

        $this->actingAs($this->admin);
        $monthStr = now()->format('Y-m');
        $response = $this->get(route('billing.index', ['month' => $monthStr, 'sort' => 'desc']));
        $response->assertStatus(200);
        $response->assertSee('List of Consumers by Billing Month');
        $response->assertSee('Juan Dela Cruz');
        $response->assertSee('1001');
    }

    /**
     * Requirement 6: Print complete billing history of a consumer.
     */
    public function test_requirement_6_print_complete_billing_history(): void
    {
        $this->createBill([
            'billing_date' => now()->subMonth(),
            'period' => 'September 2026',
            'status' => 'Paid',
            'or_number' => 'OR-100010',
        ]);

        $this->createBill([
            'billing_date' => now(),
            'period' => 'October 2026',
            'status' => 'Pending',
        ]);

        // Admin can view and print complete billing history
        $this->actingAs($this->admin);
        $adminResponse = $this->get(route('customers.billing-history.print', $this->customer));
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('OFFICIAL BILLING STATEMENT HISTORY');
        $adminResponse->assertSee('Juan Dela Cruz');
        $adminResponse->assertSee('September 2026');
        $adminResponse->assertSee('October 2026');
        $adminResponse->assertSee('OR-100010');

        // Consumer can view their own billing history
        $this->actingAs($this->consumerUser);
        $consumerResponse = $this->get(route('customers.billing-history.print', $this->customer));
        $consumerResponse->assertStatus(200);
        $consumerResponse->assertSee('Juan Dela Cruz');
    }

    /**
     * Requirement 7: Disconnection policy settings and notification feature.
     */
    public function test_requirement_7_disconnection_policy_and_notifications(): void
    {
        // 1. Create 2 unpaid bills
        $this->createBill([
            'billing_date' => now()->subMonth(),
            'period' => 'September 2026',
            'status' => 'Pending',
        ]);
        $this->createBill([
            'billing_date' => now(),
            'period' => 'October 2026',
            'status' => 'Pending',
        ]);

        SystemSetting::set('disconnection_unpaid_months', 2);
        $this->customer->refresh();

        $this->assertEquals(2, $this->customer->unpaid_bills_count);
        $this->assertTrue($this->customer->isEligibleForDisconnection());

        // 2. Admin sends disconnection notice
        $this->actingAs($this->admin);
        $noticeResponse = $this->post(route('customers.send-disconnection-notice', $this->customer));
        $noticeResponse->assertSessionHas('success');

        // Verify message created for consumer
        $this->assertDatabaseHas('messages', [
            'receiver_id' => $this->consumerUser->id,
        ]);

        // 3. Consumer sees disconnection warning on their dashboard
        $this->actingAs($this->consumerUser);
        $dashboardResponse = $this->get(route('dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Possible Service Disconnection');
    }

    public function test_receipt_shows_individual_dates_and_amounts_for_unpaid_bills(): void
    {
        $bill1 = $this->createBill([
            'billing_date' => '2026-05-01',
            'total_amount' => 165,
            'status' => 'Pending',
        ]);

        $bill2 = $this->createBill([
            'billing_date' => '2026-06-01',
            'total_amount' => 165,
            'status' => 'Pending',
        ]);

        $billCurrent = $this->createBill([
            'billing_date' => '2026-07-01',
            'total_amount' => 165,
            'status' => 'Pending',
        ]);

        $this->actingAs($this->admin);
        $response = $this->get(route('billing.receipt', $billCurrent));
        $response->assertStatus(200);
        $response->assertSee('Unpaid Bill');
        $response->assertSee('May 2026');
        $response->assertSee('June 2026');
        $response->assertSee('₱165');
        $response->assertSee('₱330'); // total arrears = 165 + 165
        $response->assertSee('₱495'); // total due = 165 + 330
    }

    public function test_disconnection_notice_only_shows_when_consumer_has_4_unpaid_bills(): void
    {
        // Clear any custom setting to test the default 4-bill policy
        \App\Models\SystemSetting::where('key', 'disconnection_unpaid_months')->delete();

        // Create 3 unpaid bills
        for ($i = 1; $i <= 3; $i++) {
            $this->createBill([
                'billing_date' => now()->subMonths(4 - $i),
                'period' => "Month $i",
                'status' => 'Pending',
            ]);
        }

        $this->customer->refresh();
        $this->assertEquals(3, $this->customer->unpaid_bills_count);
        $this->assertFalse($this->customer->isEligibleForDisconnection());

        // 3 unpaid bills: Consumer does NOT see disconnection notice on dashboard
        $this->actingAs($this->consumerUser);
        $res3 = $this->get(route('dashboard'));
        $res3->assertStatus(200);
        $res3->assertDontSee('Possible Service Disconnection');

        // Add 4th unpaid bill
        $this->createBill([
            'billing_date' => now(),
            'period' => 'Month 4',
            'status' => 'Pending',
        ]);

        $this->customer->refresh();
        $this->assertEquals(4, $this->customer->unpaid_bills_count);
        $this->assertTrue($this->customer->isEligibleForDisconnection());

        // 4 unpaid bills: Consumer sees disconnection notice
        $res4 = $this->get(route('dashboard'));
        $res4->assertStatus(200);
        $res4->assertSee('Possible Service Disconnection');
    }

    public function test_paid_bill_receipt_does_not_show_unpaid_bills(): void
    {
        // 1. Create a prior unpaid bill
        $this->createBill([
            'billing_date' => '2026-08-01',
            'period' => 'August 2026',
            'total_amount' => 320,
            'status' => 'Pending',
        ]);

        // 2. Create a paid bill for 130
        $paidBill = $this->createBill([
            'billing_date' => '2026-09-01',
            'period' => 'September 2026',
            'base_charge' => 100,
            'usage_charge' => 30,
            'total_amount' => 130,
            'status' => 'Paid',
            'paid_date' => '2026-10-01',
        ]);

        $this->actingAs($this->admin);
        $response = $this->get(route('billing.receipt', $paidBill));
        $response->assertStatus(200);

        // Does NOT show unpaid bills breakdown or arrears on a paid receipt
        $response->assertDontSee('Unpaid previous bills');
        $response->assertDontSee('Unpaid Bill');

        // Shows Total Paid of only the paid bill amount (130)
        $response->assertSee('Total Paid');
        $response->assertSee('₱130');
        $response->assertDontSee('Current: ₱130 + Unpaid Bill: ₱320');
        $response->assertDontSee('₱450');
    }
}

