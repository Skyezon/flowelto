@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <img class="w-100" src="{{$datas->image}}" alt="{{$datas->id}}">
            </div>
            <div class="col-md-9">
                <form action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="category_name">Category Name</label>
                        <input type="text" class="form-control" name="categoryName" id="category_name"
                            value="{{old('categoryName') != null ? old('categoryName') : $datas->name}}">
                        @error('categoryName')
                            <small class="text-danger">Help text</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="category_image">Category Image</label>
                        <input type="file" class="form-control-file" name="categoryImage" id="category_image">
                        @error('categoryName')
                            <small class="text-danger">Help text</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
