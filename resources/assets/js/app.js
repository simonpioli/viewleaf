import './bootstrap.js';

import Echo from 'laravel-echo';
import Vue from 'vue';
import _ from 'lodash';
require('../../../node_modules/jquery.marquee/jquery.marquee.js');

import CurrentTime from './components/CurrentTime';
import GoogleCalendar from './components/GoogleCalendar';
import InternetConnection from './components/InternetConnection';
import Sonos from './components/Sonos';
import Slack from './components/Slack'
// import RainForecast from './components/RainForecast';

new Vue({

    el: '#dashboard',

    components: {
        CurrentTime,
        GoogleCalendar,
        InternetConnection,
        Sonos,
        Slack,
        // RainForecast,
    },

    created() {
        this.echo = new Echo({
            broadcaster: 'pusher',
            key: window.dashboard.pusherKey,
            cluster: 'eu',
            encrypted: true
        });
    },

    mounted() {
        $('.js-marqueeMessage').marquee({
            duration: 30000
        });
    }
});

