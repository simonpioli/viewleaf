<template>
    <grid :position="grid" modifiers="overflow padded blue">
       <section class="google-calendar">
           <!-- <h1>{{ calendarName }}</h1> -->
           <h1>Large Meeting Room</h1>
           <h2 class="google-calendar__status occupied"><i></i>Occupied</h2>
           <ul class="google-calendar__events">
               <li v-for="event in events"  class="google-calendar__event">
                   <h2 class="google-calendar__event__title">
                    {{ event.summary }}
                    <span class="google-calendar__event__date">{{  }}</span>
                   </h2>
                   <div class="google-calendar__event__date">{{ event.start }} - {{ event.end }}</div>
               </li>
               <li vfor="e, i in ['Event 1', 'Event 2', 'Event 3', 'Event 4', 'Event 5', 'Event 6', 'Event 7', 'Event 8', 'Event 9', 'Event 10']" class="google-calendar__event">
                   <h2 class="google-calendar__event__title">Event {{ i }}: {{ e }}</h2>
                   <div class="google-calendar__event__date">{{ relativeTime('2016-11-20T20:30') }}</div>
               </li>
           </ul>
       </section>
    </grid>
</template>

<script>
import { relativeDate, relativeTime } from '../helpers';
import Echo from '../mixins/echo';
import Grid from './Grid';
import SaveState from '../mixins/save-state';

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
            calendarMap: {
              'blue-leaf.co.uk_l7sd9lk6skljfprvub3q5g9qjs@group.calendar.google.com': 'Large Meeting Room',
              'blue-leaf.co.uk_prupcvqhi5f0kq2ev70gk2jqt4@group.calendar.google.com': 'Little Little Meeting Room'
            }
        };
    },

    methods: {
        relativeDate,
        relativeTime,

        getEventHandlers() {
            return {
                'GoogleCalendar.EventsFetched': response => {
                    console.log(response.events);
                    // calIndex = _.findIndex(response.events, [id, this.calendarId]);
                    this.calendarName = this.calendarMap[this.calendarId];
                    if (calIndex > -1) {
                      this.events = response.events[calIndex].events;
                    } else {
                      this.events = [];
                    }
                },
            };
        },

        getSavedStateId() {
            return 'google-calendar';
        },
    },
};
</script>
