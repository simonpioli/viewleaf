<template>
    <grid :position="grid" modifiers="padded white overflow">
        <section v-if="message !== ''" class="slack">
            <h2 class="slack__header">Latest Announcement</h2>
            <p class="slack__message marquee marquee-movement-smooth" v-bind:style="{ animationDuration: animationTime }">
                <avatar :profile="from"></avatar>
                <span v-html="formattedMessage">{{ formattedMessage }}</span>
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
            emoji: {},
            from: {},
            posted: ''
        }
    },

    computed: {
        formattedMessage: function() {
            var message = this.from.real_name + ' said: “' + this.message + '” ' + relativeTime(this.posted) + '.';
            // Loop array of mentions and replace the ID crap with the first name
            _.forEach(this.mentions, function(person) {
                // avatar = createElement(Avatar, person); // Need to work out how to actually do this...
                let avatar = '<img class="slack__avatar--mention" src="' + person.thumbnail + '">';
                message = message.replace('<@' + person.id + '>', avatar + ' (' + person.first_name + ')');
            });

            // This might be the one for the Unicode characters - https://aaronparecki.com/2017/02/05/8/day-47-slack-emoji

            _.forEach(this.emoji, function(item) {
                // emojiElem = createElement(Emoji, item);  // Need to work out how to actually do this...
                let emoji = '<img class="slack__emoji" src="' + item.image + '">';
                message = message.replace(':' + item.label + ':', emoji);
            });

            console.log(message);

            return message;
        },

        animationTime: function() {
            var cnt = this.message;
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
                        this.emoji = response.emoji;
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