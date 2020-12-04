@extends('layouts.app')

@section('title',"Home")

@section('content')
<div class="container">
    <div class="d-flex justify-content-center flex-column text-center">
        <h1><i>Welcome to Flowelto Shop</i></h1>
        <p><b>The Best Flower Shop in Binus university</b></p>
        <div class="d-flex justify-content-center align-items-center">
            <div class="d-flex flex-wrap align-items-center w-50">
                @foreach($datas as $data)
                    <div style="background-color: #ff69b4" class="m-3" >
                        <div class="pt-3 px-2">
                            <img src="{{$data->image}}" alt="{{$data->id}}" style="width: 200px"/>
                        </div>
                        <div class="my-3 font-weight-bold">
                            {{$data->name}}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


    </div>
</div>
@endsection
