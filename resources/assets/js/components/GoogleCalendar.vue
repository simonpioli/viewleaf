<template>
    <grid :position="grid" modifiers="overflow padded">
       <section class="google-calendar">
           <h1 v-html="calendarName"></h1>
           <h2 v-if="occupied == true" class="google-calendar__status occupied"><i></i>Occupied {{ nextSignificantTime }}</h2>
           <h2 v-if="occupied == false" class="google-calendar__status available"><i></i>Available {{ nextSignificantTime }}</h2>
           <ul class="google-calendar__events">
              <li v-if="events.length == 0" class="google-calendar__event-heading">
                  <h2 class="google-calendar__event__title">No bookings found</h2>
              </li>
              <li v-else class="google-calendar__event-heading">
                <h2 class="google-calendar__event__title">Next bookings</h2>
              </li>
              <li v-for="(event, index) in events" class="google-calendar__event" v-bind:class="{ 'google-calendar__event--now': event.current }">
                <div class="google-calendar__event__date">
                  {{ timeFormat(event.start, event.end) }}
                </div>
              </li>
           </ul>
       </section>
    </grid>
</template>

<script>
import { timeFormat, relativeTime } from '../helpers';
import Echo from '../mixins/echo';
import Grid from './Grid';
import SaveState from '../mixins/save-state';
import moment from 'moment';

export default {

    components: {
        Grid,
    },

    mixins: [Echo, SaveState],

    props: ['grid', 'calendarId'],

    data() {
        return {
            events: [],
            occupied: false,
            calendarMap: {
                'calendar@example.com': '<span>Example</span> Calendar'
            }
        };
    },

    computed: {
        calendarName: function() {
            return this.calendarMap[this.calendarId];
        },

        nextSignificantTime: function() {
            var evt = _.first(this.events);
            if (typeof(evt) !== 'undefined') {
                var str = 'until ';
                str += this.occupied ? timeFormat(evt.end) : timeFormat(evt.start);
                return str;
            } else {
                return '';
            }
        }
    },

    methods: {
        timeFormat,
        relativeTime,

        getEventHandlers() {
            return {
                'GoogleCalendar.EventsFetched': response => {
                    var targetId = this.calendarId;
                    var calIndex = _.findIndex(response.events, function(o) {
                        return o.id == targetId;
                    });

                    if (calIndex > -1) {
                        var events = response.events[calIndex].freebusy == null ? [] : response.events[calIndex].freebusy;
                        this.events = _.filter(events, function(o){
                            var _now = moment();
                            return !moment(o.end).isBefore(_now);
                        });
                        this.occupied = false;
                        var occupiedIndex = _.findIndex(response.events[calIndex].freebusy, function(o){
                            var _now = moment();
                            return _now.isBetween(moment(o.start), moment(o.end));
                        });

                        if (occupiedIndex > -1) {
                            this.occupied = true;
                        }
                    } else {
                        this.events = [];
                        this.occupied = false;
                    }
                },
            };
        },

        getSavedStateId() {
            return 'google-calendar-' + this.calendarId;
        },
    },
};
</script>
