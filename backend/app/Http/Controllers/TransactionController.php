<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validación estricta
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:expense,income,loan_sent,loan_received',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        // 2. Le asignamos el gasto al usuario que hizo la petición
        // Como todavía no conectamos el login, vamos a hardcodear el ID 1 (tu usuario admin)
        // Más adelante esto será: $validated['user_id'] = $request->user()->id;
        $validated['user_id'] = $request->user()->id;

        // 3. Impactamos en la base de datos
        $transaction = Transaction::create($validated);

        // 4. Devolvemos la respuesta al frontend
        return response()->json([
            'message' => 'Movimiento registrado joya',
            'data' => $transaction
        ], 201);
    }
    public function index(Request $request)
    {
        // Especificamos 'transactions.user_id' para matar la ambigüedad
        $transactions = Transaction::where('transactions.user_id', $request->user()->id)
                            ->join('categories', 'transactions.category_id', '=', 'categories.id')
                            ->select('transactions.*', 'categories.name as category_name')
                            ->orderBy('transaction_date', 'desc')
                            ->get();

        return response()->json($transactions);
    }
    // Editar una transacción existente
    public function update(Request $request, $id)
    {
        $transaction = Transaction::where('user_id', $request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:expense,income,loan_sent,loan_received',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        $transaction->update($validated);

        return response()->json(['message' => 'Dato corregido, acá no pasó nada', 'data' => $transaction]);
    }

    // Borrar una transacción
    public function destroy(Request $request, $id)
    {
        $transaction = Transaction::where('user_id', $request->user()->id)->findOrFail($id);
        $transaction->delete();

        return response()->json(['message' => 'Transacción eliminada']);
    }
    public function summary(Request $request)
    {
        $userId = $request->user()->id;

        $totals = Transaction::where('user_id', $userId)
            ->selectRaw("
                SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense
            ")
            ->first();

        $balance = $totals->total_income - $totals->total_expense;

        return response()->json([
            'income' => (float)$totals->total_income,
            'expense' => (float)$totals->total_expense,
            'balance' => (float)$balance
        ]);
    }
}