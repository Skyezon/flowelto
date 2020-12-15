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
        $searchBy = $request->query('searchby'); // untuk mengambil apa tipe yang disearch
        $content = $request->query('search');// mengambil isi request dengan key search
        $datas = [];
        if ($searchBy == 'price'){
            $datas = Product::where($searchBy,$content)->paginate(8); // mencari harga price yang sesuai dengan inputan user
        }else{
            $datas = Product::where('name','LIKE','%'.$content.'%')->paginate(8); // mencari produk dengan nama yang mengandung kata" oleh apa yang sudah diinput oleh user
        }// melakukan search yang sesuai apabila price atau name yang dipilih
        return view('home',compact('datas'));
    }

    public function get($id){
        $data = Product::find($id); // mencari produk yang sesuai
        $inCart = Auth::check() && count(Auth::user()->products()->where('product_id', $id)->get()) > 0 ? true : false; //mencari apakah produk sudah ada didalam cart
        return view('product.detail',compact('data', 'inCart'));
    }

    public function showUpdatePage($id){
        $data = Product::find($id); // mencari produk yang sesuai
        $types = Category::all();// mengambil semua kategori untuk dipilih pada update page
        return view('product.update',compact('data','types'));
    }

    public function update(ProductRequest $request,$id){
        $data = Product::find($id); // mencari product yang sesuai dengan id tersebut
        $path = $data->image;
        if ($request->image != null){
            $this->deleteImage($data); // menghapus image sebelumnya
            $path = $request->file('image')->store('public/products'); //mengambil foto dari input dan menyimpan pada folder storage/app/public/products
        }// mengisi path baru yang sesuai apabila image diisi

        $path = str_replace("public","/storage",$path);//menggantikan tulisan public dengan storage agar bisa ditampilkan pada view
        $data->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $path,
            'category_id' => $request->type
        ]); // update produk sesuai dengan inputan user

        return back();
    }

    public function store(ProductRequest $request){
        //validasi pertama melalui Product Request
        $request->validate([
            'image' => 'required',
            'name' => 'unique:products'
        ]); // akan menvalidasi apa yang belum divalidasi di product Request dan mengembalikan error yang sesuai
        $path = $request->file('image')->store('public/products'); // mengambil foto dari input dan menyimpan pada folder storage/app/public/products
        $path = str_replace("public","/storage",$path); //menggantikan tulisan public dengan storage agar bisa ditampilkan pada view
        Product::create([
           'name' => $request->name,
           'price' => $request->price,
           'description' => $request->description,
           'category_id' => $request->type,
           'image' => $path
        ]); // membuat product dan menyimpan ke database sesuai dengan input

        return redirect()->route('welcome');
    }

    public function softDelete($id){
        $data = Product::find($id); //pertama akan dicari product sesuai dengan id yang diterima dari url
        $data->users()->detach(); // akan melepaskan semua koneksi produk ke user dari table cart
        $data->delete(); // akan menandai bahwa product sudah terdelete
        return back();
    }

    private function deleteImage($data){
        //digunakan oleh update produk
        $oldImage = $data->image; //menyimpan path image dari database
        $oldImage =  str_replace('storage','public',$oldImage); //karena pada database dibuat untuk menampilkan view maka tidak bisa langsung didelete oleh karena itu harus diganti dulu biar sesuai dengan path yang disimpan
        Storage::delete($oldImage); // menghapus foto dari path yang diterima
    }

    public function showStorePage(){
        $types = Category::all(); // mengambil semua kategori
        return view('product.add',compact('types'));
    }
}
