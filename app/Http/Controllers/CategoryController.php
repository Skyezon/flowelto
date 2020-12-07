<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $datas = $this->getAllCategory();
        return view('welcome',compact('datas'));
    }

    public function manageCategory() {
        $datas = $this->getAllCategory();
        return view('category.manage_category', compact('datas'));
    }

    public function getProductByCategory($id){
        $category = Category::find($id);
        $datas = $category->products()->paginate(8);
        return view('home',compact('datas'));
    }

    private function getAllCategory() {
        return Category::all();
    }
}