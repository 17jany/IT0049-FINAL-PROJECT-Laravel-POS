@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $staff = auth()->user();
        $staffName = $staff?->full_name ?? $staff?->name ?? 'Staff';
        $hour = now()->hour;

        $greeting = $hour < 12
            ? 'Good morning'
            : ($hour < 18 ? 'Good afternoon' : 'Good evening');

        $recentSales = $recentSales ?? collect();
        $lowStockProducts = $lowStockProducts ?? collect();
    @endphp

    <style>
        .dashboard-page {
            width: 100%;
            margin: 18px auto 30px;
        }

        .dashboard-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 18px;
        }

        .dashboard-title {
            margin: 0 0 5px;
            color: #17191c;
            font-size: 30px;
            font-weight: 700;
            line-height: 1.2;
        }

        .dashboard-greeting {
            margin: 0 0 3px;
            color: #25282c;
            font-size: 17px;
            font-weight: 700;
        }

        .dashboard-description {
            margin: 0;
            color: #626b75;
            font-size: 13px;
        }

        .dashboard-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dashboard-date {
            padding: 9px 11px;
            color: #454b52;
            background: #fff;
            border: 1px solid #d9dee4;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
        }

        .dashboard-sale-button {
            display: inline-block;
            padding: 10px 14px;
            color: #fff;
            background: #1769c2;
            border: 1px solid #1769c2;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
        }

        .dashboard-sale-button:hover {
            color: #fff;
            background: #1057a4;
            border-color: #1057a4;
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 11px;
            margin-bottom: 14px;
        }

        .dashboard-stat {
            min-width: 0;
            padding: 14px 15px;
            background: #fff;
            border: 1px solid #dfe3e8;
            border-radius: 7px;
            box-shadow: 0 2px 6px rgba(20, 25, 30, 0.05);
        }

        .dashboard-stat-label {
            margin: 0 0 6px;
            color: #535b64;
            font-size: 12px;
        }

        .dashboard-stat-value {
            margin: 0;
            color: #17191c;
            font-size: 23px;
            font-weight: 700;
            line-height: 1.2;
        }

        .dashboard-panels {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(0, 0.9fr);
            gap: 13px;
        }

        .dashboard-panel {
            min-width: 0;
            padding: 14px;
            background: #fff;
            border: 1px solid #dfe3e8;
            border-radius: 7px;
            box-shadow: 0 2px 6px rgba(20, 25, 30, 0.05);
        }

        .dashboard-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
        }

        .dashboard-panel-header h2 {
            margin: 0;
            color: #202124;
            font-size: 15px;
            font-weight: 700;
        }

        .dashboard-panel-header a {
            color: #1769c2;
            font-size: 12px;
            text-decoration: none;
            white-space: nowrap;
        }

        .dashboard-panel-header a:hover {
            color: #1057a4;
            text-decoration: underline;
        }

        .dashboard-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .dashboard-page table.dashboard-table {
            width: 100%;
            min-width: 0;
            margin: 0;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 12px;
        }

        .dashboard-page table.dashboard-table th,
        .dashboard-page table.dashboard-table td {
            padding: 8px 7px;
            border-bottom: 1px solid #e5e8eb;
            text-align: left;
            overflow-wrap: anywhere;
        }

        .dashboard-page table.dashboard-table th {
            color: #40474f;
            background: #edf0f3;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.35px;
            text-transform: uppercase;
        }

        .dashboard-page table.dashboard-table td:last-child,
        .dashboard-page table.dashboard-table th:last-child {
            text-align: right;
        }

        .dashboard-page table.dashboard-table .dashboard-amount {
            white-space: nowrap;
        }

        .dashboard-stock-status {
            display: inline-block;
            padding: 3px 7px;
            color: #765000;
            background: #fff0bd;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
        }

        .dashboard-empty {
            margin: 0;
            padding: 16px 8px;
            color: #707780;
            font-size: 12px;
            text-align: center;
        }

        @media (max-width: 800px) {
            .dashboard-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .dashboard-panels {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 560px) {
            .dashboard-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .dashboard-header-actions {
                width: 100%;
                justify-content: space-between;
            }

            .dashboard-title {
                font-size: 26px;
            }

            .dashboard-stat {
                padding: 12px;
            }

            .dashboard-stat-value {
                font-size: 20px;
            }
        }
    </style>

    <section class="dashboard-page">
        <header class="dashboard-header">
            <div>
                <h1 class="dashboard-title">Dashboard</h1>
                <p class="dashboard-greeting">
                    {{ $greeting }}, {{ $staffName }}.
                </p>
                <p class="dashboard-description">
                    Here’s what’s happening at your store today.
                </p>
            </div>

            <div class="dashboard-header-actions">
                <span class="dashboard-date">{{ now()->format('M j, Y') }}</span>
                <a class="dashboard-sale-button" href="{{ url('/sales/create') }}">
                    Record a sale
                </a>
            </div>
        </header>

        <div class="dashboard-stats">
            <article class="dashboard-stat">
                <p class="dashboard-stat-label">Today’s sales</p>
                <p class="dashboard-stat-value">
                    ₱{{ number_format((float) ($todaySales ?? 0), 2) }}
                </p>
            </article>

            <article class="dashboard-stat">
                <p class="dashboard-stat-label">Transactions</p>
                <p class="dashboard-stat-value">{{ $todayTransactions ?? 0 }}</p>
            </article>

            <article class="dashboard-stat">
                <p class="dashboard-stat-label">Products</p>
                <p class="dashboard-stat-value">{{ $productCount ?? 0 }}</p>
            </article>

            <article class="dashboard-stat">
                <p class="dashboard-stat-label">Low stock</p>
                <p class="dashboard-stat-value">{{ $lowStockCount ?? 0 }}</p>
            </article>
        </div>

        <div class="dashboard-panels">
            <section class="dashboard-panel">
                <div class="dashboard-panel-header">
                    <h2>Recent sales</h2>
                    <a href="{{ url('/sales') }}">View all</a>
                </div>

                <div class="dashboard-table-wrap">
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th style="width: 22%;">Time</th>
                                <th>Items</th>
                                <th style="width: 25%;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentSales as $sale)
                                <tr>
                                    <td>{{ $sale->created_at?->format('g:i A') ?? '—' }}</td>
                                    <td>
                                        {{ $sale->product?->name ?? 'Product' }}
                                        × {{ $sale->quantity ?? 0 }}
                                    </td>
                                    <td class="dashboard-amount">
                                        ₱{{ number_format((float) ($sale->total_price ?? 0), 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        <p class="dashboard-empty">No sales recorded today.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="dashboard-panel">
                <div class="dashboard-panel-header">
                    <h2>Low stock ({{ $lowStockCount ?? 0 }})</h2>
                    <a href="{{ url('/products') }}">View all</a>
                </div>

                <div class="dashboard-table-wrap">
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th style="width: 18%;">Stock</th>
                                <th style="width: 22%;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lowStockProducts as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->stock_quantity }}</td>
                                    <td>
                                        <span class="dashboard-stock-status">Low</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        <p class="dashboard-empty">No low-stock products.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </section>
@endsection