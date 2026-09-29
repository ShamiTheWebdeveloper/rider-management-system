<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Earning;
use App\Models\Expense;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the driver financial dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $now = Carbon::now();

        // 1. Current Month Summary Stats
        $startOfMonth = $now->copy()->startOfMonth()->toDateString();
        $endOfMonth = $now->copy()->endOfMonth()->toDateString();

        $monthlyEarnings = (float) $user->earnings()
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $monthlyExpenses = (float) $user->expenses()
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $monthlyNetProfit = $monthlyEarnings - $monthlyExpenses;

        // 2. Chart Data (Time Series - Last 30 Days)
        $startDate = $now->copy()->subDays(29)->startOfDay();
        $endDate = $now->copy()->endOfDay();

        $dailyEarningsRaw = $user->earnings()
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->selectRaw('date, SUM(amount) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $dailyExpensesRaw = $user->expenses()
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->selectRaw('date, SUM(amount) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $timeSeriesLabels = [];
        $timeSeriesEarnings = [];
        $timeSeriesExpenses = [];

        $period = CarbonPeriod::create($startDate->toDateString(), $endDate->toDateString());
        foreach ($period as $date) {
            $dateKey = $date->format('Y-m-d');
            $timeSeriesLabels[] = $date->format('d M');
            $timeSeriesEarnings[] = round((float) ($dailyEarningsRaw[$dateKey] ?? 0), 2);
            $timeSeriesExpenses[] = round((float) ($dailyExpensesRaw[$dateKey] ?? 0), 2);
        }

        // 3. Chart Data (Categorical - Expenses by Category for Current Month)
        $expensesByCategory = $user->expenses()
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $categoryLabels = [];
        $categoryData = [];

        foreach (Expense::CATEGORIES as $category) {
            $amount = round((float) ($expensesByCategory[$category] ?? 0), 2);
            if ($amount > 0) {
                $categoryLabels[] = $category;
                $categoryData[] = $amount;
            }
        }

        // 4. Recent Activity: 5 most recent transactions (mixed earnings and expenses)
        $recentEarnings = $user->earnings()
            ->latest('date')
            ->latest('id')
            ->take(5)
            ->get()
            ->map(fn (Earning $earning): array => [
                'id' => $earning->id,
                'type' => 'earning',
                'title' => $earning->platform,
                'amount' => (float) $earning->amount,
                'date' => $earning->date,
                'notes' => $earning->notes,
                'created_at' => $earning->created_at,
                'delete_url' => route('earnings.destroy', $earning),
            ]);

        $recentExpenses = $user->expenses()
            ->latest('date')
            ->latest('id')
            ->take(5)
            ->get()
            ->map(fn (Expense $expense): array => [
                'id' => $expense->id,
                'type' => 'expense',
                'title' => $expense->category,
                'amount' => (float) $expense->amount,
                'date' => $expense->date,
                'notes' => $expense->notes,
                'created_at' => $expense->created_at,
                'delete_url' => route('expenses.destroy', $expense),
            ]);

        $recentTransactions = $recentEarnings->concat($recentExpenses)
            ->sortByDesc(function (array $item): string {
                $dateStr = $item['date'] instanceof \Carbon\CarbonInterface
                    ? $item['date']->format('Y-m-d')
                    : (string) $item['date'];
                $timestamp = $item['created_at'] instanceof \Carbon\CarbonInterface
                    ? (string) $item['created_at']->timestamp
                    : '0';

                return $dateStr . '_' . str_pad($timestamp, 12, '0', STR_PAD_LEFT);
            })
            ->take(5)
            ->values();

        return view('dashboard', [
            'monthlyEarnings' => $monthlyEarnings,
            'monthlyExpenses' => $monthlyExpenses,
            'monthlyNetProfit' => $monthlyNetProfit,
            'timeSeriesChart' => [
                'labels' => $timeSeriesLabels,
                'earnings' => $timeSeriesEarnings,
                'expenses' => $timeSeriesExpenses,
            ],
            'categoryChart' => [
                'labels' => $categoryLabels,
                'data' => $categoryData,
                'hasData' => count($categoryData) > 0,
            ],
            'recentTransactions' => $recentTransactions,
            'platforms' => Earning::PLATFORMS,
            'categories' => Expense::CATEGORIES,
        ]);
    }
}
