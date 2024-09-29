<!-- resources/views/banners/create.blade.php -->

@extends('layouts.owner.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Banner</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('banner.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="banner">Upload Banner</label>
            <input type="file" name="banner" class="form-control-file" id="banner" required>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Upload Banner</button>
    </form>
</div>
@endsection
