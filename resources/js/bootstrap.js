import 'bootstrap';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
    wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

// Add error handling for missing environment variables
const pusherKey = import.meta.env.VITE_PUSHER_APP_KEY;
const pusherCluster = import.meta.env.VITE_PUSHER_APP_CLUSTER;

console.log('Pusher Configuration:', {
    key: pusherKey ? 'Set' : 'Missing',
    cluster: pusherCluster ? 'Set' : 'Missing',
    host: import.meta.env.VITE_PUSHER_HOST || 'Default',
    port: import.meta.env.VITE_PUSHER_PORT || 'Default',
    scheme: import.meta.env.VITE_PUSHER_SCHEME || 'Default'
});

if (!pusherKey || !pusherCluster) {
    console.error('Echo Initialization SKIPPED: Missing required Pusher configuration. Please check your .env file for VITE_PUSHER_APP_KEY and VITE_PUSHER_APP_CLUSTER');
    window.Echo = undefined;
} else {
    try {
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: pusherKey,
            cluster: pusherCluster,
            wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${pusherCluster}.pusher.com`,
            wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
            wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
            forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
            enabledTransports: ['ws', 'wss'],
            disableStats: true,
            encrypted: true,
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            }
        });

        // Add connection status logging
        window.Echo.connector.pusher.connection.bind('state_change', states => {
            console.log('Pusher connection state changed:', states);
        });

        window.Echo.connector.pusher.connection.bind('error', error => {
            console.error('Pusher connection error:', error);
        });

        console.log('Laravel Echo initialized successfully');
        // Dispatch a custom event to signal Echo is ready
        document.dispatchEvent(new CustomEvent('echo:ready', { detail: window.Echo }));
    } catch (error) {
        console.error('Failed to initialize Laravel Echo:', error);
        window.Echo = undefined;
    }
}
