<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $showArchived = $request->boolean('archived');

        $customers = Customer::query()
            ->where('is_archived', $showArchived)
            ->withCount('sales')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('full_name')
            ->paginate(10)
            ->withQueryString();

        return view('customers', compact(
            'customers',
            'search',
            'showArchived'
        ));
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'customer_type' => ['required', Rule::in(['suki', 'credit'])],
            'credit_limit' => [
                'nullable',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],
        ]);

        if ($validated['customer_type'] === 'suki') {
            $validated['credit_limit'] = 0;
        }

        $validated['credit_limit'] = $validated['credit_limit'] ?? 0;
        $validated['balance'] = 0;
        $validated['is_archived'] = false;

        Customer::create($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer added successfully.');
    }

    public function edit(Customer $customer): View
    {
        abort_if($customer->is_archived, 404);

        return view('customers.edit', compact('customer'));
    }

    public function update(
        Request $request,
        Customer $customer
    ): RedirectResponse {
        abort_if($customer->is_archived, 404);

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'customer_type' => ['required', Rule::in(['suki', 'credit'])],
            'credit_limit' => [
                'nullable',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],
        ]);

        if ($validated['customer_type'] === 'suki') {
            if ((float) $customer->balance > 0) {
                return back()
                    ->withErrors([
                        'customer_type' =>
                            'A customer with an outstanding balance cannot be changed to Suki.',
                    ])
                    ->withInput();
            }

            $validated['credit_limit'] = 0;
        }

        $validated['credit_limit'] = $validated['credit_limit'] ?? 0;

        if (
            $validated['customer_type'] === 'credit' &&
            (float) $validated['credit_limit'] <
            (float) $customer->balance
        ) {
            return back()
                ->withErrors([
                    'credit_limit' =>
                        'Credit limit cannot be lower than the outstanding balance.',
                ])
                ->withInput();
        }

        $customer->update($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->update([
            'is_archived' => true,
        ]);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer archived successfully.');
    }

    public function restore(Customer $customer): RedirectResponse
    {
        $customer->update([
            'is_archived' => false,
        ]);

        return redirect()
            ->route('customers.index', ['archived' => 1])
            ->with('success', 'Customer restored successfully.');
    }

    public function forceDestroy(Customer $customer): RedirectResponse
    {
        if ($customer->sales()->exists()) {
            return back()->withErrors([
                'delete' =>
                    'This customer cannot be deleted because they have sales history.',
            ]);
        }

        $customer->delete();

        return redirect()
            ->route('customers.index', ['archived' => 1])
            ->with('success', 'Customer permanently deleted.');
    }
}