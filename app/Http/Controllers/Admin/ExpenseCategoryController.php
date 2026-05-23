<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $categories = ExpenseCategory::all();
        return view('admin.finance.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.finance.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories',
        ]);

        ExpenseCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.expense-categories.index')->with('success', 'Kategori pengeluaran berhasil ditambahkan!');
    }

    public function edit(ExpenseCategory $expenseCategory)
    {
        return view('admin.finance.categories.edit', compact('expenseCategory'));
    }

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name,' . $expenseCategory->id,
        ]);

        $expenseCategory->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.expense-categories.index')->with('success', 'Kategori pengeluaran berhasil diperbarui!');
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->delete();
        return redirect()->route('admin.expense-categories.index')->with('success', 'Kategori pengeluaran berhasil dihapus!');
    }
}
