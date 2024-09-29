<!-- resources/views/banners/edit.blade.php -->

@extends('layouts.owner.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Banner</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="banner">Upload Banner Baru (opsional)</label>
            <input type="file" name="banner" class="form-control-file" id="banner">
        </div>

        <button type="submit" class="btn btn-primary mt-2">Update Banner</button>
    </form>
</div>
@endsection
