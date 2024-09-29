@extends('layouts.owner.app')

@section('content')
<div class="container">
    <h1>Customer Behavior</h1>
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Total Spending</th>
                <th>Total Chats</th> <!-- Kolom baru untuk jumlah chats -->
                <th>Average Login Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>Rp {{ number_format($user->total_spending, 0, ',', '.') }}</td>
                <td>{{ $user->total_chat_count }}</td> <!-- Menampilkan jumlah chats -->
                <td>{{ gmdate('H:i:s', $user->average_login_time) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endsection