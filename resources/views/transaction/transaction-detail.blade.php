@extends('layouts.app')

@section('content')
    <div class="container">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Flower Image</th>
                    <th scope="col">Flower Name</th>
                    <th scope="col">Subtotal</th>
                    <th scope="col">Quantity</th>
                </tr>
            </thead>
            <tbody style="background-color: rgb(228, 191, 156);">
                @foreach ($kumpulan as $satuan)
                    <tr>
                        <td>
                            <img src="{{$satuan->product()->withTrashed()->first()->image}}" class="w-25">
                        </td>
                        <td>{{$satuan->product()->withTrashed()->first()->name}}</td>
                        <td>{{$satuan->product()->withTrashed()->first()->price * $satuan->quantity}}</td>
                        <td>{{$satuan->quantity}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-right font-weight-bold">Total Price Rp {{$total}}</div>
    </div>
@endsection
