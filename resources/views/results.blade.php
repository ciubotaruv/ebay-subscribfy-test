@extends('layouts.app')
@section('content')
    <div class="container py-5">
        <h2 class="mb-4">Price Results</h2>

        <table class="table table-bordered">
            <thead class="table-dark">
            <tr>
                <th>Image</th>
                <th>Keyword</th>
                <th>Min Price</th>
                <th>Max Price</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($results as $item)
                <tr>
                    <td><img src="{{ asset('storage/uploads/' . $item['thumbnail']) }}" width="60"></td>
                    <td>{{ $item['keyword'] }}</td>
                    <td>${{ number_format($item['min_price'], 2) }}</td>
                    <td>${{ number_format($item['max_price'], 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <form action="{{ route('export') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">Export to CSV</button>
        </form>

        <a href="{{ url('/') }}" class="btn btn-secondary mt-3">Back to Upload</a>
    </div>
@endsection
