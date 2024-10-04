@extends('layouts.owner.app')

@section('content')
<div class="container">
    <h1>Edit Cashier</h1>

    <form action="{{ route('owner.admins.update', $admin->id) }}" method="POST">
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

        <button type="submit" class="btn btn-primary">Update Admin</button>
    </form>
</div>
@endsection