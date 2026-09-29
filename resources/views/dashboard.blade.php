<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Driver Financial Dashboard') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ __('Overview for') }} <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ now()->format('F Y') }}</span> &bull; {{ __('Currency in Pakistani Rupees (Rs.)') }}
                </p>
            </div>
            <div class="inline-flex items-center gap-2 text-xs font-medium text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 px-3 py-1.5 rounded-full shadow-sm border border-gray-200 dark:border-gray-700">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                {{ __('Active Driver Account') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-sm shadow-sm">
                    <div class="font-semibold mb-1">{{ __('Please correct the following errors:') }}</div>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- 1. Summary Cards (Current Month) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Monthly Earnings Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-200 dark:border-gray-700/60 relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Monthly Earnings') }}</span>
                        <span class="p-2.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-4">
                        <div class="text-xs uppercase tracking-wider text-gray-400 font-semibold">{{ now()->format('F') }}</div>
                        <div class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">
                            Rs. {{ number_format($monthlyEarnings, 2) }}
                        </div>
                    </div>
                    <div class="mt-4 text-xs font-medium text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        <span>{{ __('Gross revenue from all platforms') }}</span>
                    </div>
                </div>

                {{-- Monthly Expenses Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-200 dark:border-gray-700/60 relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Monthly Expenses') }}</span>
                        <span class="p-2.5 bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-4">
                        <div class="text-xs uppercase tracking-wider text-gray-400 font-semibold">{{ now()->format('F') }}</div>
                        <div class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">
                            Rs. {{ number_format($monthlyExpenses, 2) }}
                        </div>
                    </div>
                    <div class="mt-4 text-xs font-medium text-rose-600 dark:text-rose-400 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                        <span>{{ __('Fuel, maintenance & vehicle costs') }}</span>
                    </div>
                </div>

                {{-- Net Profit Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-200 dark:border-gray-700/60 relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Net Profit') }}</span>
                        <span class="p-2.5 {{ $monthlyNetProfit >= 0 ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400' : 'bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400' }} rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-4">
                        <div class="text-xs uppercase tracking-wider text-gray-400 font-semibold">{{ now()->format('F') }} ({{ __('Take Home') }})</div>
                        <div class="text-3xl font-extrabold {{ $monthlyNetProfit >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }} mt-1">
                            Rs. {{ number_format($monthlyNetProfit, 2) }}
                        </div>
                    </div>
                    <div class="mt-4 text-xs font-medium text-gray-500 dark:text-gray-400">
                        @if ($monthlyEarnings > 0)
                            {{ __('Profit Margin:') }} <span class="font-semibold text-gray-700 dark:text-gray-200">{{ number_format(($monthlyNetProfit / $monthlyEarnings) * 100, 1) }}%</span>
                        @else
                            {{ __('Earnings - Expenses') }}
                        @endif
                    </div>
                </div>
            </div>

            {{-- 2. Quick Add Forms (Side-by-side compact panels) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Quick Add Earning --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700/60 p-6">
                    <div class="flex items-center gap-3 pb-4 mb-4 border-b border-gray-100 dark:border-gray-700">
                        <div class="p-2 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ __('Quick Add Earning') }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Record your shift payout or platform income') }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('earnings.store') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="earning_amount" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                    {{ __('Amount (Rs.)') }} <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 text-sm font-semibold">
                                        Rs.
                                    </div>
                                    <input type="number" step="0.01" min="0.01" max="9999999.99" name="amount" id="earning_amount" required
                                           placeholder="2500.00"
                                           class="pl-11 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700/70 dark:text-white text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                                </div>
                            </div>

                            <div>
                                <label for="platform" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                    {{ __('Platform') }} <span class="text-rose-500">*</span>
                                </label>
                                <select name="platform" id="platform" required
                                        class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700/70 dark:text-white text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    @foreach ($platforms as $platform)
                                        <option value="{{ $platform }}">{{ $platform }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="earning_date" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                    {{ __('Date') }} <span class="text-rose-500">*</span>
                                </label>
                                <input type="date" name="date" id="earning_date" required value="{{ date('Y-m-d') }}"
                                       class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700/70 dark:text-white text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                            </div>

                            <div>
                                <label for="earning_notes" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                    {{ __('Notes (Optional)') }}
                                </label>
                                <input type="text" name="notes" id="earning_notes" placeholder="e.g. Peak hour surge, airport drop"
                                       class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700/70 dark:text-white text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-medium rounded-lg text-sm shadow-sm transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            {{ __('Save Earning') }}
                        </button>
                    </form>
                </div>

                {{-- Quick Add Expense --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700/60 p-6">
                    <div class="flex items-center gap-3 pb-4 mb-4 border-b border-gray-100 dark:border-gray-700">
                        <div class="p-2 rounded-lg bg-rose-100 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ __('Quick Add Expense') }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Log fuel, toll tax, challans, or maintenance') }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('expenses.store') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="expense_amount" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                    {{ __('Amount (Rs.)') }} <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 text-sm font-semibold">
                                        Rs.
                                    </div>
                                    <input type="number" step="0.01" min="0.01" max="9999999.99" name="amount" id="expense_amount" required
                                           placeholder="1200.00"
                                           class="pl-11 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700/70 dark:text-white text-sm focus:border-rose-500 focus:ring-rose-500" />
                                </div>
                            </div>

                            <div>
                                <label for="category" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                    {{ __('Category') }} <span class="text-rose-500">*</span>
                                </label>
                                <select name="category" id="category" required
                                        class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700/70 dark:text-white text-sm focus:border-rose-500 focus:ring-rose-500">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category }}">{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="expense_date" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                    {{ __('Date') }} <span class="text-rose-500">*</span>
                                </label>
                                <input type="date" name="date" id="expense_date" required value="{{ date('Y-m-d') }}"
                                       class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700/70 dark:text-white text-sm focus:border-rose-500 focus:ring-rose-500" />
                            </div>

                            <div>
                                <label for="expense_notes" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                    {{ __('Notes (Optional)') }}
                                </label>
                                <input type="text" name="notes" id="expense_notes" placeholder="e.g. Petrol PSO pump, Motorway M2 toll"
                                       class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700/70 dark:text-white text-sm focus:border-rose-500 focus:ring-rose-500" />
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white font-medium rounded-lg text-sm shadow-sm transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            {{ __('Save Expense') }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- 3. Data Visualization Charts --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- 30-Day Comparison Chart (Earnings vs Expenses) --}}
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700/60 p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pb-4 border-b border-gray-100 dark:border-gray-700">
                            <div>
                                <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ __('Cash Flow Trend (Last 30 Days)') }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Comparison of daily earnings against daily operating expenses') }}</p>
                            </div>
                            <div class="flex items-center gap-4 text-xs font-semibold">
                                <span class="inline-flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400">
                                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span> {{ __('Earnings') }}
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-rose-600 dark:text-rose-400">
                                    <span class="w-3 h-3 rounded-full bg-rose-500"></span> {{ __('Expenses') }}
                                </span>
                            </div>
                        </div>
                        <div class="relative mt-4 h-72 sm:h-80 w-full">
                            <canvas id="timeSeriesChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Categorical Expense Breakdown (Doughnut Chart) --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700/60 p-6 flex flex-col justify-between">
                    <div>
                        <div class="pb-4 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ __('Expense Categories') }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Breakdown for') }} {{ now()->format('F Y') }}</p>
                        </div>

                        <div class="relative mt-4 h-72 sm:h-80 flex items-center justify-center">
                            @if ($categoryChart['hasData'])
                                <canvas id="categoryChart"></canvas>
                            @else
                                <div class="text-center p-6">
                                    <div class="mx-auto w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('No expenses logged this month') }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ __('Expenses added above will show in this breakdown chart.') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Recent Transactions (Mixed Earnings & Expenses) --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700/60 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ __('Recent Activity') }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('The 5 most recent transactions logged across your account') }}</p>
                    </div>
                </div>

                @if ($recentTransactions->isEmpty())
                    <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('No transactions recorded yet') }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ __('Use the quick add forms above to log your first ride earning or vehicle expense.') }}</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-900/40 text-xs uppercase font-semibold text-gray-500 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left">{{ __('Type') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left">{{ __('Platform / Category') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left">{{ __('Date') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left">{{ __('Notes') }}</th>
                                    <th scope="col" class="px-6 py-3 text-right">{{ __('Amount') }}</th>
                                    <th scope="col" class="px-6 py-3 text-right">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                                @foreach ($recentTransactions as $transaction)
                                    <tr class="hover:bg-gray-50/70 dark:hover:bg-gray-700/30 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($transaction['type'] === 'earning')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                                    {{ __('Earning') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                                    {{ __('Expense') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">
                                            {{ $transaction['title'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                            {{ $transaction['date'] instanceof \Carbon\CarbonInterface ? $transaction['date']->format('d M, Y') : $transaction['date'] }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                            {{ $transaction['notes'] ?: '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right font-bold {{ $transaction['type'] === 'earning' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                            {{ $transaction['type'] === 'earning' ? '+' : '-' }} Rs. {{ number_format($transaction['amount'], 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <form method="POST" action="{{ $transaction['delete_url'] }}" onsubmit="return confirm('{{ __('Are you sure you want to delete this record?') }}');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors p-1" title="{{ __('Delete') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Chart.js Library via CDN & Chart Initialization Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Configure Chart.js global defaults
            Chart.defaults.font.family = 'Figtree, ui-sans-serif, system-ui, sans-serif';
            Chart.defaults.color = '#94a3b8';

            // 1. Time Series Chart (Earnings vs Expenses - Last 30 Days)
            const timeSeriesCtx = document.getElementById('timeSeriesChart');
            if (timeSeriesCtx) {
                const timeSeriesLabels = @json($timeSeriesChart['labels']);
                const earningsData = @json($timeSeriesChart['earnings']);
                const expensesData = @json($timeSeriesChart['expenses']);

                new Chart(timeSeriesCtx, {
                    type: 'line',
                    data: {
                        labels: timeSeriesLabels,
                        datasets: [
                            {
                                label: 'Earnings (Rs.)',
                                data: earningsData,
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.15)',
                                borderWidth: 2.5,
                                fill: true,
                                tension: 0.35,
                                pointBackgroundColor: '#10b981',
                                pointRadius: 3,
                                pointHoverRadius: 6,
                            },
                            {
                                label: 'Expenses (Rs.)',
                                data: expensesData,
                                borderColor: '#f43f5e',
                                backgroundColor: 'rgba(244, 63, 94, 0.12)',
                                borderWidth: 2.5,
                                fill: true,
                                tension: 0.35,
                                pointBackgroundColor: '#f43f5e',
                                pointRadius: 3,
                                pointHoverRadius: 6,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label = label.replace(' (Rs.)', '') + ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += 'Rs. ' + Number(context.parsed.y).toLocaleString('en-US', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            });
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false,
                                },
                                ticks: {
                                    maxTicksLimit: 10,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(148, 163, 184, 0.1)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return 'Rs. ' + value.toLocaleString();
                                    },
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // 2. Category Doughnut Chart (Current Month Expenses)
            const categoryCtx = document.getElementById('categoryChart');
            if (categoryCtx) {
                const categoryLabels = @json($categoryChart['labels']);
                const categoryData = @json($categoryChart['data']);

                // Vibrant, semantic color palette for driver expenses
                const colorPalette = [
                    '#f59e0b', // Amber (Fuel)
                    '#3b82f6', // Blue (Maintenance)
                    '#ef4444', // Red (Challan/Fines)
                    '#8b5cf6', // Violet (Tolls)
                    '#06b6d4', // Cyan (Car Wash)
                    '#10b981', // Emerald (Meals)
                    '#64748b'  // Slate (Other)
                ];

                new Chart(categoryCtx, {
                    type: 'doughnut',
                    data: {
                        labels: categoryLabels,
                        datasets: [{
                            data: categoryData,
                            backgroundColor: colorPalette.slice(0, categoryLabels.length),
                            borderWidth: 2,
                            borderColor: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '68%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    padding: 14,
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const val = Number(context.raw || 0);
                                        return ' ' + label + ': Rs. ' + val.toLocaleString('en-US', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
