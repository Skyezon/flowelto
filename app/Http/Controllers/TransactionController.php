<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * get currently authenticated user carts content and return them to the cart view
     */
    public function cart() {
        $kumpulan = Auth::user()->products;
        return view('transaction.cart', compact('kumpulan'));
    }

    /**
     * Add selected product to the cart
     *
     * @param Request $request      request sended from the view
     * @param $id                   Selected product ID
     */
    public function addToCart(Request $request, $id) {
        if(Auth::check() && Auth::user()->role == 'user') {
            Validator::make($request->all(), [
                'quantity' => 'required | numeric | min:1'
            ])->validate();

            // memasukkan record baru ke intermediate table (carts) antara user dan product
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

        // setelah transactoin baru dibuat, transaction detail dibuat sesuai dengan product yang ada didalam cart
        foreach ($cartContent as $item) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item->pivot->product_id,
                'quantity' => $item->pivot->quantity
            ]);
        }

        // menghapus semua product yang ada di cart customer
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
        //membuat objek validator untuk melakukan validasi input
        $validator = Validator::make($request->all(), [
            'quantity' => 'required | numeric | min:0'
        ]);

        if($validator->fails()) {
            //jika validasi gagal, maka pesan error dikirim kembali ke url sebelumnya dan
            //id produk yang ingin diupdate dikirim kembali ke view menggunakan flash session
            return back()->withErrors($validator)->with('productId', $id);
        } else {
            if($request->quantity > 0) {
                Auth::user()->products()->updateExistingPivot($id, ['quantity' => $request->quantity]);
            } else {
                //jika quantity yang diinput adalah 0, maka produk akan dihapus dari cart customer
                Auth::user()->products()->detach($id);
            }
            return back();
        }
    }

    /**
     * get currently authenticated user transaction history and return them to transaction history view
     */
    public function transactionHistory() {
        // mengambil transaksi user yang di urutkan berdasarkan tanggal yang paling dekat ke tanggal yang paling jauh
        $kumpulan = Auth::user()->transactions()->orderBy('date', 'desc')->get();
        return view('transaction.history', compact('kumpulan'));
    }

    /**
     * View an transaction details with transaction total price
     *
     * @param $id       Selected Transaction ID
     */
    public function transactionDetail($id) {
        $transaction = Transaction::find($id);
        $kumpulan = $transaction->transactionDetails()->get();
        $total = 0;

        //menghitung total dari semua produk yang dibeli
        foreach($kumpulan as $satuan) {
            $total += $satuan->quantity * $satuan->product()->withTrashed()->first()->price;
        }

        return view('transaction.transaction-detail', compact('kumpulan', 'total'));
    }
}
