@extends('layouts.app')

@section('title','Category')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-center">
            <h1><i>Our Hand bouquet(gift)</i></h1>
        </div>
        <form action="{{route('productSearch')}}" enctype="multipart/form-data" class="d-flex">
            @csrf
            <div>
                <select name="searchby" class="form-control" id="inputGroupSelect01">
                    <option value="name" selected>Name</option>
                    <option value="price">Price</option>
                </select>
            </div>
            <div class="d-flex ml-3">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" name="search" aria-label="Search">
                <button class="btn btn-primary my-2 my-sm-0" type="submit">Search</button>
            </div>
        </form>
        <div class="d-flex flex-wrap justify-content-center mt-5">
            @foreach($kumpulan as $satuan)
                <div class="px-2 py-3 bg-color-card m-3">
                    <a href="{{route('productGet',$satuan->id)}}" class="d-block">
                        <img src="{{$satuan->image}}" style="width: 200px"/>
                    </a>

                    <div class="d-flex justify-content-center flex-column align-items-center">
                        <a href="{{route('productGet',$satuan->id)}}">
                            <span class="font-weight-bold text-dark">{{$satuan->name}}</span>
                        </a>
                        <span class="font-weight-bold" style="color: darkgoldenrod">Rp. {{$satuan->price}}</span>
                    </div>
                    @if(Auth::user() && Auth::user()->role == 'manager')
                        <div class="d-flex justify-content-around mt-3">
                            <form method="post" enctype="multipart/form-data" action="{{route('productDelete',$satuan->id)}}">
                                @method('delete')
                                @csrf
                                <input type="submit" class="btn btn-danger" value="Delete">
                            </form>
                            <a class="btn btn-primary" href="{{route('showUpdateProduct',$satuan->id)}}">Update</a>
                        </div>
                    @endif
                </div>
            @endforeach

        </div>
        <div class="my-3 d-flex justify-content-center align-items-center" style="height: fit-content">
            {{$kumpulan->links()}}
        </div>
    </div>

@endsection
