<template>
    <grid :position="grid" modifiers="padded white overflow">
        <section v-if="message != ''" class="slack">
            <h2 class="slack__header">Latest Announcement</h2>
            <p class="slack__meta">
                Posted {{ relativeTime(posted) }} by {{ from }}
            </p>
            <p class="slack__message">
                {{ message }}
            </p>
        </section>
        <section class="slack--offline" v-else>
            <h2 class="slack--header">Latest Announcement Unavailable</h2>
            <p class="slack__message">I couldn't find a relevant message to show.</p>
        </section>
    </grid>
</template>
<script>
import Echo from '../mixins/echo';
import Grid from './Grid';
import { addClassModifiers, relativeDate, relativeTime } from '../helpers';
import SaveState from '../mixins/save-state';
import moment from 'moment';

export default {

    components: {
        Grid,
    },

    mixins: [Echo, SaveState],

    props: ['grid'],

    data() {
        return {
            message: 'Loading Slack Messages...',
            from: 'No-One Yet',
            posted: 'A long time ago'
        }
    },

    methods: {
        addClassModifiers,
        relativeDate,
        relativeTime,

        getEventHandlers() {
            return {
                'Slack.Announcement': response => {
                    this.from = response.from;
                    this.message = response.message;
                    this.posted = moment(response.posted);
                }
            }
        },

        getSavedStateId() {
            return 'slack';
        },
    }

}
</script>