@extends('layouts.admin')

@section('title', 'Financial Intelligence – Siliwangi Admin')

@section('styles')
<style>
    .finance-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 32px;
        margin-bottom: 40px;
    }

    .finance-card {
        padding: 40px;
        border-radius: var(--radius-xl);
        position: relative;
        overflow: hidden;
        transition: var(--transition);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        background: var(--card-bg);
    }
    .finance-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-shadow-hover);
    }

    .finance-card::after {
        content: '';
        position: absolute;
        bottom: -20px;
        right: -20px;
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        font-size: 120px;
        opacity: 0.03;
        transform: rotate(-15deg);
    }

    .finance-income::after { content: '\f0d6'; color: var(--success); }
    .finance-expense::after { content: '\f15c'; color: var(--danger); }
    .finance-net::after { content: '\f81d'; color: var(--secondary); }

    .finance-card.income { border-left: 6px solid var(--success); }
    .finance-card.expense { border-left: 6px solid var(--danger); }
    .finance-card.net { border-left: 6px solid var(--secondary); }

    .finance-label {
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--text-muted);
        margin-bottom: 12px;
        display: block;
    }

    .finance-amount {
        font-size: 32px;
        font-weight: 900;
        color: var(--text-main);
        font-family: 'Poppins', sans-serif;
        letter-spacing: -1px;
    }

    .finance-trend {
        margin-top: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 700;
    }

    .ledger-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 32px;
    }
    @media (max-width: 1200px) {
        .ledger-grid { grid-template-columns: 1fr; }
    }

    .mini-ledger {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--card-shadow);
    }
</style>
@endsection

@section('content')

<div class="page-header" data-aos="fade-down">
    <div>
        <h1 class="page-title">Financial Intelligence</h1>
        <div class="breadcrumb">
            <span>Corporate Treasury</span>
            <i class="fas fa-chevron-right" style="font-size: 8px; margin: 0 10px; opacity: 0.5;"></i>
            <span style="color: var(--secondary); font-weight: 700;">Liquidity Overview</span>
        </div>
    </div>
    <div class="header-actions">
        <div class="flex gap-3">
            <button class="btn-glass px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                <i class="fas fa-download text-gold"></i> Export Statement
            </button>
            <button class="btn-gold px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest flex items-center gap-2 shadow-lg shadow-gold/20">
                <i class="fas fa-plus-circle"></i> Add Transaction
            </button>
        </div>
    </div>
</div>

<div class="finance-grid">
    <!-- Total Income -->
    <div class="finance-card income finance-income" data-aos="fade-up" data-aos-delay="0">
        <span class="finance-label">Gross Revenue (Inflow)</span>
        <div class="finance-amount">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
        <div class="finance-trend text-success">
            <i class="fas fa-arrow-trend-up"></i> +14.2% <span class="text-muted font-medium ml-1">Growth vs Prev Month</span>
        </div>
    </div>

    <!-- Total Expense -->
    <div class="finance-card expense finance-expense" data-aos="fade-up" data-aos-delay="100">
        <span class="finance-label">Operational Outflow</span>
        <div class="finance-amount">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
        <div class="finance-trend text-danger">
            <i class="fas fa-arrow-trend-down"></i> -2.4% <span class="text-muted font-medium ml-1">Reduced Overheads</span>
        </div>
    </div>

    <!-- Net Income -->
    <div class="finance-card net finance-net" data-aos="fade-up" data-aos-delay="200">
        <span class="finance-label">Net Liquidity (Profit)</span>
        <div class="finance-amount">Rp {{ number_format($netIncome, 0, ',', '.') }}</div>
        <div class="finance-trend text-secondary">
            <i class="fas fa-vault"></i> <span class="text-muted font-medium">Stable Capital Reserve</span>
        </div>
    </div>
</div>

<div class="ledger-grid">
    <!-- Recent Inflow (Payments) -->
    <div class="mini-ledger" data-aos="fade-right">
        <div class="p-6 border-b border-white/5 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50 backdrop-blur-md">
            <div>
                <h3 class="text-lg font-black text-main tracking-tight">Recent Inflow</h3>
                <p class="text-[10px] text-muted font-bold uppercase tracking-widest mt-1">Transaction Ledger</p>
            </div>
            <a href="{{ route('admin.finance.payments') }}" class="text-[10px] font-black text-gold uppercase tracking-widest hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-100/30 dark:bg-slate-800/20">
                        <th class="px-6 py-4 text-[9px] font-black text-slate-500 uppercase tracking-widest">ID</th>
                        <th class="px-6 py-4 text-[9px] font-black text-slate-500 uppercase tracking-widest">Customer</th>
                        <th class="px-6 py-4 text-[9px] font-black text-slate-500 uppercase tracking-widest">Valuation</th>
                        <th class="px-6 py-4 text-[9px] font-black text-slate-500 uppercase tracking-widest">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($recentPayments as $payment)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors">
                        <td class="px-6 py-4 font-mono text-[10px] text-slate-400">#{{ $payment->payment_code }}</td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-bold text-main">{{ $payment->booking->customer->name ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-black text-main">Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded-full {{ $payment->payment_status == 'success' ? 'bg-success-bg text-success-text' : 'bg-warning-bg text-warning-text' }} text-[8px] font-black uppercase tracking-widest border border-current/10">
                                {{ $payment->payment_status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-12 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest opacity-30">No Inflow Detected</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Outflow (Expenses) -->
    <div class="mini-ledger" data-aos="fade-left">
        <div class="p-6 border-b border-white/5 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50 backdrop-blur-md">
            <div>
                <h3 class="text-lg font-black text-main tracking-tight">Recent Outflow</h3>
                <p class="text-[10px] text-muted font-bold uppercase tracking-widest mt-1">Operational Spend</p>
            </div>
            <a href="{{ route('admin.expenses.index') }}" class="text-[10px] font-black text-gold uppercase tracking-widest hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-100/30 dark:bg-slate-800/20">
                        <th class="px-6 py-4 text-[9px] font-black text-slate-500 uppercase tracking-widest">Timeline</th>
                        <th class="px-6 py-4 text-[9px] font-black text-slate-500 uppercase tracking-widest">Classification</th>
                        <th class="px-6 py-4 text-[9px] font-black text-slate-500 uppercase tracking-widest">Outflow</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($recentExpenses as $expense)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors">
                        <td class="px-6 py-4 text-xs font-bold text-main">{{ $expense->date->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-[9px] font-black text-slate-500 uppercase tracking-widest border border-white/5">
                                {{ $expense->category->name ?? 'General' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-black text-danger">Rp {{ number_format($expense->amount, 0, ',', '.') }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-12 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest opacity-30">No Outflow Detected</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
