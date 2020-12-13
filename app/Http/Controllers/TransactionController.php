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
     * Add selected product to the cart
     * 
     * @param Request $request      request sended from the view
     * @param $id                   Selected product ID
     */
    public function addToCart(Request $request, $id) {
        if(Auth::check() && Auth::user()->role == 'user') {
            Auth::user()->products()->attach($id, ['quantity' => $request->quantity]);
            return redirect(route('userCart'));
        }

        return redirect(route('login'));
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

    /**
     * View an transaction details with transaction total price
     * 
     * @param $id       Selected Transaction ID
     */
    public function transactionDetail($id) {
        $transaction = Transaction::find($id);

        $datas = $transaction->transactionDetails()->get();
        $total = 0;
        foreach($datas as $data) {
            $total += $data->quantity * $data->product()->withTrashed()->first()->price;
        }

        return view('transaction.transaction-detail', compact('datas', 'total'));
    }
}
