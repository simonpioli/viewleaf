import './bootstrap.js';

import Echo from 'laravel-echo';
import Vue from 'vue';

var axios = require('axios');
var axiosInstance = axios.create({
    headers: {'X-CSRF-TOKEN': Laravel.csrfToken}
});
Vue.prototype.$http = axiosInstance;

import _ from 'lodash';
import { launchIntoFullscreen, exitFullscreen, burnGuard } from './helpers';

import CurrentTime from './components/CurrentTime';
import GoogleCalendar from './components/GoogleCalendar';
import InternetConnection from './components/InternetConnection';
import Sonos from './components/Sonos';
import Slack from './components/Slack';
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
            } else {
                launchIntoFullscreen(document.getElementById('dashboard'));
            }
        });

        $(document).on('webkitfullscreenchange mozfullscreenchange fullscreenchange MSFullscreenChange', function(e){
            $('.js-fullScreen').toggleClass('is-active');
        });

        burnGuard();
    },
});
