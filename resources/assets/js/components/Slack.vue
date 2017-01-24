<template>
    <grid :position="grid" modifiers="padded white overflow">
        <section v-if="message !== ''" class="slack">
            <h2 class="slack__header">Latest Announcement</h2>
            <p class="slack__message marquee marquee-movement-smooth" v-bind:style="{ animationDuration: animationTime }">
                <avatar :profile="from"></avatar>
                {{ formattedMessage }}
            </p>
        </section>
        <section v-else class="slack slack--offline">
            <h2 class="slack__header">Latest Announcement Unavailable</h2>
            <p class="slack__message">I couldn't find a relevant message to show.</p>
        </section>
    </grid>
</template>
<script>
import Echo from '../mixins/echo';
import Grid from './Grid';
import Avatar from './Avatar';
import { addClassModifiers, relativeDate, relativeTime } from '../helpers';
import SaveState from '../mixins/save-state';
import moment from 'moment';

export default {

    components: {
        Grid,
        Avatar
    },

    mixins: [Echo, SaveState],

    props: ['grid'],

    data() {
        return {
            message: '',
            mentions: {},
            from: {},
            posted: ''
        }
    },

    computed: {
        formattedMessage: function() {
            return this.from.real_name + ' said: “' + this.message + '” ' + relativeTime(this.posted) + '.';
        },

        animationTime: function() {
            var cnt = this.formattedMessage;
            var val = (cnt.length / 600) * 60;
            val = val.toString() + 's';
            return val;
        }
    },

    methods: {
        addClassModifiers,
        relativeDate,
        relativeTime,

        getEventHandlers() {
            return {
                'Slack.Announcement': response => {
                    if (response.message !== this.message) {
                        this.from = response.from;
                        // console.log(this.from);
                        this.mentions = response.mentions;
                        var message = response.message
                            .replace('<!channel>', '')
                            .replace('<!channel|@channel>', '')
                            .replace('<!here>', '')
                            .replace('<!here|@here>', '')
                            .replace('@bigscreen', '');

                        // Loop array of mentions and replace the ID crap with the first name
                        _.forEach(this.mentions, function(person) {
                            message = message.replace('<@' + person.id + '>', person.first_name);
                        });

                        this.message = message.trim();
                        this.posted = moment(response.posted);
                    }
                },

                'Slack.NoAnnouncement': response => {
                    this.from = '';
                    this.message = '';
                    this.posted = '';
                }
            }
        },

        getSavedStateId() {
            return 'slack';
        }
    }

}
</script>