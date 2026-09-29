<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExpenseController extends Controller
{
    /**
     * Store a newly created expense in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999.99'],
            'category' => ['required', 'string', Rule::in(Expense::CATEGORIES)],
            'date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $expense = $request->user()->expenses()->create($validated);

        return redirect()
            ->route('dashboard')
            ->with('success', sprintf('Expense of Rs. %s for %s recorded successfully!', number_format((float) $expense->amount, 2), $expense->category));
    }

    /**
     * Remove the specified expense from storage.
     */
    public function destroy(Request $request, Expense $expense): RedirectResponse
    {
        abort_if($expense->user_id !== $request->user()->id, 403, 'Unauthorized access.');

        $amount = (float) $expense->amount;
        $category = $expense->category;

        $expense->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', sprintf('Expense of Rs. %s for %s deleted successfully!', number_format($amount, 2), $category));
    }
}
