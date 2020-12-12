@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Yout Cart</h1>
        @foreach ($datas as $data)
            <div class="d-flex justify-content-around align-items-center py-3" style="background-color: rgb(228, 191, 156);">
                <img class="w-25" src="{{$data->image}}">
                <span>{{$data->name}}</span>
                <span>Rp {{$data->price * $data->pivot->quantity}}</span>
                <form action="#" method="POST" class="form-inline">
                    @method('PATCH')
                    @csrf
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="{{$data->pivot->quantity}}">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        @endforeach

        <form action="#" method="POST" class="text-center my-3">
            @csrf
            <button type="submit" class="btn btn-danger">Checkout</button>
        </form>
    </div>
@endsection