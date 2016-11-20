import './bootstrap.js';

import Echo from 'laravel-echo';
import Vue from 'vue';
import _ from 'lodash';

import CurrentTime from './components/CurrentTime';
import GoogleCalendar from './components/GoogleCalendar';
import InternetConnection from './components/InternetConnection';
import Sonos from './components/Sonos';
import RainForecast from './components/RainForecast';

new Vue({

    el: '#dashboard',

    components: {
        CurrentTime,
        GoogleCalendar,
        InternetConnection,
        Sonos,
        RainForecast,
    },

    created() {
        this.echo = new Echo({
            broadcaster: 'pusher',
            key: window.dashboard.pusherKey,
            cluster: 'eu',
            encrypted: true
        });
    },
});
