@extends('layouts.customer.app')

@section('content')
<div class="container --dark-theme" id="chat">
    <div class="chat__conversation-board">
        @foreach ($chats as $chat)
        <div class="chat__conversation-board__message-container {{ $chat->is_admin ? 'reversed' : '' }}">
            <div class="chat__conversation-board__message__person">
                <div class="chat__conversation-board__message__person__avatar">
                    <img src="{{ $chat->is_admin ? 'https://avatar.iran.liara.run/public' : asset('storage/' . auth()->user()->profile_pictures) }}"
                        alt="{{ $chat->is_admin ? 'Admin' : auth()->user()->name }}" />
                </div>
                <span class="chat__conversation-board__message__person__nickname">{{ $chat->is_admin ? 'Admin' : auth()->user()->name }}</span>
            </div>
            <div class="chat__conversation-board__message__context">
                <div class="chat__conversation-board__message__bubble">
                    <span>{{ $chat->message }}</span>
                </div>
                <div class="chat__conversation-board__message__time">
                    <small>{{ $chat->created_at->format('H:i') }}</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="chat__conversation-panel">
        <form action="{{ route('chat.store') }}" method="POST" class="chat__conversation-panel__container">
            @csrf
            <input type="hidden" name="user_id" value="{{ Auth::id() }}" />
            <input class="chat__conversation-panel__input panel-item" name="message" placeholder="Type a message..." required />
            <button class="chat__conversation-panel__button panel-item btn-icon send-message-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </button>
        </form>
    </div>
</div>
@endsection