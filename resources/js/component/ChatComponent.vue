<template>
    <div>
        <div v-for="message in messages" :key="message.id">
            <strong>{{ message.user.name }}</strong>: {{ message.body }}
        </div>
        <input v-model="newMessage" @keyup.enter="send" placeholder="Type a message..." />
    </div>
</template>

<script>
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

export default {
    data() {
        return {
            messages: [],
            newMessage: ''
        };
    },
    mounted() {
        this.fetchMessages();

        Echo.channel('chat')
            .listen('MessageSent', (e) => {
                this.messages.push(e.message);
            });
    },
    methods: {
        fetchMessages() {
            axios.get('/chat/messages').then(response => {
                this.messages = response.data;
            });
        },
        send() {
            if (this.newMessage.trim()) {
                axios.post('/chat/send', { message: this.newMessage });
                this.newMessage = '';
            }
        }
    }
};
</script>
