<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Earning;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EarningController extends Controller
{
    /**
     * Store a newly created earning in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999.99'],
            'platform' => ['required', 'string', Rule::in(Earning::PLATFORMS)],
            'date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $earning = $request->user()->earnings()->create($validated);

        return redirect()
            ->route('dashboard')
            ->with('success', sprintf('Earning of Rs. %s from %s recorded successfully!', number_format((float) $earning->amount, 2), $earning->platform));
    }

    /**
     * Remove the specified earning from storage.
     */
    public function destroy(Request $request, Earning $earning): RedirectResponse
    {
        abort_if($earning->user_id !== $request->user()->id, 403, 'Unauthorized access.');

        $amount = (float) $earning->amount;
        $platform = $earning->platform;

        $earning->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', sprintf('Earning of Rs. %s from %s deleted successfully!', number_format($amount, 2), $platform));
    }
}
