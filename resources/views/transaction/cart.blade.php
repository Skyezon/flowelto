@extends('layouts.app')

@section('content')
    <div class="container">
        @if (count($kumpulan) != 0)
            <h1 class="text-center">Yout Cart</h1>
            @foreach ($kumpulan as $satuan)
                <div class="d-flex justify-content-around align-items-center py-3" style="background-color: rgb(228, 191, 156);">
                    <img class="w-25" src="{{$satuan->image}}">
                    <span>{{$satuan->name}}</span>
                    <span>Rp {{$satuan->price * $satuan->pivot->quantity}}</span>
                    <form action="{{route('updateCartContent', $satuan->id)}}" method="POST">
                        @method('PATCH')
                        @csrf
                        @if($errors->any() && $satuan->id == session('productId'))
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <div>{{$error}}</div>
                                @endforeach
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="form-control"
                            value="{{$satuan->pivot->quantity}}">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            @endforeach

            <form action="{{route('checkout')}}" method="POST" class="text-center my-3">
                @csrf
                <button type="submit" class="btn btn-danger">Checkout</button>
            </form>
        @else
            <h1 class="text-center">Cart is empty</h1>
        @endif
    </div>

    @error('quantity')
        <div class="container position-fixed fixed-bottom" id="notification">
            <div role="alert" class="alert alert-danger alert-dismissible fade @if(session('success')) show @endif">
                <strong>{{$message}}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
            </div>
        </div>
    @enderror
@endsection
