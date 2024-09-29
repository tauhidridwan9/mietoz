@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Chat Room</h1>
    <div id="messages" style="border:1px solid #ccc; height:300px; overflow-y:scroll; padding:10px; margin-bottom:20px;">
        <!-- Pesan akan ditampilkan di sini -->
    </div>
    <input type="text" id="message-input" placeholder="Type a message..." style="width: 80%;" />
    <button id="send-button" class="btn btn-primary">Send</button>
</div>
@endsection

@section('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let messagesElement = document.getElementById('messages');
        let messageInput = document.getElementById('message-input');
        let sendButton = document.getElementById('send-button');

        // Load existing messages
        fetch('/chat/messages')
            .then(response => response.json())
            .then(messages => {
                messages.forEach(message => {
                    let messageElement = document.createElement('div');
                    messageElement.textContent = message.user.name + ': ' + message.body;
                    messagesElement.appendChild(messageElement);
                });
            });

        // Send new message
        sendButton.addEventListener('click', function() {
            let message = messageInput.value.trim();
            if (message) {
                fetch('/chat/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            message
                        })
                    }).then(response => response.json())
                    .then(data => {
                        messageInput.value = '';
                    });
            }
        });

        // Pusher setup
        Pusher.logToConsole = true;

        let pusher = new Pusher('PUSHER_APP_KEY', {
            cluster: 'PUSHER_APP_CLUSTER',
            encrypted: true
        });

        let channel = pusher.subscribe('chat');
        channel.bind('MessageSent', function(data) {
            let messageElement = document.createElement('div');
            messageElement.textContent = data.message.user.name + ': ' + data.message.body;
            messagesElement.appendChild(messageElement);
        });
    });
</script>
@endsection