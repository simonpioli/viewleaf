import './bootstrap.js';

import Echo from 'laravel-echo';
import Vue from 'vue';

var VueResource = require('vue-resource');
Vue.use(VueResource);
Vue.http.interceptors.push((request, next) => {
    request.headers.set('X-CSRF-TOKEN', Laravel.csrfToken);
    next();
});

import _ from 'lodash';
import { launchIntoFullscreen, exitFullscreen } from './helpers';

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
        $('.js-fullScreen').on('click', function(e) {
            e.preventDefault();
            if ($(this).is('.is-active')) {
                exitFullscreen();
                $(this).removeClass('is-active');
            } else {
                launchIntoFullscreen(document.getElementById('dashboard'));
                $(this).addClass('is-active');
            }

        });
    }
});