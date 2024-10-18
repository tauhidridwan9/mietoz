import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: 'da12dfa1c2928231615c',
  cluster: 'ap1',
  forceTLS: true
});

