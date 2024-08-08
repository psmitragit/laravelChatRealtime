import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'ead6ef25805345a5caa8',
    cluster: 'ap2',
    forceTLS: true
  });
