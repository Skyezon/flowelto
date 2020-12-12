<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Render current logged in user and product the current user put inside their cart
     */
    public function cart() {
        $datas = Auth::user()->products;
        return view('transaction.cart', compact('datas'));
    }

    /**
     * Take user input from view and update target cart item quantity
     * 
     * @param Request $request      request sended from the view
     * @param $id                   selected product ID in user cart
     */
    public function changeCartItemQty(Request $request, $id) {
        $cartContent = Auth::user()->products()->updateExistingPivot($id, ['quantity' => $request->quantity]);
        return back();
    }
}
