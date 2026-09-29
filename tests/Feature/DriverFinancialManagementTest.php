<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Earning;
use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DriverFinancialManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login_when_accessing_dashboard(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_driver_can_view_dashboard_with_accurate_metrics(): void
    {
        $driver = User::factory()->create();

        // Current month transactions
        Earning::create([
            'user_id' => $driver->id,
            'amount' => 5000.00,
            'platform' => 'Uber',
            'date' => Carbon::now()->toDateString(),
            'notes' => 'Airport rides',
        ]);

        Earning::create([
            'user_id' => $driver->id,
            'amount' => 3000.00,
            'platform' => 'Careem',
            'date' => Carbon::now()->subDays(2)->toDateString(),
            'notes' => 'City rides',
        ]);

        Expense::create([
            'user_id' => $driver->id,
            'amount' => 2500.00,
            'category' => 'Fuel',
            'date' => Carbon::now()->toDateString(),
            'notes' => 'Full tank petrol',
        ]);

        Expense::create([
            'user_id' => $driver->id,
            'amount' => 500.00,
            'category' => 'Car Wash',
            'date' => Carbon::now()->subDays(1)->toDateString(),
            'notes' => 'Complete wash',
        ]);

        $response = $this->actingAs($driver)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewHas('monthlyEarnings', 8000.00);
        $response->assertViewHas('monthlyExpenses', 3000.00);
        $response->assertViewHas('monthlyNetProfit', 5000.00);
        $response->assertSee('Rs. 8,000.00');
        $response->assertSee('Rs. 3,000.00');
        $response->assertSee('Rs. 5,000.00');
    }

    public function test_driver_can_store_new_earning(): void
    {
        $driver = User::factory()->create();

        $response = $this->actingAs($driver)->post(route('earnings.store'), [
            'amount' => 3500.50,
            'platform' => 'InDrive',
            'date' => Carbon::now()->toDateString(),
            'notes' => 'Evening rush hour',
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('earnings', [
            'user_id' => $driver->id,
            'amount' => 3500.50,
            'platform' => 'InDrive',
            'notes' => 'Evening rush hour',
        ]);
    }

    public function test_driver_can_store_new_expense(): void
    {
        $driver = User::factory()->create();

        $response = $this->actingAs($driver)->post(route('expenses.store'), [
            'amount' => 1200.00,
            'category' => 'Fuel',
            'date' => Carbon::now()->toDateString(),
            'notes' => 'PSO Fuel',
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('expenses', [
            'user_id' => $driver->id,
            'amount' => 1200.00,
            'category' => 'Fuel',
            'notes' => 'PSO Fuel',
        ]);
    }

    public function test_driver_can_delete_their_own_earning_and_not_others(): void
    {
        $driver1 = User::factory()->create();
        $driver2 = User::factory()->create();

        $earning = Earning::create([
            'user_id' => $driver1->id,
            'amount' => 2000.00,
            'platform' => 'Yango',
            'date' => Carbon::now()->toDateString(),
        ]);

        // Driver 2 cannot delete Driver 1's earning
        $unauthorizedResponse = $this->actingAs($driver2)->delete(route('earnings.destroy', $earning));
        $unauthorizedResponse->assertForbidden();

        // Driver 1 can delete their earning
        $authorizedResponse = $this->actingAs($driver1)->delete(route('earnings.destroy', $earning));
        $authorizedResponse->assertRedirect(route('dashboard'));

        $this->assertDatabaseMissing('earnings', [
            'id' => $earning->id,
        ]);
    }

    public function test_driver_can_delete_their_own_expense_and_not_others(): void
    {
        $driver1 = User::factory()->create();
        $driver2 = User::factory()->create();

        $expense = Expense::create([
            'user_id' => $driver1->id,
            'amount' => 450.00,
            'category' => 'Tolls',
            'date' => Carbon::now()->toDateString(),
        ]);

        // Driver 2 cannot delete Driver 1's expense
        $unauthorizedResponse = $this->actingAs($driver2)->delete(route('expenses.destroy', $expense));
        $unauthorizedResponse->assertForbidden();

        // Driver 1 can delete their expense
        $authorizedResponse = $this->actingAs($driver1)->delete(route('expenses.destroy', $expense));
        $authorizedResponse->assertRedirect(route('dashboard'));

        $this->assertDatabaseMissing('expenses', [
            'id' => $expense->id,
        ]);
    }

    public function test_validation_fails_for_invalid_platform_or_category(): void
    {
        $driver = User::factory()->create();

        $earningResponse = $this->actingAs($driver)->post(route('earnings.store'), [
            'amount' => 1000,
            'platform' => 'InvalidPlatform',
            'date' => '2026-09-30',
        ]);
        $earningResponse->assertSessionHasErrors(['platform']);

        $expenseResponse = $this->actingAs($driver)->post(route('expenses.store'), [
            'amount' => 1000,
            'category' => 'InvalidCategory',
            'date' => '2026-09-30',
        ]);
        $expenseResponse->assertSessionHasErrors(['category']);
    }
}
