<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth::id();
        $expenses = Expense::orderBy('created_at')->where('user_id', $user_id)->filter($request->all())->get();
        return $expenses;
    }
    public function show (Expense $expense) {
        $expense->category->name;
        return $expense;
    }
    public function store(Request $request) {
        $request->validate([
            'title' => 'required|max:255',
            'quantity' => 'required|integer',
            'user_id' => 'nullable',
            'category_id' => 'required|integer|max:255'
        ]);
        $expenses = Expense::create([
            'title' => $request->title,
            'quantity' => $request->quantity,
            'user_id' => Auth::id(),
            'category_id' => $request->category_id
        ]);
        return $expenses;
    }
    public function update(Request $request, Expense $expense) {
        $request->validate([
            'title' => 'required|max:255',
            'quantity' => 'required|integer',
            'category_id' => 'required|integer|max:255'
        ]);
        $expense->update($request->all());
        return $expense;
    }
    public function destroy(Expense $expense) {
        $expense->delete();
        return $expense;
    }
}
