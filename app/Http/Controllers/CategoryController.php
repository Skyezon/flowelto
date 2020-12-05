<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $datas = Category::all();
        return view('welcome',compact('datas'));
    }

    public function getProductByCategory($id){
        $category = Category::find($id);
        $datas = $category->products()->paginate(8);
        return view('home',compact('datas'));
    }
}
