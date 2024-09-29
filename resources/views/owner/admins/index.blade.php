@extends('layouts.owner.app')

@section('content')
<div class="container">
    <h1>Manage Cashier</h1>

    <!-- Button to add new admin -->
    <a href="{{ route('owner.admins.create') }}" class="btn btn-success mb-4"><i class="fa-solid fa-plus"></i>Add New Cashier</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($admins as $admin)
            <tr>
                <td>{{ $admin->id }}</td>
                <td>{{ $admin->name }}</td>
                <td>{{ $admin->email }}</td>
                <td>
                    <!-- Edit button -->
                    <a href="{{ route('owner.admins.edit', $admin->id) }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-edit"></i>Edit</a>

                    <!-- Delete button with form for CSRF protection -->
                    <form action="{{ route('owner.admins.destroy', $admin->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this admin?');"><i class="fa-solid fa-trash"></i>Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection