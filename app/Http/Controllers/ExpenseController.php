<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Category;
use Illuminate\Support\Facades\Auth; // User ki ID lene ke liye zaroori

class ExpenseController extends Controller
{
    // 1. Dashboard Show karna (List + Form)
    public function index(Request $request) // Request inject karna mat bhoolna
    {
        $userId = Auth::id();

        // Query Build karna start karein
        $query = Expense::where('user_id', $userId)->with('category');

        // 1. Agar user ne search kiya hai (Description match karein)
        if ($request->has('search') && $request->search != '') {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        // 2. Agar Category filter select kiya hai
        if ($request->has('filter_category') && $request->filter_category != '') {
            $query->where('category_id', $request->filter_category);
        }

        // Final Data Get karein
        $expenses = $query->latest('date')->get();

        // --- Baaki logic waisi hi rahegi (Totals & Charts) ---
        $total = $expenses->sum('amount'); // Note: Yeh filtered results ka total dikhayega
        $categories = Category::all();

        // Chart Data (Isay hum abhi simple rakhte hain, pure data par)
        $chartData = Expense::where('user_id', $userId)
            ->selectRaw('sum(amount) as total, category_id')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $labels = $chartData->map(function ($item) {
            return $item->category->name; });
        $data = $chartData->map(function ($item) {
            return $item->total; });

        return view('dashboard', compact('expenses', 'total', 'categories', 'labels', 'data'));
    }

    // 2. Naya Expense Save karna
    public function store(Request $request)
    {
        // Validation: Data sahi format mein hai ya nahi
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
        ]);

        // Save logic
        Expense::create([
            'user_id' => Auth::id(), // Magic here! Current user ki ID auto utha li
            'category_id' => $request->category_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

        return redirect()->route('dashboard')->with('success', 'Expense Added Successfully!');
    }

    // Top par yeh zaroor check karein ke Expense model import ho
// use App\Models\Expense; 

    public function destroy(Expense $expense)
    {
        // Security Check: Kya yeh expense isi user ka hai?
        if ($expense->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $expense->delete();

        return redirect()->route('dashboard')->with('success', 'Expense Deleted Successfully!');
    }
    // ... baaki functions ke baad ...

    // 1. Edit Form Show karna
    public function edit(Expense $expense)
    {
        // Security: Check karo ke yeh expense usi user ka hai
        if ($expense->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::all();
        return view('edit_expense', compact('expense', 'categories'));
    }

    // 2. Data Update karna
    public function update(Request $request, Expense $expense)
    {
        // Security Check
        if ($expense->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
        ]);

        // Update command
        $expense->update([
            'category_id' => $request->category_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

        return redirect()->route('dashboard')->with('success', 'Expense Updated Successfully!');
    }
}