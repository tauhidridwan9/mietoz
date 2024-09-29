import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'PUSHER_APP_KEY',
    cluster: 'PUSHER_APP_CLUSTER',
    encrypted: true
});

