@extends('layouts.app')

@section('content')
    @if (count($datas) != 0)
        <h1 class="text-center">Your Transaction History</h1>
        <div class="container">
            @foreach ($datas as $data)
                <a href="#" class="d-block p-3 text-center mb text-dark" style="background-color: rgb(228, 191, 156);">
                    Transaction at {{$data->date}}    
                </a>
            @endforeach
        </div>
    @else
        <h1 class="text-center">No Transaction Yet</h1>
    @endif
@endsection