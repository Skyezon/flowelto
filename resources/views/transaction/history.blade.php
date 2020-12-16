@extends('layouts.app')

@section('content')
    @if (count($kumpulan) != 0)
        <h1 class="text-center">Your Transaction History</h1>
        <div class="container">
            @foreach ($kumpulan as $satuan)
                <a href="{{route('transactionDetail', $satuan->id)}}" class="d-block p-3 text-center mb text-dark" style="background-color: rgb(228, 191, 156);">
                    Transaction at {{$satuan->date}}
                </a>
            @endforeach
        </div>
    @else
        <h1 class="text-center">No Transaction Yet</h1>
    @endif
@endsection
