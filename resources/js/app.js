// resources/js/app.js
import 'bootstrap';
import axios from 'axios';
import Echo from 'laravel-echo';
window.Pusher = require('pusher-js');

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

// Listening for notifications
const userId = document.getElementById('user-id').value; // Get user ID from a hidden input or some other source
Echo.private(`App.Models.User.${userId}`)
    .notification((notification) => {
        // Update notification bell or display the notification
        alert(notification.message);
    });
