@extends('layouts.customer.app')

@section('content')
<div class="container">
    <h1>Notifikasi</h1>
    <form action="{{ route('customer.notifications.clearAll') }}" method="POST" class="mb-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Clear All</button>
                </form>
    @if ($notifications->count())
    <ul class="list-group ">
        @foreach ($notifications as $notification)
        <li class="list-group-item mb-3">
            {{ $notification->data['message'] }}
            <div class="col"><small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small></div>
            

            @if (isset($notification->data['confirmation_url']) && $notification->data['order_status'] === 'delivered')
            <a href="{{ $notification->data['confirmation_url'] }}" class="mt-1 btn btn-success btn-sm">
                Konfirmasi Kedatangan
            </a>
            @endif
        </li>
        @endforeach
    </ul>
    @else
    <p>Anda tidak memiliki notifikasi baru.</p>
    @endif
</div>
@endsection