@extends('layouts.owner.app')

@section('content')
<div class="container">
    <h1>Edit Cashier</h1>

    <form action="{{ route('owner.admins.update', $admin->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ $admin->name }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ $admin->email }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password (Leave blank if not changing)</label>
            <input type="password" name="password" class="form-control" id="password">
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
        </div>

        <div class="mb-3">
            <label for="profile_pictures" class="form-label">Profile Photo</label>
            <input type="file" name="profile_pictures" class="form-control" id="profile_pictures">
        </div>

        @if ($admin->profile_pictures)
        <div class="mb-3">
            <label for="current_profile_picture" class="form-label">Current Profile Picture</label>
            <img src="{{ asset('storage/' . $admin->profile_pictures) }}" alt="Profile Picture" class="img-thumbnail" width="150">
        </div>
        @endif

        <button type="submit" class="btn btn-primary">Update Admin</button>
    </form>
</div>
@endsection
