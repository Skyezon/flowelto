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
        $kumpulan = [];
        if ($searchBy == 'price'){
            $kumpulan = Product::where($searchBy,$content)->paginate(8); // mencari harga price yang sesuai dengan inputan user
        }else{
            $kumpulan = Product::where('name','LIKE','%'.$content.'%')->paginate(8); // mencari produk dengan nama yang mengandung kata" oleh apa yang sudah diinput oleh user
        }// melakukan search yang sesuai apabila price atau name yang dipilih
        return view('home',compact('kumpulan'));
    }

    public function get($id){
        $satuan = Product::find($id); // mencari produk yang sesuai
        $inCart = Auth::check() && count(Auth::user()->products()->where('product_id', $id)->get()) > 0 ? true : false; //mencari apakah produk sudah ada didalam cart
        return view('product.detail',compact('satuan', 'inCart'));
    }

    public function showUpdatePage($id){
        $satuan = Product::find($id); // mencari produk yang sesuai
        $types = Category::all();// mengambil semua kategori untuk dipilih pada update page
        return view('product.update',compact('satuan','types'));
    }

    public function update(Request $request,$id){
        $request->validate([
            'type' => 'required',
            'name' => 'required | min:5',
            'price' => 'required | integer | min:50000',
            'description' => 'required | min:20',
            'image' => 'file | mimes:jpeg,jpg,png,svg'
        ]);
        $satuan = Product::find($id); // mencari product yang sesuai dengan id tersebut
        $path = $satuan->image;
        if ($request->image != null){
            $this->deleteImage($satuan); // menghapus image sebelumnya
            $path = $request->file('image')->store('public/products'); //mengambil foto dari input dan menyimpan pada folder storage/app/public/products
        }// mengisi path baru yang sesuai apabila image diisi

        $path = str_replace("public","/storage",$path);//menggantikan tulisan public dengan storage agar bisa ditampilkan pada view
        $satuan->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $path,
            'category_id' => $request->type
        ]); // update produk sesuai dengan inputan user

        return back();
    }

    public function store(Request $request){
        $request->validate([
            'type' => 'required',
            'name' => 'required | min:5 | unique:products',
            'price' => 'required | integer | min:50000',
            'description' => 'required | min:20',
            'image' => 'required |file | mimes:jpeg,jpg,png,svg',
        ]); // akan menvalidasi dan mengembalikan error yang sesuai
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
        $satuan = Product::find($id); //pertama akan dicari product sesuai dengan id yang diterima dari url
        $satuan->users()->detach(); // akan melepaskan semua koneksi produk ke user dari table cart
        $satuan->delete(); // akan menandai bahwa product sudah terdelete
        return back();
    }

    private function deleteImage($satuan){
        //digunakan oleh update produk
        $oldImage = $satuan->image; //menyimpan path image dari database
        $oldImage =  str_replace('storage','public',$oldImage); //karena pada database dibuat untuk menampilkan view maka tidak bisa langsung didelete oleh karena itu harus diganti dulu biar sesuai dengan path yang disimpan
        Storage::delete($oldImage); // menghapus foto dari path yang diterima
    }

    public function showStorePage(){
        $types = Category::all(); // mengambil semua kategori
        return view('product.add',compact('types'));
    }
}
