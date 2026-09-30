<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\Message;
use App\Models\Post;
use App\Models\RegistrationCode;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemFunctionsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $consumerUser;
    protected User $readerUser;
    protected Customer $customer;
    protected CustomerType $customerType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->customerType = CustomerType::create([
            'name' => 'Residential',
            'base_charge' => 100,
            'usage_rate' => 15,
            'green_max' => 15,
            'orange_max' => 25,
            'red_max' => 35,
            'base_limit' => 10,
        ]);

        $this->customer = Customer::create([
            'admin_id' => $this->admin->id,
            'customer_id' => '1001',
            'name' => 'DELA CRUZ, JUAN',
            'type' => 'Residential',
            'customer_type_id' => $this->customerType->id,
            'email' => '1001@system.local',
            'meter_post' => 'Post 1',
            'phone_number' => '09123456789',
            'address' => 'BARANGAY 1 DOLORES EASTERN SAMAR',
            'barangay' => 'BARANGAY 1',
            'meter_reading' => 50,
            'status' => 'active',
        ]);

        $this->consumerUser = User::factory()->create([
            'role' => 'consumer',
            'customer_id' => $this->customer->id,
            'name' => 'DELA CRUZ, JUAN',
            'email' => '1001@system.local',
            'password' => bcrypt('password123'),
        ]);

        $this->readerUser = User::factory()->create([
            'role' => 'reader',
            'name' => 'Meter Reader',
            'email' => 'reader@example.com',
            'password' => bcrypt('password123'),
        ]);
    }

    public function test_dashboard_access_for_all_roles()
    {
        // Admin
        $resAdmin = $this->actingAs($this->admin)->get(route('dashboard'));
        $resAdmin->assertOk();

        // Reader should redirect to reader.dashboard
        $resReader = $this->actingAs($this->readerUser)->get(route('dashboard'));
        $resReader->assertRedirect(route('reader.dashboard'));

        // Reader dashboard
        $resReaderDash = $this->actingAs($this->readerUser)->get(route('reader.dashboard'));
        $resReaderDash->assertOk();

        // Consumer
        $resConsumer = $this->actingAs($this->consumerUser)->get(route('dashboard'));
        $resConsumer->assertOk();
    }

    public function test_prevent_reader_access_middleware()
    {
        // Reader should be blocked from admin/consumer routes
        $response = $this->actingAs($this->readerUser)->get(route('customers.index'));
        $response->assertRedirect(route('reader.dashboard'));

        $responseBilling = $this->actingAs($this->readerUser)->get(route('billing.index'));
        $responseBilling->assertRedirect(route('reader.dashboard'));

        $responseSettings = $this->actingAs($this->readerUser)->get(route('settings.index'));
        $responseSettings->assertRedirect(route('reader.dashboard'));
    }

    public function test_customer_crud_and_account_management()
    {
        // Index
        $res = $this->actingAs($this->admin)->get(route('customers.index'));
        $res->assertOk();

        // Report
        $resReport = $this->actingAs($this->admin)->get(route('customers.report'));
        $resReport->assertOk();

        // Store new customer
        $storeRes = $this->actingAs($this->admin)->post(route('customers.store'), [
            'last_name' => 'Santos',
            'first_name' => 'Maria',
            'middle_name' => 'A',
            'customer_type_id' => $this->customerType->id,
            'meter_post' => 'Post 2',
            'phone_number' => '09987654321',
            'barangay' => 'Barangay 2',
            'create_account' => '1',
            'password' => 'secret123',
        ]);
        $storeRes->assertRedirect(route('customers.index'));
        $newCustomer = Customer::where('barangay', 'BARANGAY 2')->first();
        $this->assertNotNull($newCustomer);
        $this->assertNotNull($newCustomer->user);

        // Update customer
        $updateRes = $this->actingAs($this->admin)->put(route('customers.update', $newCustomer), [
            'customer_id' => $newCustomer->customer_id,
            'last_name' => 'Santos',
            'first_name' => 'Maria Clara',
            'middle_name' => 'A',
            'customer_type_id' => $this->customerType->id,
            'meter_post' => 'Post 2A',
            'phone_number' => '09987654322',
            'barangay' => 'Barangay 2',
        ]);
        $updateRes->assertRedirect(route('customers.index'));
        $this->assertStringContainsString('MARIA CLARA', $newCustomer->fresh()->name);

        // Update password
        $passRes = $this->actingAs($this->admin)->post(route('customers.update-password', $newCustomer), [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        $passRes->assertRedirect();

        // Delete customer (soft delete)
        $delRes = $this->actingAs($this->admin)->delete(route('customers.destroy', $newCustomer));
        $delRes->assertRedirect(route('customers.index'));
        $this->assertTrue($newCustomer->fresh()->trashed());
    }

    public function test_billing_crud_and_status()
    {
        // Billing index
        $res = $this->actingAs($this->admin)->get(route('billing.index'));
        $res->assertOk();

        // Create Bill
        $billRes = $this->actingAs($this->admin)->post(route('billing.store'), [
            'customer_id' => $this->customer->id,
            'billing_date' => now()->format('Y-m-d'),
            'new_reading' => 70,
            'consumption' => 20,
            'base_charge' => 100,
            'usage_charge' => 150,
            'due_date' => now()->addDays(30)->format('Y-m-d'),
        ]);
        $billRes->assertSessionHasNoErrors();
        $bill = Bill::where('customer_id', $this->customer->id)->latest('id')->first();
        $this->assertNotNull($bill);
        $this->assertEquals(70, $this->customer->fresh()->meter_reading);

        // Show Bill
        $showRes = $this->actingAs($this->admin)->get(route('billing.show', $bill));
        $showRes->assertOk();

        // Receipt
        $receiptRes = $this->actingAs($this->admin)->get(route('billing.receipt', $bill));
        $receiptRes->assertOk();

        // Mark as paid
        $payRes = $this->actingAs($this->admin)->patch(route('billing.mark-paid', $bill));
        $payRes->assertRedirect(route('billing.index'));
        $this->assertEquals('Paid', $bill->fresh()->status);

        // Update bill
        $updateBillRes = $this->actingAs($this->admin)->put(route('billing.update', $bill), [
            'billing_date' => now()->format('Y-m-d'),
            'new_reading' => 75,
            'consumption' => 25,
            'base_charge' => 100,
            'usage_charge' => 225,
            'due_date' => now()->addDays(30)->format('Y-m-d'),
        ]);
        $updateBillRes->assertSessionHasNoErrors();
        $this->assertEquals(75, $this->customer->fresh()->meter_reading);

        // Destroy bill
        $destroyRes = $this->actingAs($this->admin)->delete(route('billing.destroy', $bill));
        $destroyRes->assertRedirect();
        $this->assertTrue($bill->fresh()->trashed());
    }

    public function test_reader_store_reading_and_bill_operations()
    {
        // Reader stores reading
        $res = $this->actingAs($this->readerUser)->post(route('reader.storeReading'), [
            'customer_id' => $this->customer->id,
            'reading' => 60,
        ]);
        $res->assertSessionHasNoErrors();
        $bill = Bill::where('customer_id', $this->customer->id)->latest('id')->first();
        $this->assertNotNull($bill);
        $this->assertEquals(60, $this->customer->fresh()->meter_reading);

        // Bill history json
        $histRes = $this->actingAs($this->readerUser)->get(route('reader.billHistory', $this->customer));
        $histRes->assertOk();
        $histRes->assertJsonStructure(['customer', 'bills']);

        // Reader view receipt
        $receiptRes = $this->actingAs($this->readerUser)->get(route('reader.receipt', $bill));
        $receiptRes->assertOk();

        // Reader delete bill
        $delRes = $this->actingAs($this->readerUser)->delete(route('reader.deleteBill', $bill));
        $delRes->assertOk();
        $this->assertTrue($bill->fresh()->trashed());
        $this->assertEquals(50, $this->customer->fresh()->meter_reading);
    }

    public function test_consumer_reading_and_announcements()
    {
        // Consumer announcements
        $resAnn = $this->actingAs($this->consumerUser)->get(route('consumer.announcements'));
        $resAnn->assertOk();

        // Consumer stores reading
        $res = $this->actingAs($this->consumerUser)->post(route('consumer.storeReading'), [
            'reading' => 55,
        ]);
        $res->assertSessionHasNoErrors();
        $this->assertEquals(55, $this->customer->fresh()->meter_reading);
    }

    public function test_messaging_and_posts()
    {
        // Admin index
        $res = $this->actingAs($this->admin)->get(route('messages.index'));
        $res->assertOk();

        // Consumer index
        $resCons = $this->actingAs($this->consumerUser)->get(route('messages.index'));
        $resCons->assertOk();

        // Admin sends message to consumer
        $msgRes = $this->actingAs($this->admin)->post(route('messages.store'), [
            'receiver_id' => $this->consumerUser->id,
            'message' => 'Hello from Admin',
        ]);
        $msgRes->assertRedirect();
        $msg = Message::latest('id')->first();
        $this->assertNotNull($msg);

        // Consumer marks read
        $readRes = $this->actingAs($this->consumerUser)->post(route('messages.markRead'), [
            'partner_id' => $this->admin->id,
        ]);
        $readRes->assertOk();

        // Admin posts announcement
        $postRes = $this->actingAs($this->admin)->post(route('messages.storePost'), [
            'title' => 'Water Interruption',
            'content' => 'Scheduled maintenance tomorrow.',
        ]);
        $postRes->assertRedirect();
        $this->assertDatabaseHas('posts', ['title' => 'Water Interruption']);

        // Message update
        $updRes = $this->actingAs($this->admin)->put(route('messages.update', $msg), [
            'message' => 'Updated message',
        ]);
        $updRes->assertOk();

        // Message destroy
        $delRes = $this->actingAs($this->admin)->delete(route('messages.destroy', $msg));
        $delRes->assertOk();
    }

    public function test_settings_lock_authorization_and_updates()
    {
        // Without session authorization, should show lock view
        $lockRes = $this->actingAs($this->admin)->get(route('settings.index'));
        $lockRes->assertOk();
        $lockRes->assertViewIs('settings.lock');

        // Authorize with admin password
        $authRes = $this->actingAs($this->admin)->post(route('settings.authorize'), [
            'password' => 'password123',
        ]);
        $authRes->assertRedirect(route('settings.index'));
        $this->assertTrue(session('settings_authorized'));

        // Authorized access to settings
        $settingsRes = $this->actingAs($this->admin)->get(route('settings.index'));
        $settingsRes->assertOk();
        $settingsRes->assertViewIs('settings.index');

        // Update settings
        $updateRes = $this->actingAs($this->admin)->post(route('settings.update'), [
            'admin_password_verification' => 'password123',
            'types' => [
                $this->customerType->id => [
                    'base_charge' => 120,
                    'usage_rate' => 18,
                    'green_max' => 15,
                    'orange_max' => 25,
                    'red_max' => 40,
                    'base_limit' => 10,
                ],
            ],
            'alert_threshold' => 1500,
            'alert_email' => 'alerts@example.com',
        ]);
        $updateRes->assertSessionHasNoErrors();
        $this->assertEquals(120, $this->customerType->fresh()->base_charge);
    }

    public function test_recovery_routes()
    {
        $res = $this->actingAs($this->admin)->get(route('recovery.index'));
        $res->assertOk();

        // Soft delete customer
        $this->customer->delete();
        $this->assertTrue($this->customer->fresh()->trashed());

        // Restore customer
        $restoreRes = $this->actingAs($this->admin)->post(route('recovery.restoreCustomer', $this->customer->id));
        $restoreRes->assertRedirect(route('recovery.index'));
        $this->assertFalse($this->customer->fresh()->trashed());
    }

    public function test_registration_codes()
    {
        $res = $this->actingAs($this->admin)->get(route('registration-codes.index'));
        $res->assertOk();

        // Generate code
        $genRes = $this->actingAs($this->admin)->post(route('registration-codes.store'));
        $genRes->assertRedirect();
        $code = RegistrationCode::first();
        $this->assertNotNull($code);

        // Delete code
        $delRes = $this->actingAs($this->admin)->delete(route('registration-codes.destroy', $code));
        $delRes->assertRedirect();
        $this->assertDatabaseMissing('registration_codes', ['id' => $code->id]);
    }

    public function test_zero_consumption_billing_in_reader_and_consumer()
    {
        // 1. Reader zero consumption test (reading equals current meter_reading)
        $readerRes = $this->actingAs($this->readerUser)->post(route('reader.storeReading'), [
            'customer_id' => $this->customer->id,
            'reading' => 50,
        ]);
        $readerRes->assertSessionHasNoErrors();
        $readerRes->assertRedirect(route('reader.dashboard'));
        
        $bill = Bill::where('customer_id', $this->customer->id)->latest('id')->first();
        $this->assertNotNull($bill);
        $this->assertEquals(0, $bill->consumption);
        $this->assertEquals(100, $bill->total_amount); // base charge

        // 2. Consumer zero consumption test with forced new billing
        $consRes = $this->actingAs($this->consumerUser)->post(route('consumer.storeReading'), [
            'reading' => 50,
        ]);
        $consRes->assertSessionHasNoErrors();
        $consRes->assertRedirect(route('dashboard'));
    }

    public function test_soft_deleted_customer_bill_views_and_receipts()
    {
        $bill = Bill::create([
            'customer_id' => $this->customer->id,
            'billing_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
            'previous_reading' => 40,
            'new_reading' => 50,
            'usage_units' => 10,
            'consumption' => 10,
            'base_charge' => 100,
            'usage_charge' => 0,
            'total_amount' => 100,
            'status' => 'Pending',
        ]);

        // Soft delete the customer
        $this->customer->delete();
        $this->assertTrue($this->customer->fresh()->trashed());

        // Viewing the bill show and receipt should NOT throw exception
        $showRes = $this->actingAs($this->admin)->get(route('billing.show', $bill));
        $showRes->assertOk();

        $receiptRes = $this->actingAs($this->admin)->get(route('billing.receipt', $bill));
        $receiptRes->assertOk();
    }

    public function test_consumer_cannot_perform_admin_customer_or_billing_actions()
    {
        $this->actingAs($this->consumerUser)
            ->post(route('customers.store'), [
                'customer_type_id' => $this->customerType->id,
                'first_name' => 'Test',
                'last_name' => 'User',
                'barangay' => 'BARANGAY 1',
                'meter_post' => 'Post 1',
            ])
            ->assertForbidden();

        $this->actingAs($this->consumerUser)
            ->delete(route('customers.destroy', $this->customer))
            ->assertForbidden();

        $this->actingAs($this->consumerUser)
            ->post(route('registration-codes.store'))
            ->assertForbidden();

        $this->actingAs($this->consumerUser)
            ->post(route('billing.store'), [
                'customer_id' => $this->customer->id,
                'new_reading' => 60,
            ])
            ->assertForbidden();
    }

    public function test_restoring_bill_updates_customer_meter_reading()
    {
        $bill = Bill::create([
            'customer_id' => $this->customer->id,
            'billing_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
            'previous_reading' => 30,
            'new_reading' => 50,
            'usage_units' => 20,
            'consumption' => 20,
            'base_charge' => 100,
            'usage_charge' => 150,
            'total_amount' => 250,
            'status' => 'Pending',
        ]);

        // Delete bill - meter reading reverts
        $this->actingAs($this->admin)->delete(route('billing.destroy', $bill));
        $this->assertEquals(30, $this->customer->fresh()->meter_reading);

        // Restore bill - meter reading should sync back to 50
        $this->actingAs($this->admin)->post(route('recovery.restoreBill', $bill->id));
        $this->assertEquals(50, $this->customer->fresh()->meter_reading);
    }
}
