window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content
window.axios.defaults.withCredentials = true;

import Echo from "laravel-echo"
// window.Pusher = require('pusher-js');

window.Echo = new Echo({
     broadcaster: 'pusher',
     key: '89afc2fa57b59d0c9293',
     forceTLS: true
});

