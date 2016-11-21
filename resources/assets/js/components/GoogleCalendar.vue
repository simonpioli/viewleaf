<template>
    <grid :position="grid" modifiers="overflow padded blue">
       <section class="google-calendar">
           <h1>{{ calendarName }}</h1>
           <h2 v-if="occupied == true" class="google-calendar__status occupied"><i></i>Occupied</h2>
           <h2 v-if="occupied == false" class="google-calendar__status available"><i></i>Available</h2>
           <ul class="google-calendar__events">
              <li v-if="events.length == 0" class="google-calendar__event--error">
                  <h2 class="google-calendar__event__title">No events found</h2>
              </li>
              <li v-for="event in events"  class="google-calendar__event">
                <div class="google-calendar__event__date">
                  {{ timeFormat(event.start, event.end) }}
                  <!-- <span class="google-calendar__event__status" v-if="nowNext(event) == false">
                    {{ nowNext(event, events) }}
                  </span> -->
                </div>
                <h2 class="google-calendar__event__title">
                  {{ event.summary }}
                  <span  class="google-calendar__event__next"></span>
                </h2>

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
              'blue-leaf.co.uk_l7sd9lk6skljfprvub3q5g9qjs@group.calendar.google.com': 'Large Meeting Room',
              'blue-leaf.co.uk_9ffftu2t7do5dhi2jdn61jpsho@group.calendar.google.com': 'Small Meeting Room',
              'blue-leaf.co.uk_prupcvqhi5f0kq2ev70gk2jqt4@group.calendar.google.com': 'Little Little Meeting Room'
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
        // nowNext,

        getEventHandlers() {
            return {
                'GoogleCalendar.EventsFetched': response => {
                    var targetId = this.calendarId;
                    var calIndex = _.findIndex(response.events, function(o) {
                      return o.id == targetId;
                    });

                    if (calIndex > -1) {
                      this.events = response.events[calIndex].events == null ? [] : response.events[calIndex].events;
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
