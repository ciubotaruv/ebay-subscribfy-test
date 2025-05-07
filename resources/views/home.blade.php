@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4">Upload up to 5 Product Images</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <input class="form-control" type="file" name="images[]" accept="image/*" multiple>
                <div class="form-text">You can upload up to 5 images (jpg, jpeg, png).</div>
            </div>
            <button class="btn btn-primary" type="submit">Analyze Prices</button>
        </form>
    </div>

@endsection
