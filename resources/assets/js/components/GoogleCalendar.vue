<template>
    <grid :position="grid" modifiers="overflow padded">
       <section class="google-calendar">
           <h1 v-html="calendarName"></h1>
           <h2 v-if="occupied == true" class="google-calendar__status occupied"><i></i>Occupied</h2>
           <h2 v-if="occupied == false" class="google-calendar__status available"><i></i>Available</h2>
           <ul class="google-calendar__events">
              <li v-if="events.length == 0" class="google-calendar__event">
                  <h2 class="google-calendar__event__title">No bookings found</h2>
              </li>
              <li v-else class="google-calendar__event">
                <h2 class="google-calendar__event__title">Next bookings</h2>
              </li>
              <li v-for="(event, index) in events" class="google-calendar__event">
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
            calendarName: '',
            events: [],
            occupied: false,
            calendarMap: {
                'blue-leaf.co.uk_l7sd9lk6skljfprvub3q5g9qjs@group.calendar.google.com': '<span>Large</span> Meeting Room',
                'blue-leaf.co.uk_9ffftu2t7do5dhi2jdn61jpsho@group.calendar.google.com': '<span>Small</span> Meeting Room',
                'blue-leaf.co.uk_prupcvqhi5f0kq2ev70gk2jqt4@group.calendar.google.com': '<span>Little Little</span> Meeting Room'
            }
        };
    },

    computed: {
        calendarName: function() {
            return this.calendarMap[this.calendarId];
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
