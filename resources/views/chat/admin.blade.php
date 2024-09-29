@extends('layouts.admin.app')

@section('content')
<div class="container" id="chat">
    <div class="row chat-container">
        <!-- Sidebar Kontak -->
        <div class="col-md-4 chat-sidebar">
            <div class="chat-sidebar-header">
                <h4>Contacts</h4>
            </div>
            <ul class="chat-contact-list">
                @foreach ($contacts->sortByDesc(function($contact) {
                return $contact->chats()->latest()->first()->id;
                }) as $contact)
                <li class="chat-contact-item {{ $selectedUser && $contact->id == $selectedUser->id ? 'active' : '' }}">
                    <a href="{{ route('admin.chat', ['user_id' => $contact->id]) }}">
                        <div class="contact-avatar">
                            <!-- Display the avatar of the user who owns the chat -->
                            @if($contact->profile_pictures)
                            <img src="{{ asset('storage/' . $contact->profile_pictures) }}" alt="{{ $contact->name }}" />
                            @else
                            <div class="avatar-placeholder">
                                <img src="{{asset('storage/avatar/avatar.jpeg')}}" alt="avatar">
                            </div>
                            @endif
                        </div>
                        <div class="contact-info">
                            <h5 class="contact-name ">{{ $contact->name }}</h5>
                            <!-- Display the preview of the last message -->
                            <p class="contact-preview">{{ optional($contact->chats()->latest()->first())->message ?? 'No messages' }}</p>
                        </div>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>

        <!-- Wadah Percakapan -->
        <div class="col-md-8 chat-conversation">
            @if ($selectedUser)
            <div class="chat-conversation-header">
                <h4>{{ $selectedUser->name }}</h4>
            </div>
            <div class="chat-conversation-body">
                @foreach ($currentChat as $chat)
                <div class="chat-message {{ $chat->is_admin ? 'admin-message' : '' }}">
                    <div class="chat-message-avatar">
                        <img src="{{ $chat->is_admin ? asset('storage/admin/avatar.png') : ($chat->user->profile_pictures ? asset('storage/' . $chat->user->profile_pictures) : asset('storage/avatar/avatar.jpeg')) }}" alt="User Avatar">
                    </div>
                    <div class="chat-message-content">
                        <span>{{ $chat->message }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="chat-conversation-footer">
                <form action="{{ route('chat.store') }}" method="POST" class="chat-conversation-form">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $selectedUser->id }}" />
                    <input class="chat-input" name="message" placeholder="Type a message..." required />
                    <button class="chat-send-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                    </button>
                </form>
            </div>
            @else
            <div class="chat-no-selection">
                <p>Please select a contact to start a chat.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection