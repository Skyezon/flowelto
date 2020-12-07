<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function manageCategory() {
        $datas = $this->getAllCategory();
        return view('category.manage_category', compact('datas'));
    }

    public function index(){
        $datas = $this->getAllCategory();
        return view('welcome',compact('datas'));
    }

    public function edit($id) {
        return view('category.category_update', ['datas' => $this->getCategoryById($id)]);
    }

    public function delete($id) {
        $category = $this->getCategoryById($id);
        $category->products()->delete();
        return back()->with('success', 'All products from '.$category->name.' category has been deleted');
    }

    public function getProductByCategory($id){
        $category = $this->getCategoryById($id);
        $datas = $category->products()->paginate(8);
        return view('home',compact('datas'));
    }

    private function getAllCategory() {
        return Category::all();
    }

    private function getCategoryById($id) {
        return Category::find($id);
    }
}
