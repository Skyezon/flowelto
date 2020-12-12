<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * get currently authenticated user carts content and return them to the cart view
     */
    public function cart() {
        $datas = Auth::user()->products;
        return view('transaction.cart', compact('datas'));
    }

    /**
     * Create new transaction for the user and remove all cart content
     */
    public function checkout() {
        $cartContent = Auth::user()->products;
        $transaction = Transaction::create([
            'user_id' => Auth::user()->id,
            'date' => date('Y-m-d')
        ]);

        foreach ($cartContent as $item) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item->pivot->product_id,
                'quantity' => $item->pivot->quantity
            ]);
        }

        Auth::user()->products()->detach();
        return back();
    }

    /**
     * Take user input from view and update target cart item quantity
     * 
     * @param Request $request      request sended from the view
     * @param $id                   selected product ID in user cart
     */
    public function changeCartItemQty(Request $request, $id) {
        Auth::user()->products()->updateExistingPivot($id, ['quantity' => $request->quantity]);
        return back();
    }

    /**
     * get currently authenticated user transaction history and return them to transaction history view
     */
    public function transactionHistory() {
        $datas = Auth::user()->transactions()->orderBy('date', 'desc')->get();
        return view('transaction.history', compact('datas'));
    }
}
