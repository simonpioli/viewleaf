import Chart from 'chart.js';
import moment from 'moment';

moment.updateLocale('en', {
    calendar: {
        lastDay: '[Yesterday]',
        sameDay: '[Today]',
        nextDay: '[Tomorrow]',
        lastWeek: '[last] dddd',
        nextWeek: 'dddd',
        sameElse: 'DD/MM/YYYY',
    },
});

Chart.defaults.global.legend.display = false;
Chart.defaults.global.tooltips.enabled = false;

var $ = require('jquery');
window.jQuery = $;
window.$ = $;
