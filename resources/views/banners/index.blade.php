<!-- resources/views/banners/index.blade.php -->

@extends('layouts.owner.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Kelola Banner</h1>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <a href="{{ route('banner.create') }}" class="btn btn-primary mb-3">Tambah Banner</a>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Banner</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($banners as $banner)
                <tr>
                    <td>
                        <img src="{{ asset('storage/' . $banner->path) }}" alt="Banner" width="200">
                    </td>
                    <td>
                        <a href="{{ route('banner.edit', $banner->id) }}" class="btn btn-warning">Edit</a>

                        <form action="{{ route('banner.destroy', $banner->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus banner ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
