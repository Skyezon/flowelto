<?php

namespace App\Http\Controllers;

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
}
