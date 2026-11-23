<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    // SALES HISTORY WITH FILTERS
    public function index(Request $request)
    {
        $filters = $this->validateFilters($request);

        $sales = $this->filteredSales($filters)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $cashiers = User::orderBy('full_name')->get();

        $customers = Customer::orderBy('full_name')->get();

        return view('sales.index', compact(
            'sales',
            'cashiers',
            'customers'
        ));
    }

    // VALIDATE SALES HISTORY FILTERS
    private function validateFilters(Request $request): array
    {
        return $request->validate([
            'date_from' => ['nullable', 'date_format:Y-m-d'],
            'date_to' => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:date_from',
            ],
            'cashier_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],
            'customer_id' => [
                'nullable',
                'integer',
                'exists:customers,id',
            ],
        ]);
    }

    // SHARED QUERY FOR SALES HISTORY AND EXPORT
    private function filteredSales(array $filters)
    {
        return Sale::with([
            'customer',
            'user',
            'items.product',
        ])
        ->when(
            !empty($filters['date_from']),
            fn ($query) => $query->whereDate(
                'created_at',
                '>=',
                $filters['date_from']
            )
        )
        ->when(
            !empty($filters['date_to']),
            fn ($query) => $query->whereDate(
                'created_at',
                '<=',
                $filters['date_to']
            )
        )
        ->when(
            !empty($filters['cashier_id']),
            fn ($query) => $query->where(
                'user_id',
                $filters['cashier_id']
            )
        )
        ->when(
            !empty($filters['customer_id']),
            fn ($query) => $query->where(
                'customer_id',
                $filters['customer_id']
            )
        );
    }

    // EXPORT SALES HISTORY TO CSV
    public function export(Request $request)
    {
        $filters = $this->validateFilters($request);

        $sales = $this->filteredSales($filters)
            ->orderByDesc('id')
            ->get();

        $filename = 'sukli-sales-' .
            now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(
            function () use ($sales) {
                $file = fopen('php://output', 'w');

                // UTF-8 BOM for Excel compatibility
                fwrite($file, "\xEF\xBB\xBF");

                fputcsv($file, [
                    'Receipt ID',
                    'Date and Time',
                    'Products',
                    'Customer',
                    'Cashier',
                    'Payment Method',
                    'Total Amount',
                    'Cash Received',
                    'Change',
                ]);

                // Prevent spreadsheet formula injection
                $safeText = function ($value) {
                    $value = (string) $value;

                    if (preg_match('/^[\s\x00-\x1F]*[=+\-@]/u', $value)) {
                        return "'" . $value;
                    }

                    return $value;
                };

                foreach ($sales as $sale) {
                    $products = $sale->items
                        ->map(function ($item) {
                            $name = $item->product?->name
                                ?? 'Unavailable Product';

                            return $name . ' x ' . $item->quantity;
                        })
                        ->implode('; ');

                    fputcsv($file, [
                        'R-' . str_pad(
                            $sale->id,
                            6,
                            '0',
                            STR_PAD_LEFT
                        ),
                        $sale->created_at->format('Y-m-d H:i:s'),
                        $safeText($products),
                        $safeText(
                            $sale->customer?->full_name
                            ?? 'Walk-in Customer'
                        ),
                        $safeText(
                            $sale->user?->full_name
                            ?? 'Unknown Staff'
                        ),
                        $sale->payment_method,
                        $sale->total_amount,
                        $sale->cash_received,
                        $sale->change_amount,
                    ]);
                }

                fclose($file);
            },
            $filename,
            ['Content-Type' => 'text/csv; charset=UTF-8']
        );
    }

    // DISPLAY RECORD SALE FORM
    public function create()
    {
        $customers = Customer::where('is_archived', 0)
            ->orderBy('full_name')
            ->get();

        $products = Product::where('is_archived', 0)
            ->where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->get();

        return view('sales.create', compact(
            'customers',
            'products'
        ));
    }

    // SAVE CASH SALE AND DEDUCT STOCK
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => [
                'nullable',
                'integer',
                Rule::exists('customers', 'id')
                    ->where('is_archived', 0),
            ],

            'payment_method' => [
                'required',
                Rule::in(['cash']),
            ],

            'cash_received' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999.99',
                'decimal:0,2',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.product_id' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('products', 'id')
                    ->where('is_archived', 0),
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        DB::transaction(function () use ($validated, $request) {

            $totalCents = 0;
            $saleItems = [];

            // VALIDATE PRODUCTS AND CALCULATE TOTAL
            foreach ($validated['items'] as $item) {

                $product = Product::whereKey($item['product_id'])
                    ->where('is_archived', 0)
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw ValidationException::withMessages([
                        'items' =>
                            'The selected product is unavailable or archived.',
                    ]);
                }

                $quantity = (int) $item['quantity'];

                if ($product->stock_quantity < $quantity) {
                    throw ValidationException::withMessages([
                        'items' =>
                            "Insufficient stock for {$product->name}.",
                    ]);
                }

                $unitPriceCents = (int) round(
                    ((float) $product->price) * 100
                );

                $subtotalCents = $unitPriceCents * $quantity;

                $totalCents += $subtotalCents;

                $saleItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPriceCents / 100,
                    'subtotal' => $subtotalCents / 100,
                ];
            }

            // CALCULATE CASH RECEIVED
            $cashCents = (int) round(
                ((float) $validated['cash_received']) * 100
            );

            // PREVENT INSUFFICIENT PAYMENT
            if ($cashCents < $totalCents) {
                throw ValidationException::withMessages([
                    'cash_received' =>
                        'Insufficient cash received. Please enter an amount equal to or greater than the total.',
                ]);
            }

            // VALIDATE CUSTOMER
            $customerId = $validated['customer_id'] ?? null;

            if ($customerId !== null) {

                $customerExists = Customer::whereKey($customerId)
                    ->where('is_archived', 0)
                    ->exists();

                if (!$customerExists) {
                    throw ValidationException::withMessages([
                        'customer_id' =>
                            'Selected customer is no longer active.',
                    ]);
                }
            }

            // CREATE SALE
            $sale = Sale::create([
                'customer_id' => $customerId,
                'user_id' => $request->user()->id,
                'total_amount' => $totalCents / 100,
                'payment_method' => 'cash',
                'cash_received' => $cashCents / 100,
                'change_amount' =>
                    ($cashCents - $totalCents) / 100,
            ]);

            // SAVE SALE ITEMS AND DEDUCT STOCK
            foreach ($saleItems as $item) {

                $sale->items()->create([
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                ]);

                $item['product']->decrement(
                    'stock_quantity',
                    $item['quantity']
                );
            }

        }, 3);

        return redirect()
            ->route('sales.index')
            ->with('success', 'Sale recorded successfully!');
    }

    // DISPLAY TRANSACTION DETAILS
    public function show(Sale $sale)
    {
        $sale->load([
            'customer',
            'user',
            'items.product',
        ]);

        return view('sales.show', compact('sale'));
    }

    // DOWNLOAD RECEIPT AS PDF
    public function receipt(Sale $sale)
    {
        // Load transaction relationships
        $sale->load([
            'customer',
            'user',
            'items.product',
        ]);

        // Generate receipt number
        $receiptNumber = 'R-' . str_pad(
            $sale->id,
            6,
            '0',
            STR_PAD_LEFT
        );

        // Generate PDF using Blade template
        $pdf = Pdf::loadView('sales.receipt', [
            'sale' => $sale,
        ]);

        // Receipt paper size
        $pdf->setPaper('a5', 'portrait');

        // Download receipt
        return $pdf->download(
            'SUKLI-POS-' . $receiptNumber . '.pdf'
        );
    }
}
