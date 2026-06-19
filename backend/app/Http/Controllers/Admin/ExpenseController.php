<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with(['branch'])->latest()->get();
        return view('admin.finance.expenses.index', compact('expenses'));
    }

    public function create()
    {
        $categories = ExpenseCategory::all();
        $branches = Store::all();
        return view('admin.finance.expenses.create', compact('categories', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'expense_category_id' => 'required',
            'branch_id' => 'required|exists:branches,id',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf,zip|max:2048',
        ]);

        $data = $request->all();

        // Resolve expense_category_id to flat category string
        $cat = ExpenseCategory::find($request->expense_category_id);
        $data['category'] = $cat ? $cat->name : $request->expense_category_id;

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('expenses', 'public');
        }

        Expense::create($data);

        return redirect()->route('admin.expenses.index')->with('success', 'Pengeluaran berhasil dicatat!');
    }

    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::all();
        $branches = Store::all();
        return view('admin.finance.expenses.edit', compact('expense', 'categories', 'branches'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'date' => 'required|date',
            'expense_category_id' => 'required',
            'branch_id' => 'required|exists:branches,id',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf,zip|max:2048',
        ]);

        $data = $request->all();

        // Resolve expense_category_id to flat category string
        $cat = ExpenseCategory::find($request->expense_category_id);
        $data['category'] = $cat ? $cat->name : $request->expense_category_id;

        if ($request->hasFile('attachment')) {
            if ($expense->attachment) {
                Storage::disk('public')->delete($expense->attachment);
            }
            $data['attachment'] = $request->file('attachment')->store('expenses', 'public');
        }

        $expense->update($data);

        return redirect()->route('admin.expenses.index')->with('success', 'Data pengeluaran berhasil diperbarui!');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->attachment) {
            Storage::disk('public')->delete($expense->attachment);
        }
        $expense->delete();
        return redirect()->route('admin.expenses.index')->with('success', 'Data pengeluaran berhasil dihapus!');
    }
}
