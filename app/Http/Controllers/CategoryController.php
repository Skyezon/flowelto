<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $datas = Category::all();
        return view('home',compact('datas'));
    }
}
