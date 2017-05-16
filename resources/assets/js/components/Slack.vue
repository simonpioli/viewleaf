<template>
    <grid :position="grid" modifiers="padded white overflow">
        <section v-if="message !== ''" class="slack">
            <h2 class="slack__header">Latest Announcement</h2>
            <p class="slack__message marquee marquee-movement-smooth" v-bind:style="{ animationDuration: animationTime }">
                <avatar :profile="from"></avatar>
                <message :message="message" :emoji="emoji" :mentions="mentions" :from="from"></message>
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
import Avatar from './slack/Avatar';
import Message from './slack/Message';
import { addClassModifiers } from '../helpers';
import SaveState from '../mixins/save-state';
import moment from 'moment';

export default {

    components: {
        Grid,
        Avatar,
        Message
    },

    mixins: [Echo, SaveState],

    props: ['grid'],

    data() {
        return {
            message: '',
            mentions: {},
            emoji: {},
            from: {},
            posted: ''
        }
    },

    computed: {
        animationTime: function() {
            var cnt = this.message;
            var val = (cnt.length / 600) * 60;
            val = val.toString() + 's';
            return val;
        }
    },

    methods: {
        addClassModifiers,

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
                            .replace('@bigscreen', '');

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