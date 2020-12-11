<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\ProductRequest;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function search(Request $request){
        $searchBy = $request->query('searchby');
        $content = $request->query('search');
        $datas = [];
        if ($searchBy == 'price'){
            $datas = Product::where($searchBy,$content)->paginate(8);
        }else{
            $datas = Product::where($searchBy,'LIKE','%'.$content.'%')->paginate(8);
        }
        return view('home',compact('datas'));
    }

    public function get($id){
        $data = Product::find($id);
        return view('product.view',compact('data'));
    }

    public function showUpdatePage($id){
        $data = Product::find($id);
        $types = Category::all();
        return view('product.update',compact('data','types'));
    }

    public function update(ProductRequest $request,$id){
        $data = Product::find($id);
//        TODO: still error
        $path = "";
        if ($request->filled('image')){
            $this->deleteImage($data);
            $path = $request->file('image')->store('public/products');
        }else{
            $path = $data->image;
        }
        $data->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $path,
            'category_id' => $request->type
        ]);

        return redirect()->route('home')->with('success','Flower data sucessfully updated');
    }

    public function store(ProductRequest $request){
        $request->validate([
            'image' => 'required'
        ]);
        $path = $request->file('image')->store('public/products');
        Product::create([
           'name' => $request->name,
           'price' => $request->price,
           'description' => $request->description,
           'category_id' => $request->type,
           'image' => $path
        ]);

        return redirect()->route('home');
    }

    public function softDelete($id){
        $data = Product::find($id);
        $data->users()->detach();
        $data->delete();
        return redirect()->route('welcome')->with('success','flower successfully deleted');
    }

    private function deleteImage($data){
        $oldImage = $data->image;
        $oldImage =  str_replace('storage','public',$oldImage);
        Storage::delete($oldImage);
    }

    public function showStorePage(){
        $types = Category::all();
        return view('product.add',compact('types'));
    }
}
